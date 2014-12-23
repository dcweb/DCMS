<?php

namespace Dcweb\Dcms\Controllers\Dealers;

use Dcweb\Dcms\Models\Dealers\Dealer;
use Dcweb\Dcms\Models\Dealers\Zipcity;
use Dcweb\Dcms\Models\Dealers\ZipcityNL;
use Dcweb\Dcms\Controllers\BaseController;
use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;


class DealerController extends BaseController {

	public $countries = ""; 

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
		return View::make('dcms::dealers/index');
	}
	
	public function getZipCityJson()
	{
		// url parameters
		$zipcity = trim(Input::get("zipcity"));
		$country_id = trim(Input::get("country_id"));
		
		//get the JSON
		if ($country_id == "1") 
		{
			return Zipcity::select('postcode as zip','gemeente as city',(DB::connection('admin')->table('admin')->raw('CONCAT(postcode, " ", gemeente) AS label')))->where('postcode','LIKE',$zipcity.'%')->orwhere('gemeente','LIKE','%'.$zipcity.'%')->get()->toJson();		
		}
		else
		{
			return ZipcityNL::select('postcode as zip','gemeente as city',(DB::connection('admin')->table('admin')->raw('CONCAT(postcode, " ", gemeente) AS label')))->where('postcode','LIKE',$zipcity.'%')->orwhere('gemeente','LIKE','%'.$zipcity.'%')->get()->toJson();	
		}
		
		
	}
	
	public function getDatatable()
	{
		return Datatable::Query(
									DB::connection('project')
											->table('dealers')
		)
						->showColumns('dealer')
						->addColumn('edit',function($model){return '<form method="POST" action="/admin/dealers/'.$model->id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
								<a class="btn btn-xs btn-default" href="/admin/dealers/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
								<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
							</form>';})
						->searchColumns('id')
						->make();
	}

	/* returns an array holding Lat Lon coordinates of the given location
		 this is used to fetch the gpscoordinates when looking for  dealers
		************************************************************/
	public function get_GPSCoordinates($zip,$city,$address,$country){
			$geozipcode 	= trim(strip_tags($zip));
			$geozipcode 	= str_replace(" ", "", $geozipcode);
			$geocity 		= trim(strip_tags($city));
			$geoaddress		= trim(strip_tags($address));
			$geoaddress 	= str_replace(" ","+",$geoaddress);
			
			$geoquery = "";
			$geoquery .= "+" . $country;
			
			if (strlen($geozipcode)>0) $geoquery .= "+".$geozipcode;
			if (strlen($geocity)>0) $geoquery .= "+".$geocity;
			if (strlen($geoaddress)>0) $geoquery .= "+".$geoaddress;
			
			$geoquery = substr($geoquery,1); // remove the first +
			$googleurl = "http://maps.google.com/maps/geo?q=" .$geoquery. "&output=csv&oe=utf8&sensor=false";
		
			$googledata = file_get_contents($googleurl);
		
			$aGoogleData = explode(",",$googledata);
			
			$aCoordinates["lat"] = 0;
			$aCoordinates["lon"] = 0;
		
			if ($aGoogleData[0] == "200"){
				$aCoordinates["lat"] = $aGoogleData[2];
				$aCoordinates["lon"] = $aGoogleData[3];
			}else{
				
				$googleurl = "http://maps.googleapis.com/maps/api/geocode/json?address=" .$geoquery. "&sensor=false";
				$googledata = file_get_contents($googleurl);
			
				$googledata = json_decode($googledata,true);
			
				$aCoordinates["lat"] = $googledata['results'][0]['geometry']['location']['lat'];
				$aCoordinates["lon"] = $googledata['results'][0]['geometry']['location']['lng'];
			}
			
			return $aCoordinates;
	}
		
	public function getCountries()
	{
			$oCountries = DB::connection("project")->table("countries")->select( "id", "country_name")->get();
			if(!is_null($oCountries))
			{
					foreach($oCountries as $c)
					{
						$this->countries[$c->id] = $c->country_name;
					}
			}
	}
		


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->getCountries();
		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::dealers/form')
			->with("countries",$this->countries);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		$rules = array(
			'dealer'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		
		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/dealers/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			$this->getCountries();
			
			$latlon = $this->get_GPSCoordinates(Input::get("zip"),Input::get("city"),Input::get("address"),$this->countries[Input::get("country_id")]);
			
			$dealer = new Dealer;
			$dealer->dealer = Input::get("dealer");
			$dealer->address = Input::get("address");
			$dealer->zip = Input::get("zip");
			$dealer->city = Input::get("city");
			$dealer->country_id = Input::get("country_id");
			$dealer->phone = Input::get("phone");
			$dealer->email = Input::get("email");
			$dealer->website = Input::get("website");
			$dealer->longitude = $latlon["lon"];
			$dealer->latitude = $latlon["lat"];
			$dealer->admin =  Auth::dcms()->user()->username;
			$dealer->save();

			
			// redirect
			Session::flash('message', 'Successfully created dealer!');
			return Redirect::to('admin/dealers');
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		// get the article
		$dealer = Dealer::find($id);
		/*	
		// show the view and pass the nerd to it
		return View::make('dcms::articles/articles/show')
			->with('article', $article)
			->with("category",$cat->title);
			*/
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		// get the article
		$dealer = Dealer::find($id);
		
		$this->getCountries();
		
		// show the edit form and pass the nerd
		return View::make('dcms::dealers/form')
			->with('dealer', $dealer)
			->with("countries",$this->countries);
	}
	
	
	/**
	 * copy the model
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function copy($id)
	{
		$new = Dealer::find($id)->replicate();
		$new->save();
		return Redirect::to('admin/dealers');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(
			'dealer'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/dealers/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			$this->getCountries();
			
			$latlon = $this->get_GPSCoordinates(Input::get("zip"),Input::get("city"),Input::get("address"),$this->countries[Input::get("country_id")]);
			
			$dealer = Dealer::find($id);
			$dealer->dealer = Input::get("dealer");
			$dealer->address = Input::get("address");
			$dealer->zip = Input::get("zip");
			$dealer->city = Input::get("city");
			$dealer->country_id = Input::get("country_id");
			$dealer->phone = Input::get("phone");
			$dealer->email = Input::get("email");
			$dealer->website = Input::get("website");
			$dealer->longitude = $latlon["lon"];
			$dealer->latitude = $latlon["lat"];
			$dealer->admin =  Auth::dcms()->user()->username;
			$dealer->save();
		
			// redirect
			Session::flash('message', 'Successfully updated dealer!');
			return Redirect::to('admin/dealers');
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// delete
		$article = Dealer::find($id);
		$article->delete();

		// redirect
		Session::flash('message', 'Successfully deleted the dealer!');
		return Redirect::to('admin/dealers');
	}


}

<?php

namespace Dcweb\Dcms\Controllers\Settings;

use Dcweb\Dcms\Models\Volumes\Volume;
use Dcweb\Dcms\Models\Volumes\Detail;
use Dcweb\Dcms\Helpers\Helper\SEOHelpers;
use Dcweb\Dcms\Controllers\BaseController;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use DateTime;
use Request;
use Route;

class VolumeController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
			return View::make('dcms::settings/volumes/index');
	}
	
	
	public function getDatatable()
	{
		return Datatable::Query(
									DB::connection('project')
											->table('products_volume_units')
											->select(
														'products_volume_units.id', 
														'products_volume_units_language.volume_unit', 
														'products_volume_units_language.volume_unit_long', 
														'products_volume_units_language.id as detail_id',
														(DB::connection("project")->raw('Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(country),".png\' >") as country'))
													)
											->join('products_volume_units_language','products_volume_units.id','=','products_volume_units_language.volume_units_id')
											->leftJoin('languages','products_volume_units_language.language_id', '=' , 'languages.id')
		)
		
						->showColumns('volume_unit','volume_unit_long','country')
						->addColumn('edit',function($model){return '<form  class="pull-right"> 
								<a class="btn btn-xs btn-default" href="/admin/settings/volumes/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
							</form>';})
						->searchColumns('volume_unit_long')
						->make();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$languages =  DB::connection("project")->table("languages")->select((DB::connection("project")->raw("'' as volume_unit, '' as volume_unit_long")), "id","id as language_id",  "language","country","language_name")->get();
		
		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::settings/volumes/form')
					->with('languages',$languages);
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
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/settings/volumes/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			
			$input = Input::get();
			
			foreach($input["volume_unit"] as $language_id  => $value)
			{
				if (strlen(trim($input["volume_unit"][$language_id]))>0 || strlen(trim($input["volume_unit_long"][$language_id]))>0 )
				{
					//since we loop with foreach we don't want to create everytime a new article
					if (!isset($Volume) || is_null($Volume) )
					{
						$Volume = new Volume;
						$Volume->volume_unit= $input["volume_unit"][$language_id];
						$Volume->save();
					}
				
					$Detail = new Detail();
					$Detail->volume_units_id 		= $Volume->id;
					$Detail->language_id 		= $language_id;
					$Detail->volume_unit		= $input["volume_unit"][$language_id];
					$Detail->volume_unit_long	= $input["volume_unit_long"][$language_id];
					$Detail->save();		
					
					Volume::find($Volume->id)->detail()->save($Detail);
					
				}//if title is set
			}//end foreach
			
			// redirect
			Session::flash('message', 'Successfully created volume!');
			return Redirect::to('admin/settings/volumes');
		}
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
			$Volume = Volume::find($id);
			
		 	$languages = DB::connection("project")->select('
													SELECT language_id, languages.language, languages.country, languages.language_name,  products_volume_units_language.id, volume_units_id, products_volume_units_language.volume_unit, volume_unit_long
													FROM products_volume_units_language
													LEFT JOIN languages on languages.id = products_volume_units_language.language_id
													LEFT JOIN products_volume_units on products_volume_units.id = products_volume_units_language.volume_units_id
													WHERE  languages.id is not null AND  volume_units_id = ?
													UNION
													SELECT languages.id , language, country, language_name, \'\' , \'\' ,  \'\' , \'\' 
													FROM languages 
													WHERE id NOT IN (SELECT language_id FROM products_volume_units_language WHERE volume_units_id = ?) ORDER BY 1
													', array($id,$id));

			// show the edit form and pass the nerd
			return View::make('dcms::settings/volumes/form')
				->with('Volume', $Volume)
				->with('languages', $languages);
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
			//'title'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/settings/volumes/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			
			$Volume = Volume::find($id);
			
			$input = Input::get();
			foreach($input["volume_unit"] as $language_id  => $value)
			{
				if (strlen(trim($input["volume_unit"][$language_id]))>0 || strlen(trim($input["volume_unit_long"][$language_id]))>0) //we don't want to populate the database when there is no title given
				{
					if (!isset($Volume) || is_null($Volume) )
					{
						$Volume = new Article;
						$Volume->volume_unit = $input["volume_unit"][$language_id];
						$Volume->save();
					}
					
					$Detail = Detail::find($input["volume_unit_id"][$language_id]);
					if (is_null($Detail) === true)
					{
						$Detail = new Detail();
					}
					
					$Detail->volume_units_id 		= $Volume->id;
					$Detail->language_id 		= $language_id;
					$Detail->volume_unit		= $input["volume_unit"][$language_id];
					$Detail->volume_unit_long	= $input["volume_unit_long"][$language_id];
					$Detail->save();		
					
					Volume::find($Volume->id)->detail()->save($Detail);
				}
			}//end foreach
			
			// redirect
			Session::flash('message', 'Successfully updated volume unit!');
			return Redirect::to('admin/settings/volumes');
		}
	}
	
	public function replicateById($id = null, $overwriteSettings = array())
	{
		$newVolumedetail = Detail::find($id)->replicate();
		if(count($overwriteSettings)>0)
		{
			foreach($overwriteSettings as $key => $value)
			{
				$newVolumedetail->$key = $value;
			}
		}
		$newVolumedetail->save();
		return $newVolumedetail;
	}
	
	public function replicateForNewLanguage($overwriteSettings = array())
	{
		$Detail = Detail::where("language_id","=",1)->get(); //language_id 1 is fixed since this is to be taken as the default!!
		if(!is_null($Detail) && count($Detail)>0)
		{
			foreach($Detail as $M)
			{
				$this->replicateById($M->id,$overwriteSettings);
			}
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
	//	$Detail = Detail::find($id);
	//	$mainArticleID = $Detail->article_id;
	//	$Detail->delete();
		
	//	if (Detail::where("article_id","=",$mainArticleID)->count() <= 0)
	//	{
	//		Article::destroy($mainArticleID);
	//	}
		
		// redirect
		Session::flash('message', 'No volume has been deleted! Ask DBA for thorough delete.');
		return Redirect::to('admin/settings/volumes');
	}
}

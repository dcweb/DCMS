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
											->table('volumes_class')
											->select(
														'volumes_class.id', 
														'volumes_class_detail.volume_class', 
														'volumes_class_detail.volume_class_long', 
														'volumes_class_detail.id as detail_id',
														(DB::connection("project")->raw('Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(country),".png\' >") as country'))
													)
											->join('volumes_class_detail','volumes_class.id','=','volumes_class_detail.volume_id')
											->leftJoin('languages','volumes_class_detail.language_id', '=' , 'languages.id')
		)
		
						->showColumns('volume_class','volume_class_long','country')
						->addColumn('edit',function($model){return '<form  class="pull-right"> 
								<a class="btn btn-xs btn-default" href="/admin/settings/volumes/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
							</form>';})
						->searchColumns('volume_class_long')
						->make();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$languages =  DB::connection("project")->table("languages")->select((DB::connection("project")->raw("'' as volume_class, '' as volume_class_long")), "id","id as language_id",  "language","country","language_name")->get();
		
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
			
			foreach($input["volume_class"] as $language_id  => $value)
			{
				if (strlen(trim($input["volume_class"][$language_id]))>0 || strlen(trim($input["volume_class_long"][$language_id]))>0 )
				{
					//since we loop with foreach we don't want to create everytime a new article
					if (!isset($Volume) || is_null($Volume) )
					{
						$Volume = new Volume;
						$Volume->volume_class= $input["volume_class"][$language_id];
						$Volume->save();
					}
				
					$Detail = new Detail();
					$Detail->volume_id 		= $Volume->id;
					$Detail->language_id 		= $language_id;
					$Detail->volume_class		= $input["volume_class"][$language_id];
					$Detail->volume_class_long	= $input["volume_class_long"][$language_id];
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
													SELECT language_id, languages.language, languages.country, languages.language_name,  volumes_class_detail.id, volume_id, volumes_class_detail.volume_class, volume_class_long
													FROM volumes_class_detail
													LEFT JOIN languages on languages.id = volumes_class_detail.language_id
													LEFT JOIN volumes_class on volumes_class.id = volumes_class_detail.volume_id
													WHERE  languages.id is not null AND  volume_id = ?
													UNION
													SELECT languages.id , language, country, language_name, \'\' , \'\' ,  \'\' , \'\' 
													FROM languages 
													WHERE id NOT IN (SELECT language_id FROM volumes_class_detail WHERE volume_id = ?) ORDER BY 1
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
			foreach($input["volume_class"] as $language_id  => $value)
			{
				if (strlen(trim($input["volume_class"][$language_id]))>0 || strlen(trim($input["volume_class_long"][$language_id]))>0) //we don't want to populate the database when there is no title given
				{
					if (!isset($Volume) || is_null($Volume) )
					{
						$Volume = new Article;
						$Volume->volume_class= $input["volume_class"][$language_id];
						$Volume->save();
					}
					
					$Detail = Detail::find($input["volume_class_id"][$language_id]);
					if (is_null($Detail) === true)
					{
						$Detail = new Detail();
					}
					
					$Detail->volume_id 		= $Volume->id;
					$Detail->language_id 		= $language_id;
					$Detail->volume_class		= $input["volume_class"][$language_id];
					$Detail->volume_class_long	= $input["volume_class_long"][$language_id];
					$Detail->save();		
					
					Volume::find($Volume->id)->detail()->save($Detail);
				}
			}//end foreach
			
			// redirect
			Session::flash('message', 'Successfully updated article!');
			return Redirect::to('admin/settings/volumes');
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
		Session::flash('message', 'Nothing has been deleted the article! Ask DBA for thorough delete.');
		return Redirect::to('admin/settings/volumes');
	}
}

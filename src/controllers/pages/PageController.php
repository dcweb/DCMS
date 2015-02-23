<?php

namespace Dcweb\Dcms\Controllers\Pages;

use Dcweb\Dcms\Models\Pages\Page;
use Dcweb\Dcms\Models\Pages\Pagetree;
use Dcweb\Dcms\Models\Pages\Detail;
use Dcweb\Dcms\Models\Languages\Language;

use Dcweb\Dcms\Controllers\BaseController;

use Dcweb\Dcms\Helpers\Helper\SEOHelpers;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use DateTime;
use Config;


class PageController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
		return View::make('dcms::pages/index');
	}
	
	
	public function getDatatable()
	{
			return Datatable::Query(
														DB::connection('project')
																->table('pagetree')
																->select(
																			'id', 
																			(DB::connection("project")->raw('concat(repeat(\'-\', level),\' \',  page) as page')),
																			'detail_id',
																			(DB::connection("project")->raw('Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(substr(regio,4)),".png\' >") as regio'))
																		)
																->where('id','>','0')
																->orderBy('language_id','asc')
																->orderBy('sort_id','asc')
															)
		
						->showColumns('page','regio')
						->addColumn('edit',function($model){return '<form method="POST" action="/admin/pages/'.$model->detail_id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
								<a class="btn btn-xs btn-default" href="/admin/pages/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
								<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
							</form>';})
						->searchColumns('title')
						->make();
	}

	
	
	
	public function getSortOptions($setExtra = 0 )
	{
		$sort_id = DB::connection("project")->table('pages')->max('sort_id');

		for($i = 1; $i<=($sort_id+$setExtra); $i++)
		{
			$SortOptions[$i] = $i;
		}

		return $SortOptions;
	}	
	

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$languages =  DB::connection("project")->table("languages")->select((DB::connection("project")->raw("'' as title, '' as parent_id, '' as body")), "id","id as language_id",  "language","country","language_name")->get();
		
		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::pages/form')
					->with('languages',$languages)
					->with('pageOptionValues',Pagetree::OptionValueArray(true))
					->with('sortOptionValues',$this->getSortOptions(1));
	}


	public function generatePageTree()
	{
		$Languages = Language::all();
		$mysqli = new \mysqli(Config::get("database.connections.project.host"), Config::get("database.connections.project.username"), Config::get("database.connections.project.password"), Config::get("database.connections.project.database"));
		
		foreach($Languages as $Lang)
		{
		//	DB::connection("project")->statement(DB::connection("project")->raw('CALL recursivepage(1,0,'.$Lang->id.',\'\',\'\',\'\',0);'));
			
			if (!$mysqli->multi_query('CALL recursivepage(1,0,'.$Lang->id.',\'\',\'\',\'\',0);')) {
					echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			
			do {
						if ($res = $mysqli->store_result()) {
								$res->free();
						} 
				} while ($mysqli->more_results() && $mysqli->next_result());
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$Languages = Language::all();
		$rules = array('sort_id'=>'required|integer');
		foreach($Languages as $Lang)
		{
			$rules['title.'.$Lang->id] = 'required';
		}
		
		$validator = Validator::make(Input::all(), $rules);

		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/pages/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store

			$input = Input::get();

			foreach($input["title"] as $language_id  => $title)
			{
				if (strlen(trim($input["title"][$language_id]))>0)
				{
					//since we loop with foreach we don't want to create everytime a new article
					if (!isset($Page) || is_null($Page) )
					{
						$Page = new Page;
						$Page->parent_id = $input["parent_id"];
						$Page->sort_id	= $input["sort_id"];
						$Page->admin =  Auth::dcms()->user()->username;
						$Page->save();
					}
				
					$Detail = new Detail();
									
					$Detail->language_id 		= $language_id;
					$Detail->title 					= $input["title"][$language_id];
					$Detail->body 					= $input["body"][$language_id];
					
					$Detail->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					$Detail->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					
					$Detail->admin 				= Auth::dcms()->user()->username;
					$Detail->save();		
					Page::find($Page->id)->detail()->save($Detail);
				}//if title is set
			}//end foreach
			
			$this->generatePageTree();
			
			// redirect
			Session::flash('message', 'Successfully created page!');
			return Redirect::to('admin/pages');
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
			$page = Page::find($id);
		
		 	$languages = DB::connection("project")->select('
													SELECT language_id, languages.language, languages.country, languages.language_name, pages.parent_id, pages_detail.id, page_id, title,  body 
													FROM pages_detail
													LEFT JOIN languages on languages.id = pages_detail.language_id
													LEFT JOIN pages on pages.id = pages_detail.page_id
													WHERE  languages.id is not null AND  page_id = ?
													UNION
													SELECT languages.id , language, country, language_name, \'\' , \'\' ,  \'\' , \'\' , \'\' 
													FROM languages 
													WHERE id NOT IN (SELECT language_id FROM pages_detail WHERE page_id = ?) ORDER BY 1
													', array($id,$id));

			// show the edit form and pass the nerd
			return View::make('dcms::pages/form')
				->with('page', $page)
				->with('languages', $languages)
				->with('pageOptionValues',Pagetree::OptionValueArray(true))
				->with('sortOptionValues',$this->getSortOptions());
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
		$Languages = Language::all();
		$rules = array('sort_id'=>'required|integer');
		foreach($Languages as $Lang)
		{
			$rules['title.'.$Lang->id] = 'required';
		}
		
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/pages/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			$Page = Page::find($id);
			
			$input = Input::get();
			foreach($input["title"] as $language_id  => $title)
			{
				
				if (strlen(trim($title))>0) //we don't want to populate the database when there is no title given
				{
					if (!isset($Page) || is_null($Page) )
					{
						$Page = new Page;
					}

					$Page->admin 			=  Auth::dcms()->user()->username;
					$Page->parent_id 	= $input["parent_id"];
					$Page->sort_id 		= $input["sort_id"];
					$Page->save();
					
					$Detail = Detail::find($input["page_detail_id"][$language_id]);
					if (is_null($Detail) === true)
					{
						$Detail = new Detail();
					}
					
					$Detail->language_id 		= $language_id;
					$Detail->title 					= $input["title"][$language_id];
					$Detail->body 					= $input["body"][$language_id];
				
					$Detail->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					$Detail->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					
					$Detail->admin 					=  Auth::dcms()->user()->username;
					$Detail->save();	
					Page::find($Page->id)->detail()->save($Detail);
				}
			}//end foreach
			
			$this->generatePageTree();
			
			// redirect
			Session::flash('message', 'Successfully updated article!');
			return Redirect::to('admin/pages');
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
		$Detail = Detail::find($id);
		$mainPageID = $Detail->page_id;
		
		$Detail->delete();
		
		if (Detail::where("page_id","=",$mainPageID)->count() <= 0)
		{
			Page::destroy($mainPageID);
		}
		
		$this->generatePageTree();

		// redirect
		Session::flash('message', 'Successfully deleted the page!');
		return Redirect::to('admin/pages');
	}
}
<?php

namespace Dcweb\Dcms\Controllers\Articles;

use Dcweb\Dcms\Controllers\BaseController;

use Dcweb\Dcms\Models\Articles\CategoryID;
use Dcweb\Dcms\Models\Articles\Category;
use Dcweb\Dcms\Models\Languages\Language;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use Dcweb\Dcms\Helpers\Helper\SEOHelpers;


class CategoryController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view and pass the categories
		return View::make('dcms::articles/categories/index');
	}
	
	
	public function getDatatable()
	{
			return Datatable::Query(	
											DB::connection('project')
													->table('articles_categories')
													->select(
																	'articles_categories.id',
																	'articles_categories_language.title', 
																	'articles_categories_language.id as catid' ,
																	(DB::connection("project")->raw('Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(country),".png\' >") as country'))
																	
																)
													->join('articles_categories_language','articles_categories_language.article_category_id','=','articles_categories.id')	
													->leftJoin('languages','articles_categories_language.language_id','=','languages.id')
									)
								->showColumns('title','country')
								->addColumn('edit',function($model){return '<form method="POST" action="/admin/articles/categories/'.$model->catid.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
								<a class="btn btn-xs btn-default" href="/admin/articles/categories/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
								<button class="btn btn-xs btn-default" type="submit" value="Delete this articles category" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
							</form>';})
						->searchColumns('title')
						->make();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
		$languages = DB::connection("project")->table("languages")->select((DB::connection("project")->raw("'' as title, '' as description")), "id","id as language_id", "language","country","language_name")->get();
		
		// load the create form (app/views/categories/create.blade.php)
		return View::make('dcms::articles/categories/form')
			->with('languages',$languages);;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		
		$Languages = Language::all();
		$rules = array();
		foreach($Languages as $Lang)
		{
			$rules['title.'.$Lang->id] = 'required';
		}
		
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/articles/categories/create')
				->withErrors($validator)
				->withInput();
		} else {
			
			$input = Input::get();
			
			if (isset($input["title"]) && count($input["title"])>0)
			{
				foreach($input["title"] as $language_id => $title)
				{
					if (trim(strlen($title))>0)
					{
							if(!isset($category))
							{
									$category = new CategoryID;
									$category->admin =  Auth::dcms()->user()->username;
									$category->save();
							}
							$translatedCategory = new Category; 
							$translatedCategory->title = $input["title"][$language_id];// Input::get('langtitle.1');
							$translatedCategory->language_id = $language_id;
							
							$translatedCategory->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
							$translatedCategory->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
							
							$translatedCategory->admin =  Auth::dcms()->user()->username;
							$translatedCategory->save();			
							CategoryID::find($category->id)->category()->save($translatedCategory);
					}//end trim strlen title
				}//end foreach
			}//end if isset(langtitle)
			
			// redirect
			Session::flash('message', 'Successfully created category!');
			return Redirect::to('admin/articles/categories');
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
		//	get the category
		$category = CategoryID::find($id);
			
	 $languages = DB::connection("project")->select('SELECT languages.id, language, country, language_name, articles_categories_language.*
			FROM articles_categories
			LEFT JOIN articles_categories_language on articles_categories.id = articles_categories_language.article_category_id
			LEFT JOIN languages on articles_categories_language.language_id = languages.id
			WHERE  languages.id is not null AND  articles_categories.id= ?
			UNION
			SELECT languages.id , language, country, language_name, \'\' , \'\' , languages.id , \'\' , \'\' , \'\' , \'\' , \'\' , \'\' 
			FROM languages 
			WHERE id NOT IN (SELECT language_id FROM articles_categories_language WHERE article_category_id = ?) ORDER BY 1
			', array($id,$id));

		return View::make('dcms::articles/categories/form')
			->with('category', $category)
			->with('languages',$languages);
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
		$rules = array();
		foreach($Languages as $Lang)
		{
			$rules['title.'.$Lang->id] = 'required';
		}
		
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/articles/categories/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			$input = Input::get();
			if (isset($input["title"]) && count($input["title"])>0)
			{
				$category = CategoryID::find($id);
				$category->admin =  Auth::dcms()->user()->username;
				
				foreach($input["title"] as $language_id => $title)
				{
					if (trim(strlen($title))>0)
					{
							if(!isset($category))
							{
									$category = new CategoryID;
									$category->admin =  Auth::dcms()->user()->username;
									$category->save();
							}
							
							$translatedCategory = Category::find($input["article_category_id"][$language_id]);
							if (is_null($translatedCategory) === true)  // if we couln't find a Model for the given PIM-id we need to create/add a new one.
							{
								$translatedCategory = new Category;
							}
							$translatedCategory->title = $input["title"][$language_id];// Input::get('langtitle.1');
							$translatedCategory->language_id = $language_id;
							
							$translatedCategory->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
							$translatedCategory->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
							
							$translatedCategory->admin =  Auth::dcms()->user()->username;
							$translatedCategory->save();			
							CategoryID::find($category->id)->category()->save($translatedCategory);
					}//end trim strlen title
				}//end foreach
			}//end if isset(langtitle)
			
			// redirect
			Session::flash('message', 'Successfully updated category!');
			return Redirect::to('admin/articles/categories');
		}
	}


	
	public function replicateById($id = null, $overwriteSettings = array())
	{
		$newCategory = Category::find($id)->replicate();
		if(count($overwriteSettings)>0)
		{
			foreach($overwriteSettings as $key => $value)
			{
				$newCategory->$key = $value;
			}
		}
		$newCategory->save();
		return $newCategory;
	}
	
	public function replicateForNewLanguage($overwriteSettings = array())
	{
		$Categories = Category::where("language_id","=",1)->get(); //language_id 1 is fixed since this is to be taken as the default!!
		if(!is_null($Categories) && count($Categories)>0)
		{
			foreach($Categories as $M)
			{
				$this->replicateById($M->id,$overwriteSettings);
			}
	//		$this->generateCategoryTree();
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
		$category = Category::find($id);
		$mainCategoryID = $category->article_category_id;
		$category->delete();
		
		if (Category::where("article_category_id","=",$mainCategoryID)->count() <= 0)
		{
			CategoryID::destroy($mainCategoryID);
		}
		
		// redirect
		Session::flash('message', 'Successfully deleted the category!');
		return Redirect::to('admin/articles/categories');
	}
}

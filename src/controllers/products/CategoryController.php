<?php

namespace Dcweb\Dcms\Controllers\Products;

use Dcweb\Dcms\Models\Products\CategoryID;
use Dcweb\Dcms\Models\Products\Categorytree;
use Dcweb\Dcms\Models\Products\Category;
use Dcweb\Dcms\Models\Languages\Language;
use Dcweb\Dcms\Controllers\BaseController;
use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use DCMSFunctions;


class CategoryController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		// load the view and pass the categories
		return View::make('dcms::products/categories/index');
	}
	
	
	public function getDatatable()
	{
		/*	return Datatable::Query(	DB::connection("project")
																						->table("products_categories")
																						->select("products_categories.id","products_categories_detail.title", "products_categories_detail.id as catid" )
																						->join('products_categories_detail','products_categories_detail.product_category_id','=','products_categories.id')
																						->where("products_categories.id",">","0")	)
			*/																			
																						
			return Datatable::Query(
														DB::connection('project')
																->table('productscategorytree')
																->select(
																			'id', 
																			(DB::connection("project")->raw('concat(repeat(\'-\', level),\' \',  productcategory) as productcategory')),
																			'detail_id',
																			(DB::connection("project")->raw('Concat("<img src=\'/images/flag-",lcase(substr(regio,4)),".png\' >") as regio'))
																		)
																->where('id','>','0')
																->orderBy('language_id','asc')
																->orderBy('sort_id','asc')
															)

											->showColumns('productcategory','regio')
											->addColumn('edit',function($model){
															return '<form method="POST" action="/admin/products/categories/'.$model->detail_id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
																					<a class="btn btn-xs btn-default" href="/admin/products/categories/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
																					<button class="btn btn-xs btn-default" type="submit" value="Delete this product category" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
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
		return View::make('dcms::products/categories/form')
			->with('languages',$languages)
			->with('categoryOptionValues',Categorytree::OptionValueTreeArray(true));
	}

	public function generatePageTree()
	{
		$Languages = Language::all();
		foreach($Languages as $Lang)
		{
			DB::connection("project")->statement(DB::connection("project")->raw('CALL recursiveproductscategory(0,0,'.$Lang->id.',\'\',\'\',\'\',0);'));
		}
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
		$rules = array(
//			'title'       => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/products/categories/create')
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
									$category->parent_id = $input["parent_id"];
									$category->sort_id = $input["sort_id"];
									$category->admin =  Auth::user()->username;
									$category->save();
							}
							$translatedCategory = new Category; 
							$translatedCategory->title = $input["title"][$language_id];// Input::get('langtitle.1');
							$translatedCategory->language_id = $language_id;
							
							$translatedCategory->url_slug = DCMSFunctions::SEOUrl($input["title"][$language_id]); 
							$translatedCategory->url_path = DCMSFunctions::SEOUrl($input["title"][$language_id]); 
							
							$translatedCategory->admin =  Auth::user()->username;
							$translatedCategory->save();			
							CategoryID::find($category->id)->category()->save($translatedCategory);
					}//end trim strlen title
				}//end foreach
			}//end if isset(langtitle)
			
			$this->generatePageTree();
			
			// redirect
			Session::flash('message', 'Successfully created category!');
			return Redirect::to('admin/products/categories');
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
   
	 	$languages = DB::connection("project")->select('SELECT languages.id, language, country, language_name, products_categories_detail.*
			FROM products_categories 
			LEFT JOIN products_categories_detail on products_categories.id = products_categories_detail.product_category_id
			LEFT JOIN languages on products_categories_detail.language_id = languages.id
			WHERE  languages.id is not null AND  products_categories.id= ?
			UNION
			SELECT languages.id , language, country, language_name, \'\' , \'\' , languages.id , \'\' , \'\' , \'\' , \'\' , \'\' , \'\' 
			FROM languages 
			WHERE id NOT IN (SELECT language_id FROM products_categories_detail WHERE product_category_id = ?) ORDER BY 1
			', array($id,$id));

		return View::make('dcms::products/categories/form')
			->with('category', $category)
			->with('languages',$languages)
			->with('categoryOptionValues',Categorytree::OptionValueTreeArray(true));
	}
	
	/**
	 * copy the model
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function copy($id)
	{
		/*
		$category = CategoryID::find($id);
		
			$Newcategory = new CategoryID;
			$Newcategory->admin =  Auth::user()->username;
			$Newcategory->save();
	*/
		return Redirect::to('admin/products/categories');
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
			//			'title'       => 'required'
		);
		
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/products/categories/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			$input = Input::get();
			if (isset($input["title"]) && count($input["title"])>0)
			{
				$category = CategoryID::find($id);
				$category->admin =  Auth::user()->username;
				$category->parent_id = $input["parent_id"];
				$category->sort_id = $input["sort_id"];
				
				foreach($input["title"] as $language_id => $title)
				{
					if (trim(strlen($title))>0)
					{
							if(!isset($category))
							{
									$category = new CategoryID;
									$category->parent_id = $input["parent_id"];
									$category->sort_id = $input["sort_id"];
									$category->admin =  Auth::user()->username;
									$category->save();
							}
							
							$translatedCategory = Category::find($input["product_category_id"][$language_id]);
							if (is_null($translatedCategory) === true)  // if we couln't find a Model for the given PIM-id we need to create/add a new one.
							{
								$translatedCategory = new Category;
							}
							$translatedCategory->title = $input["title"][$language_id];// Input::get('langtitle.1');
							$translatedCategory->language_id = $language_id;
							
							$translatedCategory->url_slug = DCMSFunctions::SEOUrl($input["title"][$language_id]); 
							$translatedCategory->url_path = DCMSFunctions::SEOUrl($input["title"][$language_id]); 
							
							$translatedCategory->admin =  Auth::user()->username;
							$translatedCategory->save();			
							CategoryID::find($category->id)->category()->save($translatedCategory);
					}//end trim strlen title
				}//end foreach
			}//end if isset(langtitle)
			
			$this->generatePageTree();
			
			// redirect
			Session::flash('message', 'Successfully updated category!');
			return Redirect::to('admin/products/categories');
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
		$category->delete();

		$this->generatePageTree();
			
		// redirect
		Session::flash('message', 'Successfully deleted the category!');
		return Redirect::to('admin/products/categories');
	}
}

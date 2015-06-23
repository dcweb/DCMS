<?php

namespace Dcweb\Dcms\Controllers\Products;

use Dcweb\Dcms\Models\Products\CategoryID;
use Dcweb\Dcms\Models\Products\Categorytree;
use Dcweb\Dcms\Models\Products\Category;
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
use Config;

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
			return Datatable::Query(
														DB::connection('project')
																->table('productscategorytree')
																->select(
																			'id', 
																			(DB::connection("project")->raw('concat(repeat(\'-\', level),\' \',  productcategory) as productcategory')),
																			'detail_id',
																			(DB::connection("project")->raw('Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(substr(regio,4)),".png\' >") as regio'))
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
		$languages = DB::connection("project")->table("languages")->select((DB::connection("project")->raw("(select max(sort_id)+1 from products_categories_language where language_id = languages.id) as sort_id, '' as title, '' as description")), "id","id as language_id", "language","country","language_name")->get();
		
		// load the create form (app/views/categories/create.blade.php)
		return View::make('dcms::products/categories/form')
			->with('languages',$languages)
			->with('categoryOptionValues',Categorytree::OptionValueTreeArray(true))
			->with('sortOptionValues',$this->getSortOptions(1))
			->with('sortOptionLanguageValues',$this->getSortOptionsLanguage(1));
	}

	public function generateCategoryTree()
	{
		$Languages = Language::all();
		$mysqli = new \mysqli(Config::get("database.connections.project.host"), Config::get("database.connections.project.username"), Config::get("database.connections.project.password"), Config::get("database.connections.project.database"));
		foreach($Languages as $Lang)
		{	
			//DB::connection("project")->statement(DB::connection("project")->raw('CALL recursiveproductscategory(1,0,'.$Lang->id.',\'\',\'\',\'\',0);'));
			
			$mysqli->multi_query('CALL recursiveproductscategory(1,0,'.$Lang->id.',\'\',\'\',\'\',0);');
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
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$Languages = Language::all();
		//$rules = array('sort_id'=>'required|integer');
		foreach($Languages as $Lang)
		{
			$rules['title.'.$Lang->id] = 'required';
		}
		
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
				//					$category->sort_id = $input["catsort_id"];
									$category->admin =  Auth::dcms()->user()->username;
									$category->save();
							}
							$translatedCategory = new Category; 
							$translatedCategory->title = $input["title"][$language_id];// Input::get('langtitle.1');
							$translatedCategory->language_id = $language_id;							
							$translatedCategory->sort_id = $input["sort_id"][$language_id];
							
							$translatedCategory->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
							$translatedCategory->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
							
							$translatedCategory->admin =  Auth::dcms()->user()->username;
							$translatedCategory->save();			
							
							$this->updatesort($translatedCategory->id, $language_id,	null, $input["sort_id"][$language_id]);
							
							CategoryID::find($category->id)->category()->save($translatedCategory);
					}//end trim strlen title
				}//end foreach
			}//end if isset(langtitle)
			
			$this->generateCategoryTree();
			
			// redirect
			Session::flash('message', 'Successfully created category!');
			return Redirect::to('admin/products/categories');
		}
	}

	
	public function getSortOptions($setExtra = 0 )
	{
		$sort_id = DB::connection("project")->table('products_categories')->max('sort_id');

		for($i = 0; $i<($sort_id+$setExtra); $i++)
		{
			$x = $i+1;
			$SortOptions[$x] = $x;
		}

		return $SortOptions;
	}	

	
	public function getSortOptionsLanguage($setExtra = 0 )
	{
		$result = DB::connection("project")->select('SELECT languages.id as language_id, (SELECT case when max(sort_id) is null then 0 else max(sort_id) end FROM products_categories_language WHERE products_categories_language.language_id = languages.id) as sort_id FROM languages ');
	
		$SortOptionsLanguage = array();		
		if(!is_null($result) && count($result)>0)
		{
			foreach($result as $i => $Model)
			{
				for($i = 0; $i<($Model->sort_id+$setExtra); $i++)
				{
					$x = $i+1;
					$SortOptionsLanguage[$Model->language_id][$x] = $x;
				}
			}
		}
		
		return $SortOptionsLanguage;
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

	 	$languages = DB::connection("project")->select('SELECT languages.id, language, country, language_name, products_categories_language.*
			FROM products_categories 
			LEFT JOIN products_categories_language on products_categories.id = products_categories_language.product_category_id
			LEFT JOIN languages on products_categories_language.language_id = languages.id
			WHERE  languages.id is not null AND  products_categories.id= ?
			UNION
			SELECT languages.id , language, country, language_name, \'\' , \'\' , languages.id , \'\' , \'\' , \'\' , \'\' , \'\' , \'\', \'\' 
			FROM languages 
			WHERE id NOT IN (SELECT language_id FROM products_categories_language WHERE product_category_id = ?) ORDER BY 1
			', array($id,$id));

		return View::make('dcms::products/categories/form')
			->with('category', $category)
			->with('languages',$languages)
			->with('categoryOptionValues',Categorytree::OptionValueTreeArray(true,array('*'),array("id","productcategory","language_id","level"))) //CategoryID::OptionValueArray(true))
			->with('sortOptionValues',$this->getSortOptions())
			->with('sortOptionLanguageValues',$this->getSortOptionsLanguage());
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
			$Newcategory->admin =  Auth::dcms()->user()->username;
			$Newcategory->save();
	*/
		return Redirect::to('admin/products/categories');
	}
	
	public function updatesort($category_language_id,$language_id, $old_sort_id, $new_sort_id)
	{
		//DB::table('users')->increment('votes', 1, array('name' => 'John'));		
		if(is_null($old_sort_id) || $old_sort_id == 0)
		{
			DB::connection('project')->raw('UPDATE products_categories_language SET sort_id = sort_id +1 WHERE product_category_id <> 1 AND sort_id >= '.$new_sort_id.' AND id <> '.$category_language_id.' AND language_id = '.$language_id.'  ');
		}
		elseif ($old_sort_id > $new_sort_id)
		{	
			DB::connection('project')->raw('UPDATE products_categories_language SET sort_id = sort_id +1 WHERE product_category_id <> 1 AND sort_id >= '.$new_sort_id.' AND sort_id < '.$old_sort_id.' AND id <> '.$category_language_id.' AND language_id = '.$language_id.'  ');
		}
		elseif ($old_sort_id < $new_sort_id)
		{	
			DB::connection('project')->raw('UPDATE products_categories_language SET sort_id = sort_id - 1 WHERE product_category_id <> 1 AND sort_id >= '.$old_sort_id.' AND sort_id < '.$new_sort_id.' AND id <> '.$category_language_id.' AND language_id = '.$language_id.'  ');
		}
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
	//	$rules = array('sort_id'=>'required|integer');
		foreach($Languages as $Lang)
		{
			$rules['title.'.$Lang->id] = 'required';
		}		
		
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
				if(!is_null($category))
				{
					$category->admin =  Auth::dcms()->user()->username;
					$category->parent_id = $input["parent_id"];
	//				$category->sort_id = $input["catsort_id"];
					$category->save();
				}
								
				foreach($input["title"] as $language_id => $title)
				{
					if (trim(strlen($title))>0)
					{
							if(!isset($category))
							{
									$category = new CategoryID;
									$category->parent_id = $input["parent_id"];
			//						$category->sort_id = $input["catsort_id"];
									$category->admin =  Auth::dcms()->user()->username;
									$category->save();
							}
							
							$translatedCategory = Category::find($input["product_category_id"][$language_id]);
							if (is_null($translatedCategory) === true)  // if we couln't find a Model for the given PIM-id we need to create/add a new one.
							{
								$translatedCategory = new Category;
							}
							
							$oldsort_id = $translatedCategory->sort_id;
							
							$translatedCategory->title = $input["title"][$language_id];// Input::get('langtitle.1');
							$translatedCategory->language_id = $language_id;
							$translatedCategory->sort_id = $input["sort_id"][$language_id];
							
							$translatedCategory->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
							$translatedCategory->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
							
							$translatedCategory->admin =  Auth::dcms()->user()->username;
							$translatedCategory->save();			
							
							$this->updatesort($translatedCategory->id, $language_id,	$oldsort_id, $input["sort_id"][$language_id]);
							
							CategoryID::find($category->id)->category()->save($translatedCategory);
					}//end trim strlen title
				}//end foreach
			}//end if isset(langtitle)
			
			$this->generateCategoryTree();
			
			// redirect
			Session::flash('message', 'Successfully updated category!');
			return Redirect::to('admin/products/categories');
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
			$this->generateCategoryTree();
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
		$mainCategoryID = $category->product_category_id;
		$category->delete();
		
		if (Category::where("product_category_id","=",$mainCategoryID)->count() <= 0)
		{
			CategoryID::destroy($mainCategoryID);
		}

		$this->generateCategoryTree();
			
		// redirect
		Session::flash('message', 'Successfully deleted the category!');
		return Redirect::to('admin/products/categories');
	}
}

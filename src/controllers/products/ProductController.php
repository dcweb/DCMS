<?php
namespace Dcweb\Dcms\Controllers\Products;

use Dcweb\Dcms\Models\Products\Product;
use Dcweb\Dcms\Models\Products\Information;
use Dcweb\Dcms\Models\Products\Price;
use Dcweb\Dcms\Models\Products\CategoryID;
use Dcweb\Dcms\Models\Products\Categorytree;
use Dcweb\Dcms\Controllers\BaseController;
use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use Dcweb\Dcms\Helpers\Helper\SEOHelpers;

class ProductController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('dcms::products/products/index');
	}
		
	/**
	 * $mDefaults contains an array of Price-Models 
	 *
	 * @return the row to inject prices
	 */
	public function getPriceRow($mDefaults=array(),$forceEmpty = false)
	{
		$rowstring = ""; 
		
		$openbody = true;
		if ($forceEmpty === true && empty($mDefaults) === true)
		{
			$openbody = false;
			$mDefaults[] = (object) array();
		}
		
		foreach($mDefaults as $Price)
		{
					$country_option = ""; 
					foreach($this->getCountries("array") as $countryid => $country)
					{
						$selected = ""; 
					//	if (isset($mDefaults["price-country-id"]) &&  $mDefaults["price-country-id"] == $Price->country_id) $selected = "selected";
						if (isset($Price->country_id) && $countryid ==$Price->country_id) $selected = "selected";
						
						$country_option .= '<option value="'.$countryid.'" '.$selected.'>'.$country.'</option>';
					}
					
					$tax_option = ""; 
					foreach($this->getTaxClasses("array") as $taxid => $tax)
					{
						$selected = ""; 
						if (isset($Price->tax_class_id) && $taxid == $Price->tax_class_id) $selected = "selected";
						
						$tax_option .= '<option value="'.$taxid.'" '.$selected.' >'.$tax.'</option>';
					}
					
					if ($openbody === true ) $rowstring .= '<tbody xxx '.$openbody.'>';
					
					//------------------------------------------------------------------------
					// 							TEMPLATE FOR THE PRICE ROW
					//------------------------------------------------------------------------
					$rowstring .= ' <tr>
														<td>
															<select id="price-country-id[{INDEX}]" class="form-control" name="price-country-id[{INDEX}]">
																'.$country_option.'
															</select>
														</td>
														<td>
															<input id="price[{INDEX}]" name="price[{INDEX}]" class="form-control" type="text" value="'.(isset($Price->price)?$Price->price:"").'">
														</td>
														<td>
															<select id="valuta_class_id[{INDEX}]" name="valuta_class_id[{INDEX}]" class="form-control">
																<option value="1">euro</option>
															</select>
														</td>
														<td>
															<select id="tax_class_id[{INDEX}]" name="tax_class_id[{INDEX}]" class="form-control">
																'.$tax_option.'
															</select>
														</td>
														<td><a class="btn btn-default pull-right delete-table-row" href=""><i class="fa fa-trash-o"></i></a></td>
													</tr>';
					
					if ($openbody === true) $rowstring .= '</tbody>';
					
					if (isset($Price->id) && intval($Price->id)>0) $rowstring = str_replace("{INDEX}",$Price->id,$rowstring);
					$openbody = false; 
		}
		return $rowstring;
	}
	
	public function getTableRow()
	{
		if (Input::get("data") === "price") 
		{
			return $this->getPriceRow(null, true);
		}
	}	
	
	/**
	 * return the requested json data.
	 *
	 * @return json data
	 */
	public function json()
	{
		$term = Input::get("term");
		$language_id = intval(Input::get("language"));
		//the json autoload tool needs some 
		$pData = Information::select('id','title as label', 'description')->where('title','LIKE','%'.$term.'%')->where('language_id','=',$language_id)->get()->toJson();
		return $pData;
	}	
	
	/**
	 * get the data for DataTable JS plugin.
	 *
	 * @return Response
	 */
	public function getDatatable()
	{
		/* 		FULL QUERY
		//--------------------
				select 
				`products`.`id`, 
				`products`.`code`, 
				`products`.`eancode`, 
				`products_to_products_information`.`product_information_id` as `info_id`, 
				`products_information`.`title`, 
				
				concat("<img src='/packages/dcweb/dcms/assets/images/flag-", lcase(settings.country),".png' >") as country_settings,
				concat("<img src='/packages/dcweb/dcms/assets/images/flag-", lcase(selling.country),".png' >",title) as country_selling,
				
					(	select group_concat(DISTINCT cast(  concat("<img src='/packages/dcweb/dcms/assets/images/flag-", lcase(countries.country),".png' >") as char(255)) SEPARATOR '  ' ) 
						from products_price 
						left join countries on products_price.country_id = countries.id 
						where  products_price.product_id = products.id 
						group by product_id)  as all_selling_countries
				
				from `products` 
				left join `products_to_products_information` on `products`.`id` = `products_to_products_information`.`product_id` 
				left join `products_information` on `products_information`.`id` = `products_to_products_information`.`product_information_id` 
				left join `languages` on `products_information`.`language_id` = `languages`.`id` 
				left join products_price on products_price.product_id = products.id and products_price.country_id = languages.country_id
				left join countries as selling on products_price.country_id = selling.id
				left join countries as settings on languages.country_id = settings.id
				
				order by `code` asc 
				limit 50;
		*/
		
				return Datatable::Query(
																	DB::connection("project")->table("products")->select(
																								"products.id", 
																								"products.code", 
																								"products.eancode",
																								"products_to_products_information.product_information_id as info_id",
																								"title",
																								//(DB::connection("project")->raw("concat(\"<img src='/packages/dcweb/dcms/assets/images/flag-\", lcase(selling.country),\".png' > \",title) as title")) ,//the title with its country
																							
																								(DB::connection("project")->raw("concat(\"<img src='/packages/dcweb/dcms/assets/images/flag-\", lcase(selling.country),\".png' > \") as country")) 

																								//Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(country),".png\' > ",title) as country,
																								/*
																								(DB::connection("project")->raw('(select group_concat(DISTINCT cast(  concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-", lcase(countries.country),".png\' >") as char(255)) order by countries.country asc SEPARATOR \'  \' ) 
from products_price 
left join countries on products_price.country_id = countries.id 
where  product_id = products.id 
group by product_id)  as country')) // all countries where this is for sale*/
																								)
																								->leftJoin('products_to_products_information','products.id','=','products_to_products_information.product_id')
																								->leftJoin('products_information','products_information.id','=','products_to_products_information.product_information_id')
																								->leftJoin('languages','products_information.language_id', '=' , 'languages.id')
																								->leftJoin('products_price', function($join){
																										 $join->on('products_price.product_id', '=', 'products.id');
																										 $join->on('products_price.country_id', '=', 'languages.country_id');
																									})
																								->leftJoin('countries as selling', 'products_price.country_id' ,'=' ,'selling.id')
																								->leftJoin('countries as settings', 'languages.country_id' ,'=' ,'settings.id')
																									
																								/*
left join products_price on products_price.product_id = products.id and products_price.country_id = languages.country_id
left join countries as selling on products_price.country_id = selling.id
left join countries as settings on languages.country_id = settings.id
*/
															)
						->showColumns('code')
						->showColumns('eancode')
						->showColumns('title')
						->showColumns('country')
						->addColumn('edit',function($model){return '<form method="POST" action="/admin/products/'.(isset($model->info_id)?$model->info_id:$model->id).'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
						<input type="hidden" name="table" value="'.(isset($model->info_id)?"information":"product").'"/>
								<a class="btn btn-xs btn-default" href="/admin/products/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
								<a class="btn btn-xs btn-default" href="/admin/products/'.$model->id.'/copy"><i class="fa fa-copy"></i></a>
								<button class="btn btn-xs btn-default" type="submit" value="Delete this product category" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
							</form>';})
						->setSearchWithAlias()
						->searchColumns('code','eancode','title')
						->make(); 
						
	}

	public function getCountries($ModelArray = "array")
	{
		$oCountries =  DB::connection("project")->select('SELECT id, country_name FROM countries');
		if ($ModelArray === "model") {
			return $oCountries;
		}
		else
		{
			$aCountries = array();
			foreach($oCountries as $c)
			{
				$aCountries[$c->id] = $c->country_name;
			}
			return $aCountries;
		}
	}

	public function getTaxClasses($ModelArray = "array")
	{
		//volumeclasses
		//there is no model for VOLUMES so no eloquent querying here
		$oTaxClasses =  DB::connection("project")->select('SELECT id, tax_class as tax FROM tax_class');
		
		if ($ModelArray === "model")
		{
			return $oTaxClasses;
		}
		else
		{
				//there was no support for the lists() method
				$aTaxClasses = array();
				foreach($oTaxClasses as $v)
				{
					$aTaxClasses[$v->id] = $v->tax;
				}
				return $aTaxClasses;
		}
	}
	
	public function getVolumesClasses($ModelArray = "array")
	{
		//volumeclasses
		//there is no model for VOLUMES so no eloquent querying here
		$oVolumeClasses =  DB::connection("project")->select('SELECT id, volume_class as volume FROM volumes_class');
		
		if ($ModelArray === "model")
		{
			return $oVolumeClasses;
		}
		else
		{
			//there was no support for the lists() method
			$aVolumesClasses = array();
			foreach($oVolumeClasses as $v)
			{
				$aVolumesClasses[$v->id] = $v->volume;
			}
			return $aVolumesClasses;
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//available languages
		$languageinformation = DB::connection("project")->table("languages")->select( (DB::connection("project")->raw("'' as title, '' as description, NULL as sort_id, (select max(sort_id) from products_information where language_id = languages.id) as maxsort, '' as information_id, '' as id , '' as product_category_id")), "id as language_id", "language","language_name","country")->get();
		
		$this->getVolumesClasses();
				
		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::products/products/form')
					->with('languageinformation',$languageinformation)
					->with('volumeclasses',$this->getVolumesClasses("array"))
					->with('taxclasses',$this->getTaxClasses("array"))
					->with('categoryOptionValues',Categorytree::OptionValueTreeArray(false)) //CategoryID::OptionValueArray(true)) //category::optionvaluearray will return a multidimensional array $a[languageid][catid]=catTitle;
					->with('sortOptionValues',$this->getSortOptions($languageinformation,1));
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
			'code'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/products/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			//--------------------------------
			// PRODUCT 
			//--------------------------------	
			$product = new Product;
			$product->code   		= Input::get('code');
			$product->eancode 	= Input::get('eancode');
			$product->image 		= Input::get('image');
			$product->volume 		= Input::get('volume');
			$product->volume_unit_class 	= Input::get('volume_unit_class');
			$product->admin =  Auth::user()->username;
			$product->save();
			
			//--------------------------------
			// PRODUCT DATA (PIM)
			//--------------------------------	
			$input = Input::get();
			
			if (isset($input["information_language_id"]) && count($input["information_language_id"])>0)
			{
				foreach($input["information_language_id"] as $i => $language_id)
				{
					if (strlen(trim($input["information_name"][$i]))>0){
						$pInformation = new Information; 
						$pInformation->title 				= $input["information_name"][$i]; // Input::get('pim-name.1');
						$pInformation->description 	= $input["information_description"][$i];//Input::get('pim-description.1');
						$pInformation->language_id 	= $language_id;
						$pInformation->sort_id 			= $input["information_sort_id"][$i];
						$pInformation->product_category_id = ($input["information_category_id"][$i]==0?NULL:$input["information_category_id"][$i]);
						$pInformation->url_slug 		= SEOHelpers::SEOUrl($input["information_name"][$i]); 
						$pInformation->url_path 		= SEOHelpers::SEOUrl($input["information_name"][$i]); 
						$pInformation->admin 				=  Auth::user()->username;
						$pInformation->save();			
						$product->information()->attach($pInformation->id);		
						
						//we may have saved this on a sort_id that had been occupied before..
						// so best to fetch all information items with an equal or higher sort, so we can increment their sortid by 1
						$updateInformations = Information::where('language_id','=',$language_id)->where('sort_id','>=',$input["information_sort_id"][$i])->where('id','<>',$pInformation->id)->get();
						if (count($updateInformations)>0)
						{
							foreach($updateInformations as $Information)
							{
								$Information->sort_id = $Information->sort_id +1;
								$Information->save();
							}//end foreach($updateInformations as $Information)
						}//end 	if (count($updateInformations)>0)
					}//if (strlen(trim($input["information_name"][$i]))>0){
				}//foreach($input["information_language_id"] as $i => $language_id)
			}//if (isset($input["information_language_id"]) && count($input["information_language_id"])>0)
			
			//--------------------------------------------
			// PRODUCT PRICES (Availability per country)
			//--------------------------------------------
			if (isset($input["price-country-id"]) && count($input["price-country-id"])>0)
			{
				foreach($input['price-country-id'] as $i => $v)
				{
						$pPrice = new Price;
						$pPrice->country_id 			= $input['price-country-id'][$i];
						$pPrice->price 						= str_replace(",",".",$input['price'][$i]);
						$pPrice->product_id 			= $product->id;
						$pPrice->valuta_class_id 	= $input['valuta_class_id'][$i];
						$pPrice->tax_class_id 		= $input['tax_class_id'][$i];
						$pPrice->admin 						=  Auth::user()->username;
						$pPrice->save();
				}
			}
	
			// redirect
			Session::flash('message', 'Successfully created Product!');
			return Redirect::to('admin/products');
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
		// get the Product
		$product = Product::find($id);	
		
		$languageinformation = DB::connection("project")->select('
														SELECT products_information.language_id ,sort_id, (select max(sort_id) from products_information as X  where X.language_id = products_information.language_id) as maxsort, language, language_name, country, products_information.title, products_information.description, products_information.id as information_id, product_category_id
														FROM  products
														INNER JOIN products_to_products_information on products.id = products_to_products_information.product_id
														INNER JOIN products_information on products_to_products_information.product_information_id = products_information.id
														INNER JOIN languages on languages.id = products_information.language_id
														WHERE products.id = ? 
														
														UNION
														SELECT languages.id as language_id , 0, (select max(sort_id) from products_information where language_id = languages.id), language, language_name, country, \'\' as title, \'\' as description, \'\' as information_id , \'\' 
														FROM languages 
														WHERE id NOT IN (SELECT language_id FROM products_information WHERE id IN (SELECT product_information_id FROM products_to_products_information WHERE product_id = ?)) ORDER BY 1 ', array($id,$id));
		
		//build the rows with the prices based on the query result / model
		$mPrices= DB::connection("project")->select('SELECT products_price.id , country_id, product_id, country_name, price, valuta_class_id, tax_class_id FROM products_price INNER JOIN countries ON countries.id = products_price.country_id WHERE product_id = ? ',array($id));
		$rowPrices = $this->getPriceRow($mPrices);
		
		// show the edit form and pass the product
		return View::make('dcms::products/products/form')
			->with('product', $product)
			->with('languageinformation', $languageinformation)
			->with('volumeclasses', $this->getVolumesClasses("array"))
			->with('taxclasses', $this->getTaxClasses("array"))
			->with('rowPrices', $rowPrices)
			->with('countries', $this->getCountries("model"))
			->with('categoryOptionValues',Categorytree::OptionValueTreeArray(false)) //CategoryID::OptionValueArray(true))
			->with('sortOptionValues',$this->getSortOptions($languageinformation));
	}
	
	public function getSortOptions($model,$setExtra = 0)
	{
		foreach($model as $M)
		{
			$increment = 0;
			if ($setExtra > 0) $increment = $setExtra;
			if(intval($M->information_id)<=0 && !is_null($M->maxsort)) $increment = 1;
			
			$maxSortID  = $M->maxsort;
			if (is_null($maxSortID) ) $maxSortID = 1;
			
			for($i = 1; $i<=($maxSortID+$increment); $i++)
			{
				$SortOptions[$M->language_id][$i] = $i;
			}
		}
		return $SortOptions;
	}	
	
	/**
	 * copy the model
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function copy($id)
	{
		$Newproduct= Product::find($id)->replicate();
		$Newproduct->save();
		
		return Redirect::to('admin/products');
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
			'code'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/products/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			
			// store
			//--------------------------------
			// PRODUCT UPDATE
			//--------------------------------	
			$Product = Product::find($id);
			
			if (!isset($Product) ||is_null($Product))
			{
				$Product = new Product();
			}
			
			$Product->code  		= Input::get('code');
			$Product->eancode		= Input::get('eancode');
			$Product->image			= Input::get('image');
			$Product->volume 		= Input::get('volume');
			$Product->volume_unit_class 	= Input::get('volume_unit_class');
			$Product->admin 		=  Auth::user()->username;
			$Product->save();
	
			////if you want to release the entire relations in the pivot table		
			$Product->information()->detach();
			
			$uPrice= Price::where('product_id','=',$id);
			$uPrice->update(array('product_id'=>null));
			
			//https://github.com/laravel/framework/pull/3326
			//$Product->price()->dissociate();
			//--------------------------------
			// PRODUCT DATA (PIM)
			//--------------------------------	
			$input = Input::get();
			
			if (isset($input["information_language_id"]) && count($input["information_language_id"])>0)
			{
				foreach($input["information_language_id"] as $i => $language_id)
				{
						$processThisFormPart = true; //by default we may process every languages
						
						$pInformation = Information::find($input["information_id"][$i]);  //we make an update when we get an PIM-id(products_data.id) from the form
						if (is_null($pInformation) === true)  // if we couln't find a Model for the given PIM-id we need to create/add a new one.
						{
							if (strlen(trim($input["information_name"][$i]))<=0) // we don't need a new Productdata(PIM) record when there is no name given - should also return an error on the validator.
							{
								$processThisFormPart = false;
							}
							else	
							{
								$pInformation = new Information();
							}
						}
						
						//we only need to process when there is a name given
						if ($processThisFormPart  === true)
						{
							$oldSortID = intval($pInformation->sort_id);
							
							$pInformation->title 				= $input["information_name"][$i]; // Input::get('pim-name.1');
							$pInformation->description 	= $input["information_description"][$i];//Input::get('pim-description.1');
							$pInformation->sort_id 			= $input["information_sort_id"][$i];
							$pInformation->language_id 	= $language_id;
							$pInformation->product_category_id = ($input["information_category_id"][$i]==0?NULL:$input["information_category_id"][$i]);
							$pInformation->url_slug = SEOHelpers::SEOUrl($input["information_name"][$i]); 
							$pInformation->url_path = SEOHelpers::SEOUrl($input["information_name"][$i]); 
							$pInformation->admin 				=  Auth::user()->username;
							$pInformation->save();	
							$Product->information()->attach($pInformation->id);			
							
							//we may have saved this on a sort_id that had been occupied before..
							// so best to fetch all information items with an equal or higher sort, so we can increment their sortid by 1
							$sort_incrementstatus = "0";
							if($oldSortID == 0)
							{
								$updateInformations = Information::where('language_id','=',$language_id)->where('sort_id','>=',$input["information_sort_id"][$i])->where('id','<>',$pInformation->id)->get();
								$sort_incrementstatus = "+1";
							}
							elseif ($oldSortID > $input["information_sort_id"][$i])
							{	
								$updateInformations = Information::where('language_id','=',$language_id)->where('sort_id','>=',$input["information_sort_id"][$i])->where('sort_id','<',$oldSortID)->where('id','<>',$pInformation->id)->get();
								$sort_incrementstatus = "+1";
							}
							elseif ($oldSortID < $input["information_sort_id"][$i])
							{	
								$updateInformations = Information::where('language_id','=',$language_id)->where('sort_id','>',$oldSortID)->where('sort_id','<=',$input["information_sort_id"][$i])->where('id','<>',$pInformation->id)->get();
								$sort_incrementstatus = "-1";
							}
							
							if ($sort_incrementstatus <> "0")
							{
								if (isset($updateInformations) && count($updateInformations)>0)
								{
									foreach($updateInformations as $Information)
									{
										if($sort_incrementstatus == "+1") 
										{
											$Information->sort_id = $Information->sort_id + 1;
											$Information->save();
										}
										elseif($sort_incrementstatus == "-1") 
										{
											$Information->sort_id = $Information->sort_id -1 ;
											$Information->save();
										}
									}//end foreach($updateInformations as $Information)
								}//end 	if (count($updateInformations)>0)
							}//$sort_incrementstatus <> "0"
						}
				}			
			}
	
			//---------------------------------------------
			// PRODUCT PRICE (Availability per country)
			//---------------------------------------------	
			if (isset($input["price-country-id"]) && count($input["price-country-id"])>0)
			{
					Price::where('product_id', '=', $id)->update(array('product_id' => NULL));	//we should remove all relations with this product
					foreach($input['price-country-id'] as $i => $v)
					{
							$pPrice = Price::find($i);  //we make an update when we get an PIM-id(products_data.id) from the form
							if (is_null($pPrice) === true)  // if we couln't find a Model for the given PIM-id we need to create/add a new one.
							{
								$pPrice = new Price;
							}
							$pPrice->country_id 		= $input['price-country-id'][$i];
							$pPrice->price 					= str_replace(",",".",$input['price'][$i]);
							$pPrice->product_id 		= $Product->id;
							$pPrice->valuta_class_id = $input['valuta_class_id'][$i];
							$pPrice->tax_class_id 	= $input['tax_class_id'][$i];
							$pPrice->admin 					=  Auth::user()->username;
							$pPrice->save();
					}
					Price::where('product_id','=',null)->delete();
			}
			
			// redirect
			Session::flash('message', 'Successfully updated Product!');
			return Redirect::to('admin/products');
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
		if (Input::get('table') == 'product')
		{
			Product::find($id)->delete();
			Price::where("product_id","=",$id)->delete();
		}
		else
		{
			// delete
			$Information = Information::find($id);
			$Information->products()->detach();
			$Information->delete();
		}
		// redirect
		Session::flash('message', 'Successfully deleted the Product!');
		return Redirect::to('admin/products');
	}
}
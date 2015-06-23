<?php

namespace Dcweb\Dcms\Models\Products;
use Dcweb\Dcms\Models\Languages\Language;
use Dcweb\Dcms\Models\EloquentDefaults;

	class CategoryID extends EloquentDefaults
	{
		protected $table  = "products_categories";
		
		protected $connection = 'project';
		
		
		public function category()
		{
			  return $this->hasMany('\Dcweb\Dcms\Models\Products\Category','product_category_id','id');
		}
		
		public function parentcategory()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Products\CategoryID', 'parent_id','id');
		}
	}
		
	class Categorytree extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "productscategorytree";
		
					
		//the columnMapper is an array with integer index values
		// 0 represesenting the id column
		// 1 		"		 "	value "
		public static function OptionValueTreeArray($enableEmpty = false, $columns = array('*') , $columnMapper = array("detail_id","productcategory","language_id","level")){
			
			$PageObj = parent::orderBy('language_id','asc')->orderBy('sort_id','asc')->get($columns);

			$OptionValueArray = array();

			if (count($PageObj)>0)
			{
				foreach($PageObj as $lang)
				{
						if (array_key_exists($lang->language_id, $OptionValueArray)== false ) $OptionValueArray[$lang->language_id] = array();
						
						if ($enableEmpty == true && array_key_exists(0, $OptionValueArray[$lang->language_id])== false )
						{
							$OptionValueArray[$lang->language_id][1] = "- ROOT -";
						}
						//we  make an array with array[languageid][maincategoryid] = translated category;
						$OptionValueArray[$lang->language_id][$lang->$columnMapper[0]]=str_repeat('-',$lang->level).' '.$lang->$columnMapper[1];
				}
			}
			elseif($enableEmpty === true)
			{
				$Languages = Language::all();
				
				foreach($Languages as $Lang)
				{	
					$OptionValueArray[$Lang->id][1] = "- ROOT -";	
				}
			}
			return $OptionValueArray;
		}
	}
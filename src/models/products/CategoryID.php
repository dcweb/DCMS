<?php

namespace Dcweb\Dcms\Models\Products;
use Eloquent;

	class CategoryID extends Eloquent
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
						
		//the columnMapper is an array with integer index values
		// 0 represesenting the id column
		// 1 		"		 "	value "
		public static function OptionTreeValueArray($enableEmpty = false, $columns = array('*') , $columnMapper = array("id","title")){

			$PageObj = parent::with('category')->get($columns);

			$OptionValueArray = array();
	
			foreach($PageObj as $page)
			{
				foreach($page->category as $lang)
				{
					if (array_key_exists($lang->language_id, $OptionValueArray)== false ) $OptionValueArray[$lang->language_id] = array();
					
					if ($enableEmpty == true && array_key_exists(0, $OptionValueArray[$lang->language_id])== false )
					{
						$OptionValueArray[$lang->language_id][0] = "-";
					}
					//we  make an array with array[languageid][maincategoryid] = translated category;
					$OptionValueArray[$lang->language_id][$page->$columnMapper[0]]=$lang->$columnMapper[1];
				}
			}
			return $OptionValueArray;
		}
		
				
		//the columnMapper is an array with integer index values
		// 0 represesenting the id column
		// 1 		"		 "	value "
		public static function OptionValueArray($enableEmpty = false, $columns = array('*') , $columnMapper = array("id","title")){

			$catObj = parent::all($columns);
			$OptionValueArray = array(array());
	
			foreach($catObj as $catlang)
			{
				foreach($catlang->category as $lang)
				{
					if (array_key_exists($lang->language_id, $OptionValueArray)== false ) $OptionValueArray[$lang->language_id] = array();
					
					if ($enableEmpty == true && array_key_exists(0, $OptionValueArray[$lang->language_id])== false )
					{
						$OptionValueArray[$lang->language_id][0] = "-";
					}
					
					//we  make an array with array[languageid][maincategoryid] = translated category;
					$OptionValueArray[$lang->language_id][$catlang->$columnMapper[0]]=$lang->$columnMapper[1];
					
					
				}
			}
					
			return $OptionValueArray;
		}
	}
	
	
	
	class Categorytree extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "productscategorytree";
		
					
		//the columnMapper is an array with integer index values
		// 0 represesenting the id column
		// 1 		"		 "	value "
		public static function OptionValueTreeArray($enableEmpty = false, $columns = array('*') , $columnMapper = array("id","productcategory","language_id","level")){
			
			$PageObj = parent::orderBy('language_id','asc')->orderBy('sort_id','asc')->get($columns);

			$OptionValueArray = array();
	
			foreach($PageObj as $lang)
			{
					if (array_key_exists($lang->language_id, $OptionValueArray)== false ) $OptionValueArray[$lang->language_id] = array();
					
					if ($enableEmpty == true && array_key_exists(0, $OptionValueArray[$lang->language_id])== false )
					{
						$OptionValueArray[$lang->language_id][0] = "- ROOT -";
					}
					//we  make an array with array[languageid][maincategoryid] = translated category;
					$OptionValueArray[$lang->language_id][$lang->$columnMapper[0]]=str_repeat('-',$lang->level).' '.$lang->$columnMapper[1];
			}
			return $OptionValueArray;
		}
	}
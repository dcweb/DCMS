<?php

namespace Dcweb\Dcms\Models\Articles;
use Eloquent;

	class CategoryID extends Eloquent
	{
		protected $table  = "articles_categories";
		
		protected $connection = 'project';
		
		
		public function category()
		{
			  return $this->hasMany('\Dcweb\Dcms\Models\Articles\Category','article_category_id','id');
		}
				
		//the columnMapper is an array with integer index values
		// 0 represesenting the id column
		// 1 		"		 "	value "
		public static function OptionValueArray($enableEmpty = false, $columns = array('*') , $columnMapper = array("id","title")  ){

			$catObj = parent::all($columns);

			$OptionValueArray = array();
	
			foreach($catObj as $cat)
			{
				foreach($cat->category as $lang)
				{
					if (array_key_exists($lang->language_id, $OptionValueArray)== false ) $OptionValueArray[$lang->language_id] = array();
					
					if ($enableEmpty == true && array_key_exists(0, $OptionValueArray[$lang->language_id])== false )
					{
						$OptionValueArray[$lang->language_id][0] = "-";
					}
					//we  make an array with array[languageid][maincategoryid] = translated category;
					$OptionValueArray[$lang->language_id][$cat->$columnMapper[0]]=$lang->$columnMapper[1];
				}
			}					
			return $OptionValueArray;
		}
	}
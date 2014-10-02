<?php

namespace Dcweb\Dcms\Models\Pages;

use Eloquent;

	class Page extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "pages";
		
		
		public function detail()
		{
			  return $this->hasMany('\Dcweb\Dcms\Models\Pages\Detail','page_id','id');
		}
		
		public function parentpage()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Pages\Page', 'parent_id','id');
		}
		
		
					
		//the columnMapper is an array with integer index values
		// 0 represesenting the id column
		// 1 		"		 "	value "
		public static function OptionValueArray($enableEmpty = false, $columns = array('*') , $columnMapper = array("id","title")){
			
			$PageObj = parent::with('detail')->get($columns);

			$OptionValueArray = array();
	
			foreach($PageObj as $page)
			{
				foreach($page->detail as $lang)
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
	}
	
	class Pagetree extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "pagetree";
		
					
		//the columnMapper is an array with integer index values
		// 0 represesenting the id column
		// 1 		"		 "	value "
		public static function OptionValueArray($enableEmpty = false, $columns = array('*') , $columnMapper = array("id","page","language_id","level")){
			
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
	
	class Detail extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "pages_detail";
		
		
		public function page()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Pages\Page','page_id','id');
		}
		
		public function parentpage()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Pages\Page', 'parent_id','id');
		}
	}
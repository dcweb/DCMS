<?php

namespace Dcweb\Dcms\Models\Countries;

use Dcweb\Dcms\Models\EloquentDefaults;

	class Country extends EloquentDefaults
	{
		protected $connection = 'project';
		protected $table = "countries";
		
				
		//the columnMapper is an array with integer index values
		// 0 represesenting the id column
		// 1 		"		 "	value "
		public static function OptionValueArray($enableEmpty = false, $columns = array('*') , $columnMapper = array("id","country_name")  ){

			$countryObj = parent::all($columns);

			$OptionValueArray = array();
	
			foreach($countryObj as $country)
			{
					if ($enableEmpty == true && array_key_exists(0, $OptionValueArray[$lang->language_id])== false )
					{
						$OptionValueArray[0] = "-";
					}
					//we  make an array with array[languageid][maincategoryid] = translated category;
					$OptionValueArray[$country->id]=$country->$columnMapper[1];
				
			}					
			return $OptionValueArray;
		}
	}
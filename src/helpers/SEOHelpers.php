<?php

namespace Dcweb\Dcms\Helpers\Helper;

//http://amitavroy.com/justread/content/articles/working-workbench-and-building-packages-laravel-4

class SEOHelpers
{		
		/* SEOURL
		formats a string into acceptable URL characters
		***********************************************************/
		public static function SEOUrl ($str, $replace=array(), $delimiter='-')
    {		
				//Database rough query //replace(replace(replace(replace(replace(replace(replace(replace(lcase(producttitleBEFR),' ','-'),'é','e'),'è','e'),'à','a'),'&','-'),'ç','c'),'+',''),'''','')
				$str = str_replace("®","",str_replace("&reg;","",$str));
				$str = str_replace("©","",str_replace("&copy;","",$str));
				if( !empty($replace) ) {
						$str = str_replace((array)$replace, ' ', $str);
				}
		
				$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
				$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
				$clean = strtolower(trim($clean, '-'));
				$clean = strtolower(trim($clean, '-'));
				$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
			
				return $clean;
		}//end function SEOUrl
}
?>
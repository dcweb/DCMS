<?php

namespace Dcweb\Dcms\Helpers\Helper;

class TXTHelpers
{	
		/* get the text and cut it
		***********************************************************/
		public static function trimtext($text, $characters_before, $characters_after, $findstring = ""){	
				//clean the input
				$text 	= strip_tags($text);
				//calculate the initial length
				$totalinitiallength = strlen(trim($text));
				$pos = 0;
				$start = 0; 
				
				$findstringposition = 0;
				if (strlen(trim(strip_tags($findstring)))>0) {
					$pos = stripos($text,$findstring);
				}
				
				$preceedingellipsis = "";
				if ( ($pos - $characters_before ) > 0) 
				{	
					$start = $pos - $characters_before;
					$preceedingellipsis = "&hellip;";//...
				}
				
				$len 	= $pos + strlen($findstring) + $characters_after - $start;
				$text 	= substr($text, $start, $len);
			
				// make sure only printing full words and not cut of strings
				$aStripTxt 	= explode(" ",$text);
				$maxKey 	= count($aStripTxt);
				$maxKey 	= $maxKey - 1;
				
				if ($start > 0) unset($aStripTxt[0]); // make sure our first word is not cut 
				if(strlen($text) <> $totalinitiallength )  unset($aStripTxt[$maxKey]);
				$text = implode(" ",$aStripTxt);
				
				// place the ellipsis after the search when the output is shorter than the initial text
				if (strlen($text) < $totalinitiallength) $text .= "&hellip;"; //...
				
				if (strlen(trim(strip_tags($findstring)))>0) { $text = str_ireplace($findstring,"<b>".$findstring."</b>",$text);  }
				
				return $preceedingellipsis.$text;
		}//end function trimtext
}
?>
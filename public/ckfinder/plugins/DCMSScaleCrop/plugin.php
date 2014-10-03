<?php
/*
* CKFinder
* ========
* http://cksource.com/ckfinder
* Copyright (C) 2007-2014, CKSource - Frederico Knabben. All rights reserved.
*
* The software, this file and its contents are subject to the CKFinder
* License. Please read the license.txt file before using, installing, copying,
* modifying or distribute this file or part of its contents. The contents of
* this file is part of the Source Code of CKFinder.
*
* CKFinder extension: resize image according to a given size
*/
//if (!defined('IN_CKFINDER')) exit;



class DCMSScaleCrop 
{
		function ScaleImageToBox($src, $dst, $width, $height, $crop=0)
		{

		  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

		  $type = strtolower(substr(strrchr($src,"."),1));

		  if($type == 'jpeg') $type = 'jpg';
		  switch($type){
			case 'bmp': $img = imagecreatefromwbmp($src); break;
			case 'gif': $img = imagecreatefromgif($src); break;
			case 'jpg': $img = imagecreatefromjpeg($src); break;
			case 'png': $img = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		  }

		  // resize
		  if($crop){
				if($w < $width or $h < $height) return "Picture is too small!";
				$ratio = max($width/$w, $height/$h);
				$y = ($h - $height / $ratio) / 2;
				$h = $height / $ratio;
				$x = ($w - $width / $ratio) / 2;
				$w = $width / $ratio;
		  }
		  else{
				if($w < $width and $h < $height) return "Picture is too small!";
				$ratio = min($width/$w, $height/$h);
				$width = $w * $ratio;
				$height = $h * $ratio;
				$y = 0;
				$x = 0;
		  }

		  $new = imagecreatetruecolor($width, $height);

		  // preserve transparency
		  if($type == "gif" or $type == "png"){
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		  }

		  imagecopyresampled($new, $img, 0, 0, $x, $y, $width, $height, $w, $h);
			
		  switch($type){
			case 'bmp': imagewbmp($new, $dst); break;
			case 'gif': imagegif($new, $dst); break;
			case 'jpg': imagejpeg($new, $dst); break;
			case 'png': imagepng($new, $dst); break;
		  }
			
			imagedestroy($new);
			
		  return true;
		}
		
	/**
     * @access private
     */
    function getConfig()
    {
        $config = array();
        if (isset($GLOBALS['config']['DCMSScaleCrop'])) {
            $config = $GLOBALS['config']['DCMSScaleCrop'];
        }
        return $config;
    }
	
	
	function onAfterFileUpload($currentFolder, $uploadedFile, $sFilePath)
	{
			$config = $this->getConfig();
			foreach($config['Scale'] as $size => $details)
			{
				if (strpos(strrev($sFilePath),"/")>0)
				{
					$reverseFilePath = strrev($sFilePath);
					
					//get the filename + extension
					$uploadFilename = strrev(substr($reverseFilePath,0,strpos($reverseFilePath,"/")));
					
					//get the foldername path
					$folderpath = str_replace('//','/',str_replace($uploadFilename,"",$sFilePath));
					
					$executeScale = false;					
					if (in_array($folderpath,$details["alloweddirectories"])) $executeScale = true;
					
					$folder = $folderpath.substr($details["subfolder"],1);
					
					if ($executeScale == true && !file_exists($folderpath.substr($details["subfolder"],1)))
					{
						mkdir($folderpath.substr($details["subfolder"],1),'0755');
					}
				}
		//		$this->ScaleImageToBox($sFilePath, $details["dir"].$uploadFilename, $details["width"], $details["height"], 1);
				if ($executeScale == true) $this->ScaleImageToBox($sFilePath, $folder.$uploadFilename, $details["width"], $details["height"], 1);
			}
			echo "\r\nDCMSPlugin executed\r\n";
	}
}

	$DCMSScaleCrop = new DCMSScaleCrop();
	
	$config['Hooks']['AfterFileUpload'][] = array($DCMSScaleCrop, "onAfterFileUpload");
	/*
	$config['Hooks']['BeforeExecuteCommand'][] = array($CommandHandler_ImageResizeInfo, "onBeforeExecuteCommand");
	$config['Hooks']['InitCommand'][] = array($CommandHandler_ImageResize, "onInitCommand");
	*/


?>
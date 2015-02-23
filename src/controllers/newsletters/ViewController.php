<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Models\Newsletters\Content;
use Dcweb\Dcms\Models\Newsletters\Campaign;
use Dcweb\Dcms\Models\Newsletters\Newsletter;

use Dcweb\Dcms\Controllers\BaseController;

use View;
use Input;
use DB;
use Mail; //inserted for sentmai();

class ViewController extends BaseController {

	public function contentLayout(Content $Content)
	{
		$layout = ""; 
		$layout = $Content->layout;
		$layout = str_replace('{Title}',	$Content->title,$layout);
		$layout = str_replace('{Body}',	$Content->body,	$layout);
		$layout = str_replace('{Image}',	$Content->image,$layout);
		$layout = str_replace('{Link}',	$Content->link,	$layout);
		$layout = str_replace('{Style}',	$Content->style,$layout);
		$layout = str_replace('{Name}',	$Content->name,	$layout);
		
		return $layout; 
	}
	
	public function campaignLayout(Campaign $Campaign,$newsletterid = null)
	{
		$layout = ""; 
		$css = ""; 
		$wrapper = $Campaign->wrapper;
		$layout = $Campaign->layout;
		
		if(isset($Campaign->wrapper) && strlen(trim($Campaign->wrapper))>0){
			$layout = str_replace("{Layout}",$layout,$wrapper);
		}
		
		$CSSmatch = array();
		$CSSstylematch = array();
		
		//Fetch the CSS style Blocks
		preg_match_all('/<style>(.*?)<\/style>/s',$layout,$CSSmatch);
		preg_match_all('/<style type=\"text\/css\">(.*?)<\/style>/s',$layout,$CSSstylematch);
		
		$strippedlayout = $layout;
		
		if(isset($CSSmatch) && count($CSSmatch[1])>0)
		{	
			foreach($CSSmatch[1] as $i => $css)
			{
				$strippedlayout = str_replace($css,"",$strippedlayout);
			}
 		}
		
		if(isset($CSSstylematch) && count($CSSstylematch[1])>0)
		{
			foreach($CSSstylematch[1] as $i => $css)
			{
				$strippedlayout = str_replace($css,"",$strippedlayout);
			}
		}
			
		//Fetch the Content Blocks
		preg_match_all('/{(.*?)}/',$strippedlayout,$matches);
		
		foreach($matches[1] as $key => $ContentName)
		{
			//echo $ContentName;
			switch (substr($ContentName,0,2)) {
					case "C:":
							$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("C:","",$ContentName),$Campaign->id),$layout);
							break;
					case "N:":
							$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("N:","",$ContentName),null,$newsletterid),$layout);
							break;
					default:
						if(substr($ContentName,0,1) <> "{") $layout = str_replace($matches[0][$key],$this->contentByName($ContentName,$Campaign->id),$layout);
				}//End switch
			
//			if(substr($ContentName,0,2) == "C:")	$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("C:","",$ContentName),$Campaign->id),$layout);
//			if(substr($ContentName,0,2) == "N:")	$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("N:","",$ContentName),null,$newsletterid),$layout);
		}
		
		if(isset($Campaign->style)&& strlen(trim($Campaign->style))>0 ) 
		{
			$layout = str_replace("</head>","<style type=\"text/css\">\r\n".$Campaign->style."\r\n</style>",$layout);
		}
		
		return $layout;
	}
	
	public function newsletterLayout(Newsletter $Newsletter)
	{
		return $this->campaign($Newsletter->campaign_id,$Newsletter->id);
	}
	
	public function contentByName($name = '', $campaignid = null, $newsletterid = null)
	{
		if(!is_null($campaignid))	return $this->contentLayout(Content::where('campaign_id','=',$campaignid)->where('name','=',$name)->first());
		if(!is_null($newsletterid))	return $this->contentLayout(Content::where('newsletter_id','=',$newsletterid)->where('name','=',$name)->first());
		return "";
	}

	public function content($contentid = null)
	{
		return $this->contentLayout(Content::find($contentid));
	}
	
	public function campaign($campaignid = null,$newsletterid = null)
	{
		return $this->campaignLayout($Campaign = Campaign::find($campaignid),$newsletterid);
	}
	
	public function newsletter($newsletterid = null)
	{
		return $this->newsletterLayout(Newsletter::find($newsletterid));
	}
	
}

?>
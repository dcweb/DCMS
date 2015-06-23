<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Models\Newsletters\Content;
use Dcweb\Dcms\Models\Newsletters\Campaign;
use Dcweb\Dcms\Models\Newsletters\Newsletter;
use Dcweb\Dcms\Models\Subscribers\Subscribers;
use Dcweb\Dcms\Models\Newsletters\NewsletterTrackcryptid;


use Dcweb\Dcms\Controllers\BaseController;

use View;
use Input;
use DB;
use Mail; //inserted for sentmai();
use URL;

class ViewController extends BaseController {

	public function contentLayout(Content $Content = null)
	{
		$layout = ""; 
		if(!is_null($Content))
		{
			$layout = $Content->layout;
			$layout = str_replace('{Title}',	$Content->title,$layout);
			$layout = str_replace('{Body}',	$Content->body,	$layout);
			$layout = str_replace('{Image}',	$Content->image,$layout);
			$layout = str_replace('{Link}',	$Content->link,	$layout);
			$layout = str_replace('{Style}',	$Content->style,$layout);
			$layout = str_replace('{Name}',	$Content->name,	$layout);
		}
		return $layout; 
	}
	
	public function campaignLayout(Campaign $Campaign = null,$newsletterid = null)
	{
		$layout = ""; 
		$css = ""; 
		if(!is_null($Campaign))
		{
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
				
				if($ContentName == "c:*")
				{
					// fetch all contentblocks for this campaign
					$oAllContent = Content::where('campaign_id','=',$Campaign->id)->orderBy('sort_id','asc')->get();
					if(count($oAllContent)>0)
					{
						$contentlayout = ""; 
						foreach($oAllContent as $Content)
						{
							$contentlayout .= $this->contentLayout($Content);
						}
					}
					
					$layout = str_replace("{c:*}",$contentlayout,$layout);
				}else{
					
					//echo $ContentName;
					switch (substr($ContentName,0,2)) {
							case "C:":
									$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("C:","",$ContentName),$Campaign->id),$layout);
									break;
							case "c:":
									$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("c:","",$ContentName),$Campaign->id),$layout);
									break;
							case "N:":
									$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("N:","",$ContentName),null,$newsletterid),$layout);
									break;
							case "n:":
									$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("n:","",$ContentName),null,$newsletterid),$layout);
									break;
							default:
								if(substr($ContentName,0,1) <> "{") $layout = str_replace($matches[0][$key],$this->contentByName($ContentName,$Campaign->id),$layout);
						}//End switch
				}
				
					
	//			if(substr($ContentName,0,2) == "C:")	$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("C:","",$ContentName),$Campaign->id),$layout);
	//			if(substr($ContentName,0,2) == "N:")	$layout = str_replace($matches[0][$key],$this->contentByName(str_replace("N:","",$ContentName),null,$newsletterid),$layout);
			}
			
			if(isset($Campaign->style)&& strlen(trim($Campaign->style))>0 ) 
			{
				$layout = str_replace("</head>","<style type=\"text/css\">\r\n".$Campaign->style."\r\n</style>",$layout);
			}
		}
		return $layout;
	}
	
	public function newsletterLayout(Newsletter $Newsletter,$cryptid = null,$mandrill = false)
	{
		$layout = $this->campaign($Newsletter->campaign_id,$Newsletter->id);
		
		if(!is_null($cryptid))	$user = Subscribers::where("cryptid","=",$cryptid)->first();
		
		if($mandrill == false)
		{
		$layout = str_replace('{{unsub}}',URL::Route('newsletter/unsubscribe',array('cryptid'=>$cryptid)),$layout);
		$layout = str_replace('{{viewonline}}',URL::Route('newsletter/viewonline',array('id'=>$Newsletter->id,'cryptid'=>$cryptid)),$layout);
		if(isset($user)){
				$layout = str_replace('{{firstname}}',$user->firstname,$layout);
				$layout = str_replace('{{lastname}}',$user->lastname,$layout);
				$layout = str_replace('{{email}}',$user->email,$layout);
				$layout = str_replace('{{cryptid}}',$user->cryptid,$layout);
		}
		}
		
		return $layout;
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
	
	public function newsletter($newsletterid = null,$cryptid = null)
	{
		return $this->newsletterLayout(Newsletter::find($newsletterid),$cryptid);
	}
	
	public function headerimage($cryptid = null)
	{
		//$reversedcryptid = strrev($cryptid);
		//$cryptid = str_replace(".jpg","",strrev(substr($reversedcryptid,0,strpos($reversedcryptid,"-"))));
		
		
		$subscriberlanguage = "nl";
		if(strstr($cryptid,"-fr")) $subscriberlanguage = "fr";
		
		$cryptid = str_replace("header-gazonmeststof-nl-","",$cryptid);
		$cryptid = str_replace("header-gazonmeststof-fr-","",$cryptid); 

		
//		$subscriberlanguage = "nl";
		$subscriber = Subscribers::where("cryptid","=",$cryptid)->first();
		if(!is_null($subscriber)) 
		{
			$subscriberid = $subscriber->id;
//			$subscriberlanguage = trim($subscriber->langauge);
		}
		else $subscriberid = 0;
		
		$headerimage = "header-gazonmeststof.jpg";  //defualt
		
		if($subscriberid%2 == 0 || $cryptid == "header-gazonmeststof-nl" || $cryptid == "{{cryptid}}" ) $headerimage = "header-gazonmeststof-nl.jpg";  //un-even numbers
		
		if($subscriberlanguage == "fr") $headerimage = "header-gazonmeststof.jpg"; //default FR
		if($subscriberlanguage == "fr" && ($subscriberid%2 == 0 || $cryptid == "header-gazonmeststof-fr"|| $cryptid == "{{cryptid}}") ) $headerimage = "header-gazonmeststof-fr.jpg";  //FR un-even numbers
		
		
		$newLog = new NewsletterTrackcryptid();
		$newLog->cryptid = $cryptid;
		$newLog->log_type = "headerimage";		
		$newLog->log_result = $headerimage;
		$newLog->ipadres 		= $_SERVER['REMOTE_ADDR'];
		$newLog->save();
						
		header('Content-Type: image/jpeg');
		readfile($_SERVER['DOCUMENT_ROOT']."/UserFiles/Image/email/2015/".$headerimage);
	}
	
}

?>
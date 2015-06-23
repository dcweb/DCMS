<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Models\Newsletters\Campaign;
use Dcweb\Dcms\Models\Newsletters\Content;
use Dcweb\Dcms\Models\Languages\Language;
use Dcweb\Dcms\Models\Countries\Country;

use Dcweb\Dcms\Controllers\BaseController;
use Dcweb\Dcms\Controllers\Newsletters\ContentController;

use Dcweb\Dcms\Helpers\Helper\SEOHelpers;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;

class CampaignController extends BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
		return View::make('dcms::newsletters/campaigns/index');
	}
	
	public function getLanguages()
	{
		$aLanguages = array();
		$oLanguage = Language::groupBy('language')->orderBy('language')->get(array('id','language'));
		if(count($oLanguage)>0)
		{
			foreach($oLanguage as $M)
			{
				$aLanguages[$M->id] = strtolower($M->language);
			}
		}
		
		return $aLanguages;
	}
	
	public function getCountries()
	{
		$aCountries = array();
		$oCountry = Country::groupBy('country')->orderBy('country')->get(array('id','country'));
		if(count($oCountry)>0)
		{
			foreach($oCountry as $M)
			{
				$aCountries[$M->id] = strtoupper($M->country);
			}
		}
		
		return $aCountries;
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$Newslettercampaign = new Campaign();
		
		return View::make('dcms::newsletters/campaigns/form')
				->with('Newslettercampaign', $Newslettercampaign)
				->with('aLanguages',$this->getLanguages())
				->with('aCountries',$this->getCountries());
	}
	
	
	
	private function validateNewslettercampaignForm()
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(/*'htmlbody' => 'required|min:1'
										,'body' => 'required|min:1'
										,'subject' => 'required|min:1'*/);
										
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)
				->withInput();
		}
		else
		{
			return true;
		}
	}
	
	
	private function saveCampaign($campaignid = null)
	{
		$input = Input::get();

		// do check if the given id is existing.
		if(!is_null($campaignid) && intval($campaignid)>0) $Campaign = Campaign::find($campaignid);  
		if(!isset($Campaign) || is_null($Campaign)) $Campaign = new Campaign;		
		
		$Campaign->subject 			= $input['campaign_subject'];
		$Campaign->country_id 	= $input['campaign_country_id'];
		$Campaign->language_id 	= $input['campaign_language_id'];
		$Campaign->wrapper 			= View::make('dcms::newsletters/newsletters/layout');
		$Campaign->layout 			= $input['campaign_layout'];
		$Campaign->style				= $input['campaign_style'];
		$Campaign->admin 				= Auth::dcms()->user()->username;
		$Campaign->save();		
		
		return $Campaign;
	}
	
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ($this->validateNewslettercampaignForm() === true)
		{
			$Campaign = $this->saveCampaign();
			$Contentcontroller = new ContentController();
			$Contentcontroller->saveContent($Campaign);
			
			// redirect
			Session::flash('message', 'Successfully created campaign!');
			//return Redirect::to('admin/newsletters/campaigns');
			return Redirect::back();
			
		}else return  $this->validateNewslettercampaignForm();
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
			//
			// get the Newsletter
			$Newslettercampaign = Campaign::find($id);
			
			$Contentcontroller = new ContentController;
			$ContentForms = $Contentcontroller->getContentForm(Content::where('campaign_id','=',$id)->orderBy('sort_id','asc')->get());
		
			// show the edit form and pass the nerd
			return View::make('dcms::newsletters/campaigns/form')
				->with('Newslettercampaign', $Newslettercampaign)
				->with('ContentForms', $ContentForms)
				->with('aLanguages',$this->getLanguages())
				->with('aCountries',$this->getCountries());
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($this->validateNewslettercampaignForm()===true)
		{ 
			$Campaign = $this->saveCampaign($id);
			$Contentcontroller = new ContentController();
			$Contentcontroller->saveContent($Campaign);
	
			// redirect
			Session::flash('message', 'Successfully updated campaign!');
			//return Redirect::to('admin/newsletters/campaigns');
			return Redirect::back();
			
		}else return  $this->validateNewslettercampaignForm();
	}
	
	
	/**
	 * copy the model
	 *
	 * @param  int  $id
	 * @param  enum  $return = topage | modelid
	 * @return to the overview page
	 */
	public function copy($id, $return = "topage")
	{
		$NewCampaign= Campaign::find($id)->replicate();
		$NewCampaign->created_at = date("Y-m-d H:i:s");
		$NewCampaign->save();
		
		$relatedContent = Content::where("campaign_id","=",$id)->get();
		if(count($relatedContent)>0)
		{
			foreach($relatedContent as $Content)
			{
				$NewContent = $Content->replicate();
				$NewContent->campaign_id = $NewCampaign->id;
				$NewContent->save();
				$NewContent->touch();
			}
		}
		
		if($return == "topage")
		{
			// redirect
			Session::flash('message', 'Successfully copied campaign!');
			return Redirect::to('admin/newsletters/campaigns');
		}
		elseif($return == "modelid")
		{
			return $NewCampaign->id;
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Content::where('campaign_id','=',$id)->delete();
		Campaign::destroy($id);
	
		// redirect
		Session::flash('message', 'Successfully deleted the campaign!');
		return Redirect::to('admin/newsletters/campaigns');
	}
}
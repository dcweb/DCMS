<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Controllers\BaseController;

use Dcweb\Dcms\Models\Newsletters\Settings;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use DateTime;
use Config;
use Com;
use Mail;
use URL;
use DateTimeZone;

class SettingController extends BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id = 1)
	{	
	
			if(!is_null($id) && intval($id)>0) $Settings = Settings::find($id);  
			if(!isset($Settings) || is_null($Settings)) $Settings = new Settings;		
			
			// show the edit form and pass the nerd
			return View::make('dcms::newsletters/newsletters/settings')
				->with('Settings', $Settings);
	}
	
	
	private function validateSettingsForm()
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array('api_key' => 'required'
										,'from_email' => 'email'
										,'replyto_email' => 'email');
										
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
	
	
	private function saveSettingsProperties($settingsid = 1)
	{
		$input = Input::get();
		
		if(!is_null($settingsid) && intval($settingsid)>0) $Settings = Settings::find($settingsid);  
		if(!isset($Settings) || is_null($Settings)) $Settings = new Settings;			
		
		$Settings->api_key 	= $input['api_key'];
		$Settings->api_sandbox_key 	= $input['api_sandbox_key'];
		$Settings->from_name 		= $input['from_name'];
		$Settings->from_email 	= $input['from_email'];
		$Settings->replyto_email= $input['replyto_email'];
		$Settings->track_opens	= ((isset($input['track_opens']))?$input['track_opens']:null) ;
		$Settings->track_clicks	= ((isset($input['track_clicks']))?$input['track_clicks']:null);
		$Settings->inline_css		= ((isset($input['inline_css']))?$input['inline_css']:null);
		$Settings->url_strip_qs	= ((isset($input['url_strip_qs']))?$input['url_strip_qs']:null);
		$Settings->signing_domain	= $input['signing_domain'];
		$Settings->google_analytics_domains	= $input['google_analytics_domains']; //semicolon ; seperated
		$Settings->google_analytics_campaign	= $input['google_analytics_campaign'];
		$Settings->admin 		= Auth::guard('dcms')->user()->username;
		$Settings->save();		
		
		return $Settings;
	}
	

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($this->validateSettingsForm()===true)
		{ 
			$Default = $this->saveSettingsProperties();
			
			// redirect
			Session::flash('message', 'Successfully saved settings!');
			return Redirect::to('admin/newsletters/settings');
			
		}else return  $this->validateSettingsForm();
	}
}
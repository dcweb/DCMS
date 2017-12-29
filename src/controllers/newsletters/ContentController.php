<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Models\Newsletters\Content;
use Dcweb\Dcms\Models\Newsletters\Campaign;
use Dcweb\Dcms\Models\Newsletters\Newsletter;

use Dcweb\Dcms\Controllers\BaseController;

use Dcweb\Dcms\Helpers\Helper\SEOHelpers;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use Form;

class ContentController extends BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
		return View::make('dcms::newsletters/content/index');
	}
	
	
	public function getContentForm($oContents=array(),$forceEmpty = false)
	{
		$rowstring = ""; 
		
		$openbody = true;
		$closebody = true;
		
		if ($forceEmpty === true && empty($oContents) === true)
		{
			$openbody = false;
			$closebody = false;
			$oContents[] = new Content();
		}
		
		foreach($oContents as $Content)
		{
			if ($openbody === true ) $rowstring .= '<tbody >';
			
			//------------------------------------------------------------------------
			// 							TEMPLATE FOR THE CONTENT FORM
			//------------------------------------------------------------------------

				$rowstring .= '<tr><td>'.View::make('dcms::newsletters/content/templates/form')
													->with('Content',$Content).'</td></tr>';

			//if ($openbody === true) $rowstring .= '</tbody>';
			
			if (isset($Content->id) && intval($Content->id)>0) $rowstring = str_replace("{ID}",$Content->id,$rowstring);
			$openbody = false; 
		}
		if ($closebody === true) $rowstring .= '</tbody>';
		
		return $rowstring;
	}
	
	public function getTableRow()
	{
		if (Input::get("data") === "content") 
		{
			return $this->getContentForm(null,true);
		}
	}	
	
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$Content = new Content();
		$Content->id = 0; //we give a default id this one is non-existing in the db this will result in a newly inserted content
		
		return View::make('dcms::newsletters/content/form')
				->with('Content', $Content);
	}
	
	
	private function validateNewslettercontentForm()
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
	
	// save content has 2 forms in play
	// views/newsletters/content/form.blade.php
	// views/newsletters/content/templates/form.blade.php
	// best to keep both as much in sync as possible
	public function saveContent(Campaign $Campaign = null, Newsletter $Newsletter = null)
	{
		$input = Input::get();
	
		$Content = null; //we return this var so, we have to declare it even if there is no-content fields given
		$aDontDeleteId = array(0=>0); //array containing the id's that get an update.. others may be deleted afterwards
		
		if(isset($input["content_name"]) && count($input["content_name"])>0)
		{
			foreach($input["content_name"] as $contentid => $content_name)
			{
					$Content = null;
					if(!is_null($contentid) && intval($contentid)>0) $Content = Content::find($contentid);  
					if(!isset($Content) || is_null($Content)) $Content = new Content;		
					
					if(!is_null($Campaign) && isset($Campaign->id) && intval($Campaign->id)>0) $Content->campaign_id = $Campaign->id;
					if(!is_null($Newsletter) && isset($Newsletter->id) && intval($Newsletter->id)>0) $Content->newsletter_id = $Newsletter->id;
					$Content->name 	= $input['content_name'][$contentid];
					if(isset($input['content_sortid'][$contentid]))	$Content->sort_id = $input['content_sortid'][$contentid];
					$Content->title = $input['content_title'][$contentid];
					$Content->body 	= $input['content_body'][$contentid];
					$Content->image = $input['content_image'][$contentid];
					$Content->link 	= $input['content_link'][$contentid];
					$Content->layout= $input['content_layout'][$contentid];
					//$Content->style = $input['content_style'][$contentid];
					$Content->admin = Auth::guard('dcms')->user()->username;
					$Content->save();		
					
					$aDontDeleteId[] = $Content->id;
			}
		}
		
		//delete these content objects that have not been sent by the form, and so it would have been removed
		if(!is_null($Campaign) && isset($Campaign->id) && intval($Campaign->id)>0)
		{
			Content::where('campaign_id','=',$Campaign->id)->whereNotIn('id',array_values($aDontDeleteId))->delete();
		}
		
		//delete these content objects that have not been sent by the form, and so it would have been removed
		if(!is_null($Newsletter) && isset($Newsletter->id) && intval($Newsletter->id)>0)
		{
			Content::where('newsletter_id','=',$Newsletter->id)->whereNotIn('id',array_values($aDontDeleteId))->delete();
		}
		
		return $Content; //simply return the last save() content model (in the foreach loop) - or the default NULL value
	}
	

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ($this->validateNewslettercontentForm() === true)
		{
			$this->saveContent();
			
			// redirect
			Session::flash('message', 'Successfully created content!');
			return Redirect::to('admin/newsletters/content');
			
		}else return  $this->validateNewslettercontentForm();
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
			// get the Newsletter
			$Content = Content::find($id);
			
			// show the edit form and pass the nerd
			return View::make('dcms::newsletters/content/form')
				->with('Content', $Content);
	}
	

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($this->validateNewslettercontentForm()===true)
		{ 
			$this->saveContent(); // there is no need to pass the id again, the id is given in the inputfield as array index
	
			// redirect
			Session::flash('message', 'Successfully updated content!');
			return Redirect::to('admin/newsletters/content');
			
		}else return  $this->validateNewslettercontentForm();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Content::destroy($id);
	
		// redirect
		Session::flash('message', 'Successfully deleted the content!');
		return Redirect::to('admin/newsletters/content');
	}
}
<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Models\Newsletters\Newsletter;
use Dcweb\Dcms\Models\Newsletters\Content;
use Dcweb\Dcms\Models\Newsletters\Settings;

use Dcweb\Dcms\Controllers\BaseController;
use Dcweb\Dcms\Controllers\Newsletters\ContentController;
use Dcweb\Dcms\Controllers\Newsletters\CampaignController;
use Dcweb\Dcms\Controllers\Subscribers\ListController;

use Dcweb\Dcms\Helpers\Helper\SEOHelpers;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatables;
use Auth;
use DateTime;
use Config;
use Com;
use Mail;
use URL;
use DateTimeZone;


class NewsletterController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view
		return View::make('dcms::newsletters/newsletters/index');
	}


	public function getDatatable($table = null, $selected_campaignid = 0)// or we give the modelid of a certain model and find the details of this model - at this moment we only give the default checked radiobutton
	{
		switch ($table) {

				case "campaigns":

							$query = DB::connection('project')
								->table('newsletters_campaigns')
								->select(
									'newsletters_campaigns.id',
									'subject',
									//'countries.country',
									//'languages.language',

									(DB::connection("project")->raw(" ucase(countries.country) as country")) ,
									(DB::connection("project")->raw(" lcase(languages.language) as language")) ,
									'division',
									'newsletters_campaigns.updated_at',
									(DB::connection("project")->raw(" '".$selected_campaignid."' as selected_campaignid"))
								)
								->leftJoin('languages','languages.id','=','newsletters_campaigns.language_id')
								->leftJoin('countries','countries.id','=','newsletters_campaigns.country_id')
//								->orderBy('selected_campaignid','asc')
//								->orderBy(DB::raw('selected_campaignid'), 'desc')
								->orderByRaw(' case when newsletters_campaigns.id = '.$selected_campaignid .' then 1 else 0 end desc' )
								->orderBy('newsletters_campaigns.created_at','desc');

		        return Datatables::queryBuilder($query)
								->addColumn('radio', function($model){return '<input type="radio" '.(($model->selected_campaignid == $model->id)?'checked="checked"':'').' name="campaign_id" value="'.$model->id.'" id="RadioGroup_'.$model->id.'" />';})
								->addColumn('edit', function($model){ return '<form method="POST" action="/admin/newsletters/campaigns/'.$model->id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
										<a class="btn btn-xs btn-default" target="_blank" href="'.URL::route('admin.newsletters.campaigns.view', array('id' => $model->id)).'"><i class="fa fa-eye"></i></a>

										<a class="btn btn-xs btn-default" href="/admin/newsletters/campaigns/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
										<a class="btn btn-xs btn-default" href="/admin/newsletters/campaigns/'.$model->id.'/copy"><i class="fa fa-copy"></i></a>
										<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
									</form>';})
		                        ->rawColumns(['radio','edit'])
		                        ->make(true) ;
						break;

				case "content":

							$query = DB::connection('project')
								->table('newsletters_content')
								->select(
									'id',
									'name'
								)
								->orderBy('created_at','desc');

			        return Datatables::queryBuilder($query)
									->addColumn('edit', function($model){ return '<form method="POST" action="/admin/newsletters/content/'.$model->id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
											<a class="btn btn-xs btn-default" target="_blank" href="'.URL::route('admin/newsletters/content/view', array('id' => $model->id)).'"><i class="fa fa-eye"></i></a>
											<a class="btn btn-xs btn-default" href="/admin/newsletters/content/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
											<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
										</form>';})
			                        ->rawColumns(['edit'])
									->orderColumns('created_at','desc')
			                        ->make(true) ;


						break;

				default:

			        return Datatables::queryBuilder(DB::connection('project')
														->table('newsletters')
														->select(
															'newsletters.id',

															'newsletters_campaigns.subject',
															'newsletters.created_at',/*
															'countries.country',
															'languages.language',*/

															(DB::connection("project")->raw(" ucase(countries.country) as country")) ,
															(DB::connection("project")->raw(" lcase(languages.language) as language")) ,

															'subscribers_lists.listname',
															'subscribers_lists.id as list_id',
															'newsletters.default_list',
															DB::connection("project")->raw('date_format(default_date,"%d-%m-%Y") as default_date'),
															DB::connection("project")->raw('(SELECT sum(count) FROM newsletters_sentlog WHERE list_id IS NOT NULL AND newsletter_id = newsletters.id) as sendcount')
														)
														->leftJoin('newsletters_campaigns','newsletters.campaign_id','=','newsletters_campaigns.id')
														->leftJoin('languages','languages.id','=','newsletters_campaigns.language_id')
														->leftJoin('countries','countries.id','=','newsletters_campaigns.country_id')
														->leftJoin('subscribers_lists','default_list','=','subscribers_lists.id')
														->orderBy('newsletters.created_at', 'desc')
													)
								->addColumn('edit', function($model){ return '<form method="POST" action="/admin/newsletters/'.$model->id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
										<a class="btn btn-xs btn-default" href="'.URL::route('admin.newsletters.send', array('id' => $model->id)).'"><i class="fa fa-paper-plane-o"></i></a>
										<a class="btn btn-xs btn-default" href="'.URL::route('admin.newsletters.view', array('id' => $model->id)).'" target="_blank"><i class="fa fa-eye"></i></a>
										<a class="btn btn-xs btn-default" href="'.URL::route('admin.newsletters.viewhtml', array('id' => $model->id)).'" target="_blank"><i class="fa fa-file-text-o"></i></a>
										'. /*<a class="btn btn-xs btn-default" href=\'/admin/newsletters/analyse?query=subject:"'.$model->subject.'" AND u_list_name:"'.$model->listname.'" AND u_list_id:'.$model->list_id.'&date_range=30&newsletter_id='.$model->id.'&list_id='.$model->list_id.'\'><i class="fa fa-line-chart"></i></a>*/'
										<a class="btn btn-xs btn-default" href="'.URL::route('admin.newsletters.analyse', array('id' => $model->id)).'"><i class="fa fa-line-chart"></i></a>
										<a class="btn btn-xs btn-default" href="/admin/newsletters/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
										<a class="btn btn-xs btn-default" href="'.URL::route('admin.newsletters.copy', array('id' => $model->id)).'"><i class="fa fa-copy"></i></a>
										<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
									</form>';})
		                        ->rawColumns(['edit'])
		                        ->make(true) ;

					break;
		}
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$Newsletter = new Newsletter();

		$Settings = Settings::find(1);
		if(!is_null($Settings))
		{
			$Newsletter->from_name = $Settings->from_name;
			$Newsletter->from_email = $Settings->from_email;
			$Newsletter->replyto_email = $Settings->replyto_email;
		}

		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::newsletters/newsletters/form')
				->with('Newsletter', $Newsletter)
				->with('aLists', ListController::getLists("A"));
	}


	private function validateNewsletterForm()
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(//'campaign_id' => 'required',
										'replyto_email' => 'required|email'
										,'from_email' => 'required|email');

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


	private function saveNewsletterProperties($newsletterid = null)
	{
		$input = Input::get();

		// do check if the given id is existing.
		if(!is_null($newsletterid) && intval($newsletterid)>0) $Newsletter = Newsletter::find($newsletterid);
		if(!isset($Newsletter) || is_null($Newsletter)) $Newsletter = new Newsletter;

		$send_at = NULL;
		if(trim(Input::get("default_date")) != "" )
		{
			$date = DateTime::createFromFormat('d-m-Y', Input::get("default_date"), new DateTimeZone('Europe/Berlin'));
			$send_at = $date->format('Y-m-d H:i:s');
		}

		if(isset($input["campaign_id"])) 	$Newsletter->campaign_id 	= $input['campaign_id'] ;
		$Newsletter->from_name 		= $input['from_name'];
		$Newsletter->from_email 	= $input['from_email'];
		$Newsletter->replyto_email= $input['replyto_email'];
		$Newsletter->default_list = $input['default_list'];
		$Newsletter->default_date = $send_at;
		$Newsletter->admin 		= Auth::guard('dcms')->user()->username;
		$Newsletter->save();

		return $Newsletter;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ($this->validateNewsletterForm() === true)
		{
			$Newsletter = $this->saveNewsletterProperties();

			$Contentcontroller = new ContentController();
			$Contentcontroller->saveContent(null,$Newsletter);

			// redirect
			Session::flash('message', 'Successfully created newsletter!');
			return Redirect::to('admin/newsletters');

		}else return  $this->validateNewsletterForm();
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
			$Newsletter = Newsletter::find($id);

			$Contentcontroller = new ContentController;
			$ContentForms = $Contentcontroller->getContentForm(Content::where('newsletter_id','=',$id)->orderBy('sort_id','asc')->get());

			// show the edit form and pass the nerd
			return View::make('dcms::newsletters/newsletters/form')
				->with('Newsletter', $Newsletter)
				->with('ContentForms', $ContentForms)
				->with('aLists', ListController::getLists("A"));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($this->validateNewsletterForm()===true)
		{
			$Newsletter = $this->saveNewsletterProperties($id);

			$Contentcontroller = new ContentController();
			$Contentcontroller->saveContent(null,$Newsletter);

			// redirect
			Session::flash('message', 'Successfully updated newsletter!');
			return Redirect::to('admin/newsletters');

		}else return  $this->validateNewsletterForm();
	}


	/**
	 * copy the model
	 *
	 * @param  int  $id
	 * @return to the overview page
	 */
	public function copy($id)
	{
		$Newsletter = Newsletter::find($id);

		$CampaignController = new CampaignController();
	//	$NewCampaignID = $CampaignController->copy($Newsletter->campaign_id,"modelid");

		$NewNewsletter = $Newsletter->replicate();
	//	$NewNewsletter->campaign_id = $NewCampaignID;
		$NewNewsletter->created_at = date("Y-m-d H:i:s");
		$NewNewsletter->save();

		$relatedContent = Content::where("newsletter_id","=",$id)->get();
		if(count($relatedContent)>0)
		{
			foreach($relatedContent as $Content)
			{
				$NewContent = $Content->replicate();
				$NewContent->newsletter_id = $NewNewsletter->id;
				$NewContent->save();
				$NewContent->touch();
			}
		}

		// redirect
		Session::flash('message', 'Successfully copied newsletter!');
		return Redirect::to('admin/newsletters');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Content::where('newsletter_id','=',$id)->delete();
		Newsletter::destroy($id);

		// redirect
		Session::flash('message', 'Successfully deleted the newsletter!');
		return Redirect::to('admin/newsletters');
	}


	public function send($id)
	{
			// get the Newsletter
			$Newsletter = Newsletter::find($id);

			$Settings = Settings::find(1);
			if(is_null($Settings) || (!is_null($Settings) && ( is_null($Settings->api_key) || strlen(trim($Settings->api_key))<=0))  )
			{
				Session::flash('message', 'There is no API key given to send emails');
			}

			if(!is_null($Settings))
			{
				$Newsletter->track_opens	= $Settings->track_opens;
				$Newsletter->track_clicks	= $Settings->track_clicks;
				$Newsletter->inline_css		= $Settings->inline_css	;
				$Newsletter->url_strip_qs	= $Settings->url_strip_qs;
				$Newsletter->signing_domain	= $Settings->signing_domain	;
				$Newsletter->google_analytics_domains	= $Settings->google_analytics_domains	;

				if(is_null($Settings->google_analytics_campaign) || strlen(trim($Settings->google_analytics_campaign))<=0) $Newsletter->google_analytics_campaign	= $Newsletter->campaign->subject;
				else 	$Newsletter->google_analytics_campaign	= $Settings->google_analytics_campaign	;
			}

			// show the edit form and pass the nerd
			return View::make('dcms::newsletters/newsletters/send')
				->with('Newsletter', $Newsletter)
				->with('aLists', ListController::getLists("A"));
	}
}

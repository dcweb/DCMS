<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

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
use DateTime;
use Config;
use Com;
use Mail;


class NewsletterController extends BaseController {
	
	public function sendmail()
	{
		// USING THE laravel's SWIFT MAILER
		for($i=1; $i<=5; $i++)
		{
			Mail::send('dcms::emails.newsletter', array('mailing' => $i), function($message) use ($i)
			{
					$message->to('email@domain.com', 'friendly name')->subject('newsletter nr #'.$i);
			});
			
		}
		
		return "using config/mail.php setup the correct settings";
		
		/* there is a way to use the persits mailsender please find detailed info on the web*/
		
	}
	

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
		return View::make('dcms::newsletters/index');
	}
	
	
	public function getDatatable()
	{
			return Datatable::Query(
														DB::connection('project')
																->table('newsletter')
																->select(
																			'id', 
																			'subject',
																			'regio'
																			//(DB::connection("project")->raw('Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(regio),".png\' >") as regio'))
																		)
																->orderBy('created_at','desc')
															)
		
						->showColumns('subject','regio')
						->addColumn('edit',function($model){return '<form method="POST" action="/admin/newsletters/'.$model->id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
								<a class="btn btn-xs btn-default" href="/admin/newsletters/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
								<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
							</form>';})
						->searchColumns('subject')
						->make();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$Newsletter = new Newsletter();
		$Newsletter->htmlbody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>{SUBJECT}</title></head><body bgcolor="#d72e2c" style="background-color:#d72e2c;"><div id="container" style="margin: 0 auto; max-width: 640px; min-width: 240px; _width: 640px; *width: 640px;">
BODY
</div><div align="center"><p style="text-align:center; font-size:11px; color:#ffffff; font-family:Tahoma,Geneva,sans-serif;">Gelieve niet te antwoorden op deze e-mail. Deze e-mail is automatisch verstuurd.<br/> Copyright &copy; 20XX DCM - member of Group De Ceuster - <A href="http://www.dcm-info.com/[REGIO]/nieuwsbrief/uitschrijven/[ID_AESMAIL]" style="color:#ffffff;">Uitschrijven</a></p><img width="1" height="1" src="http://www.dcm-info.com/UserFiles/image/email/tr/[ID_AESMAIL].gif"></div></body></html>'; 
		$Newsletter->body = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><TITLE>{SUBJECT}</TITLE></head><body bgcolor="#d72e2c" style="background-color:#d72e2c;"><div align="center" style="background:#d72e2c;"><p style="text-align:center; font-size:11px; color:#fff; font-family:Tahoma,Geneva,sans-serif;">Indien deze e-mail onleesbaar is, <a href="http://www.dcm-info.com/[REGIO]/nieuwsbrief/[SEOSUBJECT]/[ID_AESMAIL]" style="color:#ffffff;">klik hier</a>.</p><table width="640" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td>
BODY
</td></tr></table><p style="text-align:center; font-size:11px; color:#fff; font-family:Tahoma,Geneva,sans-serif;">Gelieve niet te antwoorden op deze e-mail. Deze e-mail is automatisch verstuurd.<br/> Copyright &copy; 20XX DCM - member of Group De Ceuster - <A href="http://www.dcm-info.com/"&lcase(vRegio)&"/nieuwsbrief/uitschrijven/[ID_AESMAIL]" style="color:#fff;">Uitschrijven</a></p><img width="1" height="1" src="http://www.dcm-info.com/UserFiles/image/email/tr/[ID_AESMAIL].gif"></div></body></html>'; 
		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::newsletters/form')
				->with('newsletter', $Newsletter);
	}
	
	
	
	private function validateNewsletterForm()
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array('htmlbody' => 'required|min:1'
										,'body' => 'required|min:1'
										,'subject' => 'required|min:1');
										
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
		// do check if the given id is existing.
		if(!is_null($newsletterid) && intval($newsletterid)>0) $Newsletter = Newsletter::find($newsletterid);  
		if(!isset($Newsletter) || is_null($Newsletter)) $Newsletter = new Newsletter;		
		
		$Newsletter->subject 	= Input::get('subject');
		$Newsletter->sender 	= Input::get('sender');
		$Newsletter->sendermail = Input::get('sendermail');
		$Newsletter->replyto 	= Input::get('replyto');
		$Newsletter->body 		= Input::get('body');
		$Newsletter->htmlbody = Input::get('htmlbody');
		$Newsletter->regio 		= Input::get('regio');
		$Newsletter->admin 		= Auth::dcms()->user()->username;
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
			$this->saveNewsletterProperties();
			
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
			$newsletter = Newsletter::find($id);
		
			// show the edit form and pass the nerd
			return View::make('dcms::newsletters/form')
				->with('newsletter', $newsletter);
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
	
			// redirect
			Session::flash('message', 'Successfully updated Newsletter!');
			return Redirect::to('admin/newsletters');
			
		}else return  $this->validateNewsletterForm();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Newsletter::destroy($id);
	
		// redirect
		Session::flash('message', 'Successfully deleted the newsletter!');
		return Redirect::to('admin/newsletters');
	}
}
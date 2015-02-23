<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Controllers\Newsletters\ViewController;
use Dcweb\Dcms\Models\Newsletters\Monitor;
use Dcweb\Dcms\Models\Newsletters\Content;
use Dcweb\Dcms\Models\Newsletters\Campaign;
use Dcweb\Dcms\Models\Newsletters\Newsletter;
use Dcweb\Dcms\Models\Newsletters\NewsletterSentLog;
use Dcweb\Dcms\Models\Newsletters\Settings;
use Dcweb\Dcms\Models\Subscribers\Subscribers;
use Dcweb\Dcms\Models\Subscribers\Lists;

use Dcweb\Dcms\Controllers\BaseController;

use View;
use Input;
use DB;
use \Mandrill;
use DateTime;
use DateTimeZone;
use URL;

class TransactionController extends \BaseController {
	
	public function monitor()
	{
		if(input::has("mandrill_events"))
		{
			$hookJSONpost = Input::get("mandrill_events");
			$hookJSONdecode = json_decode($hookJSONpost);
			
			foreach($hookJSONdecode as $oEvent)
			{
				$M = new Monitor();
				$M->mandrill_log = serialize(Input::get());
				$M->server_log = serialize($_SERVER);
				$M->event = $oEvent->event;
				$M->email = $oEvent->msg->email;
				$M->sender = $oEvent->msg->sender;
				$M->state = $oEvent->msg->state;
				$M->save();
				
				//http://help.mandrill.com/entries/21738186-Introduction-to-Webhooks
				//http://help.mandrill.com/entries/22880521-What-is-a-rejected-email-Rejection-Blacklist-
				
				$Subscriber = Subscribers::where("newsletter","=",1)->where("email","=",$oEvent->msg->email)->first();
				if(isset($Subscriber) && !is_null($Subscriber))
				{
					switch($oEvent->event)
					{
						case "hard_bounce":
						case "soft_bounce":
						case "reject":
							$Subscriber->bounced = $Subscriber->bounced + 1;
							break;
						case "unsub":
							$Subscriber->newsletter = 0;
							break;
						default:
							//nothing to do
							break;
					}
					$Subscriber->save();
				}
			}
		}
	}
	
	public function unsubscribe($cryptid = null)
	{
	/*	if(!is_null($cryptid) && strlen($cryptid)>0) 
		{
			foreach(Subscribers::where("cryptid","=",$cryptid)->get(array('id')) as $M)
			{
			//	$M->newsletter = 0;
			//	$M->save();
			}
		}*/
		//het uitschrijven is in de databse ook gebeurt.. via de webhook aangeboden door mandrill en in de method $this->monitor();
		return "Je bent succesvol uitgeschreven uit de mailing lijst";
	}
	

	public function send($id = null)
	{
		$Newsletter = Newsletter::find($id);
		$Campaign = Campaign::find($Newsletter->campaign_id);
		
		$v = new ViewController();
		$NewsletterHtml = $v->newsletterLayout($Newsletter);
		$Settings = Settings::find(1);
		
		if(Input::has("sandbox_key") && Input::get("sandbox_key") == "true")
		{
			echo "<br/><br/>-- SANDBOX --<br/><br/>";
			$mandrill = new Mandrill($Settings->api_sandbox_key);
		}
		else
		{
			$mandrill = new Mandrill($Settings->api_key);
		}
		
		
		$SentLog = new NewsletterSentLog();
		$SentLog->newsletter_id = $Newsletter->id;
		
		
		$to = array();
		$merge_vars = array();
		$sentCounter = 0;
		if(Input::get('select_list') == 'manual')
		{
			$SentLog->type = "manual";
			$SentLog->emails = Input::get('manual_list');
			
			$givenTo = explode(";",Input::get('manual_list'));
			foreach($givenTo as $email)
			{
				if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
					$sentCounter++;
					$to[] =  array(
												'email' => trim($email),
												'name' => $email,
												'type' => 'to'
										);
				}
			}
		}
		elseif(Input::get('select_list') == 'list' && intval(Input::get('sent_list'))>0 )
		{
			$SentLog->type = "list";
			$SentLog->list_id = Input::get('sent_list');
			
			$Subscribers = Subscribers::where('newsletter','=','1')->where('list_id','=',Input::get('sent_list'))->get(array('id','email','cryptid','firstname','lastname'));
			
			foreach($Subscribers as $M)
			{
				$sentCounter++;
				$to[] = array('email'=>$M->email,
											'name'=>$M->firstname,
											'type'=>'to'
											);
											
				$merge_vars[] = array('rcpt' => $M->email,
															'vars' => array(
																		array(
																				'name' => 'cryptid',
																				'content' => $M->cryptid
																		),
																		array(
																				'name' => 'firstname',
																				'content' => $M->firstname
																		),
																		array(
																				'name' => 'lastname',
																				'content' => $M->lastname
																		),
																		array(
																				'name' => 'email',
																				'content' => $M->email
																		),
																		array(
																				'name' => 'unsuburl',// this one is set over {{unsub}}
																				'content' => URL::to('unsubscribe',array('cryptid'=>$M->cryptid)) //'http://dcms.groupdc.be/unsubscribe/'.$M->cryptid.'/'
																		)
																)
													);
			}
		}
		
		$google_analytics_domains = array();
		if(Input::has("google_analytics_domains") && strlen(trim(Input::get("google_analytics_domains")))>0)
		{
			$google_analytics_domains = explode(";",str_replace(" ","",Input::get("google_analytics_domains")));
		}
		
		$result = array("nothing has been sent");
		if(count($to)>0)
		{
				$message = array(
						'html' => str_replace("{{unsub}}","{{unsub unsuburl}}",$NewsletterHtml),
					//  'text' => 'Example text content',
						'subject' => $Campaign->subject,
						'from_email' => Input::get('from_email'),//$Newsletter->from_email,
						'from_name' => Input::get('from_name') , //$Newsletter->from_name,
						
						'headers' => array('Reply-To' => Input::get('replyto_email') /*$Newsletter->replyto_email*/),
						'important' => false,
						'track_opens' => ((Input::has("track_opens") && Input::get("track_opens") == "true")?true:false) ,
						'track_clicks' => ((Input::has("track_clicks") && Input::get("track_clicks") == "true")?true:false) ,
						'auto_text' => null,
						'auto_html' => null,
						'inline_css' => ((Input::has("inline_css") && Input::get("inline_css") == "true")?true:false) ,
						'url_strip_qs' => ((Input::has("url_strip_qs") && Input::get("url_strip_qs") == "true")?true:false) ,
				//		'preserve_recipients' => null, //should be false in the sending defaults
				//		'view_content_link' => null,
				////  'bcc_address' => 'message.bcc_address@example.com',
				//		'tracking_domain' => null,
						'signing_domain' => ((Input::has("signing_domain") && strlen(trim(Input::get("signing_domain")))>0)?trim(Input::get("signing_domain")):null) ,
				//		'return_path_domain' => null,
						'merge' => true,
						'merge_language' => 'handlebars',//'mailchimp',//
						'global_merge_vars' => array(
																		array(
																				'name' => 'cryptid',
																				'content' => 'unset'
																		),
																		array(
																				'name' => 'firstname',
																				'content' => 'Tuinliefhebber'
																		),
																		array(
																				'name' => 'lastname',
																				'content' => ''
																		),
																		array(
																				'name' => 'email',
																				'content' => ''
																		),
																		array(
																				'name' => 'unsuburl', // this one is set over {{unsub}}
																				'content' => URL::to('unsubscribe',array('cryptid'=>"unset")) //'http://dcms.groupdc.be/unsubscribe/unset/'
																		)
						),
						'tags' => array($Campaign->subject),
		//        'subaccount' => 'customer-123',
		        'google_analytics_domains' => $google_analytics_domains,
		        'google_analytics_campaign' => ((Input::has("google_analytics_campaign") && strlen(trim(Input::get("google_analytics_campaign")))>0)?trim(Input::get("google_analytics_campaign")):null),
		//        'metadata' => array('website' => 'www.example.com'),
		//        'recipient_metadata' => array(
		 //           array(
		 //               'rcpt' => 'recipient.email@example.com',
		 //               'values' => array('user_id' => 123456)
		 //           )
		//        ),
		/*        'attachments' => array(
								array(
										'type' => 'text/plain',
										'name' => 'myfile.txt',
										'content' => 'ZXhhbXBsZSBmaWxl'
								)
						),
						'images' => array(
								array(
										'type' => 'image/png',
										'name' => 'IMAGECID',
										'content' => 'ZXhhbXBsZSBmaWxl'
								)
						)
		*/    
		//			'to' => $to, // is an array
		//			'merge_vars' => $merge_vars,
					);
				$async = false;
		    $ip_pool = null;//'Main Pool';
				
				$send_at = null; 
				if(trim(Input::get("send_at")) <> "" && substr(Input::get("send_at"),0,4) != "YYYY")
				{
					$date = DateTime::createFromFormat('d-m-Y H:i:s', Input::get("send_at"), new DateTimeZone('Europe/Berlin'));
					$date->setTimezone(new DateTimeZone('UTC'));
					$send_at = $date->format('Y-m-d H:i:s');
					$SentLog->send_at = $send_at; 
				}
			
				$SentLog->mandrill_settings = serialize($message); 
				$SentLog->save();
				$result = $mandrill->messages->send(array_merge($message,array('to'=>$to,'merge_vars'=>$merge_vars)), $async,$ip_pool,$send_at);
				$SentLog->count = $sentCounter;
				$SentLog->save();
		}
		print_r($result);
			/*
			Array ( 
			[0] => Array ( [email] => bre@groupdc.be [status] => sent [_id] => 9291f13d7ace47559864041c52e84304 [reject_reason] => ) 
			[1] => Array ( [email] => newsletter@dcm-info.com [status] => sent [_id] => d234766f54884ff28c97f1499a38130a [reject_reason] => ) ) bart index
			
			*/
/*		
		$date = new DateTime("@".time());  // will snap to UTC because of the  @timezone" syntax
		echo $date->format('Y-m-d H:i:sP') . "<br>";  // UTC time
		
		$date->setTimezone(new DateTimeZone('Europe/Berlin'));
		echo $date->format('Y-m-d H:i:sP') . "<br>";  // Berlin time    
*/
		return "<br/><br/><br/>Je mailing is waarschijnlijk verstuurd. ";//View::make('hello');
	}
}

?>
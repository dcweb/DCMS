<?php

namespace Dcweb\Dcms\Controllers\Newsletters;

use Dcweb\Dcms\Controllers\Newsletters\ViewController;

use Dcweb\Dcms\Models\Newsletters\Monitor;
use Dcweb\Dcms\Models\Newsletters\Analyse;
use Dcweb\Dcms\Models\Newsletters\Analyseresult;

use Dcweb\Dcms\Models\Newsletters\Content;
use Dcweb\Dcms\Models\Newsletters\Campaign;
use Dcweb\Dcms\Models\Newsletters\Newsletter;
use Dcweb\Dcms\Models\Newsletters\NewsletterSentLog;
use Dcweb\Dcms\Models\Newsletters\Settings;
use Dcweb\Dcms\Models\Subscribers\Subscribers;
use Dcweb\Dcms\Models\Subscribers\Lists;

use Dcweb\Dcms\Controllers\BaseController;

use Session;
use View;
use Input;
use DB;
use \Mandrill;
use DateTime;
use DateTimeZone;
use URL;
use Request;
use Config;
use Lang;
use App;
use Redirect;

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
						case "spam":
						case "unsub":
						case "hard_bounce":
							$Subscriber->bounced = $Subscriber->bounced + 1;
							$Subscriber->newsletter = 0;
							$Subscriber->verified = 0;
							$Subscriber->active = 0;
							break;
						case "soft_bounce":
						case "reject":
							$Subscriber->bounced = $Subscriber->bounced + 1;
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
	
	
	public function AnalyseResultToTable($result,&$result_total_sum)
	{
		$table = ""; 
		$totals = array();
		
		$totals["full_sent"] = 0;
		$totals["delivered"] = 0;
		
		if(isset($result) && count($result)>0)
		{
			$table =  "<table border='1'>"."\r\n";
			
			foreach($result as $resultindex => $resultdetail)
			{
				if($resultindex == 0)
				{
					$table .=   "<tr>"."\r\n";
					foreach($resultdetail as $colname => $colvalue)
					{
						$table .=  "<td>".$colname."</td>"."\r\n";
					}
					
						$table .=  "<td>full sent</td>"."\r\n";
						$table .=  "<td>delivered</td>"."\r\n";
					$table .=  "</tr>"."\r\n"."\r\n";
				}
				
				$table .=  "<tr>"."\r\n";
				foreach($resultdetail as $colname => $colvalue)
				{
					if(!isset($totals[$colname]))$totals[$colname] = 0;
					if($colname <> 'time') 
					{
						$totals[$colname] = $totals[$colname] + $colvalue;

						if(!isset($result_total_sum[$colname])) $result_total_sum[$colname] = 0;
						$result_total_sum[$colname] = intval($result_total_sum[$colname]) + intval($colvalue);
					}
					$table .=  "<td>".$colvalue."</td>"."\r\n";
				}
				
				$full_sent = ($resultdetail["sent"]+$resultdetail["hard_bounces"]+$resultdetail["soft_bounces"]+$resultdetail["complaints"]+$resultdetail["unsubs"]); 
				$delivered = ($resultdetail["sent"]+$resultdetail["complaints"]+$resultdetail["unsubs"]);
				
				$totals["full_sent"] = $totals["full_sent"] + $full_sent;
				$totals["delivered"] = $totals["delivered"] + $delivered;
				
				$table .=  "<td>".$full_sent."</td>"."\r\n";
				$table .=  "<td>".$delivered."</td>"."\r\n";
				$table .=  "</tr>"."\r\n"."\r\n";
				
				if(!isset($result_total_sum["full_sent"])) $result_total_sum["full_sent"] = 0;
				if(!isset($result_total_sum["delivered"])) $result_total_sum["delivered"] = 0;
				
				$result_total_sum["full_sent"] = intval($result_total_sum["full_sent"]) + intval($full_sent);
				$result_total_sum["delivered"] = intval($result_total_sum["delivered"]) + intval($delivered);
			}
			
			
			//TOTALS
			if(count($result)>1)
			{
				$table .=   "<tr>"."\r\n";
				$table .= "<td>Total</td>"."\r\n";
				
				foreach( array_keys($result[0]) as $colname)
				{
					if($colname<>'time')	$table .=  "<td>".$totals[$colname]."</td>"."\r\n";
				}
				
				$table .=  "<td>".$totals["full_sent"]."</td>"."\r\n";
				$table .=  "<td>".$totals["delivered"]."</td>"."\r\n";
				$table .=  "</tr>"."\r\n"."\r\n";
			}
			$table .=  "</table>"."\r\n"."\r\n";
			$table .= "<p> full sent = sent + hard_bounces + soft_bounces + complaints + unsubs</p>";
			$table .= "<p> delivered = sent + complaints + unsubs</p>";
		}
		return $table;
	}
	
	public function analyse()
	{
		$resulttable = ""; 
				
		$Settings = Settings::find(1);
		$mandrill = new Mandrill($Settings->api_key);
		
		/*
		// TOTALS for the account
		$result = $mandrill->senders->getList();
    echo "<pre>";
    print_r($result);
		echo "</pre>";
		*/
		
	//	echo date("Y-m-d",time());
	//	echo "<br/>";
	//	echo date("Y-m-d",(time()-(10*86400)));
		
		$date_range = 7;
		if(Input::has("date_range") && intval(Input::get("date_range"))>0) $date_range = intval(Input::get("date_range"));
		
		
		$list_id = null;
		$listname = null;
		if(Input::has("list_id") ) $list_id = Input::get("list_id");
		if(!is_null($list_id)){
			$oList = Lists::find($list_id);
			$listname = $oList->listname;
		}
		
		
		$query = Input::get("query"); //'subject:"DCM in de media? Ja zeker!" AND u_list_name:"DCM Retail BENL" AND u_list_id:36';
    $date_to  = date("Y-m-d",time());// '2015-02-01';
    $date_from= date("Y-m-d",(time()-($date_range*86400)));//'2015-03-01';
//    $tags = array(
//        'password-reset',
//        'welcome'
//    );
//    $senders = array('sender@example.com');
    $result = $mandrill->messages->searchTimeSeries($query, $date_from, $date_to, null, null);//($query, $date_from, $date_to, $tags, $senders);
		
		$AnalyseHistoryBuilder = Analyse::where('query','=',$query);
		if(count($result)>0)	$AnalyseHistoryBuilder = $AnalyseHistoryBuilder->where('result','=',serialize($result));
		$AnalyseHistory = $AnalyseHistoryBuilder->orderBy('created_at','desc')->first();
		
//		$AnalyseHistory = Analyse::where('query','=',$query)->where('result','=',serialize($result))->first();//->where('date_from','=',$date_from)->where('date_to','=',$date_to)

		if( count($result)>0 && (is_null($AnalyseHistory) || empty($AnalyseHistory) || trim(serialize($result)) != trim($AnalyseHistory->result) ) ) 
		{
				$Analyse = new Analyse();
				//$Analyse->analysetype = "searchTimeSeries";
				$Analyse->query = $query;
				$Analyse->date_to = $date_to;
				$Analyse->date_from = $date_from;
				$Analyse->result = serialize($result);
				$Analyse->save();
			
				foreach($result as $detail)
				{
					$Analyseresult = new Analyseresult();
					$Analyseresult->analyse_id = $Analyse->id;
					$Analyseresult->time = $detail["time"];
					$Analyseresult->sent = $detail["sent"];
					$Analyseresult->opens = $detail["opens"];
					$Analyseresult->clicks = $detail["clicks"];
					$Analyseresult->hard_bounces = $detail["hard_bounces"];
					$Analyseresult->soft_bounces = $detail["soft_bounces"];
					$Analyseresult->rejects = $detail["rejects"];
					$Analyseresult->complaints = $detail["complaints"];
					$Analyseresult->unsubs = $detail["unsubs"];
					$Analyseresult->unique_opens = $detail["unique_opens"];
					$Analyseresult->unique_clicks = $detail["unique_clicks"];
					$Analyseresult->save();
				}
		}
		elseif(!is_null($AnalyseHistory) && !empty($AnalyseHistory))
		{
			$result = unserialize($AnalyseHistory->result);
		}
	
	
		$result_total_sum = array();
	
		$resulttable .=  $query;
    $resulttable .= $this->AnalyseResultToTable($result,$result_total_sum);
		$resulttable .= "</br></br></br>";

		return View::make('dcms::newsletters/newsletters/analyse')
			->with('resulttable' , $resulttable)
			->with('Newsletter' , Newsletter::find(Input::get("newsletter_id")))
			->with('result' , $result)
			->with('result_total_sum' , $result_total_sum)
			->with('listname' , $listname);
			
	}
	
	public function unsubscribe($cryptid = null)
	{
		
		$everyLang = false;
		$email = ""; 
		$unsubscribetext = ""; 
		if(!is_null($cryptid) && strlen($cryptid)>0 && $cryptid <> 'unset') 
		{
			foreach(Subscribers::where("cryptid","=",$cryptid)->get(array('id','email')) as $M)
			{
				$email = $M->email;
				$M->newsletter = 0;
				App::setLocale(strtolower($M->language)); 
				$M->save();
				$unsubscribetext = Lang::get('dcms::newsletter/newsletter.unsubscribetext');
				
			}
		}else{
			
			foreach(Config::get('app.available_local') as $local)
			{
				App::setLocale($local); 
				$unsubscribetext .= Lang::get('dcms::newsletter/newsletter.unsubscribetext');
			}
			
			$email = Input::get("md_email");
		}
		//het uitschrijven is in de databse ook gebeurt.. via de webhook aangeboden door mandrill en in de method $this->monitor();
		
		return View::make('dcms::newsletters/newsletters/unsubscribe')
			->with('email' , $email)
			->with('unsubscribetext' , $unsubscribetext);
	}
	

	public function send($id = null)
	{
		$Newsletter = Newsletter::find($id);
		$Campaign = Campaign::find($Newsletter->campaign_id);
		
		$v = new ViewController();
		$NewsletterHtml = $v->newsletterLayout($Newsletter,null,true);
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
		
		$ListID = null;
		$Listname = "individual";
		
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
			
			$List = Lists::find(Input::get('sent_list'));
			$ListID = $List->id;
			$Listname = $List->listname;
			
			$Subscribers = Subscribers::where('newsletter','=','1')->where('list_id','=',Input::get('sent_list'))->get(array('id','email','cryptid','firstname','lastname'));
			
			foreach($Subscribers as $M)
			{
				$sentCounter++;
				$to[] = array('email'=>$M->email,
											'name'=>$M->firstname,
											'type'=>'to'
											);
											
				// !!!!!!!!!!!! THESE VARIALBES SHOULD BE SUPORTED IN THE ViewController.php for the online version viewers											
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
																				'content' => URL::route('newsletter/unsubscribe',array('cryptid'=>$M->cryptid)) //'http://dcms.groupdc.be/unsubscribe/'.$M->cryptid.'/'
																		),
																		array(
																				'name' => 'viewonline',// this one is set over {{unsub}}
																				'content' => URL::route('newsletter/viewonline',array('id'=>$Newsletter->id,'cryptid'=>$M->cryptid)) //'http://dcms.groupdc.be/unsubscribe/'.$M->cryptid.'/'
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
																				'content' => URL::route('newsletter/unsubscribe',array('cryptid'=>'unset')) //'http://dcms.groupdc.be/unsubscribe/unset/'
																		),
																		array(
																				'name' => 'viewonline', // this one is set over {{unsub}}
																				'content' => URL::route('newsletter/viewonline',array('id'=>$Newsletter->id,'cryptid'=>'unset')) //'http://dcms.groupdc.be/unsubscribe/unset/'
																		)
						),
		//				'tags' => array($Campaign->subject),
		//        'subaccount' => 'customer-123',
		        'google_analytics_domains' => $google_analytics_domains,
		        'google_analytics_campaign' => ((Input::has("google_analytics_campaign") && strlen(trim(Input::get("google_analytics_campaign")))>0)?trim(Input::get("google_analytics_campaign")):null),
		        'metadata' => array('list_id' => $ListID,'list_name'=>$Listname,'newsletter_id'=>$Newsletter->id,'campaign_id'=>$Newsletter->campaign_id),
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
	//	print_r($result);
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
		
			Session::flash('message', 'The message has been sent - we\'ve counted '.$sentCounter.' e-mail addresses ');//Je mailing is waarschijnlijk verstuurd - naar  '.$sentCounter.' mailadressen"');
			//return "<br/><br/><br/>Je mailing is waarschijnlijk verstuurd - naar  ".$sentCounter." mailadressen";//View::make('hello');
			return Redirect::to('admin/newsletters');
			
	}
}

?>
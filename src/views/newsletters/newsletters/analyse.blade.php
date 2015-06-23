@extends("dcms::template/layout")

@section("content")

		<div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="{{ URL::to('admin/newsletters') }}"><i class="fa fa-newspaper-o"></i> Newsletters</a></li>
      </ol>
    </div>
    <div class="main-content report">
    	<div class="row">
      	<div class="col-md-12">
        	<div class="main-content-block">
          	<div class="row">
            	<div class="col-md-6">
          			<h3><b>Subject:</b> {{$Newsletter->campaign->subject}}</h3>
            	</div>
              <div class="col-md-6 text-right">
            		<p class="top23"><b>Date delivered:</b> @if(isset($result[0]["time"])) {{$result[0]["time"]}} @endif </p>
             	</div>
           	</div>
          </div>
        </div>
    	</div>
      <div class="row">
      	<div class="col-md-6">
        	<div class="row">
            <div class="col-md-6">
              <div class="panel panel-body">
                <span class="total text-center"> {{$result_total_sum["full_sent"]}}</span>
                <span class="title text-center">Sent</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="panel panel-solid panel-body">
                <span class="total text-center">{{$result_total_sum["delivered"]}} <span class="percentage">({{round(($result_total_sum["delivered"]/$result_total_sum["full_sent"])*100,2)."%"}})</span></span>
                <span class="title text-center">Delivered</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="panel panel-body">
                <span class="total text-center">{{ ($result_total_sum["hard_bounces"] + $result_total_sum["soft_bounces"]) }} <span class="percentage">({{round((($result_total_sum["hard_bounces"] + $result_total_sum["soft_bounces"])/$result_total_sum["full_sent"])*100,2)."%"}})</span></span>
                <span class="title text-center">Bounced</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="panel panel-body">
                <span class="total text-center">{{ ($result_total_sum["unsubs"] + $result_total_sum["complaints"]) }} <span class="percentage">(@if($result_total_sum["delivered"]==0) 0 @else{{round((($result_total_sum["unsubs"] + $result_total_sum["complaints"])/$result_total_sum["delivered"])*100,2)."%"}}@endif)</span></span>
                <span class="title text-center">Unsubscribed</span>
              </div>
            </div>
         	</div>
      	</div>
        <div class="col-md-3">
        	<div class="panel">
          	<div class="panel-heading"><h3>Open Rate</h3></div>
            <div class="panel-body">
            	<ul>
                <li>
                  <span class="text-left">Total Opens</span>
                  <div class="progress">
                    <div class="progress-bar progress-bar-success" style="width: @if($result_total_sum["opens"] == 0) 0 @else {{round(($result_total_sum["opens"]/$result_total_sum["delivered"])*100)."%"}}@endif" aria-valuemax="{{$result_total_sum["delivered"]}}" aria-valuemin="0" aria-valuenow="{{$result_total_sum["opens"]}}" role="progressbar"> </div>
                    <span class="value">{{$result_total_sum["opens"]}} <span class="percentage">(@if($result_total_sum["delivered"] == 0 ) 0 @else{{round(($result_total_sum["opens"]/$result_total_sum["delivered"])*100,2)."%"}}@endif)</span></span>
                  </div>
                </li>
                <li>
                  <span class="text-left">Unique Opens</span>
                  <div class="progress">
                    <div class="progress-bar progress-bar-warning" style="width: @if($result_total_sum["unique_opens"] == 0) 0 @else {{round(($result_total_sum["unique_opens"]/$result_total_sum["delivered"])*100)."%"}} @endif " aria-valuemax="{{$result_total_sum["delivered"]}}" aria-valuemin="0" aria-valuenow="{{$result_total_sum["unique_opens"]}}" role="progressbar"> </div>
                    <span class="value">{{$result_total_sum["unique_opens"]}} <span class="percentage">(@if($result_total_sum["opens"] == 0 ) 0 @else{{round(($result_total_sum["unique_opens"]/$result_total_sum["delivered"])*100,2)."%"}}@endif)</span></span>
                  </div>
                </li>
              </ul>
            </div>
         	</div>
        </div>
        <div class="col-md-3">
        	<div class="panel">
          	<div class="panel-heading"><h3>Click Rate</h3></div>
            <div class="panel-body">
            	<div class="row">
              	<div class="col-md-12">
                	<ul>
                    <li>
                      <span class="text-left">Total Clicks <span class="percentage">(@if($result_total_sum["clicks"] == 0 ) 0 @else{{round(($result_total_sum["clicks"]/$result_total_sum["opens"])*100,2)."%"}}@endif)</span></span>
                      <div class="progress">
                      	<div class="progress-bar progress-bar-success" style="width: @if($result_total_sum["clicks"] == 0) 0 @else {{round(($result_total_sum["clicks"]/$result_total_sum["opens"])*100)."%"}}@endif" aria-valuemax="{{$result_total_sum["opens"]}}" aria-valuemin="0" aria-valuenow="{{$result_total_sum["clicks"]}}" role="progressbar">{{$result_total_sum["clicks"]}} </div>
                      </div>
                    </li>
                    <li>
                      <span class="text-left">Unique Clicks <span class="percentage">(@if($result_total_sum["delivered"] == 0 ) 0 @else{{round(($result_total_sum["unique_clicks"]/$result_total_sum["opens"])*100,2)."%"}}@endif)</span></span>
                      <div class="progress">
                      	<div class="progress-bar progress-bar-warning" style="width: @if($result_total_sum["clicks"] == 0) 0 @else {{round(($result_total_sum["unique_clicks"]/$result_total_sum["opens"])*100)."%"}} @endif " aria-valuemax="{{$result_total_sum["opens"]}}" aria-valuemin="0" aria-valuenow="{{$result_total_sum["unique_clicks"]}}" role="progressbar">{{$result_total_sum["unique_clicks"]}} </div>
                      </div>
                    </li>
                  </ul>
                </div>
             	</div>
            </div>
          </div>
       	</div>
      </div>
      <div class="row">

        <div class="col-md-3">
        	<div class="panel chart large">
          	<div class="panel-heading"><h3>Sent Details</h3></div>
            <div class="panel-body">
            	<div class="row">
              	<div class="col-md-12">
                	<div id="doughnut-canvas-holder">
            				<canvas id="doughnut-chart-area" width="180" height="180"></canvas>
                  </div>
               	</div>
                <div class="col-md-12">
                	<ul>
                  	<li><i class="fa fa-circle succes-color"></i>Delivered ({{$result_total_sum["delivered"]}})</li>
                    <li><i class="fa fa-circle warning-color"></i>Hard bounces ({{$result_total_sum["hard_bounces"]}})</li>
                    <li><i class="fa fa-circle info-color"></i>Soft bounces ({{$result_total_sum["soft_bounces"]}})</li>
                    <li><i class="fa fa-circle default-color"></i>Unsubscribers ({{$result_total_sum["unsubs"]}})</li>
                    <li><i class="fa fa-circle default-color"></i>Complaints ({{$result_total_sum["complaints"]}})</li>
                    <li><i class="fa fa-circle default-color"></i>Rejects ({{$result_total_sum["rejects"]}})</li>
                  </ul>
                </div>
             	</div>
            </div>
          </div>
        </div>

      	<div class="col-md-9">
        	<div class="main-content-block">
           <?php
						require_once '../app/google-api/src/Google/autoload.php';
						session_start();	 	
					
						$client_id = '882907929974-ei01eq81mi6nnl028d7dquoesk5koet9.apps.googleusercontent.com';
						$client_email = '882907929974-ei01eq81mi6nnl028d7dquoesk5koet9@developer.gserviceaccount.com';	 
						//$private_key = file_get_contents('http://www.dcm-info.com/test/google-api/secure/dcm-info.com-3f96578ad160.p12');
						$private_key = file_get_contents('../app/google-api/secure/dcm-info.com-3f96578ad160.p12');
						
						$scopes = array('https://www.googleapis.com/auth/analytics.readonly');
					
						$credentials = new Google_Auth_AssertionCredentials(
							$client_email,
							$scopes,
							$private_key
						);
					
						$client = new Google_Client();	 	
					
						$client->setAssertionCredentials($credentials);
						if($client->getAuth()->isAccessTokenExpired()) {	 	
							 $client->getAuth()->refreshTokenWithAssertion();	 	
						}
						
						$access_token = json_decode($client->getAccessToken());
						$_SESSION['access_token'] = $access_token->access_token;
						
						/*
						$service = new Google_Service_Analytics($client);
						
						//Adding Dimensions
						$params = array('dimensions' => 'ga:userType');	
					
						// requesting the data	
						$data = $service->data_ga->get("ga:15191523", "2014-12-14", "2014-12-14", "ga:users,ga:sessions", $params );
						*/
					
					?>
            
          <section id="timeline"></section>
            
            <script>
							(function(w,d,s,g,js,fjs){
								g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(cb){this.q.push(cb)}};
								js=d.createElement(s);fjs=d.getElementsByTagName(s)[0];
								js.src='https://apis.google.com/js/platform.js';
								fjs.parentNode.insertBefore(js,fjs);js.onload=function(){g.load('analytics')};
							}(window,document,'script'));
						</script>

						<script>
            gapi.analytics.ready(function() {
            
              // Authorization using an access token obtained server-side.

							gapi.analytics.auth.authorize({
								serverAuth: {
									access_token: '<?php echo $_SESSION['access_token']; ?>'
								}
							});
            
            
              // Create the timeline chart.
            
              var chart = new gapi.analytics.googleCharts.DataChart({
                reportType: 'ga',
                query: {
                  'ids': 'ga:15191523',
                  //'dimensions': 'ga:campaign,ga:medium,ga:landingPagePath',
                  'dimensions': 'ga:landingPagePath',
                  'metrics': 'ga:sessions',
                  'sort': '-ga:sessions',
                  'filters': 'ga:medium==email;ga:campaign==@if(isset($listname)){{$listname}}@endif - {{$Newsletter->campaign->subject}}',
                  'start-date': '@if(isset($result[0]["time"])){{substr($result[0]["time"],0,10)}}@endif',
                  'end-date': 'today',
                },
                chart: {
                  type: 'TABLE',
                  container: 'timeline'
                }
              });
            
							chart.execute();
            
            });
            </script>
        
        	</div>
        </div>
      </div>
    </div>
    
@stop

@section("script")
<script>
var doughnutData = [
				{
					value: {{$result_total_sum["full_sent"]}},
					color: "#428bca",
					label: "Send"
				},
				{
					value: {{$result_total_sum["hard_bounces"]}},
					color: "#ff9400",
					label: "Hard bounces"
				},
				{
					value: {{$result_total_sum["soft_bounces"]}},
					color: "#FFC000",
					label: "Soft bounces"
				},
				{
					value: {{$result_total_sum["unsubs"]}},
					color: "#0B5FA5",
					label: "Unsubscribers"
				},
				{
					value: {{$result_total_sum["complaints"]}},
					color: "#540EAD",
					label: "Complaints"
				},
				{
					value: {{$result_total_sum["rejects"]}},
					color: "#FFE900",
					label: "Rejects"
				}

			];

window.onload = function(){
	// Get context with jQuery - using jQuery's .get() method.
	var ctx = $("#doughnut-chart-area").get(0).getContext("2d");
	// This will get the first returned node in the jQuery collection.
	var myNewChart = new Chart(ctx).Doughnut(doughnutData);
};
</script>
<script src="{{ asset('packages/dcweb/dcms/assets/js/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('packages/dcweb/dcms/assets/js/chartjs/Chart.Doughnut.js') }}"></script>

@stop
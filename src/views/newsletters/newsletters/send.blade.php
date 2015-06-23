@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/newsletters') }}"><i class="fa fa-newspaper-o"></i> Newsletters</a></li>
        <li class="active"><i class="fa fa-paper-plane-o"></i> Send</li>
        <li class="active">{{$Newsletter->campaign->subject}}</li>
      </ol>
    </div>

    <div class="main-content">

    @if (Session::has('message'))
      <div class="alert alert-danger">{{ Session::get('message') }}</div>
    @endif

      {{ Form::model($Newsletter, array('route' => array('admin/newsletters/transaction', $Newsletter->id), 'method' => 'PUT')) }}

        <div class="row">
          <div class="col-md-12">
            <div class="main-content-tab tab-container">
              
              <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#settings" role="tab" data-toggle="tab">Settings</a></li>
                <li><a href="#advanced" role="tab" data-toggle="tab">Advanced Settings</a></li>
              </ul>
      
              <div class="tab-content">

              	@if($errors->any())
                <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
              	@endif

                <div id="settings" class="tab-pane active">

                  <div class="form-group">
                    {{ Form::label('list', 'List') }}
                    <div class="input-group">
                      <span class="input-group-addon">{{ Form::radio('select_list', 'list',false,array("class"=>"radiohelper_select_list")) }}</span>
                    	{{ Form::select('sent_list',  $aLists , $Newsletter->default_list, array('class' => 'form-control', 'id'=>'sent_list')); }}
                    </div>
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('manual_list', 'Manual List') }}
                    <div class="input-group">
                      <span class="input-group-addon">{{ Form::radio('select_list', 'manual',false,array("class"=>"radiohelper_select_list")) }}</span>
                      {{ Form::text('manual_list', '', array('class' => 'form-control')) }}
                    </div>
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('from_name', 'From Name') }}
                    {{ Form::text('from_name', (Input::old('from_name') ? Input::old('from_name') : $Newsletter->from_name), array('class' => 'form-control')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('from_email', 'From E-mail') }}
                    {{ Form::text('from_email', (Input::old('from_email') ? Input::old('from_email') : $Newsletter->from_email), array('class' => 'form-control')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('replyto_email', 'Reply-to E-mail') }}
                    {{ Form::text('replyto_email', (Input::old('replyto_email') ? Input::old('replyto_email') : $Newsletter->replyto_email), array('class' => 'form-control')) }}
                  </div>
                  
                </div>

                <div id="advanced" class="tab-pane">

                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        {{ Form::checkbox('track_opens', 'true') }}
                        Track Opens
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        {{ Form::checkbox('track_clicks', 'true') }}
                        Track Clicks
                      </label>
                    </div>
                  </div>
                                
                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        {{ Form::checkbox('inline_css', 'true') }}
                        Inline CSS styles in HTML emails
                      </label>
                    </div>
                  </div>
                                
                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        {{ Form::checkbox('url_strip_qs', 'true') }}
                        Remove query string when aggregating URLs
                      </label>
                    </div>
                  </div>
                                
                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        {{ Form::checkbox('sandbox_key', 'true', false) }}
                        Sandbox Mode
                      </label>
                    </div>
                  </div>
                                
                  <div class="form-group">
                    {{ Form::label('signing_domain', 'Custom SPF/DKIM Signing Domain') }}
                    {{ Form::text('signing_domain', null, array('class' => 'form-control')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('google_analytics_domains', 'Google Analytics Domain (seperate using semicolon ;)') }} 
                    {{ Form::text('google_analytics_domains', null, array('class' => 'form-control')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('google_analytics_campaign', 'Google Analytics Campaign') }}
                    {{ Form::text('google_analytics_campaign', null, array('class' => 'form-control', 'id'=>'google_analytics_campaign')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('send_at', 'Schedule') }}
                    <div id="datetimepicker" class="input-group input-append date">
                      <span class="input-group-addon btn btn-primary"><i data-time-icon="glyphicon glyphicon-time" data-date-icon="glyphicon glyphicon-calendar"></i></span>
	                    {{ Form::text('send_at', '', array('data-format' => 'dd-MM-yyyy hh:mm:ss', 'class' => 'form-control')) }}
                    </div>
                  </div>

                </div>
  						</div>
            </div>    
          </div>
          <div class="col-md-12">
            <div class="main-content-block">
              {{ Form::submit('Send', array('class' => 'btn btn-primary')) }}
              <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </div>
			{{ Form::close() }}
    </div>
@stop

@section("script")

<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap.min.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/bootstrap-datetimepicker.css') }}">
<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap-datetimepicker.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {

	var addedGoogleAnalyticsCampaign = ""; 
	var allowGoogleAnalyticsCampaign = false;

	//DateTimePicker https://github.com/tarruda/bootstrap-datetimepicker
	$('#datetimepicker').datetimepicker({
		startDate: new Date()
	});
	
	
	$('#sent_list').change(function(){
		removeGoogleAnalyticsCampaign();
		setGoogleAnalyticsCampaign();
	})
	
	$(".radiohelper_select_list").change(function(){
		if($(this).val() == "list")
		{
			allowGoogleAnalyticsCampaign = true;
			setGoogleAnalyticsCampaign();
		}else{
			allowGoogleAnalyticsCampaign = false;
			removeGoogleAnalyticsCampaign();
		}
	});
	
	function setGoogleAnalyticsCampaign()
	{
		if(allowGoogleAnalyticsCampaign == true)
		{
			addedGoogleAnalyticsCampaign = $('#sent_list').find(":selected").text() + ' - ';
			$('#google_analytics_campaign').val(addedGoogleAnalyticsCampaign + $('#google_analytics_campaign').val())  ;
		}
	}
	
	function removeGoogleAnalyticsCampaign()
	{
		$('#google_analytics_campaign').val($('#google_analytics_campaign').val().replace(addedGoogleAnalyticsCampaign,""))  ;
	}
	
	
});
</script>

@stop

@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/newsletters') }}"><i class="fa fa-newspaper-o"></i> Newsletters</a></li>
        <li class="active"><i class="fa fa-paper-plane-o"></i> Settings</li>
        
      </ol>
    </div>

    <div class="main-content">

        @if (Session::has('message'))
          <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        {{ Form::model($Settings, array('route' => array('admin.newsletters.settings.update'), 'method' => 'PUT')) }}
              
        <div class="row">
          <div class="col-md-12">
            <div class="main-content-tab tab-container">
              
              <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#settings" role="tab" data-toggle="tab">Default Settings</a></li>
                <li><a href="#advanced" role="tab" data-toggle="tab">Default Advanced Settings</a></li>
              </ul>
      
              <div class="tab-content">
              

              	@if($errors->any())
                <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
              	@endif

                <div id="settings" class="tab-pane active">

                  <div class="form-group">
                    {{ Form::label('api_key', 'API Key') }}
                    {{ Form::text('api_key', null, array('class' => 'form-control')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('api_sandbox_key', 'API Key (sandbox)') }}
                    {{ Form::text('api_sandbox_key', null, array('class' => 'form-control')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('from_name', 'Default From Name') }}
                    {{ Form::text('from_name', null, array('class' => 'form-control')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('from_email', 'Default From E-mail') }}
                    {{ Form::text('from_email', null, array('class' => 'form-control')) }}
                  </div>
                      
                  <div class="form-group">
                    {{ Form::label('replyto_email', 'Default Reply-to E-mail') }}
                    {{ Form::text('replyto_email', null, array('class' => 'form-control')) }}
                  </div>
                  
                </div>

                <div id="advanced" class="tab-pane">

                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        {{ Form::checkbox('track_opens', 'true')}}
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
                        remove query string when aggregating URLs
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
                    {{ Form::text('google_analytics_campaign', null, array('class' => 'form-control')) }}
                  </div>
                      
                </div>
  						</div>

            </div>    
          </div>
          <div class="col-md-12">
            <div class="main-content-block">
              {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
              <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </div>

			{{ Form::close() }}
      
    </div>

@stop

@section("script")

<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap.min.js') }}"></script>

@stop

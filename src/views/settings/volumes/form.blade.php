@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Articles</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/settings') }}">Settings</a></li>
        <li><a href="{{ URL::to('admin/settings/volumes') }}"><i class="fa fa-pencil"></i> Volumes</a></li>

          @if(isset($Volume))
                  <li class="active">Edit</li>
          @else
                  <li class="active">Create</li>
					@endif

      </ol>
    </div>

    <div class="main-content">

    @if(isset($Volume))
        {{ Form::model($Volume, array('route' => array('admin.settings.volumes.update', $Volume->id), 'method' => 'PUT')) }}
    @else
        {{ Form::open(array('url' => 'admin/settings/volumes')) }}
    @endif

    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">
             
              @if($errors->any())
                <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
              @endif

              @if(isset($languages))
                                    
                  <ul class="nav nav-tabs" role="tablist">
              @foreach($languages as $key => $language)
                    <li class="{{ ($key == 0 ? 'active' : '') }}"><a href="{{ '#' . $language->language . '-' . $language->country }}" role="tab" data-toggle="tab"><img src="{{ asset('packages/dcweb/dcms/assets/images/flag-' . $language->country . '.png') }}" width="18" height="12" /> {{ $language->language_name }}</a></li>
              @endforeach
                  </ul>

                  <div class="tab-content">
              @foreach($languages as $key => $information)

                    <div id="{{ $information->language . '-' . $information->country }}" class="tab-pane {{ ($key == 0 ? 'active' : '') }}">

                      {{ Form::hidden('volume_unit_id[' . $information->language_id . ']', $information->id) }}								
                                                    
                      <div class="form-group">
                        {{ Form::label('volume_unit[' . $information->language_id . ']', 'Short') }}
                        {{ Form::text('volume_unit[' . $information->language_id . ']', (Input::old('volume_unit[' . $information->language_id . ']') ? Input::old('volume_unit[' . $information->language_id . ']') : $information->volume_unit ), array('class' => 'form-control')) }}
                      </div>
                  
                      <div class="form-group">
                        {{ Form::label('volume_unit_long[' . $information->language_id . ']', 'Volume') }}
                        {{ Form::text('volume_unit_long[' . $information->language_id . ']', (Input::old('volume_unit_long[' . $information->language_id . ']') ? Input::old('volume_unit_long[' . $information->language_id . ']') : $information->volume_unit_long ), array('class' => 'form-control')) }}
                      </div>
                                                                        
                    </div>
                    
              @endforeach
                  </div>

              @endif


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
<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/bootstrap-datetimepicker.min.css') }}">

<script type="text/javascript" src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckeditor/adapters/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckbrowser.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {

	//CKFinder for CKEditor
	CKFinder.setupCKEditor( null, '/ckfinder/' );
	
	
	//CKFinder 
	$(".browse-server").click(function() {
		BrowseServer( 'Images:/articles/', 'thumbnail' );
	})
	
	//CKEditor
	$("textarea[id^='description']").ckeditor();
	$("textarea[id^='body']").ckeditor();

	//Bootstrap Tabs
	$(".tab-container .nav-tabs a").click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})
	
	//Bootstrap Datepicker
	$(".date").datetimepicker({
		todayHighlight: true,
		autoclose: true,
		pickerPosition: "bottom-left"
	});

});
</script>

@stop

@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Pages</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/pages') }}"><i class="fa fa-pencil"></i> Pages</a></li>
@if(isset($page))
        <li class="active">Edit</li>
@else
        <li class="active">Create</li>
@endif
      </ol>
    </div>

    <div class="main-content">

    @if(isset($page))
        {{ Form::model($page, array('route' => array('admin.pages.update', $page->id), 'method' => 'PUT')) }}
    @else
        {{ Form::open(array('url' => 'admin/pages')) }}
    @endif

    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">
              
              @if($errors->any())
                <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
              @endif
							    
              <div class="form-group">
                {{ Form::label('parent_id', 'Parent Page') }}
                {{ Form::select('parent_id', $pageOptionValues[1], (Input::old('parent_id') ? Input::old('parent_id') : (isset($page)?$page->parent_id:'')), array('class' => 'form-control')) }}
              </div>
							    
              <div class="form-group">
                {{ Form::label('sort_id', 'Sort') }}
                {{ Form::select('sort_id', $sortOptionValues, (Input::old('sort_id') ? Input::old('sort_id') : (isset($page)?$page->sort_id:end($sortOptionValues))), array('class' => 'form-control')) }}
              </div>

              @if(isset($languages))
                  <ul class="nav nav-tabs" role="tablist">
                    @foreach($languages as $key => $language)
                          <li class="{{ ($key == 0 ? 'active' : '') }}"><a href="{{ '#' . $language->language . '-' . $language->country }}" role="tab" data-toggle="tab"><img src="{{ asset('packages/dcweb/dcms/assets/images/flag-' . $language->country . '.png') }}" width="18" height="12" /> {{ $language->language_name }}</a></li>
                    @endforeach
                  </ul>

                  <div class="tab-content">
              @foreach($languages as $key => $information)

                    <div id="{{ $information->language . '-' . $information->country }}" class="tab-pane {{ ($key == 0 ? 'active' : '') }}">

                      {{ Form::hidden('page_detail_id[' . $information->language_id . ']', $information->id) }}								

                      <div class="form-group">
                        {{ Form::label('title[' . $information->language_id . ']', 'Title') }}
                        {{ Form::text('title[' . $information->language_id . ']', (Input::old('title[' . $information->language_id . ']') ? Input::old('title[' . $information->language_id . ']') : $information->title ), array('class' => 'form-control')) }}
                      </div>
                                                                        
                      <div class="form-group">
                        {{ Form::label('body[' . $information->language_id . ']', 'Body') }}
                        {{ Form::textarea('body[' . $information->language_id . ']', (Input::old('body[' . $information->language_id . ']') ? Input::old('body[' . $information->language_id . ']') : $information->body ), array('class' => 'form-control')) }}
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

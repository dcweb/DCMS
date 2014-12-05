@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Articles</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/articles') }}"><i class="fa fa-pencil"></i> Articles</a></li>
@if(isset($article))
        <li class="active">Edit</li>
@else
        <li class="active">Create</li>
@endif
      </ol>
    </div>

    <div class="main-content">

    @if(isset($article))
        {{ Form::model($article, array('route' => array('admin.articles.update', $article->id), 'method' => 'PUT')) }}
    @else
        {{ Form::open(array('url' => 'admin/articles')) }}
    @endif

    	<div class="row">
		@if (!is_array($categoryOptionValues) || count($categoryOptionValues)<=0 ) 	Please first create a <a href="{{ URL::to('admin/articles/categories/create') }}"> article category </a>  @else
				<div class="col-md-9">
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

                      {{ Form::hidden('article_information_id[' . $information->language_id . ']', $information->id) }}								
                                                    
                      <div class="form-group">
                        {{ Form::label('category_id[' . $information->language_id . ']', 'Category') }}
                        {{ isset($categoryOptionValues[$information->language_id])? Form::select('category_id[' . $information->language_id . ']', $categoryOptionValues[$information->language_id], (Input::old('category_id[' . $information->language_id . ']') ? Input::old('category_id[' . $information->language_id . ']') : $information->article_category_id), array('class' => 'form-control')):'No categories found' }}
                      </div>
                                                    
                      <div class="form-group">
                        {{ Form::label('title[' . $information->language_id . ']', 'Title') }}
                        {{ Form::text('title[' . $information->language_id . ']', (Input::old('title[' . $information->language_id . ']') ? Input::old('title[' . $information->language_id . ']') : $information->title ), array('class' => 'form-control')) }}
                      </div>
                  
                      <div class="form-group">
                        {{ Form::label('description[' . $information->language_id . ']', 'Description') }}
                        {{ Form::textarea('description[' . $information->language_id . ']', (Input::old('description[' . $information->language_id . ']') ? Input::old('description[' . $information->language_id . ']') : $information->description ), array('class' => 'form-control')) }}
                      </div>
                                                                        
                      <div class="form-group">
                        {{ Form::label('body[' . $information->language_id . ']', 'Body') }}
                        {{ Form::textarea('body[' . $information->language_id . ']', (Input::old('body[' . $information->language_id . ']') ? Input::old('body[' . $information->language_id . ']') : $information->body ), array('class' => 'form-control')) }}
                      </div>
                                                                        
                      <div class="form-group">
                      {{ Form::label('page_id[' . $information->language_id . ']', 'Page') }}
                      {{ isset($pageOptionValues[$information->language_id])? Form::select('page_id[' . $information->language_id . '][]', $pageOptionValues[$information->language_id], (Input::old('page_id[' . $information->language_id . ']') ? Input::old('page_id[' . $information->language_id . ']') : (isset($pageOptionValuesSelected[$information->id]) && count($pageOptionValuesSelected[$information->id])>0)?$pageOptionValuesSelected[$information->id]:''), array('multiple','class' => 'form-control')):'No pages found' }}
                      </div>
                                                                        
                    </div>
                    
              @endforeach
                  </div>

              @endif


                {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
                <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
     
          </div>
        </div>
				<div class="col-md-3">
					<div class="main-content-block">

              <div class="form-group">
                {{ Form::label('startdate', 'Date Start') }}

                <div class="input-group input-append date" data-date="{{ Input::old('startdate') }}" data-date-format="dd-mm-yyyy" data-min-view="2">
	                {{ Form::text('startdate', Input::old('startdate'), array('class' => 'form-control', 'readonly', 'size' => '16')) }}
                  <span class="input-group-addon btn btn-primary"><i class="glyphicon glyphicon-th"></i></span>
                </div>
              </div>                                

              <div class="form-group">
                {{ Form::label('enddate', 'Date End') }}

                <div class="input-group input-append date" data-date="{{ Input::old('enddate') }}" data-date-format="dd-mm-yyyy" data-min-view="2">
	                {{ Form::text('enddate', Input::old('enddate'), array('class' => 'form-control', 'readonly', 'size' => '16')) }}
                  <span class="input-group-addon btn btn-primary"><i class="glyphicon glyphicon-th"></i></span>
                </div>
              </div>                                

							 <div class="form-group">
                {{ Form::label('thumbnail', 'Thumbnail') }}
                <div class="input-group">
                    {{ Form::text('thumbnail', Input::old('thumbnail'), array('class' => 'form-control')) }}
                  <span class="input-group-btn">
                    {{ Form::button('Browse Server', array('class' => 'btn btn-primary browse-server')) }}
                  </span>
                </div>
              </div>
						</div>
          
        </div>
		@endif
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

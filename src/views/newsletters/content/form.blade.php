@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/newsletters') }}"><i class="fa fa-newspaper-o"></i> Newsletters</a></li>
        <li class=""><a href="{{ URL::to('admin/newsletters/content') }}"><i class="fa fa-newspaper-o"></i> Content</a></li>
        
        @if(isset($Content) && intval($Content->id)>0 )
        	<li class="active"><i class="fa fa-pencil"></i> Edit</li>
        @else
        	<li class="active"><i class="fa fa-pencil"></i> Create</li>
        @endif
        
      </ol>
    </div>

    <div class="main-content">

      @if(isset($Content) && intval($Content->id)>0 )
          {{ Form::model($Content, array('route' => array('admin.newsletters.content.update', $Content->id), 'method' => 'PUT')) }}
      @else
          {{Form::open(array('url' => 'admin/newsletters/content'))}}
      @endif
              
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">
              
                            <div class="form-group">
                              {{ Form::label('content_name['.$Content->id.']', 'Name') }}
  	                          {{ Form::text('content_name['.$Content->id.']', (Input::old('content_name['.$Content->id.']') ? Input::old('content_name['.$Content->id.']') : $Content->name), array('class' => 'form-control')) }}
                            </div>
                                                                                  
                            <ul role="tablist" class="nav nav-tabs">
                              <li class="active"><a data-toggle="tab" role="tab" href="#content0">Content</a></li>
                              <li class=""><a data-toggle="tab" role="tab" href="#layout0">Layout</a></li>
                              {{--<li class=""><a data-toggle="tab" role="tab" href="#style0">Style</a></li>--}}
                            </ul>
  
                            <div class="tab-content">
  
                              <div id="content0" class="tab-pane active">
                              
                                <div class="form-group">
                                  {{ Form::label('content_title['.$Content->id.']', 'Title') }}
                                  {{ Form::text('content_title['.$Content->id.']', (Input::old('content_title['.$Content->id.']') ? Input::old('content_title['.$Content->id.']') : $Content->title), array('class' => 'form-control')) }}
                                </div>
                                                                                
                                <div class="form-group">
                                  {{ Form::label('content_body['.$Content->id.']', 'Body') }}
                                  {{ Form::textarea('content_body['.$Content->id.']', (Input::old('content_body['.$Content->id.']') ? Input::old('content_body['.$Content->id.']') : $Content->body), array('class' => 'form-control ckeditor')) }}
                                </div>
                                                                                  
                                <div class="form-group">
                                  {{ Form::label('content_image['.$Content->id.']', 'Image') }}
                                  <div class="input-group">
                                      {{ Form::text('content_image['.$Content->id.']', (Input::old('content_image['.$Content->id.']') ? Input::old('content_image['.$Content->id.']') : $Content->image), array('class' => 'form-control')) }}
                                    <span class="input-group-btn">
                                      {{ Form::button('Browse Server', array('class' => 'btn btn-primary browse-server' , 'id'=>'browse_image')) }}
                                    </span>
                                  </div>
                                </div>
                                          
                                <div class="form-group">
                                  {{ Form::label('content_link['.$Content->id.']', 'Link') }}
                                  {{ Form::text('content_link['.$Content->id.']', (Input::old('content_link['.$Content->id.']') ? Input::old('content_link['.$Content->id.']') : $Content->link), array('class' => 'form-control')) }}
                                </div>
                                                                                
                              </div>
  
                              <div id="layout0" class="tab-pane">
  
                                <div class="form-group">
                                  {{ Form::textarea('content_layout['.$Content->id.']', (Input::old('content_layout['.$Content->id.']') ? Input::old('content_layout['.$Content->id.']') : $Content->layout), array('class' => 'form-control codemirror html')) }}
                                </div>
                                                                                  
                              </div>
  
                              {{--
                              <div id="style0" class="tab-pane">
  
                                <div class="form-group">
                                  {{ Form::textarea('content_style['.$Content->id.']', (Input::old('content_style['.$Content->id.']') ? Input::old('content_style['.$Content->id.']') : $Content->style), array('class' => 'form-control codemirror css')) }}
                                </div>
                                                                                  
                              </div>
                              --}}
  
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

<script type="text/javascript" src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckeditor/adapters/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckbrowser.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/codemirror.css') }}">
<script type="text/javascript" src="{{ asset('/packages/dcweb/dcms/assets/js/codemirror-compressed.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {

	//CKFinder for CKEditor
	CKFinder.setupCKEditor( null, '/ckfinder/' );
	
	//CKEditor
	$("textarea.ckeditor").ckeditor(); //custom config will be set here enabling the HTML tags//{fullPage : true}

	// CodeMirror
	$("textarea.codemirror").each(function(index, element) {
		
		var mode;
		if ( $(element).hasClass("html") ) mode = "xml";
		if ( $(element).hasClass("css") ) mode = "css";
		var cm = CodeMirror.fromTextArea(element, {
			mode: mode,
			tabSize: 2,
			lineNumbers: true,
			lineWrapping: true
		});

		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			cm.refresh();
		})

  });

});
</script>

@stop

@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/newsletters') }}"><i class="fa fa-newspaper-o"></i> Newsletters</a></li>
        
        @if(isset($Newsletter)&& intval($Newsletter->id)>0 )
        	<li class="active"><i class="fa fa-pencil"></i> Edit</li>
        @else
        	<li class="active"><i class="fa fa-pencil"></i> Create</li>
        @endif
        
      </ol>
    </div>

    <div class="main-content">

      @if(isset($Newsletter) && intval($Newsletter->id)>0 )
        {{ Form::model($Newsletter, array('route' => array('admin.newsletters.update', $Newsletter->id), 'method' => 'PUT')) }}
      @else
        {{Form::open(array('url' => 'admin/newsletters'))}}
      @endif

    	<div class="row">
				<div class="col-md-12">

            <div class="main-content-tab tab-container">
              
              <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#settings" role="tab" data-toggle="tab">Settings</a></li>
                <li><a href="#campaign" role="tab" data-toggle="tab">Campaign</a></li>
                <li><a href="#content" role="tab" data-toggle="tab">Content</a></li>
              </ul>
      
              <div class="tab-content">

                @if($errors->any())
                  <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
                @endif
              
                <div id="settings" class="tab-pane active">

                  <div class="form-group">
                    {{ Form::label('default_list', 'Default List') }}
                    {{ Form::select('default_list',array_merge(array(0=>'- None -'),$aLists), $Newsletter->default_list, array('class' => 'form-control')); }}
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
                  
                  <div class="form-group">
                    {{ Form::label('default_date', 'Date') }}
                    <div id="datetimepicker" class="input-group input-append date">
                      <span class="input-group-addon btn btn-primary"><i data-time-icon="glyphicon glyphicon-time" data-date-icon="glyphicon glyphicon-calendar"></i></span>
	                    {{ Form::text('default_date', '', array('data-format' => 'dd-MM-yyyy', 'class' => 'form-control')) }}
                    </div>
                  </div>

                </div>
  
                <div id="campaign" class="tab-pane">
  
                  <div class="btnbar btnbar-right" style="margin-bottom: 24px;"><a class="btn btn-small btn-primary" href="{{ URL::to('/admin/newsletters/create') }}">Create new</a></div>

                  {{ Datatable::table()
                      ->setId('campaigns-datatable')
                      ->addColumn('')
                      ->addColumn('Subject')
                      ->addColumn('Language')
                      ->addColumn('Timestamp')
                      ->addColumn('')
                      ->setUrl( URL::to('admin/newsletters/api/table',array('table'=>'campaigns','selected_campaignid'=>$Newsletter->campaign_id))) //'admin/newsletters/api/table'
                      ->setOptions(array(
                          'pageLength' => 10,
                          'autoWidth' => false,
                          ))
                      ->render() }}
  
                </div>
  
                <div id="content" class="tab-pane">
  
                   <table class="table table-bordered">
                      
                    @if(isset($ContentForms))
	                    {{ $ContentForms }}
                    @endif
                                
                    <tfoot>
                      <tr>
                        <td colspan="3"><a class="btn btn-default pull-right add-table-row" href=""><i class="fa fa-plus"></i></a></td>
                      </tr>
                    </tfoot>
      						</table>
  
                </div>

                <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.css">    
                <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
                <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.js"></script>

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
<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/jquery-ui-sortable.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckeditor/adapters/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckbrowser.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/codemirror.css') }}">
<script type="text/javascript" src="{{ asset('/packages/dcweb/dcms/assets/js/codemirror-compressed.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/bootstrap-datetimepicker.css') }}">
<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap-datetimepicker.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {

	//CKFinder for CKEditor
	CKFinder.setupCKEditor( null, '/ckfinder/' );

	//CKEditor
	$("textarea.ckeditor").ckeditor();
	
	//CKFinder 	
	$(".browse-server").click(function() {
		var returnid = $(this).attr("id").replace("browse_","");
		BrowseServer( 'Images:/', returnid);
	})
	//CKFinder 	
	$(".browse-server-files").click(function() {
		var returnid = $(this).attr("id").replace("browse_","");
		BrowseServer( 'Files:/', returnid);
	})
	
	//Populate List Fields
	$("select[name='default_list']").on('change', function() {
		var id = $("select[name='default_list']").val();
		$.get( "{{ URL::to('admin/newsletters/api/json?data=list&id=') }}"+id, function( data ) {
			data = $.parseJSON(data);
			$("input[name='from_name']").val(data.from_name);
			$("input[name='from_email']").val(data.from_email);
			$("input[name='replyto_email']").val(data.replyto_email);
		});
	})

	//DateTimePicker https://github.com/tarruda/bootstrap-datetimepicker
	$("#datetimepicker").datetimepicker({
		pickTime: false,
		startDate: new Date()
	});	

	//ADD EDIT DEL SORT SORTABLE table row  
	$.fn.addtablerow = function( options ) {

		var table = this;
		var rows = table.find('tbody tr').length;

		addtablerow(table.find('.add-table-row'));
		sorttablerow();
		edittablerow(table.find('.edit-table-row'));
		deltablerow(table.find('.delete-table-row'));
		sortabletablerow();	

		function addtablerow(e) {
			e.click (function() {
				$.get( options.source, function( data ) {
	
					if (!table.find('tbody').length) table.find('tfoot').before("<tbody></tbody>");
	
					rows++;
					data = data.replace(/{ID}/g, "_"+rows);
					data = data.replace(/{_ID}/g, rows);
					
					table.find('tbody').append( data );
	
					sorttablerow();
					edittablerow(table.find('.edit-table-row').last());
					deltablerow(table.find('.delete-table-row').last());
					sortabletablerow();	

					table.find('tbody tr:last textarea.codemirror').each(function(index, element) {
						
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
					
					//CKEditor
					$("textarea.ckeditor").ckeditor(); //custom config will be set here enabling the HTML tags//{fullPage : true}
					
					//CKFinder 	
					$(".browse-server").click(function() {
						var returnid = $(this).attr("id").replace("browse_","") ;
						BrowseServer( 'Images:/', returnid);
					})
					//CKFinder 	
					$(".browse-server-files").click(function() {
						var returnid = $(this).attr("id").replace("browse_","") ;
						BrowseServer( 'Files:/', returnid);
					})


				});
				return false;
			});
		}

		function deltablerow(e) {
			e.click (function() {
				$(this).closest('tr').remove();
				if (!table.find('tbody tr').length) table.find('tbody').remove();
				sorttablerow();
				return false;
			});
		}

		function edittablerow(e) {
			e.click (function() {
				$(this).closest('tr').find('.table-row-sub').toggle();
				return false;
			});
		}

		function sorttablerow() {
			var i = 1;
			table.find("input[name^='content_sortid']").each(function() {
				$(this).val(i);
				i++;
			});
		}

		function sortabletablerow() {
			table.find("tbody").sortable({
				handle: ".sort-table-row",
				axis: "y",
				forceHelperSize: true,
				forcePlaceholderSize: true,
				cursor: "move",
				update: function( event, ui ) {
					sorttablerow();
				}
			});
		}

	}; 

	$("#content table").addtablerow({
		source: "{{ URL::to('admin/newsletters/api/tablerow?data=content') }}"
	});

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

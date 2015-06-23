@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/newsletters') }}"><i class="fa fa-newspaper-o"></i> Newsletters</a></li>
        <li class=""><a href="{{ URL::to('admin/newsletters/campaigns') }}"><i class="fa fa-newspaper-o"></i> Campaign</a></li>
        
        @if(isset($Newslettercampaign) && intval($Newslettercampaign->id) > 0 )
          <li class="active"><i class="fa fa-pencil"></i> Edit</li>
	      @else
          <li class="active"><i class="fa fa-pencil"></i> Create</li>
        @endif

      </ol>
    </div>

    <div class="main-content">
    
  @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
  @endif


      @if(isset($Newslettercampaign) && intval($Newslettercampaign->id)>0 )
          {{ Form::model($Newslettercampaign, array('route' => array('admin.newsletters.campaigns.update', $Newslettercampaign->id), 'method' => 'PUT')) }}
      @else
          {{Form::open(array('url' => 'admin/newsletters/campaigns'))}}
      @endif
          
    	<div class="row">
				<div class="col-md-12">
              
            <div class="main-content-tab tab-container">

              <ul role="tablist" class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" role="tab" href="#campaign">Campaign</a></li>
                 <li class=""><a data-toggle="tab" role="tab" href="#layout">Layout</a></li>
                <li class=""><a data-toggle="tab" role="tab" href="#style">Style</a></li>
                <li class=""><a data-toggle="tab" role="tab" href="#content">Content</a></li>
              </ul>

              <div class="tab-content">

              @if($errors->any())
                <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
              @endif
              
                <div id="campaign" class="tab-pane active">

                  <div class="form-group">
                    {{ Form::label('campaign_subject', 'Subject') }}
                    {{ Form::text('campaign_subject', (Input::old('campaign_subject') ? Input::old('campaign_subject') : $Newslettercampaign->subject), array('class' => 'form-control')) }}
                  </div>
                                                                  
                  <div class="form-group">
                    {{ Form::label('campaign_country_id', 'Country') }}
                    {{ Form::select('campaign_country_id', $aCountries, $Newslettercampaign->country_id, array('class' => 'form-control'));}}                                  
                  </div>
                                                                  
                  <div class="form-group">
                    {{ Form::label('campaign_language_id', 'Country - Language') }}
                    {{ Form::select('campaign_language_id', $aLanguages, $Newslettercampaign->language_id, array('class' => 'form-control'));}}                                  
                  </div>

                </div>


                <div id="layout" class="tab-pane">

                  <div class="form-group">
                    {{ Form::textarea('campaign_layout', (Input::old('campaign_layout') ? Input::old('campaign_layout') : $Newslettercampaign->layout), array('class' => 'form-control codemirror html')) }}
                  </div>
                                                                    
                </div>

                <div id="style" class="tab-pane">

                  <div class="form-group">
                    {{ Form::textarea('campaign_style', (Input::old('campaign_style') ? Input::old('campaign_style') : $Newslettercampaign->style), array('class' => 'form-control codemirror css')) }}
                  </div>
                                                                    
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

<script type="text/javascript">
$(document).ready(function() {

	//CKFinder for CKEditor
	CKFinder.setupCKEditor( null, '/ckfinder/' );
	
	//CKEditor
	$("textarea.ckeditor").ckeditor(); //custom config will be set here enabling the HTML tags//{fullPage : true}

	// Default values in CKEditor Table Dialog Window.
	CKEDITOR.on( 'dialogDefinition', function( ev ) {
			var dialogName = ev.data.name;
			var dialogDefinition = ev.data.definition;
	
			if ( dialogName == 'table' ) {
					var info = dialogDefinition.getContents( 'info' );
	
					info.get( 'txtRows' )[ 'default' ] = '1'; 
					info.get( 'txtWidth' )[ 'default' ] = '100%'; 
					info.get( 'txtBorder' )[ 'default' ] = '0'; 
					info.get( 'txtCellSpace' )[ 'default' ] = '0'; 
					info.get( 'txtCellPad' )[ 'default' ] = '0'; 
			}
	});
	
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
	
	//ADD EDIT COPY DEL SORT SORTABLE table row  
	$.fn.modtablerow = function( options ) {

		var table = this;

		addtablerow(table.find('.add-table-row'));
		sorttablerow();
		edittablerow(table.find('.edit-table-row'));
		copytablerow(table.find('.copy-table-row'));
		deltablerow(table.find('.delete-table-row'));
		sortabletablerow();	

		function addtablerow(e) {
			e.click (function() {
				$.get( options.source, function( data ) {
	
					if (!table.find('tbody').length) table.find('tfoot').before("<tbody></tbody>");
	
					var rows = table.find('tbody tr').length;
					rows++;
					data = data.replace(/{ID}/g, "_"+rows);
					data = data.replace(/{_ID}/g, rows);
					
					table.find('tbody').append( data );
	
					sorttablerow();
					edittablerow(table.find('.edit-table-row').last());
					copytablerow(table.find('.copy-table-row').last());
					deltablerow(table.find('.delete-table-row').last());
					sortabletablerow();	

					table.find('tbody tr:last textarea.codemirror').last().each(function(index, element) {
						
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
					table.find("textarea.ckeditor").last().ckeditor();
					
					//CKFinder 	
					table.find(".browse-server").last().click(function() {
						var returnid = $(this).attr("id").replace("browse_","") ;
						BrowseServer( 'Images:/', returnid);
					})
					//CKFinder 	
					table.find(".browse-server-files").last().click(function() {
						var returnid = $(this).attr("id").replace("browse_","") ;
						BrowseServer( 'Files:/', returnid);
					})
									
				});
				return false;
			});
		}

		function edittablerow(e) {
			e.click (function() {
				$(this).closest('tr').find('.table-row-sub').toggle();
				return false;
			});
		}

		function copytablerow(e) {
			e.click (function() {
				var tr = $(this).closest('tr');
				$.get( options.source, function( data ) {
	
					if (!table.find('tbody').length) table.find('tfoot').before("<tbody></tbody>");
	
					var rows = table.find('tbody tr').length;
					rows++;
					data = data.replace(/{ID}/g, "_"+rows);
					data = data.replace(/{_ID}/g, rows);
					
					tr.after( data );

					tr.next().find("input[name^='content_name']").val( tr.find("input[name^='content_name']").val() + " #" + rows );
					tr.next().find("input[name^='content_title']").val( tr.find("input[name^='content_title']").val() );
					tr.next().find("textarea[name^='content_body']").val( tr.find("textarea[name^='content_body']").val() );
					tr.next().find("input[name^='content_image']").val( tr.find("input[name^='content_image']").val() );
					tr.next().find("input[name^='content_link']").val( tr.find("input[name^='content_link']").val() );
					tr.next().find("textarea[name^='content_layout']").val( tr.find("textarea[name^='content_layout']").val() );
	
					sorttablerow();
					edittablerow(tr.next().find('.edit-table-row'));
					copytablerow(tr.next().find('.copy-table-row'));
					deltablerow(tr.next().find('.delete-table-row'));
					sortabletablerow();	

					tr.next().find('textarea.codemirror').each(function(index, element) {
						
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
					tr.next().find("textarea.ckeditor").ckeditor();
					
					//CKFinder 	
					tr.next().find(".browse-server").click(function() {
						var returnid = $(this).attr("id").replace("browse_","") ;
						BrowseServer( 'Images:/', returnid);
					})
					//CKFinder 	
					tr.next().find(".browse-server-files").click(function() {
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
				},
				start: function( event, ui ) {
					table.find("textarea.ckeditor").ckeditor(function(){
						this.destroy();
					});
				},
				stop: function( event, ui ) {
					table.find("textarea.ckeditor").ckeditor();
				}
			});
		}

	}; 

	$("#content table").modtablerow({
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


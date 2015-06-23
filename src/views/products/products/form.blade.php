@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Products</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/products') }}"><i class="fa fa-shopping-cart"></i> Products</a></li>
        @if(isset($product))
                <li class="active">Edit</li>
        @else
                <li class="active">Create</li>
        @endif
      </ol>
    </div>


    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
            
            @if(isset($product)) 
              {{ Form::model($product, array('route' => array('admin.products.update', $product->id), 'method' => 'PUT')) }}
            @else 
              {{ Form::open(array('url' => 'admin/products')) }}
            @endif

            <div class="main-content-tab tab-container">
            @if (!is_array($categoryOptionValues) || count($categoryOptionValues)<=0 ) 	Please first create a <a href="{{ URL::to('admin/products/categories/create') }}"> product category </a>  @else
                <ul class="nav nav-tabs" role="tablist">
                  <li class="active"><a href="#data" role="tab" data-toggle="tab">Data</a></li>
                  <li><a href="#information" role="tab" data-toggle="tab">Information</a></li>
                  <li><a href="#price" role="tab" data-toggle="tab">Price</a></li>
                  <li><a href="#attachments" role="tab" data-toggle="tab">Attachments</a></li>
                </ul>
        
              	<div class="tab-content">

                @if($errors->any())
                  <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
                @endif
  
  								<div id="data" class="tab-pane active">
									  <!-- #data -->        
                    
									  <!-- #Code -->        
									  <div class="form-group">
                      {{ Form::label('code', 'Code') }}
                      {{ Form::text('code', Input::old('code'), array('class' => 'form-control')) }}
                    </div>
                    
									  <!-- #EAN CODE-->        
                    <div class="form-group">
                      {{ Form::label('eancode', 'EAN Code') }}
                      {{ Form::text('eancode', Input::old('eancode'), array('class' => 'form-control')) }}
                    </div>
                    
                    <div class="form-group">
                      {{ Form::label('image', 'Image') }}
                      <div class="input-group">
                          {{ Form::text('image', Input::old('image'), array('class' => 'form-control')) }}
                        <span class="input-group-btn">
                          {{ Form::button('Browse Server', array('class' => 'btn btn-primary browse-server' , 'id'=>'browse_image')) }}
                        </span>
                      </div>
                    </div>
                              
									  <!-- #Volume + unitclass (kg - l - g - ...) -->        
                    <div class="row">
                      <div class="col-sm-10">
                        <div class="form-group">
                          {{ Form::label('volume', 'Volume') }}
                          {{ Form::text('volume', Input::old('volume'), array('class' => 'form-control')) }}
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          {{ Form::label('volume_unit_id', 'Unit') }}
                          {{ Form::select('volume_unit_id', $volumeclasses, Input::old('volume_unit_id'), array('class' => 'form-control')); }}
                        </div>
                      </div>
                    </div>
                    
                    @if(!isset($extendgeneralTemplate)  || is_null($extendgeneralTemplate) )
                      {{-- nothing to include.. --}}
                    @elseif(!is_null($extendgeneralTemplate["template"]))
                      @include($extendgeneralTemplate["template"], array('model'=>$extendgeneralTemplate["model"],'product'=>(isset($product)?$product:null)))
                      @yield('extendedgeneral')
                    @endif
                    
  								<!-- #data -->        
                </div>

        				<div id="information" class="tab-pane">
									<!-- #information -->                                    
                      <div class="tab-container">

                      @if(isset($languageinformation))
                                            
                          <ul class="nav nav-tabs" role="tablist">
                            @foreach($languageinformation as $key => $language)
                                  <li class="{{ ($key == 0 ? 'active' : '') }}"><a href="{{ '#' . $language->language . '-' . $language->country }}" role="tab" data-toggle="tab"><img src="{{ asset('packages/dcweb/dcms/assets/images/flag-' . $language->country . '.png') }}" width="18" height="12" /> {{ $language->language_name }}</a></li>
                            @endforeach
                          </ul>

                          <div class="tab-content">
                          		@if(!isset($informationtemplate) || is_null($informationtemplate) )
                                @include('dcms::products/products/templates/information', array('languageinformation'=>$languageinformation,'sortOptionValues'=>$sortOptionValues))
                                @yield('information')
                              @else
	                              @include($informationtemplate, array('languageinformation'=>$languageinformation,'sortOptionValues'=>$sortOptionValues))
                                @yield('information')
                              @endif
                          </div>

                      @endif

                      </div>
										<!-- #information -->        
                  </div>
        
        					<div id="price" class="tab-pane">
										<!-- #price -->        
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Country</th>
                          <th>Price</th>
                          <th>Unit</th>
                          <th>Vat</th>
                          <th></th>
                        </tr>
                      </thead>
                      
                      @if(isset($rowPrices))
                      {{ $rowPrices }}
                      @endif
                      
                    <tfoot>
                      <tr>
                        <td colspan="5"><a class="btn btn-default pull-right add-table-row" href=""><i class="fa fa-plus"></i></a></td>
                      </tr>
                    </tfoot>
      						</table>
									<!-- #price -->        
                </div>
                
                
        
        					<div id="attachments" class="tab-pane">
										<!-- #price -->        
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>language-COUNTRY (RFC 3066)</th>
                          <th>File</th>
                          <th>Filename</th>
                          <th></th>
                        </tr>
                      </thead>
                      
                      @if(isset($rowAttachments))
                      {{ $rowAttachments }}
                      @endif
                      
                    <tfoot>
                      <tr>
                        <td colspan="5"><a class="btn btn-default pull-right add-table-row" href=""><i class="fa fa-plus"></i></a></td>
                      </tr>
                    </tfoot>
      						</table>
									<!-- #price -->        
                </div>
  
    							{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
   							 	<a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
  						</div><!-- end tab-content -->
               @endif
            </div><!-- end main-content-tab -->
  
					{{ Form::close() }}
      
      	</div><!-- end col-md-12 -->
      </div><!-- end row -->
    </div><!-- end main-content -->

@stop

@section("script")

<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/jquery-ui-autocomplete.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/jquery-ui-autocomplete.css') }}">

<script type="text/javascript" src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckeditor/adapters/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckbrowser.js') }}"></script>

<script type="text/javascript">

$(document).ready(function() {

	//CKFinder for CKEditor
	CKFinder.setupCKEditor( null, '/ckfinder/' );
	
	//CKEditor
	$("textarea.ckeditor").ckeditor();
	
	//CKFinder 	
	$(".browse-server").click(function() {
		var returnid = $(this).attr("id").replace("browse_","") ;
		BrowseServer( 'Images:/', returnid);
	})
	//CKFinder 	
	/*
	$(".browse-server-files").click(function() {
		var returnid = $(this).attr("id").replace("browse_","") ;
		BrowseServer( 'Files:/', returnid);
	})
*/
	//Bootstrap Tabs
	$(".tab-container .nav-tabs a").click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})

	//UI Autocomplete Product Detail
	$("#information .tab-pane input[id^='information_name']").autocomplete({
		source: function (request, response) {
			var language = this.element.closest(".tab-pane").find("input[name^='information_language_id']").val();
			$.getJSON("{{ route('admin/products/api/pim') }}?term=" + request.term + "&language=" + language, function (data) {
				response(data);
			});
		},
		select: function( event, ui ) {
			$(this).val( ui.item.label );
			
			$x = $(this);
			$.each(ui.item, function(i,v){
					$x.closest(".tab-pane").find("[id^='information_"+i+"']").val( v );
				}); //end of each function
				
			return false;
		},
		minLength: 3,
		delay: 200
	});
	$('#information .input-group .information-id-reset').click(function() {
			$(this).closest(".tab-pane").find("input[id^='information_id']").val( "" );
			return false;
	});

	//Add table row  
	$.fn.addtablerow = function( options ) {

		var table = this;
		var rows = table.find('tbody tr').length;
				
		table.find('.add-table-row').click(function() {
			$.get( options.source, function( data ) {
				if (!table.find('tbody').length) table.find('thead').after("<tbody></tbody>");
				table.find('tbody').append( data.replace(/{INDEX}/g, "extra"+rows) );
				rows++;
				deltablerow(table.find('.delete-table-row').last());
			});
			return false;
		});

		deltablerow(table.find('.delete-table-row'));
		browsetablerow(table.find('.browse-server-files'));

		function browsetablerow(e) {
			console.log("gevonden");
			e.click (function() {
				alert('hoppa');
				var returnid = $(this).attr("id").replace("browse_","");
				BrowseServer( 'Files:/', returnid);
			});
		}

		function deltablerow(e) {
	//		console.log("delete gevonden");
			e.click (function() {
				$(this).closest("tr").remove();
				if (!table.find('tbody tr').length) table.find('tbody').remove();
				return false;
			});
		}

	}; 
	
	$("body").on("click",".browse-server-files", function(){
		var returnid = $(this).attr("id").replace("browse_","") ;
		BrowseServer( 'Files:/', returnid);
		});
	$("#price table").addtablerow({
		source: "{{ URL::to('admin/products/api/tablerow?data=price') }}" //generate the row with the dropdown fields/empty boxes/etc.
	});
	
	$("#attachments table").addtablerow({
		source: "{{ URL::to('admin/products/api/tablerow?data=attachments') }}" //generate the row with the dropdown fields/empty boxes/etc.
	});

});
</script>

@stop

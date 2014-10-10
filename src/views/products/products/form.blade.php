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
                          {{ Form::button('Browse Server', array('class' => 'btn btn-primary browse-server')) }}
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
                          {{ Form::label('volume_unit_class', 'Unit') }}
                          {{ Form::select('volume_unit_class', $volumeclasses, Input::old('volume_unit_class'), array('class' => 'form-control')); }}
                        </div>
                      </div>
                    </div>
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
                      @foreach($languageinformation as $key => $information)

                          	<div id="{{ $information->language . '-' . $information->country }}" class="tab-pane {{ ($key == 0 ? 'active' : '') }}">

                              {{ Form::hidden('information_language_id[' . $key . ']', $information->language_id) }}								
                                                            
                              <div class="form-group">
                                {{ Form::label('information_category_id[' . $key . ']', 'Category') }}
                                {{ isset($categoryOptionValues[$information->language_id])? Form::select('information_category_id[' . $key . ']', $categoryOptionValues[$information->language_id], (Input::old('information_category_id[' . $key . ']') ? Input::old('information_category_id[' . $key . ']') : $information->product_category_id), array('class' => 'form-control')):'' }}
                              </div>   
                              
                                        
                              <div class="form-group">
                           		  {{ Form::label('information_sort_id[' . $key . ']', 'Sort') }}
                                {{ Form::select('information_sort_id[' . $key . ']', $sortOptionValues[$information->language_id], (Input::old('information_sort_id[' . $key . ']') ? Input::old('information_sort_id[' . $key . ']') : $information->sort_id), array('class' => 'form-control')) }}
                              </div>
                                                            
                              <div class="row">
                                <div class="col-sm-10">
                                
                                  <div class="form-group">
                                    {{ Form::label('information_name[' . $key . ']', 'Product Name') }}
                                    {{ Form::text('information_name[' . $key . ']', (Input::old('information_name[' . $key . ']') ? Input::old('information_name[' . $key . ']') : $information->title ), array('class' => 'form-control')) }}
                                  </div>
                                                                                  
                                </div>
                                <div class="col-sm-2">
                                
                                  <div class="form-group">
                                    {{ Form::label('information_id[' . $key . ']', 'ID') }}
                                    <div class="input-group">
                                        {{ Form::text('information_id[' . $key . ']', (Input::old('information_id[' . $key . ']') ? Input::old('information_id[' . $key . ']') : $information->information_id ), array('class' => 'form-control', 'readonly')) }}
                                      <span class="input-group-btn">
                                        {{ Form::button('Reset', array('class' => 'btn btn-primary information-id-reset')) }}
                                      </span>
                                    </div>
                                  </div>
                                
                                </div>
                              </div>
                              
                              <div class="form-group">
                                {{ Form::label('information_description[' . $key . ']', 'Description') }}
                                {{ Form::textarea('information_description[' . $key . ']', (Input::old('information_description[' . $key . ']') ? Input::old('information_description[' . $key . ']') : $information->description ), array('class' => 'form-control')) }}
                              </div>
                                                                                
                            </div>
                            
                      @endforeach
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
                        <td colspan="5"><a class="btn btn-default pull-right add-table-row" href=""><i class="fa fa-plus"></a></td>
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
	$("textarea[id^='information_description']").ckeditor();
	
	//CKFinder 
	$(".browse-server").click(function() {
		BrowseServer( 'Images:/products/', 'image' );
	})

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
			$(this).closest(".tab-pane").find("input[id^='information_id']").val( ui.item.id );
			$(this).closest(".tab-pane").find("textarea[id^='information_description']").val( ui.item.description );
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
				
		table.find('.add-table-row').click (function() {
			$.get( options.source, function( data ) {
				if (!table.find('tbody').length) table.find('thead').after("<tbody></tbody>");
				table.find('tbody').append( data.replace(/{INDEX}/g, "extra"+rows) );
				rows++;
				deltablerow(table.find('.delete-table-row').last());
			});
			return false;
		});

		deltablerow(table.find('.delete-table-row'));

		function deltablerow(e) {
			e.click (function() {
				$(this).closest("tr").remove();
				if (!table.find('tbody tr').length) table.find('tbody').remove();
				return false;
			});
		}

	}; 
	$("#price table").addtablerow({
		source: "{{ URL::to('admin/products/api/tablerow?data=price') }}"
	});

});
</script>

@stop

@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Articles</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/dealers') }}"><i class="fa fa-pencil"></i> Dealers</a></li>
@if(isset($article))
        <li class="active">Edit</li>
@else
        <li class="active">Create</li>
@endif
      </ol>
    </div>

    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">
              
              @if(isset($dealer))
                <h2>Edit Dealer</h2>
                  {{ Form::model($dealer, array('route' => array('admin.dealers.update', $dealer->id), 'method' => 'PUT')) }}
              @else
                <h2>Create Dealer</h2>
                  {{ Form::open(array('url' => 'admin/dealers')) }}
              @endif

              @if($errors->any())
                <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
              @endif

              <div class="form-group">
                {{ Form::label('dealer', 'Dealer') }}
                {{ Form::text('dealer', Input::old('dealer'), array('class' => 'form-control')) }}
              </div>

              <div class="form-group">
                {{ Form::label('address', 'Address') }}
                {{ Form::text('address', Input::old('address'), array('class' => 'form-control')) }}
              </div>

              <div class="row">
                <div class="col-sm-2">
              
                  <div class="form-group">
                    {{ Form::label('zip', 'Zip') }}
                    {{ Form::text('zip', Input::old('zip'), array('class' => 'form-control')) }}
                  </div>
              
                </div>
                <div class="col-sm-10">
                   <div class="form-group">
                      {{ Form::label('city', 'City') }}
                      {{ Form::text('city', Input::old('city'), array('class' => 'form-control')) }}
                    </div>
                </div>
              </div>

             <div class="form-group">
                {{ Form::label('country_id', 'Country') }}
                {{ Form::select('country_id', $countries, Input::old('country_id'), array('class' => 'form-control')); }}
                
              </div>

             <div class="form-group">
                {{ Form::label('phone', 'Phone') }}
                {{ Form::text('phone', Input::old('phone'), array('class' => 'form-control')) }}
              </div>

             <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
              </div>

             <div class="form-group">
                {{ Form::label('website', 'Website') }}
                {{ Form::text('website', "http://".str_replace("http://","",Input::old('website')), array('class' => 'form-control')) }}
              </div>

              {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
                  <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
              {{ Form::close() }}
     
      </div>
    </div>
  </div>
</div>

@stop

@section("script")


<script type="text/javascript">
$(document).ready(function() {

	//Bootstrap Tabs
	$(".tab-container .nav-tabs a").click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	})

	//UI Autocomplete Product Detail
	$(".main-content-block input[id='zip'], .main-content-block input[id='city']").autocomplete({
		source: function (request, response) {
			var country_id = $("#country_id").val();
			$.getJSON("{{ route('admin/dealers/api/zipcity') }}?zipcity=" + request.term + "&country_id=" + country_id , function (data) {
				response(data);
			});
		},
		select: function( event, ui ) {
			$(this).val( ui.item.label );
			$(this).closest(".main-content-block").find("input[id='zip']").val( ui.item.zip );
			$(this).closest(".main-content-block").find("input[id='city']").val( ui.item.city );
			return false;
		},
		minLength: 1,
		delay: 200
	});

});
</script>

<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/jquery-ui-autocomplete.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/jquery-ui-autocomplete.css') }}">


@stop

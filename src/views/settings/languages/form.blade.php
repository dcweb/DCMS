@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Languages</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/settings') }}"> settings</a></li>
        <li><a href="{{ URL::to('admin/settings/languages') }}"><i class="fa fa-pencil"></i> Languages</a></li>
        @if(isset($Language))
                <li class="active">Edit</li>
        @else
                <li class="active">Create</li>
        @endif
      </ol>
    </div>

    <div class="main-content">

    @if(isset($Language))
        {{ Form::model($Language, array('route' => array('admin.settings.languages.update', $Language->id), 'method' => 'PUT')) }}
    @else
        {{ Form::open(array('url' => 'admin/settings/languages')) }}
    @endif

    	<div class="row">
		@if (!is_array($countryOptionValues) || count($countryOptionValues)<=0 ) 	Please first create a <a href="{{ URL::to('admin/settings/countries/create') }}"> Country </a>  @else
				<div class="col-md-12">
					<div class="main-content-block">
             
							  @if($errors->any())
                  <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
                @endif

                @if(isset($Language))
                    {{ Form::model($Language, array('route' => array('admin.settings.languages.update', $Language->id), 'method' => 'PUT')) }}
                    {{ Form::hidden('id', $Language->id) }}	
                @else
                    {{ Form::open(array('url' => 'admin/settings/languages')) }}
                @endif
                                      
              <div class="form-group">
                {{ Form::label('country_id', 'Country') }}
                {{ isset($countryOptionValues)? Form::select('country_id', $countryOptionValues, Input::old('country_id'), array('class' => 'form-control')):'No categories found' }}
              </div>
                                            
              <div class="form-group">
                {{ Form::label('language', 'Language (lower-case two-letter codes as defined by ISO-639)') }}
                {{ Form::text('language', Input::old('language'), array('class' => 'form-control')) }}
              </div>
                                            
              <div class="form-group">
                {{ Form::label('language_name', 'Language') }}
                {{ Form::text('language_name', Input::old('language_name') , array('class' => 'form-control')) }}
              </div>

              {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
              <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
     
          </div>
        </div>
		@endif
      </div>

      {{ Form::close() }}

    </div>

@stop

@section("script")

<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap.min.js') }}"></script>

@stop

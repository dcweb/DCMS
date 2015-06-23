@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Countries</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/settings') }}"> settings</a></li>
        <li><a href="{{ URL::to('admin/settings/countries') }}"><i class="fa fa-pencil"></i> Countries</a></li>
      
          @if(isset($Country))
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
             
                @if($errors->any())
                  <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
                @endif

                @if(isset($Country))
                    {{ Form::model($Country, array('route' => array('admin.settings.countries.update', $Country->id), 'method' => 'PUT')) }}
                    {{ Form::hidden('id', $Country->id) }}	
                @else
                    {{ Form::open(array('url' => 'admin/settings/countries')) }}
                @endif
        
                <div class="form-group">
                  {{ Form::label('country', 'Country (upper-case two-letter codes as defined by ISO-3166 - RFC 3066)') }} 
                  {{ Form::text('country', Input::old('country') , array('class' => 'form-control')) }}
                </div>
            
        
                <div class="form-group">
                  {{ Form::label('country_name', 'Country') }}
                  {{ Form::text('country_name', Input::old('country_name') , array('class' => 'form-control')) }}
                </div>
            
                <div class="form-group">
                  {{ Form::label('flag', 'Flag') }}
                  * the system does not hold the flags by default, you shoud upload a file to: <i><b>/packages/dcweb/dcms/assets/images/flag-XX.png</b></i> - XX holds the lower-case two-letter codes as defined by ISO-3166
                </div>
                                                                  
                <div class="form-group">
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
<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap.min.js') }}"></script>
@stop
@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Languages</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/settings') }}"> settings</a></li>
        <li><a href="{{ URL::to('admin/settings/taxes') }}"><i class="fa fa-pencil"></i> Taxes</a></li>
        @if(isset($Tax))
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

                @if(isset($Tax))
                    {{ Form::model($Tax, array('route' => array('admin.settings.taxes.update', $Tax->id), 'method' => 'PUT')) }}
                    {{ Form::hidden('id', $Tax->id) }}	
                @else
                    {{ Form::open(array('url' => 'admin/settings/taxes')) }}
                @endif
                                      
              <div class="form-group">
                {{ Form::label('tax', 'Tax') }}
                {{ Form::text('tax', Input::old('tax'), array('class' => 'form-control')) }}
              </div>
            
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

@stop

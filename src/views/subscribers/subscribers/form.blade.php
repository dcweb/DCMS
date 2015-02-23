@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/subscribers') }}"><i class="fa fa-newspaper-o"></i> Subscribers</a></li>
        
        @if(isset($Subscriber)&& intval($Subscriber->id)>0 )
        	<li class="active"><i class="fa fa-pencil"></i> Edit</li>
        @else
        	<li class="active"><i class="fa fa-pencil"></i> Create</li>
        @endif
        
      </ol>
    </div>

    <div class="main-content">

      @if(isset($Subscriber) && intval($Subscriber->id)>0 )
          {{ Form::model($Subscriber, array('route' => array('admin.subscribers.update', $Subscriber->id), 'method' => 'PUT')) }}
      @else
          {{Form::open(array('url' => 'admin/subscribers'))}}
      @endif
  
        <div class="row">
          <div class="col-md-12">		
            <div class="main-content-block">

              <div class="form-group">
                {{ Form::label('list_id', 'List') }}
                {{ Form::select('list_id', $aLists , null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::text('email', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('firstname', 'Firstname') }}
                {{ Form::text('firstname', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('lastname', 'Lastname') }}
                {{ Form::text('lastname', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('gender', 'Gender') }}
                {{ Form::select('gender', array(''=>' - ','M'=>'Male','F'=>'Female') , null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('street', 'Street') }}
                {{ Form::text('street', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('nr', 'Nr') }}
                {{ Form::text('nr', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('bus', 'Bus') }}
                {{ Form::text('bus', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('zip', 'Zip') }}
                {{ Form::text('zip', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('city', 'City') }}
                {{ Form::text('city', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('country', 'Country') }}
                {{ Form::text('country', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('language', 'Language') }}
                {{ Form::text('language', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('newsletter', 'Newsletter') }}
                {{ Form::radio('newsletter', '0', null, array('class' => 'form-control')); }} No
                {{ Form::radio('newsletter', '1', (is_null($Subscriber->newsletter)?true:null), array('class' => 'form-control')); }} Yes
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

<script type="text/javascript">
$(document).ready(function() {


});
</script>

@stop

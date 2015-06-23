@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Profile</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/profile') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/users/profile') }}"><i class="fa fa-user"></i> Profile</a></li>
        <li class="active">Edit</li>
      </ol>
    </div>

    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">


  @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
  @endif

	<h2>{{ Auth::dcms()->user()->name }}</h2>
	
{{ Form::model($user, array('route' => array('admin/profile/edit'), 'method' => 'POST')) }}

@if($errors->any())
  <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
@endif


	<div class="form-group">
		{{ Form::label('username', 'Username') }}
		{{ Form::text('username', null, array('class' => 'form-control', 'readonly')) }}
	</div>
  
	<div class="form-group">
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name', null, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('email', 'Email') }}
		{{ Form::text('email', null, array('class' => 'form-control')) }}
	</div>
	
  <div class="form-group">
    {{ Form::label('password', 'Current Password') }}
    {{ Form::password('password', array('class' => 'form-control')) }}
  </div>
  
  <div class="form-group">
    {{ Form::label('newpassword', 'New Password') }}
    {{ Form::password('newpassword', array('class' => 'form-control')) }}
  </div>
  
  <div class="form-group">
    {{ Form::label('newpasswordrepeat', 'Repeat New Password') }}
    {{ Form::password('newpasswordrepeat', array('class' => 'form-control')) }}
  </div>

	{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
  <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>

{{ Form::close() }}
      
	      	</div>
      	</div>
      </div>
    </div>

@stop
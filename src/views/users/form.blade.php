@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Users</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/users') }}"><i class="fa fa-user"></i> Users</a></li>
@if(isset($user))
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

@if(isset($user))
	<h2>Edit User</h2>

{{ Form::model($user, array('route' => array('admin.users.update', $user->id), 'method' => 'PUT')) }}
@else
	<h2>Create User</h2>
	
{{ Form::open(array('url' => 'admin/users')) }}
@endif

@if($errors->any())
  <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
@endif


	<div class="form-group">
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name', null, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('username', 'Username') }}
		{{ Form::text('username', null, array('class' => 'form-control')) }}
	</div>
  
	<div class="form-group">
		{{ Form::label('role', 'Role') }}
		{{ Form::select('role', array('user' => 'user', 'administrator'=>'administrator'), null,  array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('email', 'Email') }}
		{{ Form::text('email', null, array('class' => 'form-control')) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('password', 'Password') }}
		{{ Form::password('password', array('class' => 'form-control')) }}
	</div>

@if(!isset($user))
	<div class="form-group">
		{{ Form::label('sendemail', 'Send password by e-mail to the new user.') }}
		{{ Form::checkbox('sendemail', '1', true) }}
	</div>
@endif

	{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
  <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>

{{ Form::close() }}
      
	      	</div>
      	</div>
      </div>
    </div>

@stop

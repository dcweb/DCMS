<!DOCTYPE html>
<html lang="">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ isset( $title ) ? $title - DCMS | Admin : 'DCMS | Admin' }}</title>

<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/style.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/font-awesome.min.css') }}">
<link rel="shortcut icon" href="/favicon.ico" />

<!--
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery-ui/jquery-ui-1.10.4.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui-1.10.4.custom.min.css" />
--> 

<style type="text/css">
.logo {
	margin-bottom: 40px;
}

.form-signin {
    margin: 80px auto;
    max-width: 330px;
    padding: 15px;
}
.form-signin .form-signin-heading, .form-signin .checkbox {
    margin-bottom: 10px;
}
.form-signin .checkbox {
    font-weight: normal;
}
.form-signin .form-control {
    box-sizing: border-box;
    font-size: 16px;
    height: auto;
    padding: 10px;
    position: relative;
}
.form-signin .form-control:focus {
    z-index: 2;
}
.form-signin input[type="text"] {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    margin-bottom: -1px;
}
.form-signin input[type="password"] {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    margin-bottom: 10px;
}
</style>

<!--
<script type="text/javascript">
$(document).ready(function() {
});
</script>
-->

</head>
<body>

<div class="outer-wrap">
	<div class="inner-wrap">

  {{ Form::open(array('url'=>'admin/login', 'class' => 'form-signin')) }}
  	
  	<div class="logo">
    	<a href=""><img src="{{ asset('packages/dcweb/dcms/assets/images/logo_large_white.png') }}" alt="DCMS"></a>
    </div>
  	<h3>Please login</h3>
    {{ Form::text("username", Input::old("username"), array('placeholder' => 'Username', 'class' => 'form-control', 'required', 'autofocus')) }}
    {{ Form::password("password", array('placeholder' => 'Password', 'class' => 'form-control', 'required')) }}
    @if($errors->any())
		<div class="alert alert-danger">{{$errors->first()}}</div>
		@endif
    {{ Form::submit("Login", array('class' => 'btn btn-lg btn-primary btn-block')) }}
  {{ Form::close() }}

	</div> 
</div> 

</body>
</html>

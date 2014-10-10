@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Dashboard v0.7.2</h1>
      <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
      </ol>
    </div>

    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">

      <h2>Hello {{ isset( Auth::user()->username ) ? Auth::user()->username : '' }}</h2>
      <p>Welcome to your DCMS Admin Dashboard page.</p>
      
	      	</div>
      	</div>
      </div>
    </div>

@stop

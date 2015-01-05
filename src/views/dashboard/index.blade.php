@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Dashboard v1.1.0 beta release</h1>
      
      
      <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
      </ol>
    </div>

    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">

      <h2>Hello {{ isset( Auth::dcms()->user()->username ) ? Auth::dcms()->user()->username : '' }}</h2>
      <p>Welcome to your DCMS Admin Dashboard page.</p>
      
	      	</div>
      	</div>
      </div>
    </div>

@stop

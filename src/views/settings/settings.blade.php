@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Settings</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-pencil"></i> Settings</li>
      </ol>
    </div>

    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">
						<ul>
            <li><a href="{{ URL::to('admin/settings/countries') }}" >Countries</a></li>
            <li><a href="{{ URL::to('admin/settings/languages') }}" >Languages</a></li>
            </ul>
	      	</div>
      	</div>
      </div>
    </div>

@stop

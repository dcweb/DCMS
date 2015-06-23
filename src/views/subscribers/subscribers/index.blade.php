@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Subscribers</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        @if(isset($List) && !is_null($List)) <li class=""><a href="{{ URL::to('admin/subscribers/lists') }}"><i class="fa fa-newspaper-o"></i> Lists</a></li> @else  <li class="active"><i class="fa fa-newspaper-o"></i> Subscribers</li> @endif 
        @if(isset($List) && !is_null($List)) <li class="active"> {{$List->listname}}</li> @endif
      </ol>
    </div>

    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">

  @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
  @endif

    <div class="btnbar btnbar-right"><a class="btn btn-small btn-primary" href="{{ URL::to('/admin/subscribers/create') }}">Create new</a></div>

    <h2>Overview</h2>

 {{ Datatable::table()
    ->setId('datatable')
    ->addColumn('Firstname')
    ->addColumn('Lastname')
    ->addColumn('Email')
    ->addColumn('List')
    ->addColumn('Newsletter')
		->addColumn('')
    ->setUrl( URL::Route('admin/subscribers/api/table',array('id'=>$id)))
    ->setOptions(array(
        'pageLength' => 50,
        ))
    ->render() }}

    
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.css">
    
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.js"></script>

	      	</div>
      	</div>
      </div>
    </div>

@stop

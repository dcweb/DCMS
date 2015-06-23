@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-newspaper-o"></i> Newsletters</li>
      </ol>
    </div>

    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">

  @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
  @endif

    <div class="btnbar btnbar-right"><a class="btn btn-small btn-primary" href="{{ URL::to('/admin/newsletters/create') }}">Create new</a></div>

    <h2>Overview</h2>

 {{ Datatable::table()
    ->setId('datatable')
    ->addColumn('Created')
    ->addColumn('Subject')
    ->addColumn('Country')
    ->addColumn('Language')
		->addColumn('List')
		->addColumn('Default date')
		->addColumn('Send count')
		->addColumn('')
    ->setUrl( URL::to('admin/newsletters/api/table'))
    ->setOptions(array(
        'pageLength' => 50,
        'order'=>array(0,'desc'),
        'columnDefs'=>array(
            array(
                "targets"=> array(0),
                "visible"=> false
            )
        )
        
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

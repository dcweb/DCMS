@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/subscribers') }}"><i class="fa fa-newspaper-o"></i> Subscribers</a></li>
        <li><a href="{{ URL::to('admin/subscribers/lists') }}"><i class="fa fa-newspaper-o"></i> Lists</a></li>
        
        @if(isset($List)&& intval($List->id)>0 )
        	<li class="active"><i class="fa fa-pencil"></i> Edit</li>
        @else
        	<li class="active"><i class="fa fa-pencil"></i> Create</li>
        @endif
        
      </ol>
    </div>

    <div class="main-content">

      @if(isset($List) && intval($List->id)>0 )
          {{ Form::model($List, array('route' => array('admin.subscribers.lists.update', $List->id), 'method' => 'PUT')) }}
      @else
          {{Form::open(array('url' => 'admin/subscribers/lists'))}}
      @endif
  
        <div class="row">
          <div class="col-md-12">		
            <div class="main-content-block">

              <div class="form-group">
                {{ Form::label('listname', 'List Name') }}
                {{ Form::text('listname', null,array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('from_name', 'Default From Name') }}
                {{ Form::text('from_name', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('from_email', 'Default From E-mail') }}
                {{ Form::text('from_email', null, array('class' => 'form-control')); }}
              </div>
  
              <div class="form-group">
                {{ Form::label('replyto_email', 'Default Reply-to E-mail') }}
                {{ Form::text('replyto_email', null, array('class' => 'form-control')); }}
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

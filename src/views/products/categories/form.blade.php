@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Categories</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/products') }}"><i class="fa fa-pencil"></i> products</a></li>
        <li><a href="{{ URL::to('admin/products/categories') }}"><i class="fa fa-pencil"></i> Categories</a></li>
        @if(isset($category))
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

              @if(isset($category))
                <h2>Edit category</h2>
                {{ Form::model($category, array('route' => array('admin.products.categories.update', $category->id), 'method' => 'PUT')) }}
              @else
                <h2>Create category</h2>
                
                  {{ Form::open(array('url' => 'admin/products/categories')) }}
              @endif
              
              @if($errors->any())
                <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
              @endif
                  
              <div class="form-group">
                {{ Form::label('parent_id', 'Parent Category') }}
                {{ Form::select('parent_id', $categoryOptionValues[1], (Input::old('parent_id') ? Input::old('parent_id') : (isset($category)?$category->parent_id:'')), array('class' => 'form-control')) }}
              </div>
							    
    
							<?php 
              $activeisset = false;
              $liTabs = ""; 
              $divTabContent = ""; 
              ?>
              
              @if(isset($languages))
              @foreach($languages as $lang)
                <?php
                    $active = "";
                    if ($activeisset == false) $active = "active";
                    $activeisset = true;
                    
                    $liTabs .= '<li class="'.$active.'"><a href="#'.$lang->country.$lang->language.'" role="tab" data-toggle="tab"><img src="'.asset('packages/dcweb/dcms/assets/images/flag-'.$lang->country.'.png').'" width="18" height="12" />'.$lang->language_name.'</a></li>';		
                    $divTabContent .= '<div id="'.$lang->country.$lang->language.'" class="tab-pane '.$active.'"><div class="form-group">';
                    $divTabContent .= Form::hidden('product_category_id['.$lang->language_id.']', $lang->id);
										
										
                    $divTabContent .= Form::label('sort_id['.$lang->language_id.']', 'Sort ');
                    $divTabContent .= Form::select('sort_id['.$lang->language_id.']', $sortOptionLanguageValues[$lang->language_id], $lang->sort_id, array('class' => 'form-control'));
										
                    $divTabContent .= Form::label('title['.$lang->language_id.']', 'Title ');
                    $divTabContent .= Form::text('title['.$lang->language_id.']', $lang->title, array('class' => 'form-control'));
                    
										$divTabContent .= '</div></div>';
                ?>
              @endforeach
              @endif

              <div class="tab-container">
                  <ul class="nav nav-tabs" role="tablist">
                    <?php echo $liTabs; ?>
                  </ul>
                          
                  <div class="tab-content">
                       <?php  echo $divTabContent; ?>
                  </div>
              </div>

							{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
              <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
            	{{ Form::close() }}

	      	</div>
      	</div>
      </div>
    </div>

<script type="text/javascript">
$(document).ready(function() {

	//Bootstrap Tabs
	$(".tab-container .nav-tabs a").click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
});
	
</script>

<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap.min.js') }}"></script>
@stop



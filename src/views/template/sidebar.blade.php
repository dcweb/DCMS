@section("sidebar")
	<div class="sidebar">
    <ul class="nav nav-sidebar">
      <li><a href="{{ URL::to('admin/dashboard') }}">
      	<i class="fa fa-dashboard"></i>
      	<span>Dashboard</span>
      </a></li>
      <li class="dropdown">
        <a href="{{ URL::to('admin/articles') }}"><i class="fa fa-pencil"></i><span>Articles</span><b class="arrow fa fa-angle-down"></b></a>
        <ul class="dropdown-menu">
          <li><a href="{{ URL::to('admin/articles') }}">Overview</a></li>
          <li><a href="{{ URL::to('admin/articles/categories') }}">Categories</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <a href="{{ URL::to('admin/products') }}"><i class="fa fa-shopping-cart"></i><span>Products</span><b class="arrow fa fa-angle-down"></b></a>
        <ul class="dropdown-menu">
          <li><a href="{{ URL::to('admin/products') }}">Overview</a></li>
          <li><a href="{{ URL::to('admin/products/categories') }}">Categories</a></li>
        </ul>
      </li>
      <li><a href="{{ URL::to('admin/pages') }}"><i class="fa fa-file"></i><span>Pages</span></a></li>
      <li><a href="{{ URL::to('admin/dealers') }}"><i class="fa fa-map-marker"></i><span>Dealers</span></a></li>
      <li><a href="{{ URL::to('admin/newsletters') }}"><i class="fa-newspaper-o"></i><span>Newsletter</span></a></li>
      <li><a href="{{ URL::to('admin/files') }}"><i class="fa fa-folder"></i><span>Files</span></a></li>
    </ul>
    <div class="text-right collapse-button">
      <button id="sidebar-collapse" class="btn btn-default" style="">
	      <i class="fa fa-angle-right"></i>
      </button>
    </div>
  </div>
@show
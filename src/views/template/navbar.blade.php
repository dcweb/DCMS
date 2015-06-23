@section("navbar")
<div class="navbar navbar-default navbar-fixed-top">
  <div class="navbar-header">
    <a class="navbar-brand" href="{{ URL::to('admin/dashboard') }}"></a>
  </div>
  <div class="navbar-collapse collapse">
@unless (Auth::dcms()->user()->role !== 'administrator')
    <ul class="nav navbar-nav">
      <li class="dropdown"><a href="{{ URL::to('admin/settings') }}"><i class="fa fa-cog"></i> Settings</a>
          <ul class="dropdown-menu">
              <li><a href="{{ URL::to('admin/settings/countries') }}" >Countries</a></li>
              <li><a href="{{ URL::to('admin/settings/languages') }}" >Languages</a></li>
              <li><a href="{{ URL::to('admin/settings/taxes') }}" >Tax Classes</a></li>
              <li><a href="{{ URL::to('admin/settings/volumes') }}" >Volume Classes</a></li>
         	</ul>
      </li>
      <li><a href="{{ URL::to('admin/users') }}"><i class="fa fa-user"></i> Users</a></li>
    </ul>
@endunless
    <ul class="nav navbar-nav">
      <li><a href="/"><b>Visit website</b></a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="active"><a href="{{ URL::route('admin/profile') }}">Logged in as <b>{{ Auth::dcms()->user()->username }}</b></a></li>
      <li><a href="{{ URL::route('admin/users/logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>
  </div>
</div>
@show
@section("navbar")
<div class="navbar navbar-default navbar-fixed-top">
  <div class="navbar-header">
    <a class="navbar-brand" href="{{ URL::to('admin/dashboard') }}"></a>
  </div>
  <div class="navbar-collapse collapse">
@unless (Auth::user()->role !== 'administrator')
    <ul class="nav navbar-nav">
      <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
      <li><a href="{{ URL::to('admin/users') }}"><i class="fa fa-user"></i> Users</a></li>
    </ul>
@endunless
    <ul class="nav navbar-nav">
      <li><a href="/"><b>Visit website</b></a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="active"><a href="{{ URL::route('admin/users/profile') }}">Logged in as <b>{{ Auth::user()->username }}</b></a></li>
      <li><a href="{{ URL::route('admin/users/logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>
  </div>
</div>
@show
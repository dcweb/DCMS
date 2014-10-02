@extends("dcms::template/layout")
@section("content")
  <h2>Hello {{ isset( Auth::user()->username ) ? Auth::user()->username : 'niemand' }}</h2>
  <p>Welcome to your sparse profile page.</p>
@stop
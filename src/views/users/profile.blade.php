@extends("dcms::template/layout")
@section("content")
  <h2>Hello {{ isset( Auth::dcms()->user()->username ) ? Auth::dcms()->user()->username : 'niemand' }}</h2>
  <p>Welcome to your sparse profile page.</p>
@stop
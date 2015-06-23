<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth.dcms', function()
{
	if (Auth::dcms()->guest()) return Redirect::guest('admin/login');
});

Route::filter("admin.dcms",function()
{
	if (Auth::dcms()->user()->role !== 'administrator') return Redirect::to('admin/dashboard');
});

Route::filter('auth.dcms.basic', function()
{
	return Auth::dcms()->basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest.dcms', function()
{
	if (Auth::dcms()->check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (stristr(Request::server('HTTP_USER_AGENT'),"mandrill") === false && Session::token() !== Input::get('_token')   )
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

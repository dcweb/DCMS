## DCMS

This is a package to make a basic CMS.
We are still in development. Getting files up to date, for easy installation


## Installation

Require this package 

1. cmd:  composer require 
          dcweb

2. MARK!! using Ollieread/multiaut ==> https://github.com/ollieread/multiauth
		- **Author**: Ollie Read 
		- **Author Homepage**: http://ollieread.com
		since we're using this package we need to keep in mind some Auth tweaks..

3. Now you'll want to update or install via composer.

    composer update

4. Next you open up app/config/app.php and replace the AuthServiceProvider with

    "Ollieread\Multiauth\MultiauthServiceProvider"


5. app/config set:

		Next you open up app/config/app.php and replace the AuthServiceProvider with

    "Ollieread\Multiauth\MultiauthServiceProvider"
		
		
		service providers to add
			'Dcweb\Dcms\DcmsServiceProvider',
			'Chumper\Datatable\DatatableServiceProvider',
			'Barryvdh\Debugbar\ServiceProvider',	
		
		alias
			'Datatable' => 'Chumper\Datatable\Facades\DatatableFacade',

6. MultiAuth: Configuration is pretty easy too, take app/config/auth.php from your root laravel installation with its default values:

    return array(
			'driver' => 'eloquent',
			'model' => 'User',
			'table' => 'users',
			'reminder' => array(
				'email' => 'emails.auth.reminder',
				'table' => 'password_reminders',
				'expire' => 60,
			),
		);

7. Now remove the first three options and replace as follows:

    return array(
				'multi' => array(
						'user' => array(
								'driver' => 'eloquent',
								'table' => 'User'
						)
				),
	
			'reminder' => array(
				'email' => 'emails.auth.reminder',
				'table' => 'password_reminders',
				'expire' => 60,
			),
	);

8. A neccesary update to the app/filters.php adding the user configuration to the filter and others:
	
	Route::filter('auth.user', function()
	{
		if (Auth::user()->guest()) return Redirect::guest('login');
	});
	
	
	Route::filter('auth.user.basic', function()
	{
		return Auth::user()->basic();
	});
		
9. set database info
	- admin (connection: for zipcodes)
	- project (your backend database)

10. cmd: php artisan dump-autoload

11. cmd: php artisan asset:publish

12. cmd: php artisan migrate --package="dcweb/dcms" 

13. it may be easier to simply execute the given dcms.sql file to your database, some export function did not set up the seeder correctly (foreign keys - stored procedures)
	cmd: php artisan db:seed --class=DCMSTableSeeder
    - seed the databse with dumy info - this will help you're project launched since some items are needed: 

14. make sure you have ckfinder / ckeditor installed-configured-... (or copied from your previous installs)

15. find the install on:
    - yourdomain.be/admin
		- login with your credentials (u:admin pw:dcmsadmin)
		
the dealers module needs an extra database 'admin' which holds global content i.e. zipcode of countries etc.

16. CSRF vulnerability
	http://blog.laravel.com/csrf-vulnerability-in-laravel-4/

##TO DO
1. userlogin should go to 'admin' database where rights could be managed

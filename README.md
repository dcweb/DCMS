## DCMS

This is a package to make a basic CMS.
We are still in development. Getting files up to date, for easy installation


## Installation

Require this package 

1. cmd:  composer require 
          dcweb

2. app/config set:
 

		service providers 
			'Dcweb\Dcms\DcmsServiceProvider',
			'Chumper\Datatable\DatatableServiceProvider',
			'Barryvdh\Debugbar\ServiceProvider',	
		
		alias
			'Datatable' => 'Chumper\Datatable\Facades\DatatableFacade',
		
3. set database info
	- admin (connection: for zipcodes)
	- project (your backend database)

4. cmd: php artisan dump-autoload

5. cmd: php artisan asset:publish

6. cmd: php artisan migrate --package="dcweb/dcms" 

7. cmd: php artisan db:seed --class=DCMSTableSeeder
    - seed the databse with dumy info - this will help you're project launched since some items are needed: 
8. make sure you have ckfinder / ckeditor installed-configured-... (or copied from your previous installs)

9. find the install on:
    - yourdomain.be/admin
		- login with your credentials (u:admin pw:dcmsadmin)
		
the dealers module needs an extra database 'admin' which holds global content i.e. zipcode of countries etc.

##TO DO
1. userlogin should go to 'admin' database where rights could be managed

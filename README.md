## DCMS
[![Latest Stable Version](https://poser.pugx.org/barryvdh/laravel-debugbar/version.png)](https://packagist.org/packages/dcweb/dcms)

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

4. php artisan dump-autoload

5. php artisan asset:publish

6. php artisan migrate --package="dcweb/dcms" 
    seed the databse with dumy info: 
    --> php artisan db:seed --class=DCMSTableSeeder

7. make sure you have ckfinder / ckeditor installed-configured-... (or copied from your previous installs)
       

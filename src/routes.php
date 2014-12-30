<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group( array("prefix" => "admin"), function() {

	Route::get("/", function() { return Redirect::to("admin/login"); });
	
	Route::any("/login", array( "as" => "admin/users/login", "uses" => "Dcweb\Dcms\Controllers\Users\UserController@login"));
	Route::any("/logout", array( "as" => "admin/users/logout", "uses" => "Dcweb\Dcms\Controllers\Users\UserController@logout"));
	// Route::any("/request", array("as" => "admin/users/request", "uses" => "Dcweb\Dcms\Controllers\Users\UserController@request"));
	// Route::any("/reset/{token}", array("as" => "admin/users/reset", "uses" => "Dcweb\Dcms\Controllers\Users\UserController@reset"));

	Route::group(array("before" => "auth.dcms"), function() {
		/*
				// DYNAMIC ROUTING : http://stackoverflow.com/questions/20418922/laravel-4-dynamic-routing-overkill
				Route::any('{controller}/{action?}/{args?}', function($controller, $action = 'index', $args = '')
				{
						$cont = "Controller";
						$notFound = "NotFound";
						$params = explode("/", $args);
						$app = app();
				
						if (!class_exists($controller.$cont) || !function_exists($contName.$cont.".".$action)) {
								$controller = $notFound;
								$action = 'index';
						}
				
						$controller = $app->make($controller.$cont);
						return $controller->callAction($app, $app['router'], $action, $params);
				
				 })
				->where(array(
						'controller' => '[^/]+',
						'action' => '[^/]+',
						'args' => '[^?$]+'
				));
		*/

		//DASHBOARD - CMS HOME
		Route::any("dashboard", array( "as" => "admin/dashboard", "uses" => "Dcweb\Dcms\Controllers\Dashboard\DashboardController@dashboard"));

		//SETTINGS - SET UP EXTRA LANGUAGES
		Route::group( array("prefix" => "settings"), function() {
			//COUNTRIES
			Route::group(array("prefix" => "countries"), function() {
				Route::any('api/table', array('as'=>'admin/settings/countries/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Settings\CountryController@getDatatable'));
			});
			Route::resource('countries','Dcweb\Dcms\Controllers\Settings\CountryController');
			
			//LANGUAGES
			Route::group(array("prefix" => "languages"), function() {
				Route::any('api/table', array('as'=>'admin/settings/languages/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Settings\LanguageController@getDatatable'));
			});
			Route::resource('languages','Dcweb\Dcms\Controllers\Settings\LanguageController');
			
			//TAXES
			Route::group(array("prefix" => "taxes"), function() {
				Route::any('api/table', array('as'=>'admin/settings/taxes/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Settings\TaxController@getDatatable'));
			});
			Route::resource('taxes','Dcweb\Dcms\Controllers\Settings\TaxController');

			//VOLUMES
			Route::group(array("prefix" => "volumes"), function() {
				Route::any('api/table', array('as'=>'admin/settings/volumes/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Settings\VolumeController@getDatatable'));
			});
			Route::resource('volumes','Dcweb\Dcms\Controllers\Settings\VolumeController');
		});
		Route::any('settings','Dcweb\Dcms\Controllers\Settings\SettingController@index');		

		
		//PROFILE
		Route::any("profile", array( "as" => "admin/users/profile", "uses" => "Dcweb\Dcms\Controllers\Users\UserController@profile"));
		
		//FILES
		Route::any("files", array( "as" => "admin/files", "uses" => "Dcweb\Dcms\Controllers\Files\FileController@index"));	
		
		//Pages
		Route::group( array("prefix" => "pages"), function() {
			Route::any('api/table', array('as'=>'admin/pages/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Pages\PageController@getDatatable'));
		});
		Route::resource('pages','Dcweb\Dcms\Controllers\Pages\PageController');		
		
		
		//Newsletter
		Route::group( array("prefix" => "newsletters"), function() {		
	//		Route::get('{id}/copy', array('as'=>'admin/newsletter/copy', 'uses' => 'Dcweb\Dcms\Controllers\Dealers\DealerController@copy'));
	//		Route::get('api/zipcity', array('as'=>'admin/dealers/api/zipcity', 'uses' => 'Dcweb\Dcms\Controllers\Dealers\DealerController@getZipCityJson'));
			Route::any("api/table", array( "as" => "admin/newsletters/api/table", "uses" => "Dcweb\Dcms\Controllers\Newsletters\NewsletterController@getDatatable"));		
			Route::any("api/send", array( "as" => "admin/newsletters/api/send", "uses" => "Dcweb\Dcms\Controllers\Newsletters\NewsletterController@sendmail"));		
		});
		Route::resource("newsletters", 'Dcweb\Dcms\Controllers\Newsletters\NewsletterController');
		
		//DEALERS
		Route::group( array("prefix" => "dealers"), function() {		
			Route::get('{id}/copy', array('as'=>'admin/dealers/copy', 'uses' => 'Dcweb\Dcms\Controllers\Dealers\DealerController@copy'));
			Route::get('api/zipcity', array('as'=>'admin/dealers/api/zipcity', 'uses' => 'Dcweb\Dcms\Controllers\Dealers\DealerController@getZipCityJson'));
			Route::any("api/table", array( "as" => "admin/dealers/api/table", "uses" => "Dcweb\Dcms\Controllers\Dealers\DealerController@getDatatable"));		
		});
		Route::resource("dealers", 'Dcweb\Dcms\Controllers\Dealers\DealerController');

		//ARTICLES
		Route::group( array("prefix" => "articles"), function() {
			//CATEGORIES
			Route::resource('categories','Dcweb\Dcms\Controllers\Articles\CategoryController');
			Route::get('categories/{id}/copy', array('as'=>'admin/articles/categories/{id}/copy', 'uses' => 'Dcweb\Dcms\Controllers\Articles\CategoryController@copy'));
			Route::any('categories/api/table', array('as'=>'admin/articles/categories/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Articles\CategoryController@getDatatable'));
			Route::get('{id}/copy', array('as'=>'admin/articles/copy', 'uses' => 'Dcweb\Dcms\Controllers\Articles\ArticleController@copy'));
			Route::any('api/table', array('as'=>'admin/articles/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Articles\ArticleController@getDatatable'));
		});
		Route::resource('articles','Dcweb\Dcms\Controllers\Articles\ArticleController');		

		//PRODUCTS
		Route::group( array("prefix" => "products"), function() {
			//CATEGORIES
			Route::get('categories/generatetree', array('as'=>'admin/products/categories/generatetree', 'uses' => 'Dcweb\Dcms\Controllers\Products\CategoryController@generateCategoryTree'));
			Route::resource('categories','Dcweb\Dcms\Controllers\Products\CategoryController');
			Route::get('categories/{id}/copy', array('as'=>'admin/products/categories/{id}/copy', 'uses' => 'Dcweb\Dcms\Controllers\Products\CategoryController@copy'));
			Route::any('categories/api/table', array('as'=>'admin/products/categories/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Products\CategoryController@getDatatable'));
			Route::resource('categorieslanguages','Dcweb\Dcms\Controllers\Products\CategoryLanguageController');
			Route::get('categorieslanguages/{id}/copy', array('as'=>'admin/products/categorieslanguages/{id}/copy', 'uses' => 'Dcweb\Dcms\Controllers\Products\CategoryLanguageController@copy'));
			Route::any('categorieslanguages/api/table', array('as'=>'admin/products/categorieslanguages/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Products\CategoryLanguageController@getDatatable'));
			Route::any('api/table', array('as'=>'admin/products/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Products\ProductController@getDatatable'));
			Route::any('api/tablerow', array('as'=>'admin/products/api/tablerow', 'uses' => 'Dcweb\Dcms\Controllers\Products\ProductController@getTableRow'));
			Route::get('api/pim', array('as'=>'admin/products/api/pim', 'uses' => 'Dcweb\Dcms\Controllers\Products\ProductController@json'));
			Route::get('{id}/copy', array('as'=>'admin/products/{id}/copy', 'uses' => 'Dcweb\Dcms\Controllers\Products\ProductController@copy'));
		});
		Route::resource('products','Dcweb\Dcms\Controllers\Products\ProductController');		

		//USERS
		Route::group(array("before"=>"admin.dcms"), function() {
			Route::resource('users','Dcweb\Dcms\Controllers\Users\UserController');		
			Route::any('users/api/table', array('as'=>'admin/users/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Users\UserController@getDatatable'));
		});
	});
});

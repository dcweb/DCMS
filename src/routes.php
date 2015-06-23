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


Route::any("admin/newsletters/monitortransaction", array('as'=>'monitortransaction', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\TransactionController@monitor')); //used as webhook return path in mandrill 
Route::any("unsubscribe/{ID?}", array('as'=>'newsletter/unsubscribe', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\TransactionController@unsubscribe')); //used in the {{unsub}} tag of mandrill
Route::any("newsletter/{ID}/{CRYPTID?}", array('as'=>'newsletter/viewonline', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\ViewController@newsletter')); //used in the {{unsub}} tag of mandrill

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

		//USERS
		Route::group(array("before"=>"admin.dcms"), function() {
			Route::resource('users','Dcweb\Dcms\Controllers\Users\UserController');		
			Route::any('users/api/table', array('as'=>'admin/users/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Users\UserController@getDatatable'));
		});

		//PROFILE
		Route::any("profile", array( "as" => "admin/profile", "uses" => "Dcweb\Dcms\Controllers\Users\UserController@profile"));
		Route::any("profile/edit", array( "as" => "admin/profile/edit", "uses" => "Dcweb\Dcms\Controllers\Users\UserController@updateProfile"));
		
		//SETTINGS - SET UP EXTRA LANGUAGES
		Route::group( array("prefix" => "settings","before"=>"admin.dcms"), function() {
			//COUNTRIES
			Route::group(array("prefix" => "countries"), function() {
				Route::any('api/table', array('as'=>'admin/settings/countries/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Settings\CountryController@getDatatable'));
			});
			Route::resource('countries','Dcweb\Dcms\Controllers\Settings\CountryController');
			
			//LANGUAGES
			Route::group(array("prefix" => "languages","before"=>"admin.dcms"), function() {
				Route::any('api/table', array('as'=>'admin/settings/languages/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Settings\LanguageController@getDatatable'));
			});
			Route::resource('languages','Dcweb\Dcms\Controllers\Settings\LanguageController');
			
			//TAXES
			Route::group(array("prefix" => "taxes","before"=>"admin.dcms"), function() {
				Route::any('api/table', array('as'=>'admin/settings/taxes/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Settings\TaxController@getDatatable'));
			});
			Route::resource('taxes','Dcweb\Dcms\Controllers\Settings\TaxController');

			//VOLUMES
			Route::group(array("prefix" => "volumes","before"=>"admin.dcms"), function() {
				Route::any('api/table', array('as'=>'admin/settings/volumes/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Settings\VolumeController@getDatatable'));
			});
			Route::resource('volumes','Dcweb\Dcms\Controllers\Settings\VolumeController');
		});
		Route::any('settings','Dcweb\Dcms\Controllers\Settings\SettingController@index');

		
		//FILES
		Route::any("files", array( "as" => "admin/files", "uses" => "Dcweb\Dcms\Controllers\Files\FileController@index"));
		
		//PAGES
		Route::group( array("prefix" => "pages"), function() {
			Route::any('api/table', array('as'=>'admin/pages/api/table', 'uses' => 'Dcweb\Dcms\Controllers\Pages\PageController@getDatatable'));
		});
		Route::resource('pages','Dcweb\Dcms\Controllers\Pages\PageController');
		
		
		//NEWSLETTERS
		Route::group( array("prefix" => "newsletters"), function() {
			//CONTENT
			Route::get("content/{id}/view", array('as'=>'admin/newsletters/content/view', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\ViewController@content'));
			Route::any('api/tablerow', array('as'=>'admin/newsletters/api/tablerow', 'uses' => 'Dcweb\Dcms\Controllers\Newsletters\ContentController@getTableRow'));
			Route::resource("content", 'Dcweb\Dcms\Controllers\Newsletters\ContentController');
			//CAMPAIGNS
			Route::get("campaigns/{id}/view", array('as'=>'admin/newsletters/campaigns/view', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\ViewController@campaign'));
			Route::get('campaigns/{id}/copy', array('as'=>'admin/newsletters/campaigns/{id}/copy', 'uses' => 'Dcweb\Dcms\Controllers\Newsletters\CampaignController@copy'));
			Route::resource("campaigns", 'Dcweb\Dcms\Controllers\Newsletters\CampaignController');
			//NEWSLETTERS
			Route::get("{id}/send", array('as'=>'admin/newsletters/send', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\NewsletterController@send'));
			Route::any("{id}/transaction", array('as'=>'admin/newsletters/transaction', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\TransactionController@send'));
			Route::get("{id}/view", array('as'=>'admin/newsletters/view', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\ViewController@newsletter'));
			Route::get('{id}/copy', array('as'=>'admin/newsletters/{id}/copy', 'uses' => 'Dcweb\Dcms\Controllers\Newsletters\NewsletterController@copy'));
			Route::any("api/table/{table?}/{selected_campaignid?}", array( "as" => "admin/newsletters/api/table", "uses" => "Dcweb\Dcms\Controllers\Newsletters\NewsletterController@getDatatable"));
			Route::any("api/json", array( "as" => "admin/newsletters/api/json", "uses" => "Dcweb\Dcms\Controllers\Subscribers\ListController@getJsonData"));
			Route::resource("settings", 'Dcweb\Dcms\Controllers\Newsletters\SettingController');
			//ANALYSE
			Route::any("analyse", array('as'=>'admin/newsletters/analyse', 'uses'=>'Dcweb\Dcms\Controllers\Newsletters\TransactionController@analyse'));
		});
		Route::resource("newsletters", 'Dcweb\Dcms\Controllers\Newsletters\NewsletterController');
		
		//SUBSCRIBERS
		Route::group( array("prefix" => "subscribers"), function() {		
			Route::group( array("prefix" => "lists"), function() {		
				Route::resource("api/table", 'Dcweb\Dcms\Controllers\Subscribers\ListController@getDatatable');
			});
			Route::resource("lists", 'Dcweb\Dcms\Controllers\Subscribers\ListController');
				
			Route::any("list/{listid?}", array( "as" => "admin/subscribers/list", "uses" => "Dcweb\Dcms\Controllers\Subscribers\SubscriberController@index"));		
			Route::any("api/table/{listid?}", array( "as" => "admin/subscribers/api/table", "uses" => "Dcweb\Dcms\Controllers\Subscribers\SubscriberController@getDatatable"));			
		});
		Route::resource("subscribers", 'Dcweb\Dcms\Controllers\Subscribers\SubscriberController');
		
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

	});

});

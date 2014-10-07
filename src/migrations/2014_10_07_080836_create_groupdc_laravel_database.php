<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupdc_LaravelDatabase extends Migration {

        /**
         * Run the migrations.
         *
         * @return void
         */
         public function up()
         {
            
	    /**
	     * Table: articles
	     */
	    Schema::create('articles', function($table) {
                $table->increments('id')->unsigned();
                $table->date('startdate')->nullable();
                $table->date('enddate')->nullable();
                $table->string('thumbnail', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: articles_categories
	     */
	    Schema::create('articles_categories', function($table) {
                $table->increments('id')->unsigned();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: articles_categories_detail
	     */
	    Schema::create('articles_categories_detail', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('article_category_id')->nullable();
                $table->integer('language_id')->nullable();
                $table->string('title', 255)->nullable();
                $table->string('url_slug', 255)->nullable();
                $table->string('url_path', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: articles_detail
	     */
	    Schema::create('articles_detail', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('language_id')->nullable()->default("1");
                $table->integer('article_category_id')->nullable();
                $table->integer('article_id')->nullable();
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->text('body')->nullable();
                $table->string('url_slug', 255)->nullable();
                $table->string('url_path', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: countries
	     */
	    Schema::create('countries', function($table) {
                $table->increments('id');
                $table->string('country', 10)->nullable();
                $table->string('country_name', 150)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: dealers
	     */
	    Schema::create('dealers', function($table) {
                $table->increments('id');
                $table->string('dealer', 200)->nullable();
                $table->string('address', 250)->nullable();
                $table->string('zip', 50)->nullable();
                $table->string('city', 150)->nullable();
                $table->integer('country_id')->nullable()->default("1");
                $table->string('phone', 20)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('website', 150)->nullable();
                $table->decimal('longitude', 13,7)->nullable();
                $table->decimal('latitude', 13,7)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: languages
	     */
	    Schema::create('languages', function($table) {
                $table->increments('id')->unsigned();
                $table->string('language', 255);
                $table->string('language_name', 255)->nullable();
                $table->string('country', 10)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: pages
	     */
	    Schema::create('pages', function($table) {
                $table->increments('id');
                $table->integer('parent_id')->nullable();
                $table->integer('sort_id')->nullable();
                $table->string('admin', 255)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: pages_detail
	     */
	    Schema::create('pages_detail', function($table) {
                $table->increments('id');
                $table->integer('language_id')->default("1");
                $table->integer('page_id')->nullable();
                $table->string('title', 255)->nullable();
                $table->text('body')->nullable();
                $table->string('url_path', 255)->nullable();
                $table->string('url_slug', 255)->nullable();
                $table->string('admin', 255)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: pagetree
	     */
	    Schema::create('pagetree', function($table) {
                $table->integer('id')->nullable();
                $table->increments('detail_id');
                $table->integer('parent_id')->nullable();
                $table->string('regio', 10)->nullable();
                $table->integer('language_id')->nullable();
                $table->integer('sort_id')->nullable();
                $table->string('page', 255)->nullable();
                $table->integer('childcount')->nullable();
                $table->integer('level')->nullable();
                $table->text('url')->nullable();
                $table->text('path')->nullable();
                $table->text('idPath')->nullable();
            });


	    /**
	     * Table: password_reminders
	     */
	    Schema::create('password_reminders', function($table) {
                $table->string('email', 255);
                $table->string('token', 255);
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->index('password_reminders_email_index');
                $table->index('password_reminders_token_index');
            });


	    /**
	     * Table: products
	     */
	    Schema::create('products', function($table) {
                $table->increments('id')->unsigned();
                $table->string('code', 255)->nullable();
                $table->string('eancode', 255);
                $table->string('image', 255)->nullable();
                $table->string('volume', 50)->nullable();
                $table->integer('volume_unit_class')->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: products_categories
	     */
	    Schema::create('products_categories', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('parent_id')->nullable();
                $table->integer('sort_id')->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: products_categories_detail
	     */
	    Schema::create('products_categories_detail', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('product_category_id')->nullable();
                $table->integer('language_id')->nullable();
                $table->string('title', 255)->nullable();
                $table->string('url_slug', 255)->nullable();
                $table->string('url_path', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: products_information
	     */
	    Schema::create('products_information', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('language_id')->nullable()->default("1");
                $table->integer('product_category_id')->nullable();
                $table->integer('sort_id')->nullable();
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->string('url_slug', 255)->nullable();
                $table->string('url_path', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: products_price
	     */
	    Schema::create('products_price', function($table) {
                $table->increments('id');
                $table->integer('country_id')->nullable();
                $table->integer('product_id')->nullable();
                $table->decimal('price', 6,2)->nullable()->default("0.00");
                $table->integer('valuta_class_id')->nullable()->default("1");
                $table->integer('tax_class_id')->nullable()->default("1");
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: products_to_products_information
	     */
	    Schema::create('products_to_products_information', function($table) {
                $table->increments('product_id');
                $table->increments('product_information_id');
            });


	    /**
	     * Table: productscategorytree
	     */
	    Schema::create('productscategorytree', function($table) {
                $table->integer('id')->nullable();
                $table->integer('detail_id')->nullable();
                $table->integer('parent_id')->nullable();
                $table->string('regio', 10)->nullable();
                $table->integer('language_id')->nullable();
                $table->integer('sort_id')->nullable();
                $table->string('productcategory', 255)->nullable();
                $table->integer('childcount')->nullable();
                $table->integer('level')->nullable();
                $table->text('url')->nullable();
                $table->text('path')->nullable();
                $table->text('idPath')->nullable();
            });


	    /**
	     * Table: tax_class
	     */
	    Schema::create('tax_class', function($table) {
                $table->increments('id')->unsigned();
                $table->string('tax_class', 50);
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: users
	     */
	    Schema::create('users', function($table) {
                $table->increments('id')->unsigned();
                $table->string('email', 255);
                $table->string('name', 255);
                $table->string('role', 20)->nullable()->default("user");
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
                $table->string('username', 255);
                $table->string('password', 255);
                $table->string('remember_token', 255)->nullable();
                $table->index('user_email_unique');
            });


	    /**
	     * Table: volumes_class
	     */
	    Schema::create('volumes_class', function($table) {
                $table->increments('id')->unsigned();
                $table->string('volume_class', 50);
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: volumes_class_detail
	     */
	    Schema::create('volumes_class_detail', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('volume_id');
                $table->integer('language_id')->nullable();
                $table->string('volume_class', 20)->nullable();
                $table->string('volume_class_long', 70)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


         }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
         public function down()
         {
            
	            Schema::drop('articles');
	            Schema::drop('articles_categories');
	            Schema::drop('articles_categories_detail');
	            Schema::drop('articles_detail');
	            Schema::drop('countries');
	            Schema::drop('dealers');
	            Schema::drop('languages');
	            Schema::drop('pages');
	            Schema::drop('pages_detail');
	            Schema::drop('pagetree');
	            Schema::drop('password_reminders');
	            Schema::drop('products');
	            Schema::drop('products_categories');
	            Schema::drop('products_categories_detail');
	            Schema::drop('products_information');
	            Schema::drop('products_price');
	            Schema::drop('products_to_products_information');
	            Schema::drop('productscategorytree');
	            Schema::drop('tax_class');
	            Schema::drop('users');
	            Schema::drop('volumes_class');
	            Schema::drop('volumes_class_detail');
         }

}
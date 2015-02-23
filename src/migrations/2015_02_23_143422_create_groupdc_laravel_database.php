<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDCMSDatabase extends Migration {

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
                $table->integer('sort_id')->nullable();
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
	     * Table: articles_detail_to_pages
	     */
	    Schema::create('articles_detail_to_pages', function($table) {
                $table->integer('article_detail_id');
                $table->integer('page_id');
            });


	    /**
	     * Table: countries
	     */
	    Schema::create('countries', function($table) {
                $table->increments('id')->unsigned();
                $table->string('country', 10)->nullable();
                $table->string('country_name', 150)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: dealers
	     */
	    Schema::create('dealers', function($table) {
                $table->increments('id')->unsigned();
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
                $table->integer('country_id')->nullable()->unsigned();
                $table->string('language', 255);
                $table->string('language_name', 255)->nullable();
                $table->string('country', 10)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->index('FK_langcountryid');
            });


	    /**
	     * Table: newsletter
	     */
	    Schema::create('newsletter', function($table) {
                $table->increments('id');
                $table->string('subject', 255)->nullable();
                $table->string('sender', 150)->nullable()->default("newsletter@dcm-info.com");
                $table->string('sendermail', 150)->nullable()->default("newsletter@dcm-info.com");
                $table->string('replyto', 255)->nullable()->default("newsletter@dcm-info.com");
                $table->text('body')->nullable();
                $table->text('htmlbody')->nullable();
                $table->dateTime('date')->nullable();
                $table->string('language', 20)->nullable();
                $table->string('regio', 20)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('timestamp')->nullable();
            });


	    /**
	     * Table: newsletters
	     */
	    Schema::create('newsletters', function($table) {
                $table->increments('id');
                $table->integer('campaign_id')->nullable();
                $table->string('from_name', 150)->nullable()->default("newsletter@dcm-info.com");
                $table->string('from_email', 150)->nullable()->default("newsletter@dcm-info.com");
                $table->string('replyto_email', 150)->nullable()->default("newsletter@dcm-info.com");
                $table->integer('default_list')->nullable()->default("1");
                $table->timestamp('default_date')->nullable()->default("0000-00-00 00:00:00");
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->index('FK_newslettercampaignid');
            });


	    /**
	     * Table: newsletters_campaigns
	     */
	    Schema::create('newsletters_campaigns', function($table) {
                $table->increments('id');
                $table->string('subject', 255)->nullable();
                $table->integer('language_id')->nullable();
                $table->text('wrapper')->nullable();
                $table->text('layout')->nullable();
                $table->text('style')->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: newsletters_content
	     */
	    Schema::create('newsletters_content', function($table) {
                $table->increments('id');
                $table->integer('campaign_id')->nullable();
                $table->integer('newsletter_id')->nullable();
                $table->integer('sort_id')->nullable();
                $table->string('name', 200)->nullable();
                $table->string('title', 255)->nullable();
                $table->text('body')->nullable();
                $table->string('image', 255)->nullable();
                $table->string('link', 200)->nullable();
                $table->text('layout')->nullable();
                $table->text('style')->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->index('FK_contentnewsletter');
                $table->index('FK_contentcampaign');
            });


	    /**
	     * Table: newsletters_default
	     */
	    Schema::create('newsletters_default', function($table) {
                $table->increments('id')->default("1");
                $table->string('api_key', 255)->nullable();
                $table->string('api_sandbox_key', 255)->nullable();
                $table->string('from_name', 255)->nullable();
                $table->string('from_email', 255)->nullable();
                $table->string('replyto_email', 255)->nullable();
                $table->string('track_opens', 10)->nullable();
                $table->string('track_clicks', 10)->nullable();
                $table->string('inline_css', 10)->nullable();
                $table->string('url_strip_qs', 10)->nullable();
                $table->string('signing_domain', 255)->nullable();
                $table->text('google_analytics_domains')->nullable();
                $table->string('google_analytics_campaign', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: newsletters_sentlog
	     */
	    Schema::create('newsletters_sentlog', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('newsletter_id')->nullable();
                $table->string('type', 50)->nullable();
                $table->text('emails')->nullable();
                $table->integer('list_id')->nullable();
                $table->integer('count')->nullable()->unsigned();
                $table->text('mandrill_settings')->nullable();
                $table->timestamp('send_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: newsletters_transactionmonitor
	     */
	    Schema::create('newsletters_transactionmonitor', function($table) {
                $table->increments('id');
                $table->text('mandrill_log')->nullable();
                $table->text('server_log')->nullable();
                $table->string('event', 150)->nullable();
                $table->string('email', 255)->nullable();
                $table->string('sender', 255)->nullable();
                $table->string('state', 150)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: pages
	     */
	    Schema::create('pages', function($table) {
                $table->increments('id')->unsigned();
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
                $table->increments('id')->unsigned();
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
                $table->integer('detail_id');
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
                $table->primary('detail_id');
            });


	    /**
	     * Table: password_reminders
	     */
	    Schema::create('password_reminders', function($table) {
                $table->string('email', 255);
                $table->string('token', 255);
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->index('email','password_reminders_email_index');
                $table->index('token','password_reminders_token_index');
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
                $table->integer('new')->nullable();
                $table->integer('pro')->nullable();
                $table->string('period', 12)->nullable()->default("000000000000");
                $table->integer('matter_id')->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->integer('_oldvolumeid')->nullable();
                $table->integer('_oldproductid')->nullable();
            });


	    /**
	     * Table: products_categories
	     */
	    Schema::create('products_categories', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('parent_id')->nullable()->unsigned();
                $table->integer('sort_id')->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
                $table->integer('_oldid')->nullable();
                $table->integer('_oldtagid')->nullable();
            });


	    /**
	     * Table: products_categories_detail
	     */
	    Schema::create('products_categories_detail', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('product_category_id')->nullable()->unsigned();
                $table->integer('language_id')->nullable()->unsigned();
                $table->string('title', 255)->nullable();
                $table->string('url_slug', 255)->nullable();
                $table->string('url_path', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->integer('_oldid')->nullable();
                $table->integer('_oldtagid')->nullable();
                $table->index('FK_categoryparent');
                $table->index('FK_categorylanguage');
            });


	    /**
	     * Table: products_information
	     */
	    Schema::create('products_information', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('language_id')->nullable()->default("1")->unsigned();
                $table->integer('product_category_id')->nullable()->unsigned();
                $table->integer('sort_id')->nullable()->unsigned();
                $table->string('title', 255);
                $table->string('composition', 255)->nullable();
                $table->text('description')->nullable();
                $table->text('guarantee')->nullable();
                $table->string('pdf', 255)->nullable();
                $table->string('url_slug', 255)->nullable();
                $table->string('url_path', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->integer('_oldproductid')->nullable();
                $table->index('FK_productlanguage');
                $table->index('FK_productcategory');
            });


	    /**
	     * Table: products_labels
	     */
	    Schema::create('products_labels', function($table) {
                $table->increments('id');
                $table->integer('sort_id')->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: products_labels_detail
	     */
	    Schema::create('products_labels_detail', function($table) {
                $table->increments('id');
                $table->integer('label_id')->nullable();
                $table->integer('language_id')->nullable();
                $table->integer('sort_id')->nullable();
                $table->string('title', 255)->nullable();
                $table->string('subtitle', 255)->nullable();
                $table->string('slug', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('image', 255)->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('timestamp')->nullable()->default("CURRENT_TIMESTAMP");
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->index('FK_labelid');
            });


	    /**
	     * Table: products_matters
	     */
	    Schema::create('products_matters', function($table) {
                $table->increments('id');
                $table->timestamp('timestamp')->default("CURRENT_TIMESTAMP");
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: products_matters_detail
	     */
	    Schema::create('products_matters_detail', function($table) {
                $table->increments('id');
                $table->integer('matter_id');
                $table->integer('language_id')->nullable();
                $table->string('matter', 100)->nullable();
                $table->timestamp('timestamp')->nullable()->default("CURRENT_TIMESTAMP");
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
            });


	    /**
	     * Table: products_price
	     */
	    Schema::create('products_price', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('country_id')->nullable()->unsigned();
                $table->integer('product_id')->nullable()->unsigned();
                $table->decimal('price', 6,2)->nullable()->default("0.00");
                $table->integer('valuta_class_id')->nullable()->default("1");
                $table->integer('tax_class_id')->nullable()->default("1");
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
                $table->index('FK_pricecountry');
                $table->index('FK_priceproduct');
            });


	    /**
	     * Table: products_to_products_information
	     */
	    Schema::create('products_to_products_information', function($table) {
                $table->integer('product_id');
                $table->integer('product_information_id');
            });


	    /**
	     * Table: products_to_products_labels
	     */
	    Schema::create('products_to_products_labels', function($table) {
                $table->integer('product_id');
                $table->integer('label_id');
            });


	    /**
	     * Table: products_volumedata
	     */
	    Schema::create('products_volumedata', function($table) {
                $table->increments('id');
                $table->integer('product_id');
                $table->integer('language_id')->nullable();
                $table->string('used_for', 255)->nullable();
                $table->timestamp('timestamp')->nullable()->default("CURRENT_TIMESTAMP");
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
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
	     * Table: subscribers
	     */
	    Schema::create('subscribers', function($table) {
                $table->increments('id')->unsigned();
                $table->integer('list_id')->nullable();
                $table->string('email', 150)->nullable();
                $table->string('username', 255)->nullable();
                $table->string('cryptid', 255)->nullable();
                $table->string('password', 255)->nullable();
                $table->string('firstname', 100)->nullable();
                $table->string('lastname', 100)->nullable();
                $table->string('gender', 20)->nullable();
                $table->string('street', 150)->nullable();
                $table->string('nr', 5)->nullable();
                $table->string('bus', 5)->nullable();
                $table->string('zip', 7)->nullable();
                $table->string('city', 100)->nullable();
                $table->string('country', 50)->nullable();
                $table->string('language', 20)->nullable();
                $table->boolean('newsletter')->nullable()->default("1");
                $table->boolean('active')->nullable();
                $table->boolean('verified')->nullable();
                $table->timestamp('lastlogin')->nullable()->default("0000-00-00 00:00:00");
                $table->integer('bounced')->nullable();
                $table->timestamp('bouncedtime')->nullable()->default("0000-00-00 00:00:00");
                $table->string('remember_token', 255)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable();
                $table->string('admin', 50)->nullable();
                $table->timestamp('timestamp')->nullable()->default("CURRENT_TIMESTAMP");
                $table->index('UK_username');
                $table->index('UK_email_list');
                $table->index('UK_email_list');
            });


	    /**
	     * Table: subscribers_lists
	     */
	    Schema::create('subscribers_lists', function($table) {
                $table->increments('id')->unsigned();
                $table->string('listname', 100)->nullable();
                $table->string('from_name', 100)->nullable()->default("newsletter@dcm-info.com");
                $table->string('from_email', 100)->nullable()->default("newsletter@dcm-info.com");
                $table->string('replyto_email', 100)->nullable()->default("newsletter@dcm-info.com");
                $table->string('admin', 50)->nullable();
                $table->timestamp('created_at')->nullable()->default("0000-00-00 00:00:00");
                $table->timestamp('updated_at')->nullable()->default("0000-00-00 00:00:00");
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
                $table->unique('email','user_email_unique');
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
	
		$sql_procedure = <<<SQL
		DROP PROCEDURE IF EXISTS `recursivepage`;
CREATE PROCEDURE `recursivepage`(IN `iRoot` int,  IN `iLevel` int, IN iLanguageID int, IN iPathString text, iURLstring text, IN iIDpath text, iSortCounter int)
BEGIN
	
DECLARE irows,ichildid,iparentid,iLanguage_id, ichildcount,isortid, langdone,iCurLangID, iDetail_id, done, iLanguages INT DEFAULT 0;

DECLARE cPage VARCHAR(255);
DECLARE cUrl VARCHAR(255);
DECLARE iRegio VARCHAR(10);
DECLARE txtPath TEXT;
DECLARE txtUrl TEXT;
DECLARE idPath TEXT;

DECLARE txtDivision VARCHAR(255) ;
DECLARE txtSector VARCHAR(255);
DECLARE txtSectorCategory VARCHAR(255);

DECLARE urlDivision VARCHAR(255) ;
DECLARE urlSector VARCHAR(255);
DECLARE urlSectorCategory VARCHAR(255);


SET @@SESSION.max_sp_recursion_depth=25;
#rows : count the subcategories on this parentID only 1 layer deeper
  SET irows = ( SELECT COUNT(*) FROM Pages WHERE parent_ID=iroot );

 # if the level is set to 0 - clear the _descendants so we can start from scratch (this is a recursive function so it will pass by for fetching the deeper layers
 
  IF iLevel = 0 THEN
	IF (iLanguageID= 1)THEN 
		DROP  TABLE IF EXISTS PageTree;
			CREATE TABLE PageTree ( id INT , detail_id int, parent_id INT, regio varchar(10) , language_id int,   sort_id int,  page VARCHAR(255),  
			childcount INT, 
			level INT, 
			url TEXT,  
			path TEXT,  
			idPath TEXT,
			PRIMARY KEY (detail_id)
			);
	END IF;
  END IF;
  #end if iLevel = 0

#rows how many subcategories just below this root Page
IF irows > 0 THEN
    BEGIN
		DECLARE cur CURSOR FOR

			SELECT  	t.id,
				pages_detail.id,
				t.parent_id, 
				f.sort_id,  
				concat(languages.language,'-',languages.country) as Regio,
				languages.id,
				pages_detail.title as Page,
				pages_detail.url_slug as Path,

				(SELECT COUNT(*) FROM Pages WHERE parent_ID=t.ID) AS childcount , 

		case when length(iPathString) > 0 THEN  case when (length((select title from pages_Detail where page_id = f.id and language_id = languages.id limit 1))>0) then concat(iPathString , " | ", (select title from pages_Detail where page_id = f.id and language_id = languages.id limit 1)) else '' end  ELSE (select title from pages_Detail where page_id = f.id and language_id = languages.id limit 1) END as tPath, 

		case when length(iURLstring) > 0 THEN  case when (length((select url_slug from pages_Detail where page_id = f.id and language_id = languages.id limit 1))>0) then concat(iURLstring, "/", (select url_slug from pages_Detail where page_id = f.id and language_id = languages.id limit 1)) else '' end ELSE case  (select url_slug from pages_Detail where page_id = f.id and language_id = languages.id limit 1) when 'INDEX' then 'INDEX' else  (select concat(languages.language,'-',languages.country,'/',url_slug) from pages_Detail where page_id = f.id and language_id = languages.id limit 1)  end  END as tUrl,

		case when length(iIDpath)>0 then concat(iIDpath , ',',f.id)  else f.id end  as tIDpath

		FROM pages t JOIN pages f ON t.ID=f.ID
		inner join pages_detail on t.id= pages_detail.page_id
		inner join languages on pages_detail.language_id = languages.id
			WHERE t.parent_id=iroot
		and languages.id = iLanguageID
			ORDER BY f.sort_id asc, f.updated_at desc, childcount desc; 
	#sortid asc
	
	
		DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

      	OPEN cur;
      	
	select @counter := iSortcounter  ;

	WHILE NOT done DO

        	FETCH cur INTO 
				ichildid,
				iDetail_id,
				iparentid, 
				isortid, 
				iRegio,
				iLanguage_id,
				cPage, 
				cURL  ,
				ichildcount,
				txtPath, 
				txtUrl,  
				idPath;

       		IF NOT done THEN
					
			select @counter := @counter + 1 ;
			INSERT INTO PageTree VALUES(ichildid,iDetail_id,iparentid,iRegio,iLanguage_id, @counter , 
				cPage,
				ichildcount,
				ilevel, 
				txtUrl, 
				txtPath, 
				idPath 
			);

			update pages set sort_id = @counter where id = ichildid;

       			IF ichildcount > 0 THEN
       				CALL recursivepage( ichildid, ilevel + 1, iLanguageID,
						txtPath, 
						txtUrl, 
						idPath,
						@counter  
				);

       			END IF;
      		END IF;
      END WHILE;
	
      CLOSE cur;
  
    END;	
END IF;

END
SQL;
        DB::connection()->getPdo()->exec($sql_procedure);
		
$sql_procedure_recursiveproductcategory = <<<SQL
DROP PROCEDURE IF EXISTS `recursiveproductscategory`;

CREATE PROCEDURE `recursiveproductscategory`(IN `iRoot` int,  IN `iLevel` int, IN iLanguageID int, IN iPathString text, iURLstring text, IN iIDpath text, iSortCounter int)
BEGIN
	#Routine body goes here...
	#http://www.artfulsoftware.com/mysqlbook/sampler/mysqled1ch20.html

DECLARE irows,ichildid,iparentid,iLanguage_id, ichildcount,isortid, langdone,iCurLangID, iDetail_id, done, iLanguages INT DEFAULT 0;

DECLARE cPage VARCHAR(255);
DECLARE cUrl VARCHAR(255);
DECLARE iRegio VARCHAR(10);
DECLARE txtPath TEXT;
DECLARE txtUrl TEXT;
DECLARE idPath TEXT;

DECLARE txtDivision VARCHAR(255) ;
DECLARE txtSector VARCHAR(255);
DECLARE txtSectorCategory VARCHAR(255);

DECLARE urlDivision VARCHAR(255) ;
DECLARE urlSector VARCHAR(255);
DECLARE urlSectorCategory VARCHAR(255);



SET @@SESSION.max_sp_recursion_depth=25;
#rows : count the subcategories on this parentID only 1 layer deeper
  SET irows = ( SELECT COUNT(*) FROM products_categories WHERE parent_ID=iroot );

 # if the level is set to 0 - clear the _descendants so we can start from scratch (this is a recursive function so it will pass by for fetching the deeper layers
 
  IF iLevel = 0 THEN
	IF (iLanguageID= 1)THEN 
    DROP  TABLE IF EXISTS productscategorytree;
    CREATE TABLE productscategorytree ( id INT ,  detail_id int, parent_id INT, regio varchar(10) , language_id int,   sort_id int,  productcategory VARCHAR(255),  
childcount INT, 
level INT, 
url TEXT,  
path TEXT,  
idPath TEXT);
	END IF;
  END IF;
#end if iLevel = 0

#rows how many subcategories just below this root Page
IF irows > 0 THEN
    BEGIN

	DECLARE cur CURSOR FOR

        SELECT  	t.id,	
			products_categories_detail.id,
			t.parent_id, 
			f.sort_id,  
			concat(languages.language,'-',languages.country) as Regio,
			languages.id , 
			products_categories_detail.title as Page,
			products_categories_detail.url_slug as Path,

			(SELECT COUNT(*) FROM products_categories WHERE parent_ID=t.ID) AS childcount , 

	case when length(iPathString) > 0 THEN  case when (length((select title from products_categories_detail where product_category_id = f.id and language_id = languages.id limit 1))>0) then concat(iPathString , " | ", (select title from products_categories_detail where product_category_id = f.id and language_id = languages.id limit 1)) else '' end  ELSE (select title from products_categories_detail where product_category_id = f.id and language_id = languages.id limit 1) END as tPath, 

	case when length(iURLstring) > 0 THEN  case when (length((select url_slug from products_categories_detail where product_category_id = f.id and language_id = languages.id limit 1))>0) then concat(iURLstring, "/", (select url_slug from products_categories_detail where product_category_id = f.id and language_id = languages.id limit 1)) else '' end ELSE case  (select url_slug from products_categories_detail where product_category_id = f.id and language_id = languages.id limit 1) when 'INDEX' then 'INDEX' else  (select concat(languages.language,'-',languages.country,'/',url_slug) from products_categories_detail where product_category_id = f.id and language_id = languages.id limit 1)  end  END as tUrl,

	case when length(iIDpath)>0 then concat(iIDpath , ',',f.id)  else f.id end  as tIDpath

	FROM products_categories t JOIN products_categories f ON t.ID=f.ID
	inner join products_categories_detail on t.id= products_categories_detail.product_category_id
	inner join languages on products_categories_detail.language_id = languages.id
        WHERE t.parent_id=iroot
	and languages.id = iLanguageID
        ORDER BY  f.sort_id asc, f.updated_at desc, childcount desc; 
#sortid asc

      	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

      	OPEN cur;
      	
	select @counter := iSortcounter  ;

	WHILE NOT done DO

        	FETCH cur INTO 
				ichildid,
				iDetail_id,
				iparentid, 
				isortid, 
				iRegio,
				iLanguage_id,
				cPage, 
				cURL  ,
				ichildcount,
				txtPath, 
				txtUrl,  
				idPath;


       		IF NOT done THEN
					
			select @counter := @counter + 1 ;
			INSERT INTO productscategorytree VALUES(ichildid,iDetail_id,iparentid,iRegio,iLanguage_id,@counter , 
				cPage,
				ichildcount,
				ilevel, 
				txtUrl, 
				txtPath, 
				idPath 
			);

			update products_categories set sort_id = @counter where id = ichildid;

       			IF ichildcount > 0 THEN
       				CALL recursiveproductscategory( ichildid, ilevel + 1, iLanguageID,
						txtPath, 
						txtUrl, 
						idPath,
						@counter  
				);

       			END IF;
      		END IF;
      END WHILE;

      CLOSE cur;

    END;	
END IF;

END
SQL;
		DB::connection()->getPdo()->exec($sql_procedure_recursiveproductcategory);
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
	            Schema::drop('articles_detail_to_pages');
	            Schema::drop('countries');
	            Schema::drop('dealers');
	            Schema::drop('languages');
	            Schema::drop('newsletter');
	            Schema::drop('newsletters');
	            Schema::drop('newsletters_campaigns');
	            Schema::drop('newsletters_content');
	            Schema::drop('newsletters_default');
	            Schema::drop('newsletters_sentlog');
	            Schema::drop('newsletters_transactionmonitor');
	            Schema::drop('pages');
	            Schema::drop('pages_detail');
	            Schema::drop('pagetree');
	            Schema::drop('password_reminders');
	            Schema::drop('products');
	            Schema::drop('products_categories');
	            Schema::drop('products_categories_detail');
	            Schema::drop('products_information');
	            Schema::drop('products_labels');
	            Schema::drop('products_labels_detail');
	            Schema::drop('products_matters');
	            Schema::drop('products_matters_detail');
	            Schema::drop('products_price');
	            Schema::drop('products_to_products_information');
	            Schema::drop('products_to_products_labels');
	            Schema::drop('products_volumedata');
	            Schema::drop('productscategorytree');
	            Schema::drop('subscribers');
	            Schema::drop('subscribers_lists');
	            Schema::drop('tax_class');
	            Schema::drop('users');
	            Schema::drop('volumes_class');
	            Schema::drop('volumes_class_detail');
         }

}
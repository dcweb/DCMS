<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDatabaseNewsletter extends Migration {

        /**
         * Run the migrations.
         *
         * @return void
         */
         public function up()
         {
            
	   

			/**
			 * Table: newsletter
			 */
			Schema::create('newsletter', function($table) 
			{
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


			DB::connection()->getPdo()->exec($sql_procedure_recursiveproductcategory);
		}

        /**
         * Reverse the migrations.
         *
         * @return void
         */
         public function down()
         {
                Schema::drop('newsletter');
	     }

}
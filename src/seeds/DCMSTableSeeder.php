<?php

class DCMSTableSeeder extends Seeder {
    public function run()
    {
        
        DB::table('countries')->insert(array(
            
            array(
                'id' => 1,
                'country' => 'BE',
                'country_name' => 'België',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 2,
                'country' => 'NL',
                'country_name' => 'Nederland',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
        DB::table('languages')->insert(array(
            
            array(
                'id' => 1,
                'country_id' => 1,
                'language' => 'nl',
                'language_name' => 'Nederlands',
                'country' => 'BE',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 2,
                'country_id' => 1,
                'language' => 'fr',
                'language_name' => 'Frans',
                'country' => 'BE',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 3,
                'country_id' => 2,
                'language' => 'nl',
                'language_name' => 'Nederlands',
                'country' => 'NL',
                'created_at' => '2014-07-16 10:45:19',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
        DB::table('pages')->insert(array(
            
            array(
                'id' => 1,
                'parent_id' => NULL,
                'sort_id' => 0,
                'admin' => 'DB-migration',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            )

        ));
        DB::table('pages_detail')->insert(array(
            
            array(
                'id' => 1,
                'language_id' => 1,
                'page_id' => 1,
                'title' => '- ROOT -',
                'body' => 'root',
                'url_path' => 'root',
                'url_slug' => 'root',
                'admin' => 'DB-migration',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 2,
                'language_id' => 2,
                'page_id' => 1,
                'title' => '- ROOT -',
                'body' => 'root',
                'url_path' => 'root',
                'url_slug' => 'root',
                'admin' => 'DB-migration',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 3,
                'language_id' => 3,
                'page_id' => 1,
                'title' => '- ROOT -',
                'body' => 'root',
                'url_path' => 'root',
                'url_slug' => 'root',
                'admin' => 'DB-migration',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
        DB::table('products_categories')->insert(array(
            
            array(
                'id' => 1,
                'parent_id' => NULL,
                'sort_id' => NULL,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            )

        ));
        DB::table('products_categories_detail')->insert(array(
            
            array(
                'id' => 1,
                'product_category_id' => 1,
                'language_id' => 1,
                'title' => '- ROOT -',
                'url_slug' => 'root',
                'url_path' => 'root',
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 2,
                'product_category_id' => 1,
                'language_id' => 2,
                'title' => '- ROOT -',
                'url_slug' => 'root',
                'url_path' => 'root',
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 3,
                'product_category_id' => 1,
                'language_id' => 3,
                'title' => '- ROOT -',
                'url_slug' => 'root',
                'url_path' => 'root',
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
        DB::table('tax_class')->insert(array(
            
            array(
                'id' => 1,
                'tax_class' => '6 %',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 2,
                'tax_class' => '21 %',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
        DB::table('users')->insert(array(
            
            array(
                'id' => 4,
                'email' => 'admin@yourdomain.be',
                'name' => 'admin',
                'role' => 'administrator',
                'created_at' => '2014-10-08 16:02:33',
                'updated_at' => '2014-10-08 16:02:33',
                'username' => 'admin',
                'password' => '$2y$10$TImTiIu70t4G6ZvTNpCroeg0rYYSMTq8G4uPjFUrizFXueN8fv9XW',
                'remember_token' => NULL,
            ),

        ));
        DB::table('volumes_class')->insert(array(
            
            array(
                'id' => 1,
                'volume_class' => 'l',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 2,
                'volume_class' => 'kg',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 3,
                'volume_class' => 'g',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
        DB::table('volumes_class_detail')->insert(array(
            
            array(
                'id' => 1,
                'volume_id' => 1,
                'language_id' => 1,
                'volume_class' => 'l',
                'volume_class_long' => 'liter',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 2,
                'volume_id' => 1,
                'language_id' => 2,
                'volume_class' => 'l',
                'volume_class_long' => 'litre',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 3,
                'volume_id' => 2,
                'language_id' => 1,
                'volume_class' => 'kg',
                'volume_class_long' => 'kilogram',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 4,
                'volume_id' => 2,
                'language_id' => 2,
                'volume_class' => 'kg',
                'volume_class_long' => 'kilogramme',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 5,
                'volume_id' => 3,
                'language_id' => 1,
                'volume_class' => 'g',
                'volume_class_long' => 'gram',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 6,
                'volume_id' => 3,
                'language_id' => 2,
                'volume_class' => 'g',
                'volume_class_long' => 'gramme',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
    }
}
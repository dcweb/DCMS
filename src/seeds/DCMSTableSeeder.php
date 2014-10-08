<?php

class DCMSTableSeeder extends Seeder {
    public function run()
    {
        
        DB::table('articles')->insert(array(
            
            array(
                'id' => 2,
                'startdate' => NULL,
                'enddate' => '2014-07-31',
                'thumbnail' => 'http://test.groupdc.be/userfiles/images/articles/Cartoons-The-Simpsons-3-1800x2880-2-.jpg',
                'admin' => 'bartr',
                'created_at' => '2014-07-17 14:47:21',
                'updated_at' => '2014-08-12 11:36:26',
            ),

            array(
                'id' => 3,
                'startdate' => NULL,
                'enddate' => NULL,
                'thumbnail' => NULL,
                'admin' => 'bartr',
                'created_at' => '2014-09-22 09:19:11',
                'updated_at' => '2014-09-22 09:19:11',
            ),

            array(
                'id' => 4,
                'startdate' => NULL,
                'enddate' => NULL,
                'thumbnail' => NULL,
                'admin' => 'bartr',
                'created_at' => '2014-09-22 09:31:14',
                'updated_at' => '2014-09-22 09:31:14',
            ),

            array(
                'id' => 5,
                'startdate' => NULL,
                'enddate' => NULL,
                'thumbnail' => NULL,
                'admin' => 'bartr',
                'created_at' => '2014-09-22 09:32:49',
                'updated_at' => '2014-09-22 09:32:49',
            ),

        ));
        DB::table('articles_categories')->insert(array(
            
            array(
                'id' => 1,
                'admin' => NULL,
                'created_at' => '2014-07-03 07:22:49',
                'updated_at' => '2014-07-03 14:35:52',
            ),

            array(
                'id' => 6,
                'admin' => 'bartr',
                'created_at' => '2014-07-17 07:58:57',
                'updated_at' => '2014-07-17 07:58:57',
            ),

            array(
                'id' => 7,
                'admin' => 'bartr',
                'created_at' => '2014-07-17 14:38:53',
                'updated_at' => '2014-07-17 14:38:53',
            ),

            array(
                'id' => 8,
                'admin' => 'bartr',
                'created_at' => '2014-09-25 15:10:37',
                'updated_at' => '2014-09-25 15:10:37',
            ),

            array(
                'id' => 9,
                'admin' => 'bartr',
                'created_at' => '2014-10-01 09:04:03',
                'updated_at' => '2014-10-01 09:04:03',
            ),

            array(
                'id' => 10,
                'admin' => 'bartr',
                'created_at' => '2014-10-01 13:58:39',
                'updated_at' => '2014-10-01 13:58:39',
            ),

        ));
        DB::table('articles_categories_detail')->insert(array(
            
            array(
                'id' => 9,
                'article_category_id' => 1,
                'language_id' => 2,
                'title' => 'Blogue',
                'url_slug' => NULL,
                'url_path' => NULL,
                'admin' => 'bartr',
                'created_at' => '2014-07-15 09:05:21',
                'updated_at' => '2014-07-15 09:05:21',
            ),

            array(
                'id' => 11,
                'article_category_id' => 1,
                'language_id' => 1,
                'title' => 'Blogs',
                'url_slug' => NULL,
                'url_path' => NULL,
                'admin' => 'bartr',
                'created_at' => '2014-07-15 09:31:27',
                'updated_at' => '2014-07-17 07:57:59',
            ),

            array(
                'id' => 12,
                'article_category_id' => 1,
                'language_id' => 3,
                'title' => 'Blog',
                'url_slug' => NULL,
                'url_path' => NULL,
                'admin' => 'bartr',
                'created_at' => '2014-07-15 09:31:27',
                'updated_at' => '2014-07-15 09:31:27',
            ),

            array(
                'id' => 13,
                'article_category_id' => 9,
                'language_id' => 1,
                'title' => 'Niews',
                'url_slug' => 'niews',
                'url_path' => 'niews',
                'admin' => 'bartr',
                'created_at' => '2014-10-01 09:04:03',
                'updated_at' => '2014-10-01 09:04:03',
            ),

        ));
        DB::table('articles_detail')->insert(array(
            
            array(
                'id' => 3,
                'language_id' => 3,
                'article_category_id' => 1,
                'article_id' => 2,
                'title' => 'Article - NL',
                'description' => '<p>qsdfqsfze aera d</p>
',
                'body' => '<p>ssdf</p>
',
                'url_slug' => 'article-nl',
                'url_path' => 'article-nl',
                'admin' => 'bartr',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2014-08-11 12:43:00',
            ),

            array(
                'id' => 4,
                'language_id' => 1,
                'article_category_id' => NULL,
                'article_id' => 2,
                'title' => 'Article - BENL',
                'description' => '<p>jup description invullen</p>
',
                'body' => '<p>body invullend</p>
',
                'url_slug' => 'article-benl',
                'url_path' => 'article-benl',
                'admin' => 'bartr',
                'created_at' => '2014-08-11 12:42:41',
                'updated_at' => '2014-08-11 12:43:00',
            ),

            array(
                'id' => 8,
                'language_id' => 1,
                'article_category_id' => NULL,
                'article_id' => NULL,
                'title' => 'x',
                'description' => '<p>x</p>
',
                'body' => '<p>x</p>
',
                'url_slug' => 'x',
                'url_path' => 'x',
                'admin' => 'bartr',
                'created_at' => '2014-09-22 09:31:14',
                'updated_at' => '2014-09-22 09:31:14',
            ),

        ));
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
        DB::table('dealers')->insert(array(
            
            array(
                'id' => 2,
                'dealer' => 'Horticoop',
                'address' => 'Ravenswade 108',
                'zip' => '3439 LD',
                'city' => 'NIEUWEGEIN',
                'country_id' => 2,
                'phone' => NULL,
                'email' => NULL,
                'website' => 'http://',
                'longitude' => 5.1125520,
                'latitude' => 52.0518409,
                'admin' => 'bartr',
                'created_at' => '2014-07-15 13:55:32',
                'updated_at' => '2014-07-15 13:57:17',
            ),

            array(
                'id' => 8,
                'dealer' => 'Aveve goossens',
                'address' => 'Hoekske 2',
                'zip' => NULL,
                'city' => NULL,
                'country_id' => 1,
                'phone' => NULL,
                'email' => NULL,
                'website' => 'http://',
                'longitude' => 4.7986853,
                'latitude' => 51.0403078,
                'admin' => 'bartr',
                'created_at' => '2014-07-15 12:26:24',
                'updated_at' => '2014-07-16 12:00:10',
            ),

        ));
        DB::table('languages')->insert(array(
            
            array(
                'id' => 1,
                'language' => 'nl',
                'language_name' => 'Nederlands',
                'country' => 'BE',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 2,
                'language' => 'fr',
                'language_name' => 'Frans',
                'country' => 'BE',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 3,
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
                'parent_id' => 0,
                'sort_id' => 1,
                'admin' => 'bartr',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2014-09-22 13:58:27',
            ),

            array(
                'id' => 2,
                'parent_id' => 0,
                'sort_id' => 2,
                'admin' => 'bartr',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2014-09-26 11:57:12',
            ),

            array(
                'id' => 7,
                'parent_id' => 2,
                'sort_id' => 3,
                'admin' => 'bartr',
                'created_at' => '2014-09-23 10:56:28',
                'updated_at' => '2014-09-26 11:56:44',
            ),

            array(
                'id' => 8,
                'parent_id' => 2,
                'sort_id' => 4,
                'admin' => 'bartr',
                'created_at' => '2014-09-23 10:57:20',
                'updated_at' => '2014-09-26 11:56:36',
            ),

            array(
                'id' => 10,
                'parent_id' => 8,
                'sort_id' => 5,
                'admin' => 'bartr',
                'created_at' => '2014-09-23 14:23:41',
                'updated_at' => '2014-09-23 14:23:41',
            ),

            array(
                'id' => 11,
                'parent_id' => 0,
                'sort_id' => 6,
                'admin' => 'bartr',
                'created_at' => '2014-09-26 11:43:23',
                'updated_at' => '2014-09-26 11:56:27',
            ),

            array(
                'id' => 12,
                'parent_id' => NULL,
                'sort_id' => 1,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
        DB::table('pages_detail')->insert(array(
            
            array(
                'id' => 1,
                'language_id' => 1,
                'page_id' => 1,
                'title' => 'ContactBENL',
                'body' => '<p>Hier komt de contact</p>
',
                'url_path' => 'contactbenl',
                'url_slug' => 'contactbenl',
                'admin' => 'bartr',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2014-09-25 10:51:15',
            ),

            array(
                'id' => 2,
                'language_id' => 1,
                'page_id' => 2,
                'title' => 'Onze Visie',
                'body' => '<p>Producten die bij de beroepsmensen hun deugdelijkheid bewijzen komen op die wijze ter beschikking van het brede publiek. Aan hen biedt Edialux milieuverantwoorde oplossingen voor vele netelige huis- en tuinproblemen. Uitsluitend duurzame bestrijdingsmiddelen, die geen of hooguit een verantwoorde belasting voor het milieu betekenen worden weerhouden. Zorg voor de leefomgeving staat immers hoog in het vaandel en is de drijfveer voor het doorgedreven onderzoek, dat aan de basis ligt van elk product. Een gepaste slogan die Edialux typeert is dan ook &ldquo;Doelgericht bestrijden&rdquo;.</p>

<p>Bovenop chemische bestrijdingsmiddelen biedt Edialux eveneens tal van plantaardige en biologische producten aan. Het assortiment omvat het meest complete gamma voor de tuinliefhebber: onkruid-, ziekten- en insectenbestrijders, en de nieuwste generatie ratten- en muizenverdelgingsmiddelen. Kortom, voor elk probleem in &ldquo;home&rdquo;, &ldquo;garden&rdquo; en &ldquo;pet&rdquo; heeft Edialux een antwoord &eacute;n een product in huis. Niet alleen voor de bestrijdingsmiddelen maar ook voor de noodzakelijke apparatuur kunt u natuurlijk bij Edialux terecht. Zo haalt men met de spuitapparaten van BIRCHMEIER de absolute top in huis!</p>

<p>Ons compleet assortiment, onze technische ondersteuning en een breed verdelersnet zijn onze troeven. Edialux is niet alleen een graag geziene partner van de professionele groensector, maar via de gespecialiseerde tuincentra ook van de particuliere tuinliefhebber.</p>
',
                'url_path' => 'onze-visie',
                'url_slug' => 'onze-visie',
                'admin' => 'bartr',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2014-09-23 08:19:28',
            ),

            array(
                'id' => 9,
                'language_id' => 2,
                'page_id' => 1,
                'title' => 'ContactBEFR',
                'body' => NULL,
                'url_path' => 'contactbefr',
                'url_slug' => 'contactbefr',
                'admin' => 'bartr',
                'created_at' => '2014-09-23 10:09:52',
                'updated_at' => '2014-09-25 10:51:15',
            ),

            array(
                'id' => 13,
                'language_id' => 1,
                'page_id' => 7,
                'title' => 'Ecologisch',
                'body' => '<p>- wij proberen ecologisch te zijn</p>
',
                'url_path' => 'ecologisch',
                'url_slug' => 'ecologisch',
                'admin' => 'bartr',
                'created_at' => '2014-09-23 10:56:28',
                'updated_at' => '2014-09-23 10:56:28',
            ),

            array(
                'id' => 14,
                'language_id' => 2,
                'page_id' => 7,
                'title' => 'ecofr',
                'body' => '<p>oops, andere root</p>
',
                'url_path' => 'ecofr',
                'url_slug' => 'ecofr',
                'admin' => 'bartr',
                'created_at' => '2014-09-23 10:56:50',
                'updated_at' => '2014-09-25 09:05:32',
            ),

            array(
                'id' => 15,
                'language_id' => 1,
                'page_id' => 8,
                'title' => 'c2c',
                'body' => '<p>cradle to cradle</p>
',
                'url_path' => 'c2c',
                'url_slug' => 'c2c',
                'admin' => 'bartr',
                'created_at' => '2014-09-23 10:57:20',
                'updated_at' => '2014-09-23 10:57:20',
            ),

            array(
                'id' => 16,
                'language_id' => 1,
                'page_id' => 0,
                'title' => '- ROOT - ',
                'body' => NULL,
                'url_path' => 'nl',
                'url_slug' => 'nl',
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 17,
                'language_id' => 1,
                'page_id' => 10,
                'title' => 'aba',
                'body' => '<p>gyproc is c2c en heeft aba zijdes</p>
',
                'url_path' => 'aba',
                'url_slug' => 'aba',
                'admin' => 'bartr',
                'created_at' => '2014-09-23 14:23:41',
                'updated_at' => '2014-09-23 14:23:41',
            ),

            array(
                'id' => 18,
                'language_id' => 2,
                'page_id' => 2,
                'title' => 'Notre vision',
                'body' => '<p>L&agrave; o&ugrave; Edialux se sp&eacute;cialise dans les produits pour le march&eacute; des professionnels, Formulex d&eacute;veloppe la gamme pour les utilisateurs particuliers. Formulex met &agrave; la disposition des consommateurs des produits qui font la renomm&eacute;e des professionnels.</p>

<p>Quel utilisateur professionnel ou particulier ne conna&icirc;t pas le rodenticide Sorkil, l&#39;insecticide pour fourmis et gu&ecirc;pes Permas-D, le spray anti-mouches Zerox et sa variante One Shot pour les puces, l&#39;herbicide total Herbonex, la gamme Pinto et Zerox-P contre les ectoparasites?</p>

<p>Cette volont&eacute; d&#39;offrir &agrave; tout prix des produits de qualit&eacute; est en grande partie la raison du succ&egrave;s de la gamme maison et jardin de Formulex.</p>

<p>Gr&acirc;ce &agrave; notre engagement et notre vision, ainsi qu&#39;avec l&#39;aide et le soutien indispensable de nombre de personnes, la gamme est aujourd&#39;hui riche de plus de 100 produits.</p>

<p>Cette vaste gamme comprend les c&eacute;l&egrave;bres Dinet, Dursban, D&eacute;p&ocirc;ts Verts, Moscide, Pyretrex Special, Rosabel, Scomrid, Aeroxon, pulv&eacute;risateurs Birchmeier et beaucoup d&#39;autres sp&eacute;cialit&eacute;s. Tous offrent pour le n&eacute;gociant et l&#39;amateur de jardin une solution id&eacute;ale et performante.</p>
',
                'url_path' => 'notre-vision',
                'url_slug' => 'notre-vision',
                'admin' => 'bartr',
                'created_at' => '2014-09-25 09:22:36',
                'updated_at' => '2014-09-25 09:22:36',
            ),

            array(
                'id' => 30,
                'language_id' => 3,
                'page_id' => 1,
                'title' => 'Contact NLNL',
                'body' => '<p>hier kan je contact opnemen met ons.</p>

<p>mvg<br />
&nbsp;</p>
',
                'url_path' => 'contact-nlnl',
                'url_slug' => 'contact-nlnl',
                'admin' => 'bartr',
                'created_at' => '2014-09-25 11:53:23',
                'updated_at' => '2014-09-25 11:53:57',
            ),

            array(
                'id' => 31,
                'language_id' => 3,
                'page_id' => 8,
                'title' => 'testje niet beschikbaar',
                'body' => '<p>deze pagina kan niet beschikbaar zijn omdat er geen root in gegeven is&nbsp;</p>
',
                'url_path' => 'testje-niet-beschikbaar',
                'url_slug' => 'testje-niet-beschikbaar',
                'admin' => 'bartr',
                'created_at' => '2014-09-25 11:58:28',
                'updated_at' => '2014-09-25 11:58:28',
            ),

            array(
                'id' => 32,
                'language_id' => 1,
                'page_id' => 11,
                'title' => 'Vacatures',
                'body' => '<p>hier staan vacatures</p>
',
                'url_path' => 'vacatures',
                'url_slug' => 'vacatures',
                'admin' => 'bartr',
                'created_at' => '2014-09-26 11:43:23',
                'updated_at' => '2014-09-26 11:43:23',
            ),

        ));
        DB::table('pagetree')->insert(array(
            
            array(
                'id' => 1,
                'detail_id' => 1,
                'parent_id' => 0,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 1,
                'page' => 'ContactBENL',
                'childcount' => 0,
                'level' => 0,
                'url' => 'nl-BE/contactbenl',
                'path' => 'ContactBENL',
                'idPath' => 1,
            ),

            array(
                'id' => 2,
                'detail_id' => 2,
                'parent_id' => 0,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 2,
                'page' => 'Onze Visie',
                'childcount' => 2,
                'level' => 0,
                'url' => 'nl-BE/onze-visie',
                'path' => 'Onze Visie',
                'idPath' => 2,
            ),

            array(
                'id' => 1,
                'detail_id' => 9,
                'parent_id' => 0,
                'regio' => 'fr-BE',
                'language_id' => 2,
                'sort_id' => 1,
                'page' => 'ContactBEFR',
                'childcount' => 0,
                'level' => 0,
                'url' => 'fr-BE/contactbefr',
                'path' => 'ContactBEFR',
                'idPath' => 1,
            ),

            array(
                'id' => 7,
                'detail_id' => 13,
                'parent_id' => 2,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 3,
                'page' => 'Ecologisch',
                'childcount' => 0,
                'level' => 1,
                'url' => 'nl-BE/onze-visie/ecologisch',
                'path' => 'Onze Visie | Ecologisch',
                'idPath' => '2,7',
            ),

            array(
                'id' => 7,
                'detail_id' => 14,
                'parent_id' => 2,
                'regio' => 'fr-BE',
                'language_id' => 2,
                'sort_id' => 3,
                'page' => 'ecofr',
                'childcount' => 0,
                'level' => 1,
                'url' => 'fr-BE/notre-vision/ecofr',
                'path' => 'Notre vision | ecofr',
                'idPath' => '2,7',
            ),

            array(
                'id' => 8,
                'detail_id' => 15,
                'parent_id' => 2,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 4,
                'page' => 'c2c',
                'childcount' => 1,
                'level' => 1,
                'url' => 'nl-BE/onze-visie/c2c',
                'path' => 'Onze Visie | c2c',
                'idPath' => '2,8',
            ),

            array(
                'id' => 10,
                'detail_id' => 17,
                'parent_id' => 8,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 5,
                'page' => 'aba',
                'childcount' => 0,
                'level' => 2,
                'url' => 'nl-BE/onze-visie/c2c/aba',
                'path' => 'Onze Visie | c2c | aba',
                'idPath' => '2,8,10',
            ),

            array(
                'id' => 2,
                'detail_id' => 18,
                'parent_id' => 0,
                'regio' => 'fr-BE',
                'language_id' => 2,
                'sort_id' => 2,
                'page' => 'Notre vision',
                'childcount' => 2,
                'level' => 0,
                'url' => 'fr-BE/notre-vision',
                'path' => 'Notre vision',
                'idPath' => 2,
            ),

            array(
                'id' => 1,
                'detail_id' => 30,
                'parent_id' => 0,
                'regio' => 'nl-NL',
                'language_id' => 3,
                'sort_id' => 1,
                'page' => 'Contact NLNL',
                'childcount' => 0,
                'level' => 0,
                'url' => 'nl-NL/contact-nlnl',
                'path' => 'Contact NLNL',
                'idPath' => 1,
            ),

            array(
                'id' => 11,
                'detail_id' => 32,
                'parent_id' => 0,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 6,
                'page' => 'Vacatures',
                'childcount' => 0,
                'level' => 0,
                'url' => 'nl-BE/vacatures',
                'path' => 'Vacatures',
                'idPath' => 11,
            ),

        ));
        DB::table('password_reminders')->insert(array(
            
            array(
                'email' => 'bre@groupdc.be',
                'token' => '431fcbfc7c379abb30471922033ed7b7ad33c98a',
                'created_at' => '2014-06-13 11:58:57',
            ),

            array(
                'email' => 'bre@groupdc.be',
                'token' => 'fb075c6e568ed27d9f436bc9afe0bc6b82c2e77a',
                'created_at' => '2014-06-13 11:59:10',
            ),

            array(
                'email' => 'bre@groupdc.be',
                'token' => 'e92c61aa36b759016f97911f881636a92b626b82',
                'created_at' => '2014-06-13 11:59:37',
            ),

        ));
        DB::table('products')->insert(array(
            
            array(
                'id' => 1,
                'code' => 'p1',
                'eancode' => 'p1',
                'image' => 'http://test.groupdc.be/userfiles/images/articles/Hydrangeas.jpg',
                'volume' => 5,
                'volume_unit_class' => 3,
                'admin' => 'jand',
                'created_at' => '2014-07-18 11:07:57',
                'updated_at' => '2014-09-30 14:27:22',
            ),

            array(
                'id' => 18,
                'code' => 'p2',
                'eancode' => 'p2',
                'image' => NULL,
                'volume' => NULL,
                'volume_unit_class' => 1,
                'admin' => 'bartr',
                'created_at' => '2014-08-08 15:25:43',
                'updated_at' => '2014-08-11 12:57:35',
            ),

            array(
                'id' => 22,
                'code' => 'p3',
                'eancode' => 3165,
                'image' => NULL,
                'volume' => NULL,
                'volume_unit_class' => 1,
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:18:50',
                'updated_at' => '2014-08-12 08:18:50',
            ),

            array(
                'id' => 23,
                'code' => 'p4',
                'eancode' => 987321,
                'image' => NULL,
                'volume' => NULL,
                'volume_unit_class' => 1,
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:27:15',
                'updated_at' => '2014-08-12 08:27:15',
            ),

            array(
                'id' => 24,
                'code' => 'p5',
                'eancode' => 58246,
                'image' => NULL,
                'volume' => NULL,
                'volume_unit_class' => 1,
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:28:43',
                'updated_at' => '2014-08-12 08:28:43',
            ),

            array(
                'id' => 25,
                'code' => 'p6',
                'eancode' => 13258214,
                'image' => NULL,
                'volume' => NULL,
                'volume_unit_class' => 1,
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:31:21',
                'updated_at' => '2014-08-12 08:31:21',
            ),

            array(
                'id' => 26,
                'code' => 'p7',
                'eancode' => 77777,
                'image' => NULL,
                'volume' => NULL,
                'volume_unit_class' => 1,
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:32:47',
                'updated_at' => '2014-08-12 08:32:47',
            ),

        ));
        DB::table('products_categories')->insert(array(
            
            array(
                'id' => 1,
                'parent_id' => 0,
                'sort_id' => 2,
                'admin' => NULL,
                'created_at' => '2014-07-03 07:22:49',
                'updated_at' => '2014-07-03 14:35:52',
            ),

            array(
                'id' => 3,
                'parent_id' => 0,
                'sort_id' => 1,
                'admin' => NULL,
                'created_at' => '2014-07-03 07:23:05',
                'updated_at' => '2014-07-03 07:23:05',
            ),

            array(
                'id' => 4,
                'parent_id' => 0,
                'sort_id' => 1,
                'admin' => NULL,
                'created_at' => '2014-07-03 07:50:15',
                'updated_at' => '2014-07-03 07:50:15',
            ),

            array(
                'id' => 21,
                'parent_id' => 0,
                'sort_id' => NULL,
                'admin' => 'bartr',
                'created_at' => '2014-07-15 09:35:10',
                'updated_at' => '2014-07-15 09:35:10',
            ),

            array(
                'id' => 24,
                'parent_id' => 4,
                'sort_id' => 3,
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:30:58',
                'updated_at' => '2014-09-26 13:30:58',
            ),

            array(
                'id' => 25,
                'parent_id' => 4,
                'sort_id' => 2,
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:36:03',
                'updated_at' => '2014-09-26 13:36:03',
            ),

            array(
                'id' => 26,
                'parent_id' => 3,
                'sort_id' => 5,
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:38:23',
                'updated_at' => '2014-09-26 13:38:23',
            ),

            array(
                'id' => 27,
                'parent_id' => 26,
                'sort_id' => 6,
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:38:35',
                'updated_at' => '2014-09-26 13:38:35',
            ),

            array(
                'id' => 28,
                'parent_id' => 27,
                'sort_id' => 7,
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:38:44',
                'updated_at' => '2014-09-26 13:38:44',
            ),

            array(
                'id' => 29,
                'parent_id' => NULL,
                'sort_id' => NULL,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));
        DB::table('products_categories_detail')->insert(array(
            
            array(
                'id' => 3,
                'product_category_id' => 4,
                'language_id' => 1,
                'title' => 'Insecten',
                'url_slug' => 'insecten',
                'url_path' => 'insecten',
                'admin' => 'bartr',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2014-09-26 13:51:12',
            ),

            array(
                'id' => 4,
                'product_category_id' => 4,
                'language_id' => 2,
                'title' => 'Insectes',
                'url_slug' => 'insectes',
                'url_path' => 'insectes',
                'admin' => 'bartr',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2014-09-26 13:51:12',
            ),

            array(
                'id' => 5,
                'product_category_id' => 3,
                'language_id' => 1,
                'title' => 'Schimmelziekten',
                'url_slug' => 'schimmelziekten',
                'url_path' => NULL,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 6,
                'product_category_id' => 3,
                'language_id' => 2,
                'title' => 'Maladies de moisissure',
                'url_slug' => 'maladies-de-moisisurre',
                'url_path' => NULL,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 24,
                'product_category_id' => 1,
                'language_id' => 1,
                'title' => 'Onkruid',
                'url_slug' => 'onkruid',
                'url_path' => 'onkruid',
                'admin' => 'bartr',
                'created_at' => '2014-07-03 13:34:47',
                'updated_at' => '2014-09-26 11:09:29',
            ),

            array(
                'id' => 28,
                'product_category_id' => 3,
                'language_id' => 3,
                'title' => 'Schimmelziekten',
                'url_slug' => 'schimmel',
                'url_path' => NULL,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2014-07-11 14:00:43',
            ),

            array(
                'id' => 66,
                'product_category_id' => 1,
                'language_id' => 3,
                'title' => 'Onkruiden',
                'url_slug' => 'onkruiden',
                'url_path' => 'onkruiden',
                'admin' => 'bartr',
                'created_at' => '2014-07-15 08:50:02',
                'updated_at' => '2014-09-26 11:09:29',
            ),

            array(
                'id' => 68,
                'product_category_id' => 1,
                'language_id' => 2,
                'title' => 'Mauvaise herbes',
                'url_slug' => 'mauvaise-herbes',
                'url_path' => 'mauvaise-herbes',
                'admin' => 'bartr',
                'created_at' => '2014-07-15 09:35:04',
                'updated_at' => '2014-09-26 11:09:29',
            ),

            array(
                'id' => 69,
                'product_category_id' => 0,
                'language_id' => 1,
                'title' => '- ROOT -',
                'url_slug' => '-root-',
                'url_path' => NULL,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 70,
                'product_category_id' => 0,
                'language_id' => 2,
                'title' => '- ROOT -',
                'url_slug' => '-root-',
                'url_path' => NULL,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 71,
                'product_category_id' => 0,
                'language_id' => 3,
                'title' => '- ROOT -',
                'url_slug' => '-root-',
                'url_path' => NULL,
                'admin' => NULL,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

            array(
                'id' => 73,
                'product_category_id' => 24,
                'language_id' => 1,
                'title' => 'op dieren ',
                'url_slug' => 'op-dieren-',
                'url_path' => 'op-dieren-',
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:30:58',
                'updated_at' => '2014-09-26 13:30:58',
            ),

            array(
                'id' => 74,
                'product_category_id' => 25,
                'language_id' => 1,
                'title' => 'op planten',
                'url_slug' => 'op-planten',
                'url_path' => 'op-planten',
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:36:03',
                'updated_at' => '2014-09-26 13:36:03',
            ),

            array(
                'id' => 78,
                'product_category_id' => 25,
                'language_id' => 2,
                'title' => 'plantes',
                'url_slug' => 'plantes',
                'url_path' => 'plantes',
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:48:06',
                'updated_at' => '2014-09-26 13:48:06',
            ),

            array(
                'id' => 79,
                'product_category_id' => 24,
                'language_id' => 2,
                'title' => 'animeaux',
                'url_slug' => 'animeaux',
                'url_path' => 'animeaux',
                'admin' => 'bartr',
                'created_at' => '2014-09-26 13:49:54',
                'updated_at' => '2014-09-26 13:49:54',
            ),

        ));
        DB::table('products_information')->insert(array(
            
            array(
                'id' => 3,
                'language_id' => 3,
                'product_category_id' => 3,
                'sort_id' => 1,
                'title' => 'Anti scihmmel NL',
                'description' => '<p>rrrrrrr</p>
',
                'url_slug' => 'anti-scihmmel-nl',
                'url_path' => 'anti-scihmmel-nl',
                'admin' => 'bartr',
                'created_at' => '2014-07-18 11:09:02',
                'updated_at' => '2014-08-12 11:11:29',
            ),

            array(
                'id' => 13,
                'language_id' => 1,
                'product_category_id' => NULL,
                'sort_id' => 3,
                'title' => 'Anti schimmel 3',
                'description' => NULL,
                'url_slug' => 'anti-schimmel-3',
                'url_path' => 'anti-schimmel-3',
                'admin' => 'jand',
                'created_at' => '2014-08-08 14:38:29',
                'updated_at' => '2014-09-30 14:27:22',
            ),

            array(
                'id' => 16,
                'language_id' => 1,
                'product_category_id' => NULL,
                'sort_id' => 5,
                'title' => 'asdf',
                'description' => NULL,
                'url_slug' => 'asdf',
                'url_path' => 'asdf',
                'admin' => 'bartr',
                'created_at' => '2014-08-08 15:25:43',
                'updated_at' => '2014-08-12 11:12:06',
            ),

            array(
                'id' => 18,
                'language_id' => 1,
                'product_category_id' => NULL,
                'sort_id' => 4,
                'title' => 'p3 s4',
                'description' => '<p>qdfqdf</p>
',
                'url_slug' => 'p3-s4',
                'url_path' => 'p3-s4',
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:18:50',
                'updated_at' => '2014-08-12 11:12:06',
            ),

            array(
                'id' => 19,
                'language_id' => 1,
                'product_category_id' => NULL,
                'sort_id' => 7,
                'title' => 'xxx',
                'description' => '<p>xxx</p>
',
                'url_slug' => 'xxx',
                'url_path' => 'xxx',
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:27:15',
                'updated_at' => '2014-08-12 11:12:06',
            ),

            array(
                'id' => 20,
                'language_id' => 1,
                'product_category_id' => NULL,
                'sort_id' => 2,
                'title' => 'xxdqdf',
                'description' => '<p>qdfae</p>
',
                'url_slug' => 'xxdqdf',
                'url_path' => 'xxdqdf',
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:28:43',
                'updated_at' => '2014-08-12 10:38:29',
            ),

            array(
                'id' => 21,
                'language_id' => 1,
                'product_category_id' => NULL,
                'sort_id' => 1,
                'title' => 'p6 sort 3',
                'description' => '<p>hihi ik zet u op 3</p>
',
                'url_slug' => 'p6-sort-3',
                'url_path' => 'p6-sort-3',
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:31:21',
                'updated_at' => '2014-08-12 10:37:35',
            ),

            array(
                'id' => 22,
                'language_id' => 1,
                'product_category_id' => NULL,
                'sort_id' => 6,
                'title' => 'be s5',
                'description' => '<p>test</p>
',
                'url_slug' => 'be-s5',
                'url_path' => 'be-s5',
                'admin' => 'bartr',
                'created_at' => '2014-08-12 08:32:47',
                'updated_at' => '2014-08-12 11:12:06',
            ),

            array(
                'id' => 43,
                'language_id' => 3,
                'product_category_id' => NULL,
                'sort_id' => 2,
                'title' => 'NL 2',
                'description' => NULL,
                'url_slug' => 'nl-2',
                'url_path' => 'nl-2',
                'admin' => 'jand',
                'created_at' => '2014-08-12 11:07:59',
                'updated_at' => '2014-09-30 14:27:22',
            ),

            array(
                'id' => 44,
                'language_id' => 2,
                'product_category_id' => NULL,
                'sort_id' => 2,
                'title' => 'p3 s2',
                'description' => NULL,
                'url_slug' => 'p3-s2',
                'url_path' => 'p3-s2',
                'admin' => 'bartr',
                'created_at' => '2014-08-12 11:12:06',
                'updated_at' => '2014-08-12 11:12:06',
            ),

            array(
                'id' => 45,
                'language_id' => 3,
                'product_category_id' => NULL,
                'sort_id' => 3,
                'title' => 'p3 s3',
                'description' => NULL,
                'url_slug' => 'p3-s3',
                'url_path' => 'p3-s3',
                'admin' => 'bartr',
                'created_at' => '2014-08-12 11:12:06',
                'updated_at' => '2014-08-12 11:12:06',
            ),

        ));
        DB::table('products_price')->insert(array(
            
            array(
                'id' => 9,
                'country_id' => 2,
                'product_id' => 1,
                'price' => 21.00,
                'valuta_class_id' => 1,
                'tax_class_id' => 2,
                'admin' => 'jand',
                'created_at' => '2014-09-25 15:28:18',
                'updated_at' => '2014-09-30 14:27:22',
            ),

            array(
                'id' => 10,
                'country_id' => 1,
                'product_id' => 1,
                'price' => 6.00,
                'valuta_class_id' => 1,
                'tax_class_id' => 1,
                'admin' => 'jand',
                'created_at' => '2014-09-25 15:28:18',
                'updated_at' => '2014-09-30 14:27:22',
            ),

            array(
                'id' => 11,
                'country_id' => 2,
                'product_id' => 18,
                'price' => 99.00,
                'valuta_class_id' => 1,
                'tax_class_id' => 2,
                'admin' => 'bartr',
                'created_at' => '2014-09-25 15:30:27',
                'updated_at' => '2014-09-25 15:30:27',
            ),

        ));
        DB::table('products_to_products_information')->insert(array(
            
            array(
                'product_id' => 1,
                'product_information_id' => 13,
            ),

            array(
                'product_id' => 1,
                'product_information_id' => 43,
            ),

            array(
                'product_id' => 18,
                'product_information_id' => 16,
            ),

            array(
                'product_id' => 22,
                'product_information_id' => 18,
            ),

            array(
                'product_id' => 22,
                'product_information_id' => 44,
            ),

            array(
                'product_id' => 22,
                'product_information_id' => 45,
            ),

            array(
                'product_id' => 23,
                'product_information_id' => 19,
            ),

            array(
                'product_id' => 24,
                'product_information_id' => 20,
            ),

            array(
                'product_id' => 25,
                'product_information_id' => 21,
            ),

            array(
                'product_id' => 26,
                'product_information_id' => 22,
            ),

        ));
        DB::table('productscategorytree')->insert(array(
            
            array(
                'id' => 4,
                'detail_id' => 3,
                'parent_id' => 0,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 1,
                'productcategory' => 'Insecten',
                'childcount' => 2,
                'level' => 0,
                'url' => 'nl-BE/insecten',
                'path' => 'Insecten',
                'idPath' => 4,
            ),

            array(
                'id' => 25,
                'detail_id' => 74,
                'parent_id' => 4,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 2,
                'productcategory' => 'op planten',
                'childcount' => 0,
                'level' => 1,
                'url' => 'nl-BE/insecten/op-planten',
                'path' => 'Insecten | op planten',
                'idPath' => '4,25',
            ),

            array(
                'id' => 24,
                'detail_id' => 73,
                'parent_id' => 4,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 3,
                'productcategory' => 'op dieren ',
                'childcount' => 0,
                'level' => 1,
                'url' => 'nl-BE/insecten/op-dieren-',
                'path' => 'Insecten | op dieren ',
                'idPath' => '4,24',
            ),

            array(
                'id' => 3,
                'detail_id' => 5,
                'parent_id' => 0,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 4,
                'productcategory' => 'Schimmelziekten',
                'childcount' => 1,
                'level' => 0,
                'url' => 'nl-BE/schimmelziekten',
                'path' => 'Schimmelziekten',
                'idPath' => 3,
            ),

            array(
                'id' => 1,
                'detail_id' => 24,
                'parent_id' => 0,
                'regio' => 'nl-BE',
                'language_id' => 1,
                'sort_id' => 5,
                'productcategory' => 'Onkruid',
                'childcount' => 0,
                'level' => 0,
                'url' => 'nl-BE/onkruid',
                'path' => 'Onkruid',
                'idPath' => 1,
            ),

            array(
                'id' => 4,
                'detail_id' => 4,
                'parent_id' => 0,
                'regio' => 'fr-BE',
                'language_id' => 2,
                'sort_id' => 1,
                'productcategory' => 'Insectes',
                'childcount' => 2,
                'level' => 0,
                'url' => 'fr-BE/insectes',
                'path' => 'Insectes',
                'idPath' => 4,
            ),

            array(
                'id' => 25,
                'detail_id' => 78,
                'parent_id' => 4,
                'regio' => 'fr-BE',
                'language_id' => 2,
                'sort_id' => 2,
                'productcategory' => 'plantes',
                'childcount' => 0,
                'level' => 1,
                'url' => 'fr-BE/insectes/plantes',
                'path' => 'Insectes | plantes',
                'idPath' => '4,25',
            ),

            array(
                'id' => 24,
                'detail_id' => 79,
                'parent_id' => 4,
                'regio' => 'fr-BE',
                'language_id' => 2,
                'sort_id' => 3,
                'productcategory' => 'animeaux',
                'childcount' => 0,
                'level' => 1,
                'url' => 'fr-BE/insectes/animeaux',
                'path' => 'Insectes | animeaux',
                'idPath' => '4,24',
            ),

            array(
                'id' => 3,
                'detail_id' => 6,
                'parent_id' => 0,
                'regio' => 'fr-BE',
                'language_id' => 2,
                'sort_id' => 4,
                'productcategory' => 'Maladies de moisissure',
                'childcount' => 1,
                'level' => 0,
                'url' => 'fr-BE/maladies-de-moisisurre',
                'path' => 'Maladies de moisissure',
                'idPath' => 3,
            ),

            array(
                'id' => 1,
                'detail_id' => 68,
                'parent_id' => 0,
                'regio' => 'fr-BE',
                'language_id' => 2,
                'sort_id' => 5,
                'productcategory' => 'Mauvaise herbes',
                'childcount' => 0,
                'level' => 0,
                'url' => 'fr-BE/mauvaise-herbes',
                'path' => 'Mauvaise herbes',
                'idPath' => 1,
            ),

            array(
                'id' => 3,
                'detail_id' => 28,
                'parent_id' => 0,
                'regio' => 'nl-NL',
                'language_id' => 3,
                'sort_id' => 1,
                'productcategory' => 'Schimmelziekten',
                'childcount' => 1,
                'level' => 0,
                'url' => 'nl-NL/schimmel',
                'path' => 'Schimmelziekten',
                'idPath' => 3,
            ),

            array(
                'id' => 1,
                'detail_id' => 66,
                'parent_id' => 0,
                'regio' => 'nl-NL',
                'language_id' => 3,
                'sort_id' => 2,
                'productcategory' => 'Onkruiden',
                'childcount' => 0,
                'level' => 0,
                'url' => 'nl-NL/onkruiden',
                'path' => 'Onkruiden',
                'idPath' => 1,
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
                'id' => 1,
                'email' => 'admin@yourdomain.be',
                'name' => 'admin',
                'role' => 'administrator',
                'created_at' => '2014-05-28 08:52:37',
                'updated_at' => '201	4-10-03 07:56:06',
                'username' => 'admin',
                'password' => '$2y$10$TImTiIu70t4G6ZvTNpCroeg0rYYSMTq8G4uPjFUrizFXueN8fv9XW',
                'remember_token' => null,
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
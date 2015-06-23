<?php

namespace Dcweb\Dcms\Models\Articles;

use Dcweb\Dcms\Models\EloquentDefaults;

	class Category extends EloquentDefaults
	{
		protected $connection = 'project';
		
	  protected $table  = "articles_categories_language";
		
    protected $fillable = array('language_id', 'title');
	}
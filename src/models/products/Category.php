<?php

namespace Dcweb\Dcms\Models\Products;
use Dcweb\Dcms\Models\EloquentDefaults;

	class Category extends EloquentDefaults
	{
		protected $connection = 'project';
		
	  protected $table  = "products_categories_language";
		
    protected $fillable = array('language_id', 'title');
	}
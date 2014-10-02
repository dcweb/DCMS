<?php

namespace Dcweb\Dcms\Models\Products;
use Eloquent;

	class Category extends Eloquent
	{
		protected $connection = 'project';
		
	  protected $table  = "products_categories_detail";
		
    protected $fillable = array('language_id', 'title');
	}
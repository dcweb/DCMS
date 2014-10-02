<?php

namespace Dcweb\Dcms\Models\Articles;
use Eloquent;

	class Category extends Eloquent
	{
		protected $connection = 'project';
		
	  protected $table  = "articles_categories_detail";
		
    protected $fillable = array('language_id', 'title');
	}
<?php

namespace Dcweb\Dcms\Models\Articles;

use Dcweb\Dcms\Models\EloquentDefaults;

	class Article extends EloquentDefaults
	{
		protected $connection = 'project';
		
		public function detail()
		{
			  return $this->hasMany('\Dcweb\Dcms\Models\Articles\Detail','article_id','id');
		}
		
		public function category()
		{
			return $this->belongsTo("Dcweb\Dcms\Models\Articles\Category", 'article_category_id', 'id');
			//return $this->hasOne("Category");
		}
	}
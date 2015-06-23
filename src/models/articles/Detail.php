<?php

namespace Dcweb\Dcms\Models\Articles;

use Dcweb\Dcms\Models\EloquentDefaults;

	class Detail extends EloquentDefaults
	{
		protected $connection = 'project';
		
		protected $table  = "articles_language";
		
    protected $fillable = array('language_id', 'article_id', 'title', 'text', 'description');
		
		public function article()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Articles\Article','article_id','id');
		}
				
		public function articlecategory()
		{
				return $this->belongsTo('\Dcweb\Dcms\Models\Articles\Category', 'article_category_id', 'id');
		}
		
		public function pages()
		{
			// BelongsToMany belongsToMany(string $related, string $table = null, string $foreignKey = null, string $otherKey = null, string $relation = null)
			return $this->belongsToMany('\Dcweb\Dcms\Models\Pages\Page', 'articles_language_to_pages', 'article_detail_id', 'page_id');
		}
		
	}
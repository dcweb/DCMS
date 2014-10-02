<?php

namespace Dcweb\Dcms\Models\Dealers;
use Eloquent;

	class Dealer extends Eloquent
	{
		protected $connection = 'project';
		
		protected $table  = "dealers";
		
//    protected $fillable = array('language_id', 'article_id', 'title', 'text', 'description');
/*		
		public function article()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Articles\Article','article_id','id');
		}
				
		public function articlecategory()
		{
				return $this->belongsTo('\Dcweb\Dcms\Models\Articles\Category', 'article_category_id', 'id');
		}
*/		
	}
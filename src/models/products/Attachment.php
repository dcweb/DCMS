<?php

namespace Dcweb\Dcms\Models\Products;
use Dcweb\Dcms\Models\EloquentDefaults;

	class Attachment extends EloquentDefaults
	{
		protected $connection = 'project';
	  
		protected $table  = "products_attachments";
		
		protected $fillable = array('language_id', 'product_id','file','filename');
		
		
		public function product()
    {
				return $this->hasOne('\Dcweb\Dcms\Models\Products\Product', 'id', 'product_id');
    }
	}
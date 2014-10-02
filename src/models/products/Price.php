<?php

namespace Dcweb\Dcms\Models\Products;
use Eloquent;

	class Price extends Eloquent
	{
		protected $connection = 'project';
	  
		protected $table  = "products_price";
		
		protected $fillable = array('country_id', 'product_id');
		
		
		public function product()
    {
				return $this->hasOne('\Dcweb\Dcms\Models\Products\Product', 'id', 'product_id');
    }
	}
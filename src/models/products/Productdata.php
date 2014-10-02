<?php

namespace Dcweb\Dcms\Models\Products;
use Eloquent;

	class Productdata extends Eloquent
	{
		protected $connection = 'project';
		
		protected $table  = "products_information";
		
    protected $fillable = array('language_id', 'title', 'description');
		
		public function products()
    {
        return $this->belongsToMany('\Dcweb\Dcms\Models\Products\Product', 'products_to_products_data', 'product_data_id', 'product_id');
    }
		
		public function productcategory()
		{
				return $this->belongsTo('\Dcweb\Dcms\Models\Products\Category', 'product_category_id', 'id');
		}
		
	}
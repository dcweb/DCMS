<?php

namespace Dcweb\Dcms\Models\Products;
use Eloquent;

	class Information extends Eloquent
	{
		protected $connection = 'project';
		
		protected $table  = "products_information";
		
    protected $fillable = array('language_id', 'title', 'description');
		
		/*
		public function products()
    {
        return $this->belongsToMany('\Dcweb\Dcms\Models\Products\Product', 'products_to_products_information', 'product_information_id', 'product_id');
    }*/
		
		
		public function products()
    {
			/*
			The first argument in belongsToMany() is the name of the class Productdata, the second argument is the name of the pivot table, followed by the name of the product_id column, and at last the name of the product_data_id column.
			*/
        return $this->belongsToMany('\Dcweb\Dcms\Models\Products\Product', 'products_to_products_information', 'product_information_id', 'product_id');
    }
		
		public function productcategory()
		{
				return $this->belongsTo('\Dcweb\Dcms\Models\Products\Category', 'product_category_id', 'id');
		}
		
	}
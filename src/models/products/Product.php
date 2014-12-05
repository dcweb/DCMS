<?php

namespace Dcweb\Dcms\Models\Products;

use Eloquent;

	class Product extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "products";
		
		public function information()
    {
			/*
			The first argument in belongsToMany() is the name of the class Productdata, the second argument is the name of the pivot table, followed by the name of the product_id column, and at last the name of the product_data_id column.
			BelongsToMany belongsToMany(string $related, string $table = null, string $foreignKey = null, string $otherKey = null, string $relation = null)
			*/
        return $this->belongsToMany('\Dcweb\Dcms\Models\Products\Information', 'products_to_products_information', 'product_id', 'product_information_id');
    }
		
		public function price()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Products\Price','product_id');
		}
		
	}
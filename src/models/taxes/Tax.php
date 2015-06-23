<?php
namespace Dcweb\Dcms\Models\Taxes;
use Dcweb\Dcms\Models\EloquentDefaults;

	class Tax extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "products_price_tax";
	}
	
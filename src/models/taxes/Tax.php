<?php
namespace Dcweb\Dcms\Models\Taxes;

use Eloquent;

	class Tax extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "tax_class";
	}
	
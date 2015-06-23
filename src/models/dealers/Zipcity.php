<?php

namespace Dcweb\Dcms\Models\Dealers;

use Dcweb\Dcms\Models\EloquentDefaults;

	class Zipcity extends EloquentDefaults
	{
		protected $connection = 'admin';
		
		protected $table  = "zipcode-be";
	}
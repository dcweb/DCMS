<?php

namespace Dcweb\Dcms\Models\Dealers;
use Dcweb\Dcms\Models\EloquentDefaults;

	class ZipcityNL extends EloquentDefaults
	{
		protected $connection = 'admin';
		
		protected $table  = "zip_nl";
	
	}
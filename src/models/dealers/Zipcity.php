<?php

namespace Dcweb\Dcms\Models\Dealers;
use Eloquent;

	class Zipcity extends Eloquent
	{
		protected $connection = 'admin';
		
		protected $table  = "zipcode-be";
	}
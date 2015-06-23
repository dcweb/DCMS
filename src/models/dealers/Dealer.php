<?php

namespace Dcweb\Dcms\Models\Dealers;

use Dcweb\Dcms\Models\EloquentDefaults;

	class Dealer extends EloquentDefaults
	{
		protected $connection = 'project';
		protected $table  = "dealers";
	}
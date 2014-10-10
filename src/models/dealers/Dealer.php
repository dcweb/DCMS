<?php

namespace Dcweb\Dcms\Models\Dealers;
use Eloquent;

	class Dealer extends Eloquent
	{
		protected $connection = 'project';
		protected $table  = "dealers";
	}
<?php

namespace Dcweb\Dcms\Models\Newsletters;

use Eloquent;
	class Campaign extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "newsletters_campaigns";
	}
	

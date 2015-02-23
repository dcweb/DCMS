<?php

namespace Dcweb\Dcms\Models\Newsletters;

use Eloquent;
	class Monitor extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "newsletters_transactionmonitor";
	}
	

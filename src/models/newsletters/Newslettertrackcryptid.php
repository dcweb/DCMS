<?php

namespace Dcweb\Dcms\Models\Newsletters;

use Dcweb\Dcms\Models\EloquentDefaults;
	class NewsletterTrackcryptid extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "newsletters_trackcryptid";
	}
	

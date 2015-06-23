<?php

namespace Dcweb\Dcms\Models\Newsletters;

use Dcweb\Dcms\Models\EloquentDefaults;
	class NewsletterSentLog extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "newsletters_sentlog";		
	}
	

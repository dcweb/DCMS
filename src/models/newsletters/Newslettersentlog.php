<?php

namespace Dcweb\Dcms\Models\Newsletters;

use Eloquent;
	class NewsletterSentLog extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "newsletters_sentlog";
	}
	

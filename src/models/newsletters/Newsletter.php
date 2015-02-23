<?php

namespace Dcweb\Dcms\Models\Newsletters;

use Eloquent;
	class Newsletter extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "newsletters";
		
		public function campaign()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Newsletters\Campaign','campaign_id','id');
		}
	}
	

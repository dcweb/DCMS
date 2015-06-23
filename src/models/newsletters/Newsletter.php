<?php

namespace Dcweb\Dcms\Models\Newsletters;

use Dcweb\Dcms\Models\EloquentDefaults;
	class Newsletter extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "newsletters";
		
		public function campaign()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Newsletters\Campaign','campaign_id','id');
		}
	}
	

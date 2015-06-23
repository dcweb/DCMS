<?php

namespace Dcweb\Dcms\Models\Subscribers;

use Dcweb\Dcms\Models\EloquentDefaults;
	class Lists extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "subscribers_lists";
		
		
		public function subscribers()
    {
        return $this->hasMany('\Dcweb\Dcms\Models\Subscribers\Subscribers', 'list_id', 'id');
    }
	}
	

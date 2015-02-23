<?php

namespace Dcweb\Dcms\Models\Subscribers;

use Eloquent;
	class Lists extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "subscribers_lists";
		
		
		public function subscribers()
    {
        return $this->hasMany('\Dcweb\Dcms\Models\Subscribers\Subscribers', 'list_id', 'id');
    }
	}
	

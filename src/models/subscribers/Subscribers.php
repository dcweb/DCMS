<?php

namespace Dcweb\Dcms\Models\Subscribers;

use Dcweb\Dcms\Models\EloquentDefaults;
	class Subscribers extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "subscribers";
		
		
		public function lists()
    {
        return $this->belongsTo('\Dcweb\Dcms\Models\Subscribers\Lists', 'list_id', 'id');
    }
		
	}
	

<?php

namespace Dcweb\Dcms\Models\Subscribers;

use Eloquent;
	class Subscribers extends Eloquent
	{
		protected $connection = 'project';
	  protected $table  = "subscribers";
		
		
		public function lists()
    {
        return $this->belongsTo('\Dcweb\Dcms\Models\Subscribers\Lists', 'list_id', 'id');
    }
		
	}
	

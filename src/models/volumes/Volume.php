<?php

namespace Dcweb\Dcms\Models\Volumes;
use Eloquent;

	class Volume extends Eloquent
	{
		protected $connection = 'project';
		protected $table = 'volumes_class';
		
		public function detail()
		{
			  return $this->hasMany('\Dcweb\Dcms\Models\Volumes\Detail','volume_id','id');
		}
	}
	
	class Detail extends Eloquent
	{
		protected $connection = 'project';
		
		protected $table  = "volumes_class_detail";
		
    protected $fillable = array('language_id', 'volume_id', 'volume_class', 'volume_class_lang');
		
		public function volume()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Volumes\Volume','volume_id','id');
		}
	}
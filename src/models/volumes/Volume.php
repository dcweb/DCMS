<?php

namespace Dcweb\Dcms\Models\Volumes;
use Dcweb\Dcms\Models\EloquentDefaults;

	class Volume extends EloquentDefaults
	{
		protected $connection = 'project';
		protected $table = 'products_volume_units';
		
		public function detail()
		{
			  return $this->hasMany('\Dcweb\Dcms\Models\Volumes\Detail','volume_units_id','id');
		}
	}
	
	class Detail extends EloquentDefaults
	{
		protected $connection = 'project';
		
		protected $table  = "products_volume_units_language";
		
    protected $fillable = array('language_id', 'volume_units_id', 'volume_unit', 'volume_unit_lang');
		
		public function volume()
		{
			return $this->belongsTo('\Dcweb\Dcms\Models\Volumes\Volume','volume_units_id','id');
		}
	}
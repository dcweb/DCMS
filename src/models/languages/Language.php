<?php
namespace Dcweb\Dcms\Models\Languages;

use Dcweb\Dcms\Models\EloquentDefaults;

	class Language extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "languages";
	}
	
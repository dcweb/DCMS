<?php

namespace Dcweb\Dcms\Models\Newsletters;

use Dcweb\Dcms\Models\EloquentDefaults;
	class Monitor extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "newsletters_transactionmonitor";
	}
	
	class Analyse extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "newsletters_analyse";
	}
	
	
	class Analyseresult extends EloquentDefaults
	{
		protected $connection = 'project';
	  protected $table  = "newsletters_analyseresult";
	}
	

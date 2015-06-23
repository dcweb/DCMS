<?php 

namespace Dcweb\Dcms\Models;

use Eloquent;
use Auth;
use Schema;

class EloquentDefaults extends Eloquent 
{
/*	public function getPropertyLang($prop= "",$lang="")
		{
			$property = $prop.$lang;			
			return $this->$property;
		}
		*/
		
		public function save(array $options = array())
		{
			if(Schema::connection('project')->hasColumn($this->table,'admin'))
			{
				if(isset(Auth::dcms()->user()->username)) $this->admin = Auth::dcms()->user()->username;
			}
			parent::save($options);
		}
}

?>
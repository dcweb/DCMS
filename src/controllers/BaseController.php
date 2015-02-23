<?php

namespace Dcweb\Dcms\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Request;
use Input;

class BaseController extends Controller {

	
	public function __construct() {
		if (!Request::ajax() || (Request::ajax() && Input::has('_token')) )
		{
    	$this->beforeFilter('csrf', array('on'=>'post'));
		}
	}
	
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
	
}

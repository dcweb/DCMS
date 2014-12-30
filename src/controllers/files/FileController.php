<?php

namespace Dcweb\Dcms\Controllers\Files;

use Dcweb\Dcms\Controllers\BaseController;
use View;

class FileController extends BaseController {
	

	public function index()
	{
		return View::make('dcms::files/index');
	}

}

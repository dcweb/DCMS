<?php

namespace Dcweb\Dcms\Controllers\Dashboard;

use Dcweb\Dcms\Controllers\BaseController;
use View;

class DashboardController extends BaseController {

	public function dashboard()
	{
		return View::make('dcms::dashboard/index');
	}

}

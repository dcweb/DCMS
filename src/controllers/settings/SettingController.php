<?php

namespace Dcweb\Dcms\Controllers\Settings;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use DateTime;

class SettingController extends BaseController {
			/**
			 * Display a listing of the resource.
			 *
			 * @return Response
			 */
			public function index()
			{
				// load the view 
					return View::make('dcms::settings/settings');
			}
}
?>
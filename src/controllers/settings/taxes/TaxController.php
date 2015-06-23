<?php

namespace Dcweb\Dcms\Controllers\Settings;

use Dcweb\Dcms\Models\Taxes\Tax;
use Dcweb\Dcms\Helpers\Helper\SEOHelpers;
use Dcweb\Dcms\Controllers\BaseController;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use DateTime;
use Request;
use Route;

class TaxController extends BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
		return View::make('dcms::settings/taxes/index');
	}
	
	public function getDatatable()
	{
		return Datatable::Query(
									DB::connection('project')
											->table('products_price_tax')
											->select(
														'products_price_tax.id' ,
														'products_price_tax.tax' 
													)
		)
		
						->showColumns('tax')
						->addColumn('edit',function($model){return '<form class="pull-right"> <a class="btn btn-xs btn-default" href="/admin/settings/taxes/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a></form>';})
						->searchColumns('language_name')
						->make();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{		
		return View::make('dcms::settings/taxes/form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		$rules = array(
			'tax'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/settings/taxes/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			$input = Input::get();
			
			$Tax = new Tax;
			$Tax->tax= $input['tax'];
			$Tax->save();

			// redirect
			Session::flash('message', 'Successfully created language!');
			return Redirect::to('admin/settings/taxes');
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// get the country
		$Tax = Tax::find($id);

		// show the edit form and pass the nerd
		return View::make('dcms::settings/taxes/form')
					->with('Tax',$Tax);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(
			'tax'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/settings/taxes/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			$input = Input::get();
			
			$Tax = new Tax;
			$Tax->tax= $input['tax'];
			$Tax->save();
			
			// redirect
			Session::flash('message', 'Successfully updated country!');
			return Redirect::to('admin/settings/taxes');
		}
	}
	
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// delete
		//$Country = Country::find($id);
		//$Country->delete();
		
		// redirect
		Session::flash('message', 'Sorry nothing has been deleted, ask your DBA for thorough delete!');
		return Redirect::to('admin/settings/taxes');
	}
}
?>
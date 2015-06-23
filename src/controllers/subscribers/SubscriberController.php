<?php


namespace Dcweb\Dcms\Controllers\Subscribers;

use Dcweb\Dcms\Controllers\BaseController;
use Dcweb\Dcms\Controllers\Subscribers\ListController;
use Dcweb\Dcms\Models\Subscribers\Subscribers;
use Dcweb\Dcms\Models\Subscribers\Lists;


use View;
use Input;
use DB;
use Datatable;
use URL;
use Validator;
use Auth;
use Redirect;
use Session;
use Crypt;

class SubscriberController extends BaseController {
	
	private $aespassword = "W38d3V";

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id = null)
	{
		$List = null;
		if(!is_null($id)) $List = Lists::find($id);
		
		return View::make('dcms::subscribers/subscribers/index')
							->with('id',$id)
							->with('List',$List);
							
	}
	
	public function getDatatable($id = null)// or we give the modelid of a certain model and find the details of this model - at this moment we only give the default checked radiobutton
	{
			if(is_null($id) || intval($id)<=0)	$DT = Datatable::collection(Subscribers::with('lists')->get(array('firstname','lastname','email','id','list_id','newsletter')));
			else $DT = Datatable::collection(Subscribers::where("list_id","=",intval($id))->with('lists')->get(array('firstname','lastname','email','id','list_id','newsletter')));
			
			return	$DT
						->showColumns('firstname','lastname','email')
						 ->addColumn('name',function($model)
														{
																return $model->lists->listname;
														})
						->showColumns('newsletter')
						->addColumn('edit', function($model){ return '<form method="POST" action="/admin/subscribers/'.$model->id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
										<a class="btn btn-xs btn-default" href="/admin/subscribers/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
										<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
									</form>';})
								->searchColumns('firstname','lastname','email')
								->orderColumns('email')
								->make();
	}
	
	
	private function validateListForm()
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array('email' => 'required|email');
										
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)
				->withInput();
		}
		else
		{
			return true;
		}
	}
	
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::subscribers/subscribers/form')
				->with('Subscriber', new Subscribers)
				->with('aLists', ListController::getLists("A"));
	}
	
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
			// show the edit form and pass the nerd
			return View::make('dcms::subscribers/subscribers/form')
				->with('Subscriber', Subscribers::find($id))
				->with('aLists', ListController::getLists("A"));
	}
	
	
	private function setCryptid(Subscribers $Subscriber)
	{
		if(intval($Subscriber->id)>0)
		{	
				DB::connection('project')->select(DB::raw("UPDATE subscribers SET cryptid = hex(AES_ENCRYPT(concat(list_id,':',email), '".$this->aespassword."')) WHERE id = '".intval($Subscriber->id)."'"));
		}
	}
	
	
	private function saveSubcriberProperties($subscriberid = null)
	{
		$input = Input::get();
		
		// do check if the given id is existing.
		if(!is_null($subscriberid) && intval($subscriberid)>0) $Subscriber = Subscribers::find($subscriberid);  
		if(!isset($Subscriber) || is_null($Subscriber)) $Subscriber = new Subscribers;		
		
		$Subscriber->list_id		= $input['list_id'];
		$Subscriber->email 			= $input['email'];
	//	$Subscriber->username 	= $input['username'];
		$Subscriber->firstname	= $input['firstname'];
		$Subscriber->lastname		= $input['lastname'];
		$Subscriber->gender			= $input['gender'];
		$Subscriber->street			= $input['street'];
		$Subscriber->nr					= $input['nr'];
		$Subscriber->bus				= $input['bus'];
		$Subscriber->zip				= $input['zip'];
		$Subscriber->city				= $input['city'];
		$Subscriber->country		= $input['country'];
		$Subscriber->language		= $input['language'];
		$Subscriber->newsletter	= (isset($input['newsletter'])?$input['newsletter']:0);
		$Subscriber->admin 			= Auth::dcms()->user()->username;
		$Subscriber->save();		
		
		$this->setCryptid($Subscriber);
		
		return $Subscriber;
	}
	

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ($this->validateListForm() === true)
		{
			$Subscriber = $this->saveSubcriberProperties();
			// redirect
			Session::flash('message', 'Successfully created subscriber!');
			return Redirect::to('admin/subscribers/list/'.$Subscriber->list_id);
			
		}else return  $this->saveSubcriberProperties();
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if ($this->validateListForm() === true)
		{
			$Subscriber = $this->saveSubcriberProperties($id);
			// redirect
			Session::flash('message', 'Successfully updated subscriber!');
			return Redirect::to('admin/subscribers/list/'.$Subscriber->list_id);
			
		}else return  $this->saveSubcriberProperties();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
		Subscribers::destroy($id);
	
		// redirect
		Session::flash('message', 'Successfully deleted the subscriber!');
//		return Redirect::to('admin/subscribers');
		return Redirect::back();
	}
	
}
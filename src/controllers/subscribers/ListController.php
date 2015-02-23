<?php

namespace Dcweb\Dcms\Controllers\Subscribers;

use Dcweb\Dcms\Controllers\BaseController;
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

class ListController extends BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
		return View::make('dcms::subscribers/lists/index');
	}
	
	
	public function getJsonData()
	{
		if(Input::has("data"))
		{
			switch (Input::get("data"))
			{
				case "list":
					$List = Lists::where("id","=",Input::get("id"))->get(array('from_name','from_email','replyto_email'))->first()->toJson();
					return $List;
					exit();
					break;
				default:
					
					break;
			}
		}
	}
	
	
	public function getDatatable()// or we give the modelid of a certain model and find the details of this model - at this moment we only give the default checked radiobutton
	{
			return	Datatable::collection(Lists::all())
						->showColumns('listname')
						->addColumn('edit', function($model){ return '<form method="POST" action="/admin/subscribers/lists/'.$model->id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
										<a class="btn btn-xs btn-default" href="/admin/subscribers/lists/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
										<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
									</form>';})
								->searchColumns('listname')
								->orderColumns('listname')
								->make();
	}
	
	
	private function validateListForm()
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array('listname' => 'required'
										,'from_email' => 'email'
										,'replyto_email' => 'email');
										
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
		return View::make('dcms::subscribers/lists/form')
				->with('List', new Lists);
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
			return View::make('dcms::subscribers/lists/form')
				->with('List', Lists::find($id));
	}
	
	private function saveListProperties($listid = null)
	{
		$input = Input::get();
		
		// do check if the given id is existing.
		if(!is_null($listid) && intval($listid)>0) $List = Lists::find($listid);  
		if(!isset($List) || is_null($List)) $List = new Lists;		
		
		$List->listname 		= $input['listname'];
		$List->from_name 		= $input['from_name'];
		$List->from_email 	= $input['from_email'];
		$List->replyto_email= $input['replyto_email'];
		$List->admin 		= Auth::dcms()->user()->username;
		$List->save();		
		
		return $List;
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
			$List = $this->saveListProperties();
			// redirect
			Session::flash('message', 'Successfully created list!');
			return Redirect::to('admin/subscribers/lists');
			
		}else return  $this->validateListForm();
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
			$List = $this->saveListProperties($id);
			// redirect
			Session::flash('message', 'Successfully updated list!');
			return Redirect::to('admin/subscribers/lists');
			
		}else return  $this->validateListForm();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Lists::destroy($id);
	
		// redirect
		Session::flash('message', 'Successfully deleted the lists!');
		return Redirect::to('admin/subscribers/lists');
	}
	
	
	/**
	 * getLists and set a return Type A = array ; O = object; 
	 *
	 * @param  string  $returnType
	 * @return array/object
	 */
	public static function getLists($returnType = "A") //
	{
		$returnVar = null;
		$Lists = Lists::get(array('id','listname'));
		if ($returnType == "A") 
		{
			$returnVar = array();
			foreach($Lists as $List)
			{
				$returnVar[$List->id] = $List->listname;//. " ( #".$List->subscribers->count().")";
			}
		}
		else
		{
			$returnVar = $Lists;
		}
		return $returnVar;
	}
}
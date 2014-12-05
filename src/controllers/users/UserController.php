<?php

namespace Dcweb\Dcms\Controllers\Users;

use Dcweb\Dcms\Models\Users\User;
use Dcweb\Dcms\Controllers\BaseController;
use View;
use Input;
use Validator;
use Redirect;
use Auth;
use DB;
use Datatable;
use Session;
use Hash;
use Mail;

class UserController extends BaseController {

  public function login()
  {

    if ($this->isPostRequest()) {

      $validator = $this->getLoginValidator();
  
      if ($validator->passes()) {
        $credentials = $this->getLoginCredentials();
  
        if (Auth::attempt($credentials)) {
					session_start();
					$_SESSION["admin"]["allow_ckfinder"] = true;
					return Redirect::intended("admin/dashboard"); //intended will keep in mind your entry point, if none has been found a default is given
        //  return Redirect::route("admin/dashboard");
        }
  
        return Redirect::back()->withErrors(array( "password" => array("Username and/or password invalid.")));

      } else {
        return Redirect::back()
          ->withInput()
          ->withErrors($validator);
      }

    }
  
    return View::make("dcms::users/login");

  }
  
  protected function isPostRequest()
  {
    return Input::server("REQUEST_METHOD") == "POST";
  }
  
  protected function getLoginValidator()
  {
    return Validator::make(Input::all(), array(
      "username" => "required",
      "password" => "required"
    ));
  }
  
  protected function getLoginCredentials()
  {
    return array(
      "username" => Input::get("username"),
      "password" => Input::get("password")
    );
  }
  
	public function profile()
	{
		return View::make("dcms::users/profile");
	}
	
	public function logout()
	{
	  Auth::logout();
	  
	  return Redirect::route("admin/users/login");
	}

/*	
	public function request()
	{
		
	  if ($this->isPostRequest()) {
		$response = $this->getPasswordRemindResponse();

		if ($this->isInvalidUser($response)) {
		  return Redirect::back()
			->withInput()
			->with("error", Lang::get($response));
		}
	  
		return Redirect::back()
		  ->with("status", Lang::get($response));
	  }
	  
	  return View::make("dcms::request");
	}
	  
	protected function getPasswordRemindResponse()
	{
	  return Password::remind(Input::only("email"));
	}
	  
	protected function isInvalidUser($response)
	{
	  return $response === Password::INVALID_USER;
	}
	
	public function reset($token)
	{
	  if ($this->isPostRequest()) {
		$credentials = Input::only(
		  "email",
		  "password",
		  "password_confirmation"
		) + compact("token");
	 
		$response = $this->resetPassword($credentials);
	 
		if ($response === Password::PASSWORD_RESET) {
		  return Redirect::route("admin/users/profile");
		}
	 
		return Redirect::back()
		  ->withInput()
		  ->with("error", Lang::get($response));
	  }
	 
	  return View::make("dcms::reset", compact("token"));
	}
	 
	protected function resetPassword($credentials)
	{
	  return Password::reset($credentials, function($user, $pass) {
		$user->password = Hash::make($pass);
		$user->save();
	  });
	}
	
*/	



	/*****************************************************
	  CRUD METHODS
	*******************************************************/
	
  
  
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		// get all the users
//		$users = User::all();
		$users = User::paginate(5);

		// load the view and pass the users
		return View::make('dcms::users/index')
			->with('users', $users);
	}
	
	
	
	
	public function getDatatable()
	{
	
		return Datatable::Query(DB::connection("project")->table("users"))
						->showColumns('name')
						->showColumns('email')
						->showColumns('username')
						->showColumns('role')
						->addColumn('edit',function($model){return '<form method="POST" action="/admin/users/'.$model->id.'" accept-charset="UTF-8" class="pull-right"><input name="_token" type="hidden" value="'.csrf_token().'">					<input name="_method" type="hidden" value="DELETE">					<!-- <input class="btn btn-warning" type="submit" value="Delete this User"> -->
								<a class="btn btn-xs btn-default" href="/admin/users/'.$model->id.'"><i class="fa fa-eye"></i></a>
								<a class="btn btn-xs btn-default" href="/admin/users/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
								<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
							</form>';})
						->searchColumns('name','email')
						->make();
	}
	
	
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// load the create form (app/views/users/create.blade.php)
		return View::make('dcms::users/form');
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
			'name'       => 'required',
			'username'       => 'required',
			'email'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		
		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/users/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			$user = new User;
			$user->name   		= Input::get('name');
			$user->username   	= Input::get('username');
			$user->email 		= Input::get('email');
			$user->role	 		= Input::get('role');
			$user->password 	= Hash::make(Input::get('password'));
			$this->sendemail();
			$user->save();
	
			// redirect
			Session::flash('message', 'Successfully created user!');
			return Redirect::to('admin/users');
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
	
		
		//
		// get the user
		$user = User::find($id);
		$cat = $user->category;
		
		// show the view and pass the user to it
		return View::make('dcms::users/show')
			->with('user', $user);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		// get the user
		$user = User::find($id);
		$user->password = "";

		// show the edit form and pass the nerd
		return View::make('dcms::users/form')
			->with('user', $user);
	}
	
	/**
	 * Sendemail will send the user his username and password
	 *
	 * @param obj $model
	 */
	protected function sendemail()
	{
		
		if (Input::get("sendemail")=="1"){
			/*****************************************
				- MAIL SETTINGS -
			*****************************************/
			// I'm creating an array with user's info but most likely you can use $user->email or pass $user object to closure later
			$emailuser = array(
				'email'=>Input::get('email'),
				'name'=>Input::get('name'),
				'username'=>Input::get('username'),
				'password'=>Input::get('password')
			);
	 
			// the data that will be passed into the mail view blade template
			$data = array(
				'name'  => 'name: <b>'.$emailuser['name'].'</b>',
				'username'  => 'username: <b>'.$emailuser['username'].'</b>',
				'email'  => 'email: <b>'.$emailuser['email'].'</b>',
				'password'  => 'password: <b>'.$emailuser['password'].'</b>'			
			);
			 
			// use Mail::send function to send email passing the data and using the $user variable in the closure
			Mail::send('dcms::users/email', $data, function($message) use ($emailuser)
			{
				$message->from('web@groupdc.be', 'New DCMS User');
				$message->to($emailuser['email'], $emailuser['name'])->subject('Welcome to My Laravel app!');
			});
			/*****************************************
				- END MAIL  -
			*****************************************/
		}
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
			'name'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/users/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			$user = User::find($id);
			$user->name		= Input::get('name');
			$user->username	= Input::get('username');
			$user->email	= Input::get('email');
			$user->role	= Input::get('role');
			
			if (strlen(trim(Input::get('password')))>0){
				$user->password = Hash::make(Input::get('password'));
			}
			$this->sendemail();
			$user->save();

			// redirect
			Session::flash('message', 'Successfully updated user!');
			return Redirect::to('admin/users');
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
		$user = User::find($id);
		$user->delete();

		// redirect
		Session::flash('message', 'Successfully deleted the user!');
		return Redirect::to('admin/users');
	}
	
	/*****************************************************
	 END CRUD METHODS
	*******************************************************/

}
<?php

namespace Dcweb\Dcms\Controllers\Articles;

use Dcweb\Dcms\Models\Articles\Article;
use Dcweb\Dcms\Models\Articles\Detail;
use Dcweb\Dcms\Models\Articles\CategoryID;
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
use DCMSFunctions;


class ArticleController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view 
		return View::make('dcms::articles/articles/index');
	}
	
	
	public function getDatatable()
	{
	
		return Datatable::Query(
									DB::connection('project')
											->table('articles')
											->select(
														'articles.id', 
														'articles_detail.title', 
														'articles_detail.id as detail_id',
														(DB::connection("project")->raw('Concat("<img src=\'/images/flag-",lcase(country),".png\' >") as country'))
													)
											->join('articles_detail','articles.id','=','articles_detail.article_id')
											->leftJoin('languages','articles_detail.language_id', '=' , 'languages.id')
		)
		
						->showColumns('title','country')
						->addColumn('edit',function($model){return '<form method="POST" action="/admin/articles/'.$model->detail_id.'" accept-charset="UTF-8" class="pull-right"> <input name="_token" type="hidden" value="'.csrf_token().'"> <input name="_method" type="hidden" value="DELETE">
								<a class="btn btn-xs btn-default" href="/admin/articles/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
								<a class="btn btn-xs btn-default" href="/admin/articles/'.$model->id.'/copy"><i class="fa fa-copy"></i></a>
								<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
							</form>';})
						->searchColumns('title')
						->make();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$languages =  DB::connection("project")->table("languages")->select((DB::connection("project")->raw("'' as title, '' as description , '' as body, '' as article_category_id")), "id","id as language_id",  "language","country","language_name")->get();
		
		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::articles/articles/form')
					->with('languages',$languages)
					->with('categoryOptionValues',CategoryID::OptionValueArray(true));
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
			'title'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		
		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/articles/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			
			$input = Input::get();
			
			foreach($input["title"] as $language_id  => $title)
			{
				if (strlen(trim($input["title"][$language_id]))>0)
				{
					//since we loop with foreach we don't want to create everytime a new article
					if (!isset($Article) || is_null($Article) )
					{
						$Article = new Article;
						$Article->startdate =  (!empty($input["startdate"]) ? DateTime::createFromFormat('d-m-Y', $input["startdate"])->format('Y-m-d') : null);
						$Article->enddate =  (!empty($input["enddate"]) ? DateTime::createFromFormat('d-m-Y', $input["enddate"])->format('Y-m-d') : null);
						$Article->thumbnail = $input["thumbnail"];
						$Article->admin =  Auth::user()->username;
						$Article->save();
					}
				
					$Detail = new Detail();
									
					$Detail->language_id 		= $language_id;
					$Detail->article_category_id = ($input["category_id"][$language_id]==0?NULL:$input["category_id"][$language_id]); 
					$Detail->title 				= $input["title"][$language_id];
					$Detail->description 	= $input["description"][$language_id];
					$Detail->body 					= $input["body"][$language_id];
					
					$Detail->url_slug = DCMSFunctions::SEOUrl($input["title"][$language_id]); 
					$Detail->url_path = DCMSFunctions::SEOUrl($input["title"][$language_id]); 
					
					$Detail->admin 				= Auth::user()->username;
					$Detail->save();		
					Article::find($Article->id)->detail()->save($Detail);
					
				}//if title is set
			}//end foreach
			
			// redirect
			Session::flash('message', 'Successfully created article!');
			return Redirect::to('admin/articles');
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
		// get the article
		$article = Article::find($id);
		$cat = $article->category;
		
		// show the view and pass the nerd to it
		return View::make('dcms::articles/articles/show')
			->with('article', $article)
			->with("category",$cat->title);
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
			// get the article
			$article = Article::find($id);
			$article->startdate = (is_null($article->startdate)? null : DateTime::createFromFormat('Y-m-d', $article->startdate)->format('d-m-Y'));
			$article->enddate = (is_null($article->enddate)? null : DateTime::createFromFormat('Y-m-d', $article->enddate)->format('d-m-Y'));
		
		 	$languages = DB::connection("project")->select('
													SELECT language_id, languages.language, languages.country, languages.language_name, article_category_id, articles_detail.id, article_id, title, description, body, date_format(startdate,\'%d-%m-%Y\') as startdate , date_format(enddate,\'%d-%m-%Y\')  as enddate 
													FROM articles_detail
													LEFT JOIN languages on languages.id = articles_detail.language_id
													LEFT JOIN articles on articles.id = articles_detail.article_id
													WHERE  languages.id is not null AND  article_id = ?
													UNION
													SELECT languages.id , language, country, language_name, \'\' , \'\' ,  \'\' , \'\' , \'\' , \'\'  , \'\' , \'\' 
													FROM languages 
													WHERE id NOT IN (SELECT language_id FROM articles_detail WHERE article_id = ?) ORDER BY 1
													', array($id,$id));

			// show the edit form and pass the nerd
			return View::make('dcms::articles/articles/form')
				->with('article', $article)
				->with('languages', $languages)
				->with('categoryOptionValues',CategoryID::OptionValueArray(true));;
	}
	
	
	/**
	 * copy the model
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function copy($id)
	{
		$newDetail 	= Detail::find($id)->replicate();
		$Article = new Article();
		$Article->save();
		$newDetail->article_id = $Article->id;
		$newDetail->save();

		return Redirect::to('admin/articles');
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
			'title'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/articles/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			
			$Article = Article::find($id);
			
			$input = Input::get();
			foreach($input["title"] as $language_id  => $title)
			{
				
				if (strlen(trim($title))>0) //we don't want to populate the database when there is no title given
				{
					if (!isset($Article) || is_null($Article) )
					{
						$Article = new Article;
					}


					$Article->startdate = (!empty($input["startdate"]) ? DateTime::createFromFormat('d-m-Y', $input["startdate"])->format('Y-m-d') : null);
					$Article->enddate 	= (!empty($input["enddate"]) ? DateTime::createFromFormat('d-m-Y', $input["enddate"])->format('Y-m-d') : null);
					$Article->thumbnail = $input["thumbnail"];
					$Article->admin 		=  Auth::user()->username;
					$Article->save();
					
					$Detail = Detail::find($input["article_information_id"][$language_id]);
					if (is_null($Detail) === true)
					{
						$Detail = new Detail();
					}
					
					$Detail->language_id 					= $language_id;
					$Detail->article_category_id 	= ($input["category_id"][$language_id]==0?NULL:$input["category_id"][$language_id]);
					$Detail->title 								= $input["title"][$language_id];
					$Detail->description 					= $input["description"][$language_id];
					$Detail->body 								= $input["body"][$language_id];
				
					$Detail->url_slug = DCMSFunctions::SEOUrl($input["title"][$language_id]); 
					$Detail->url_path = DCMSFunctions::SEOUrl($input["title"][$language_id]); 
					
					$Detail->admin 								=  Auth::user()->username;
					$Detail->save();	
					Article::find($Article->id)->detail()->save($Detail);
				}
			}//end foreach
			
			// redirect
			Session::flash('message', 'Successfully updated article!');
			return Redirect::to('admin/articles');
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
		$Detail = Detail::find($id);
		$Detail->delete();

		// redirect
		Session::flash('message', 'Successfully deleted the article!');
		return Redirect::to('admin/articles');
	}


}

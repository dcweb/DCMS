<?php

namespace Dcweb\Dcms\Controllers\Articles;

use Dcweb\Dcms\Models\Articles\Article;
use Dcweb\Dcms\Models\Articles\Detail;
use Dcweb\Dcms\Models\Articles\CategoryID;
use Dcweb\Dcms\Models\Pages\Pagetree;
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
use Dcweb\Dcms\Helpers\Helper\SEOHelpers;


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
														(DB::connection("project")->raw('Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(country),".png\' >") as country'))
													)
											->join('articles_detail','articles.id','=','articles_detail.article_id')
											->leftJoin('languages','articles_detail.language_id', '=' , 'languages.id')
		)
		
						->showColumns('title','country')
						->addColumn('edit',function($model){return '<form method="POST" action="/admin/articles/'.$model->detail_id.'" accept-charset="UTF-8" class="pull-right"> 
								<input name="_token" type="hidden" value="'.csrf_token().'"> 
								<input name="_method" type="hidden" value="DELETE">
								<a class="btn btn-xs btn-default" href="/admin/articles/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a>
								<a class="btn btn-xs btn-default" href="/admin/articles/'.$model->detail_id.'/copy"><i class="fa fa-copy"></i></a>
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
					->with('categoryOptionValues',CategoryID::OptionValueArray(false))
					->with('pageOptionValues',Pagetree::OptionValueArray(false));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ($this->validateArticleForm() === true)
		{
			$Article = $this->saveArticleProperties();
			if (!is_null($Article))	$this->saveArticleDetail($Article);
	
			// redirect
			Session::flash('message', 'Successfully created article!');
			return Redirect::to('admin/articles');
		}else return  $this->validateArticleForm();
			
		
	/*	
		//
		$rules = array(
		//	'title'       => 'required',
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
					//----------------------------------------
					// We loop over different titles based
					// on their languages
					// the first time we hit a title
					// we need to set the Articles global data
					// the second time we just tag the 
					// language details to the global article
					//----------------------------------------
					if (!isset($Article) || is_null($Article) )
					{
						$Article = new Article;
						$Article->startdate =  (!empty($input["startdate"]) ? DateTime::createFromFormat('d-m-Y', $input["startdate"])->format('Y-m-d') : null);
						$Article->enddate =  (!empty($input["enddate"]) ? DateTime::createFromFormat('d-m-Y', $input["enddate"])->format('Y-m-d') : null);
						$Article->thumbnail = $input["thumbnail"];
						$Article->admin =  Auth::user()->username;
						$Article->save();
					}
					
					//----------------------------------------
					// set the details of different languages
					// of the article
					//----------------------------------------
					$Detail = new Detail();
									
					$Detail->language_id 	= $language_id;
					$Detail->article_category_id = ($input["category_id"][$language_id]==0?NULL:$input["category_id"][$language_id]); 
					$Detail->title 				= $input["title"][$language_id];
					$Detail->description 	= $input["description"][$language_id];
					$Detail->body 				= $input["body"][$language_id];
					
					$Detail->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					$Detail->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					
					$Detail->admin 				= Auth::user()->username;
					$Detail->save();		
					Article::find($Article->id)->detail()->save($Detail);
					
					//----------------------------------------
					// link the article to the selected page
					// we will take the $Detail->id since
					// this directly holds the language_id
					// otherwise we'd be storing it twice
					//----------------------------------------
					if(isset($input["page_id"]) && count($input["page_id"][$language_id])>0)
					{
						foreach($input["page_id"][$language_id] as $arrayindex => $page_id)
						{
							//Detail::find($Detail->id)->pages->save($page_id);
							$Detail->pages()->attach($page_id);		
						}
					}//end - if(count($input["page_id"][$language_id])>0)

				}//if title is set
			}//end foreach
			
			// redirect
			Session::flash('message', 'Successfully created article!');
			return Redirect::to('admin/articles');
		}
		*/
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
		
		 	$objlanguages = DB::connection("project")->select('
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

				
		 	$objselected_pages = DB::connection("project")->select('
													SELECT article_detail_id, page_id 
													FROM articles_detail_to_pages 
													WHERE article_detail_id IN (SELECT id FROM articles_detail WHERE article_id = ?)',array($id));


			$pageOptionValuesSelected = array();
			if (count($objselected_pages)>0)
			{
				foreach($objselected_pages as $obj)
				{
					$pageOptionValuesSelected[$obj->article_detail_id][$obj->page_id] = $obj->page_id;
				}
			}
			
			// show the edit form and pass the nerd
			return View::make('dcms::articles/articles/form')
				->with('article', $article)
				->with('languages', $objlanguages)
				->with('categoryOptionValues',CategoryID::OptionValueArray(false))
				->with('pageOptionValues',Pagetree::OptionValueArray(false))
				->with('pageOptionValuesSelected',$pageOptionValuesSelected);
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


	private function validateArticleForm()
	{
		$rules = array(
		//	'title' => 'required'
		);
		$validator = Validator::make(Input::all(),$rules);
		
		// process the login
		if ($validator->fails()) {
			return Redirect::back()//to('admin/products/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		}
		else
		{
			return true;
		}
	}


	private function saveArticleProperties($articleid = null)
	{
		$input = Input::get();

		// do check if the given id is existing.
		if(!is_null($articleid) && intval($articleid)>0) $Article= Article::find($articleid);
		if(!isset($Article) || is_null($Article)) $Article = new Article;

		foreach($input["title"] as $language_id  => $title)
		{
			if (strlen(trim($input["title"][$language_id]))>0)
			{
				//----------------------------------------
				// once there is a title set
				// we can set the properties to the article
				// and instantly return the article model
				// by default nothing is return so the
				// script may stop.
				//----------------------------------------
				$Article->startdate =  (!empty($input["startdate"]) ? DateTime::createFromFormat('d-m-Y', $input["startdate"])->format('Y-m-d') : null);
				$Article->enddate =  (!empty($input["enddate"]) ? DateTime::createFromFormat('d-m-Y', $input["enddate"])->format('Y-m-d') : null);
				$Article->thumbnail = $input["thumbnail"];
				$Article->admin =  Auth::user()->username;
				$Article->save();
				return $Article;
				break; // we only have to save the global settings once.
			}
		}
		//does not return anything by defuault... (may be false dunno - find out)
	}
	
	private function saveArticleDetail(Article $Article, $givenlanguage_id = null)
	{
		$input = Input::get();
	
		$Detail = null;
	
		foreach($input["title"] as $language_id  => $title)
		{
			if (strlen(trim($title))>0) //we don't want to populate the database when there is no title given
			{
				if ((is_null($givenlanguage_id) || ($language_id == $givenlanguage_id)))
				{
					$Detail = null;
					$Detail = Detail::find($input["article_information_id"][$language_id]);
					if (is_null($Detail) === true)	$Detail = new Detail(); 
					
					$Detail->language_id 					= $language_id;
					$Detail->article_category_id 	= ($input["category_id"][$language_id]==0?NULL:$input["category_id"][$language_id]);
					$Detail->title 								= $input["title"][$language_id];
					$Detail->description 					= $input["description"][$language_id];
					$Detail->body 								= $input["body"][$language_id];
				
					$Detail->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					$Detail->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					
					$Detail->admin 								=  Auth::user()->username;
					$Detail->save();	
					
					//Article::find($Article->id)->detail()->save($Detail);
					$Article->detail()->save($Detail);
					//$Article->detail()->attach($Detail->id); //does not seem to work.. what's the difference with attach vs save
					
					//----------------------------------------
					// Detach the $Detail from all pages
					//----------------------------------------
					$Detail->pages()->detach();		
					
					//----------------------------------------
					// link the article to the selected page
					// we will take the $Detail->id since
					// this directly holds the language_id
					// otherwise we'd be storing it twice
					//----------------------------------------
					if(isset($input["page_id"]) && isset($input["page_id"][$language_id]) && count($input["page_id"][$language_id])>0)
					{
						foreach($input["page_id"][$language_id] as $arrayindex => $page_id)
						{
							//Detail::find($Detail->id)->pages->save($page_id);
							$Detail->pages()->attach($page_id);		
						}
					}//end - if(count($input["page_id"][$language_id])>0)
				}
			}
		}
		return $Detail;		
	}
	

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if ($this->validateArticleForm() === true)
		{
			$Article = $this->saveArticleProperties($id);
			if (!is_null($Article))	$this->saveArticleDetail($Article);
	
			// redirect
			Session::flash('message', 'Successfully updated article!');
			return Redirect::to('admin/articles');
		}else return  $this->validateArticleForm();

/*		
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(
			//'title' => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) 
		{
			return Redirect::to('admin/articles/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} 
		else 
		{
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
				
					$Detail->url_slug = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					$Detail->url_path = SEOHelpers::SEOUrl($input["title"][$language_id]); 
					
					$Detail->admin 								=  Auth::user()->username;
					$Detail->save();	
					
					Article::find($Article->id)->detail()->save($Detail);
					
					//----------------------------------------
					// Detach the $Detail from all pages
					//----------------------------------------
					$Detail->pages()->detach();		
					
					//----------------------------------------
					// link the article to the selected page
					// we will take the $Detail->id since
					// this directly holds the language_id
					// otherwise we'd be storing it twice
					//----------------------------------------
					if(count($input["page_id"][$language_id])>0)
					{
						foreach($input["page_id"][$language_id] as $arrayindex => $page_id)
						{
							//Detail::find($Detail->id)->pages->save($page_id);
							$Detail->pages()->attach($page_id);		
						}
					}//end - if(count($input["page_id"][$language_id])>0)
				}
			}//end foreach
			
			// redirect
			Session::flash('message', 'Successfully updated article!');
			return Redirect::to('admin/articles');
		}
		*/
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
		
		$mainArticleID = $Detail->article_id;
		
		$Detail->delete();
		
		if (Detail::where("article_id","=",$mainArticleID)->count() <= 0)
		{
			Article::destroy($mainArticleID);
		}
		
		// redirect
		Session::flash('message', 'Successfully deleted the article!');
		return Redirect::to('admin/articles');
	}
}

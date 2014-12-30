@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Newsletters</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('admin/newsletters') }}"><i class="fa fa-pencil"></i> Newsletters</a></li>
@if(isset($newsletter))
        <li class="active">Edit</li>
@else
        <li class="active">Create</li>
@endif
      </ol>
    </div>

    <div class="main-content">

    @if(isset($newsletter) && intval($newsletter->id)>0 )
        {{ Form::model($newsletter, array('route' => array('admin.newsletters.update', $newsletter->id), 'method' => 'PUT')) }}
    @else
        {{Form::open(array('url' => 'admin/newsletters'))}}
    @endif

    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">
              
              @if($errors->any())
                <div class="alert alert-danger">{{ HTML::ul($errors->all()) }}</div>
              @endif
              
              <div class="form-group">
                {{ Form::label('subject', 'Subject') }}
                {{ Form::text('subject', (Input::old('subject') ? Input::old('subject') : $newsletter->subject), array('class' => 'form-control')) }}
              </div>
							    
              <div class="form-group">
                {{ Form::label('sender', 'Sender name') }}
                {{ Form::text('sender', (Input::old('sender') ? Input::old('sender') : $newsletter->sender), array('class' => 'form-control')) }}
              </div>
							    
              <div class="form-group">
                {{ Form::label('sendermail', 'Sender e-mail') }}
                {{ Form::text('sendermail', (Input::old('sendermail') ? Input::old('sendermail') : $newsletter->sendermail), array('class' => 'form-control')) }}
              </div>
							    
              <div class="form-group">
                {{ Form::label('replyto', 'Reply-to e-mail') }}
                {{ Form::text('replyto', (Input::old('replyto') ? Input::old('replyto') : $newsletter->replyto), array('class' => 'form-control')) }}
              </div>
              
              <div class="form-group">
                {{ Form::label('regio', 'Regio') }}
                {{ Form::text('regio', (Input::old('regio') ? Input::old('regio') : $newsletter->regio), array('class' => 'form-control ckeditor')) }}
              </div>
              
              <div class="form-group">
                {{ Form::label('htmlbody', 'Body (online edition)') }}  <!--  
                <p>&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD XHTML 1.0 Strict//EN&quot; &quot;http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd&quot;&gt;&lt;html xmlns=&quot;http://www.w3.org/1999/xhtml&quot;&gt;&lt;head&gt;&lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;text/html; charset=UTF-8&quot;&gt;&lt;title&gt;{SUBJECT}&lt;/title&gt;&lt;/head&gt;&lt;body bgcolor=&quot;#d72e2c&quot; style=&quot;background-color:#d72e2c;&quot;&gt;&lt;div id=&quot;container&quot; style=&quot;margin: 0 auto; max-width: 640px; min-width: 240px; _width: 640px; *width: 640px;&quot;&gt;</p>
                 <p>BODY</p>
                 <p>&lt;/div&gt;&lt;div align=&quot;center&quot;&gt;&lt;p style=&quot;text-align:center; font-size:11px; color:#ffffff; font-family:Tahoma,Geneva,sans-serif;&quot;&gt;Gelieve niet te antwoorden op deze e-mail. Deze e-mail is automatisch verstuurd.&lt;br/&gt; Copyright &amp;copy; 20XX DCM - member of Group De Ceuster - &lt;A href=&quot;http://www.dcm-info.com/[REGIO]/nieuwsbrief/uitschrijven/[ID_AESMAIL]&quot; style=&quot;color:#ffffff;&quot;&gt;Uitschrijven&lt;/a&gt;&lt;/p&gt;&lt;img width=&quot;1&quot; height=&quot;1&quot; src=&quot;http://www.dcm-info.com/UserFiles/image/email/tr/[ID_AESMAIL].gif&quot;&gt;&lt;/div&gt;&lt;/body&gt;&lt;/html&gt;</p> -->
                {{ Form::textarea('htmlbody', (Input::old('htmlbody') ? Input::old('htmlbody') : $newsletter->htmlbody ), array('class' => 'form-control ckeditor')) }}
              </div>
              
              <div class="form-group">
                {{ Form::label('body', 'Body (e-mail edition)') }}<!--
               	<p>&lt;html&gt;&lt;head&gt;&lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;text/html; charset=iso-8859-1&quot;&gt;&lt;TITLE&gt;{SUBJECT}&lt;/TITLE&gt;&lt;/head&gt;&lt;body bgcolor=&quot;#d72e2c&quot; style=&quot;background-color:#d72e2c;&quot;&gt;&lt;div align=&quot;center&quot; style=&quot;background:#d72e2c;&quot;&gt;&lt;p style=&quot;text-align:center; font-size:11px; color:#fff; font-family:Tahoma,Geneva,sans-serif;&quot;&gt;Indien deze e-mail onleesbaar is, &lt;a href=&quot;http://www.dcm-info.com/[REGIO]/nieuwsbrief/[SEOSUBJECT]/[ID_AESMAIL]&quot; style=&quot;color:#ffffff;&quot;&gt;klik hier&lt;/a&gt;.&lt;/p&gt;&lt;table width=&quot;640&quot; align=&quot;center&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; border=&quot;0&quot;&gt;&lt;tr&gt;&lt;td&gt;</p>
                <p>BODY</p>
                <p>&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;&lt;p style=&quot;text-align:center; font-size:11px; color:#fff; font-family:Tahoma,Geneva,sans-serif;&quot;&gt;Gelieve niet te antwoorden op deze e-mail. Deze e-mail is automatisch verstuurd.&lt;br/&gt; Copyright &amp;copy; 20XX DCM - member of Group De Ceuster - &lt;A href=&quot;http://www.dcm-info.com/&quot;&amp;lcase(vRegio)&amp;&quot;/nieuwsbrief/uitschrijven/[ID_AESMAIL]&quot; style=&quot;color:#fff;&quot;&gt;Uitschrijven&lt;/a&gt;&lt;/p&gt;&lt;img width=&quot;1&quot; height=&quot;1&quot; src=&quot;http://www.dcm-info.com/UserFiles/image/email/tr/[ID_AESMAIL].gif&quot;&gt;&lt;/div&gt;&lt;/body&gt;&lt;/html&gt;</p>-->
                {{ Form::textarea('body', (Input::old('body') ? Input::old('body') : $newsletter->body ), array('class' => 'form-control ckeditor')) }}
              </div>


              {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
              <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>

          </div>
        </div>
      </div>

      {{ Form::close() }}

    </div>

@stop

@section("script")

<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/bootstrap.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckeditor/adapters/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" src="{{ asset('/ckfinder/ckbrowser.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {

	//CKFinder for CKEditor
	CKFinder.setupCKEditor( null, '/ckfinder/' );
	
	//CKEditor
	$("textarea.ckeditor").ckeditor({fullPage : true}); //custom config will be set here enabling the HTML tags

});
</script>

@stop

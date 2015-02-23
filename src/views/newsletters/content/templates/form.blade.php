
    <div class="clearfix">
      <div class="pull-left" style="width:45px">
        <a class="btn btn-default sort-table-row" href=""><i class="fa fa-bars"></i></a>
      </div>
      <div class="pull-left" style="width:50%">
        {{ Form::text('content_name[{ID}]', ((strlen($Content->name)>0) ? $Content->name : "Block#{_ID}"), array('class' => 'form-control')) }}
		    {{ Form::hidden('content_sortid[{ID}]', ((strlen($Content->sort_id)>0) ? $Content->sort_id : "{_ID}")) }}
      </div>
      <div class="pull-right">
        <a class="btn btn-default edit-table-row" href=""><i class="fa fa-pencil"></i></a> 
        <a class="btn btn-default delete-table-row" href=""><i class="fa fa-trash-o"></i></a> 
      </div>
    </div>
    <div class="table-row-sub" style="display:none; margin-top:15px;">
    <!-- content-form -->
    
    	
      <ul role="tablist" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" role="tab" href="#content{ID}">Content</a></li>
        <li class=""><a data-toggle="tab" role="tab" href="#layout{ID}">Layout</a></li>
        {{--<li class=""><a data-toggle="tab" role="tab" href="#style{ID}">Style</a></li>--}}
      </ul>

      <div class="tab-content">

        <div id="content{ID}" class="tab-pane active">
        
          <div class="form-group">
            {{ Form::label('content_title[{ID}]', 'Title') }}
            {{ Form::text('content_title[{ID}]', $Content->title, array('class' => 'form-control'))  }}
          </div>
                                                          
          <div class="form-group">
            {{ Form::label('content_body[{ID}]', 'Body') }}
            {{ Form::textarea('content_body[{ID}]', $Content->body, array('class' => 'form-control ckeditor'))  }}
          </div>
                                                            
          <div class="form-group">
            {{ Form::label('content_image[{ID}]', 'Image') }}
            <div class="input-group">
                {{ Form::text('content_image[{ID}]', $Content->image, array('class' => 'form-control', 'id'=>'content_image{ID}'))  }}
              <span class="input-group-btn">
                {{ Form::button('Browse Server', array('class' => 'btn btn-primary browse-server' , 'id'=>'browse_content_image{ID}'))  }}
              </span>
            </div>
          </div>
                    
          <div class="form-group">
            {{ Form::label('content_link[{ID}]', 'Link') }}
            {{ Form::text('content_link[{ID}]', $Content->link, array('class' => 'form-control'))  }}
          </div>
                                                          
        </div>

        <div id="layout{ID}" class="tab-pane">

          <div class="form-group">
            {{ Form::textarea('content_layout[{ID}]', $Content->layout, array('class' => 'form-control codemirror html'))  }}
          </div>
                                                            
        </div>

        {{--
        <div id="style{ID}" class="tab-pane">

          <div class="form-group">
            {{ Form::label('content_style[{ID}]', 'Style') }}
            {{ Form::textarea('content_style[{ID}]', $Content->style, array('class' => 'form-control codemirror css'))  }}
          </div>
                                                            
        </div>
        --}}

      </div>

    <!-- content-form -->
    </div>

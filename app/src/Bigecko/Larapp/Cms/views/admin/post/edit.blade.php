@extends('grcms::admin.base')

@section('content')
<div class="container">
    <div class="span12">
        {{Form::open(array(
            'method'=>'put',
            'url'=>Config::get('firadmin::route.post').'/'.$post->id,
            'class'=>'form-horizontal')
        )}}
        <fieldset>
            <legend>{{Lang::get('grcms::post.edit_title')}}</legend>

            <!--Title-->
            <div class="control-group">
                <label class="control-label" for="title">{{Lang::get('grcms::post.title')}}</label>

                <div class="controls">
                    <input class="input-large" name="title" type="text" value="{{$post->title}}"/>
                </div>
            </div>

            <!--Sticky-->
            <div class="control-group">
                <label class="control-label" for="sticky">{{Lang::get('grcms::post.sticky')}}</label>

                <div class="controls">
                    {{Form::select('sticky',array(
                    '0'=>Lang::get('grcms::post.no'),
                    '1'=>Lang::get('grcms::post.yes')
                    ),$post->sticky)}}
                </div>
            </div>


            <!--Status-->
            <div class="control-group">
                <label class="control-label" >{{Lang::get('grcms::post.status')}}</label>
                <div class="controls">
                    {{Form::select('status',\Bigecko\Larapp\Cms\Models\Post::$status_list,$post->status)}}
                </div>
            </div>

            <!--Category-->
            <?php
            $categories = array();
            foreach(\Bigecko\Larapp\Cms\Models\Category::all() as $category)
                $categories[$category->id] = $category->name;
            ?>
            <div class="control-group">
                <label class="control-label" >{{Lang::get('grcms::post.category')}}</label>
                <div class="controls">
                    {{Form::select('cate_id',array('0'=>'未分组') + $categories,$post->cate_id)}}
                </div>
            </div>

            <!--Content-->
            <div class="control-group">
                <label class="control-label" >{{Lang::get('grcms::post.content')}}</label>
                <div class="controls">
                    <textarea name="content" rows="10" style="width: 50em;">
                        {{$post->content}}
                    </textarea>
                </div>
            </div>



            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-primary" type="submit">
                        {{Lang::get('grcms::post.save')}}
                    </button>
                    <a data-toggle="modal" action="{{url(Config::get('firadmin::route.post')).'/'.$post->id}}"
                       class="btn-delete btn-danger btn" href="#"
                       msg="{{Lang::get('grcms::post.delete_message')}}">
                        {{Lang::get('grcms::post.delete')}}
                    </a>
                    <a href="javascript:history.back()" class="btn">
                        {{Lang::get('grcms::post.cancel')}}
                    </a>
                </div>
            </div>
    </div>
    </fieldset>
    {{Form::close()}}
</div>
</div>
@stop

<?php

namespace Bigecko\Larapp\Cms\Controllers;

use Bigecko\Larapp\Validators\ValidationException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Bigecko\Larapp\Cms\Models\Post;
use Illuminate\Support\Facades\Auth;


class PostAdminController extends AdminController
{
    public function __construct(){
        parent::__construct();

        $this->beforeFilter('csrf',array('on'=>'put|delete|post'));
    }


    public function index()
    {
        $posts = Post::all()->all();

        usort($posts,function($a,$b){
            if($a->sticky === $b->sticky){
                return $b->created_at->timestamp > $a->created_at->timestamp;
            }else{
                return $b->sticky > $a->sticky;
            }
        });

        $limit = Input::get('limit',10);
        $page = Input::get('page',1);

        $posts = Paginator::make(
            array_slice($posts , ($page - 1) * $limit , $limit),
            count($posts),
            $limit
        );

        return View::make('grcms::admin.post.index',compact("posts"));
    }

    public function create()
    {
        return View::make('grcms::admin.post.create');
    }

    public function store()
    {
        $validator = Validator::make(Input::all(),array('title'=>'required'));
        if($validator->fails()) throw new ValidationException('Create Post fail',$validator);

        Post::create(array(
            'user_id'=>Auth::user()->id,
            'title'=>Input::get('title'),
            'content'=>Input::get('content'),
            'cate_id'=>Input::get('cate_id'),
            'sticky'=>Input::get('sticky'),
            'status'=>Input::get('status'),
        ));

        return \Redirect::to(Config::get('firadmin::route.post'));
    }

    public function show($post)
    {
        return View::make('grcms::admin.post.show',compact('post'));
    }

    public function edit($post)
    {
        return View::make('grcms::admin.post.edit',compact('post'));
    }

    public function update($post)
    {
        $validator = Validator::make(Input::all(),array('title'=>'required'));
        if($validator->fails()) throw new ValidationException('Update post fail',$validator);

        $post->title = Input::get('title');
        $post->content = Input::get('content');
        $post->sticky = Input::get('sticky');
        $post->status = Input::get('status');
        $post->cate_id = Input::get('cate_id');

        $post->save();

        return \Redirect::to(Config::get('firadmin::route.post'));
    }

    public function destroy($post)
    {
        $post->delete();
        return \Redirect::to(Config::get('firadmin::route.post'));
    }
}
<?php namespace Bigecko\Larapp\Cms\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Bigecko\Larapp\Cms\Models\Category;
use Illuminate\Support\Facades\Input;

class CategoryAdminController extends AdminController
{

   public function index()
   {
       $parentId = Input::get('parentId', 0);
       $level = Input::get('level', 0);
       $all = Category::where('parent_id',$parentId)->paginate(3);
       return View::make('grcms::admin.category.index', array('all' => $all, 'parentId'=>$parentId, 'level'=>$level));
   }

   public function destroy($post)
   {
       $post->delete();
       return Redirect::back();
   }

/*    public function show()
    {
        return 'asdfasd';
    }*/

    public function create()
    {
        $parentId = Input::get('parentId', 0);
        $level = Input::get('level', 0);
        return View::make('grcms::admin.category.create', array('parent_id'=>$parentId, 'level'=>$level));
    }
    public function store()
    {
        $cateName = Input::get('cateName');
        $parentId = Input::get('parent_id');
        $weight = Input::get('weight');
        $level = Input::get('level');
        $thisLevel = ++$level;
        $post = new Category;
        $post->name = $cateName;
        $post->parent_id = $parentId;
        $post->weight= $weight;
        $post->level = $thisLevel;
        $post->save();
        return Redirect::to("admin/category?parentId=$parentId&level=$thisLevel");
    }

    public function edit($post)
    {
//        $id = Input::get('id');
//        $val = Category::find($id);
        return View::make('grcms::admin.category.edit', array('val'=>$post));
    }

    public function update ($post)
    {
/*        $id = Input::get('id');
        $name = Input::get('cateName');
        $post = Category::find($id);*/
        $name = Input::get('cateName');
        $post->name = $name;
        $post->save();
        return Redirect::to('admin/category');
    }

} 
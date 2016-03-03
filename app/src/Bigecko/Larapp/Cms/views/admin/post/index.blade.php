@extends('grcms::admin.base')

@section('content')
<div class="container">

    <div class="row-fluid">
        <div class="span12">
            <h3>{{Lang::get('grcms::post.post')}}<h3>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <a href="{{ URL::to(Config::get('firadmin::route.post') . '/create') }}" class="btn btn-primary pull-right">
                <span class="icon-white icon-plus"></span> {{Lang::get('grcms::post.create')}}
            </a>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span12">

            <table class="table" style="font-size: 14px;">
                <thead>
                <tr>
                    <th>{{Lang::get('grcms::post.id')}}</th>
                    <th>{{Lang::get('grcms::post.title')}}</th>
                    <th>{{Lang::get('grcms::post.user_id')}}</th>
                    <th>{{Lang::get('grcms::post.category')}}</th>
                    <th>{{Lang::get('grcms::post.status')}}</th>
                    <th>{{Lang::get('grcms::post.created_at')}}</th>
                    <th>{{Lang::get('grcms::post.updated_at')}}</th>
                    <th>{{Lang::get('grcms::post.sticky')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{$post->id}}</td>
                        <td><a href="{{URL::to(Config::get('firadmin::route.post')).'/'.$post->id}}">{{$post->title}}</a></td>
                        <td>{{$post->user_id}}</td>
                        <td>{{$post->category ? $post->category->name : "NULL"}}</td>
                        <td>{{\Bigecko\Larapp\Cms\Models\Post::$status_list[$post->status]}}</td>
                        <td>{{$post->created_at}}</td>
                        <td>{{$post->updated_at}}</td>
                        <td>{{$post->sticky == 1 ? Lang::get('grcms::post.yes') : Lang::get('grcms::post.no')}}</td>
                        <td>
                            <span>
                            <a href="{{URL::to(Config::get('firadmin::route.post')).'/'.$post->id.'/edit'}}" class="btn-info btn">
                                {{Lang::get('grcms::post.edit')}}
                            </a>
                            </span>
                            <span>
                                <a data-toggle="modal" action="{{url(Config::get('firadmin::route.post')).'/'.$post->id}}"
                                   class="btn-delete btn-danger btn" href="#" msg="{{Lang::get('grcms::post.delete_message')}}">
                                    {{Lang::get('grcms::post.delete')}}
                                </a>
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination text-center">
                {{$posts->links()}}
            </div>
        </div>
    </div>
</div>

@stop

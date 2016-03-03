@extends('grcms::admin.base')

@section('content')
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>{{Lang::get('grcms::post.key')}}</th>
                <th>{{Lang::get('grcms::post.value')}}</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td><strong>{{Lang::get('grcms::post.title')}}</strong></td>
                <td>{{$post->title}}</td>
            </tr>
            <tr>
                <td><strong>{{Lang::get('grcms::post.content')}}</strong></td>
                <td>{{$post->content}}</td>
            </tr>
        </tbody>

    </table>
</div>
@stop

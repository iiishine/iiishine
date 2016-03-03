@extends('grcms::admin.base')
@section('content')
<div class="create">
    <h3 class="title" style="margin-bottom: 20px;">创建新列表</h3>
    {{Form::open(array('url'=>'admin/category', 'method'=>'post', 'class'=>'form-horizontal', )) }}
    <div class="control-group">
        <label class="control-label" for="inputEmail" style="float: left;">分类名称：</label>
        <div class="controls">
            {{ Form::hidden('parent_id',"$parent_id") }}
            {{ Form::hidden('level',"$level") }}
            {{ Form::text('cateName') }}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmail" style="float: left;">权重：</label>
        <div class="controls">
            {{ Form::text('weight') }}
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            {{ Form::submit('创建', array('class'=>'btn')) }}
            <a class="btn" href="javascript:window.history.back()">取消</a>
        </div>
    </div>
    {{Form::close()}}
</div>

@stop
@extends('grcms::admin.base')

@section('content')
<div class="create">
    <div class="title btn btn-info" style="margin-bottom: 20px;">修改活动</div>
    {{Form::open(array('url'=>"admin/category/$val->id", 'method'=>'PUT', 'class'=>'form-horizontal')) }}
    <div class="control-group">
        <label class="control-label" for="inputEmail" style="float: left;">活动名称：</label>
        <div class="controls">
            {{ Form::text('cateName', "$val->name") }}
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
            {{ Form::submit('修改', array('class'=>'btn')) }}
            <a class="btn" href="javascript:window.history.back()">取消</a>
        </div>
    </div>
    {{Form::close()}}
</div>
@stop
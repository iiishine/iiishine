@extends('firadmin::layout')

@section('body')
<div class="container">
    @yield('content')
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">确认删除</h3>
    </div>
    <div class="modal-body">
        <p></p>
    </div>
    <div class="modal-footer">
        {{ Form::open(array('method'=>'delete')) }}
        <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
        {{ Form::submit('确定' ,array('class'=>'btn btn-primary subD')) }}
        {{ Form::close() }}
    </div>
</div>

</div>
@stop
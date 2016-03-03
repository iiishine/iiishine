@extends('grcms::admin.base')

@section('content')
<div class="cate">
    <h3 class="left">{{ Lang::get('grcms::category.listName' )}}</h3>
    <div class="right"><a href="{{ url('admin/category/create?parentId='.$parentId.'&level='.$level) }}" class="btn btn-primary">{{ Lang::get('grcms::category.addName' )}}</a></div>
</div>
<div class="cateMain">
    <table class="table">
        <thead>
        <tr>
            <th>{{ Lang::get('grcms::category.name' )}}</th>
            <th>{{ Lang::get('grcms::category.create_at' )}}</th>
            <th>{{ Lang::get('grcms::category.update_at' )}}</th>
            <th>子分类</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($all as $value):?>
            <tr>
                <td>{{ $value->name}}</td>
                <td>{{ $value->created_at }}</td>
                <td>{{ $value->updated_at }}</td>
                <td><a href="{{ url('admin/category?parentId=' .$value->id .'&level='. $level) }}">查看子分类</a></td>
                <td>
                    <span><a href="{{ url('admin/category/' . $value->id . '/edit') }}" class="btn btn-info">修改</a></span>
                    <span>
                        <a action="{{ url('admin/category/' . $value->id) }}" href="#" data-toggle="modal"
                           class="btn-delete btn-danger btn" msg="确定要删除这个分类？">删除</a>
                    </span>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="pagination"><?php echo $all ->links();?></div>
</div>
@stop

@section('scripts')
@parent
@stop

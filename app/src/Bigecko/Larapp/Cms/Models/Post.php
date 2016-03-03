<?php

namespace Bigecko\Larapp\Cms\Models;

use Eloquent;

class Post extends Eloquent {

    protected $table = 'posts';

    public static $status_list = array(
        0 => '未发布',
        1 => '已发布',
        2 => '归档',
    );

    protected $guarded = array();


    /* Relationship */
    public function user() {
        return $this->belongsTo('User','user_id');
    }

    public function category(){
        return $this->belongsTo('Bigecko\Larapp\Cms\Models\Category','cate_id');
    }

}

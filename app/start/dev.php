<?php

// weinre 设置，可以通过url加ww参数，设置weinre的id，
// 如果ww参数的值为off，则表示关闭weinre调试，不会在页面里引入weinre的js。
if (Input::has('ww')) {
    $weinre = Input::get('ww');
    if ($weinre == 'off') {
        Session::remove('weinre');
    }
    else {
        Session::put('weinre', $weinre);
    }
}

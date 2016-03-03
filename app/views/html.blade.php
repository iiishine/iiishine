<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="HandheldFriendly" content="True">
    @section('metas')
      <meta name="apple-touch-fullscreen" content="yes" />
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <meta name="apple-mobile-web-app-status-bar-style" content="black" />
      <meta name="format-detection" content="telephone=no" />
      <meta name="viewport" content="target-densitydpi=320,width=750,user-scalable=no" />
    @show
    <title>@yield('title')</title>
    @section('styles')
    {{ HTML::style('css/normalize.css') }}
    @show
  </head>
  <body class="page-{{{ str_replace('/', '-', Input::path()) }}}">
      @yield('body')
    @section('scripts')
    <!--[if lt IE 9]>
      {{ HTML::script('vendor/jquery-1.11.1.min.js') }}
    <![endif]-->
    <!--[if gte IE 9]><!-->
      {{ HTML::script('vendor/jquery-2.1.1.min.js') }}
    <!--<![endif]-->
    <script>{{ JS::renderObj('app') }}</script>
    {{ HTML::script('js/larapp.js') }}
    {{ JS::renderScripts() }}
    <script type="text/javascript" charset="utf-8">
      $(function() {
          app.setupClickTrack();
      });
    </script>
    @show

  <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
  <script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $wechatJs->config(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false, true) ?>);
    wx.ready(function() {
      var shareData = {
        title: '{{ event_text('wechat_share_title') }}',
        desc: '{{ event_text('wechat_share_description') }}',
        link: '{{ url('') }}',
        imgUrl: '{{ $shareres->mobile->image }}'
      };

      shareData.success = function() {
        window.location.href = '{{ url('prize/share') }}';
      };

      wx.onMenuShareAppMessage(shareData);
      wx.onMenuShareTimeline(shareData);
    });
  </script>

    {{ $trackcode }}
    @if(Session::has('weinre'))
      <script src="http://case.bigecko.com:9678/target/target-script-min.js#{{ Session::get('weinre') }}"></script>
    @endif
  </body>
</html>

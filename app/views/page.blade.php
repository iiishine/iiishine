@extends('html')

@section('styles')
  @parent
  {{ HTML::style('css/style.css') }}
@stop

@section('title')
  {{{ event_text('page_title') }}}
@stop

@section('body')
  <div class="wrap">
     <div class="nav">
       <a href="#" id="btnRules" class="rule">{{ event_text('btn_rules') }}</a>
       <a href="http://gkx.me/bquMnq" class="yh">{{ event_text('btn_merchants') }}</a>
     </div>
    <div class="logo"><img src="{{ asset('images/logo.png') }}" alt=""></div>
  </div>

  <div class="global_main">
    @yield('content')
  </div>

  <div id="rulesPopup" class="tc">
    <div class="tcmain">
      <a href="#" id="btnRulesClose" class="close"><img src="{{ asset('images/close.png') }}" alt=""></a>
      <div class="srule">{{ event_text('rules_body') }}</div>
    </div>
  </div>

  <div id="sharePopup" class="tc2">
    <div class="tcmain"></div>
  </div>
@stop

@section('scripts')
  @parent
  <script type="text/javascript" charset="utf-8">
    $('.btn-share').click(function(event) {
      event.preventDefault();
      $('#sharePopup').show();
    });
    $('#sharePopup').on('click', function() {
      $(this).hide();
    });

    $('#btnRules').click(function(event) {
      event.preventDefault();
      $('#rulesPopup').show();
      $('.logo').css('position','fixed');
    });
    $('#btnRulesClose').click(function(event) {
      event.preventDefault();
      $('#rulesPopup').hide();
      $('.logo').css('position','absolute');
    });

  </script>
@stop

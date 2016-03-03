@extends('page')

@section('content')
  <div class="main">
    <div class="mb1">
      <div class="imgbox">
        @if(Input::get('tip') == '1')
          <img class="title" src="{{ asset('images/jihui.png') }}" alt="">
        @endif
        <img src="{{ asset('images/ewm.jpg') }}" alt="">
        <img class="explain" src="{{ event_text('main_merchant_pic') }}" alt="">
      </div>
      <div class="giftbox clearfix">
        <div class="img"><img src="{{event_text('first_merchant_image')}}" alt=""></div>
        <div class="con">
          <p class="t1">{{ event_text('first_merchant_title') }}</p>
          <p class="t2">{{ event_text('first_merchant_desc') }}</p>
          <p><a class="t3" href="{{ event_text('first_merchant_link') }}" class="btn">{{ event_text('btn_goto_shopping') }}</a></p>
        </div>
      </div>
    </div>
    <div class="mb2">
      <div class="giftbox clearfix">
        <div class="img"><img src="{{event_text('picSrc_dazzling_cafe')}}" alt=""></div>
        <div class="con">
          <p class="t1">{{ event_text('title_dazzling_cafe') }}</p>
          <p class="t2">{{ event_text('list_dazzling_cafe') }}</p>
          <p><a class="t3" href="{{ event_text('href_dazzling_cafe') }}" class="btn">{{ event_text('btn_goto_shopping') }}</a></p>
        </div>
      </div>
      <div class="giftbox clearfix">
        <div class="img"><img src="{{event_text('picSrc_pushi')}}" alt=""></div>
        <div class="con">
          <p class="t1">{{ event_text('title_pushi') }}</p>
          <p class="t2">{{ event_text('list_pushi') }}</p>
          <p><a class="t3" href="{{ event_text('href_pushi') }}" class="btn">{{ event_text('btn_goto_shopping') }}</a></p>
        </div>
      </div>
      <div class="giftbox clearfix">
        <div class="img"><img src="{{event_text('picSrc_panzinvrenfang')}}" alt=""></div>
        <div class="con">
          <p class="t1">{{ event_text('title_panzinvrenfang') }}</p>
          <p class="t2">{{ event_text('list_panzinvrenfang') }}</p>
          <p><a class="t3" href="{{ event_text('href_panzinvrenfang') }}" class="btn">{{ event_text('btn_goto_shopping') }}</a></p>
        </div>
      </div>
      <div class="giftbox clearfix">
        <div class="img"><img src="{{event_text('picSrc_aimi1895')}}" alt=""></div>
        <div class="con">
          <p class="t1">{{ event_text('title_aimi1895') }}</p>
          <p class="t2">{{ event_text('list_aimi1895') }}</p>
          <p><a class="t3" href="{{ event_text('href_aimi1895') }}" class="btn">{{ event_text('btn_goto_shopping') }}</a></p>
        </div>
      </div>
      <div class="btn"><a href="{{ event_text('href_more_merchant_offers') }}" style="color: #ff2e4b;">{{ event_text('btn_more_merchant_offers') }}</a></div>
    </div>
  </div>
@stop

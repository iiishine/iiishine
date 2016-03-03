@extends('page')

@section('content')
<div class="login-bg">
  <div class="loginForm">
    {{ Form::open(array('url' => 'auth/login', 'id' => 'smscode-box', 'class' => 'wmain')) }}
    <div class="row list">
      {{ Form::input('tel', 'phone', null, array('id' => 'phoneInput', 'placeholder' => event_text('login_mphone_label'))) }}
    </div>

    <div class="list clearfix">
      {{Form::input('text', 'captcha', null, array('id' => 'captchaInput', 'class'=>'textn', 'placeholder' => event_text('login_img_label')))}}
      <a style="float: right" class="yzm" href="#" id="captcha" btnName="图形验证码">{{HTML::image(Captcha::img(), '图形验证码')}}</a>
    </div>

    <div class="list clearfix">
      {{ Form::text('sms_code', null, array('id' => 'smsVerifyInput', 'class'=> 'textn', 'placeholder' => event_text('login_pwd_label'))) }}
      <button type="button" class="yzm btn-send">
        获取语音验证码
        <img src="{{asset('images/ajax-loader.gif')}}" style="display: none" alt=""/>
      </button>
    </div>

    <button btnName="提交按钮" type="submit" class="msy">{{ event_text('login_submit_text') }}</button>
    {{ Form::close() }}
  </div>
</div>
@stop

@section('scripts')
  @parent
  {{ HTML::script('js/smsverify.js') }}
  <script>
    ;(function($){
      $(function(){
        $('#captcha').click(function (event) {
          event.preventDefault();
          var $this = $(this);
          var img = $this.find('img');
          img.attr('src', '{{ Captcha::img() }}');
        });
      });
    })(jQuery);
    @if (!$errors->isEmpty())
    alert('{{{ $errors->first() }}}');
    @endif

    @if (Session::has('errorMsg'))
    alert('{{{ Session::get('errorMsg') }}}');
    @endif
  </script>
@stop

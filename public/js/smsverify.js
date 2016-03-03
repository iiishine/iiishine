;(function($) {

    var tid = null;
    var count = 60;

    function setSmsTimer() {
        clearInterval(tid);

        var smsbox = $('#smscode-box');
        var msgbox = smsbox.find('.sms-msg');
        var msgbox1 = smsbox.find('.btn-send');

        tid = setInterval(function() {
            count--;
            if (count < 1) {
                clearInterval(tid);
                msgbox1.text('发送语音验证码');
                $('#smscode-box .btn-send').removeClass('disabled');
                count = 60;
            }
            else {
                msgbox.text('验证码已发送 ('+count+'秒后可再次请求)');
            }
        }, 1000);
    }

    function sendSmsCode() {
        var smsbox = $('#smscode-box');
        smsbox.find('.error').remove();

        var msgbox = smsbox.find('.btn-send');

        var phoneNumber = $('#phoneInput').val();

        var data = {
            phone: phoneNumber,
            captcha: $('#captchaInput').val()
        };

        $.post(app.url('smsapi/send-verifier-code'), data, 'json')
            .done(function(data) {
                if (data.code == 200 && data.status == 'OK') {
                    msgbox.find('img').hide();
                    msgbox.text('验证码已发送');
                    $('#smscode-box .btn-send').addClass('disabled');
                    setSmsTimer();
                    alert(data.smsmsg);
                }
                else if (data.smsmsg) {
                    alert(data.smsmsg);
                    msgbox.find('img').hide();
                }
                else if (data.msg) {
                    alert(data.msg);
                    msgbox.find('img').hide();
                }
                else {
                    alert('短信发送失败，请联系客服');
                    msgbox.find('img').hide();
                }
            }).error(function(){
            alert('短信发送失败，请联系客服');
            msgbox.find('img').hide();
        });
    }

    $('#smscode-box').on('click', '.btn-send', function(event) {
        event.preventDefault();
        var $this = $(this);
        if ($this.hasClass('disabled')) {
            return;
        }
        $this.find('img').show();

        sendSmsCode();
    });

})(jQuery);

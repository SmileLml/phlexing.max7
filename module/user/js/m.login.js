$(document).ready(function()
{
    $('#loginForm').submit(function()
    {
        var account          = $('#account').val().trim();
        var password         = $('#password').val().trim();
        var passwordStrength = computePasswordStrength(password);

        var hasMD5    = typeof(md5) == 'function';
        var link      = createLink('user', 'login');
        var keepLogin = $('[name=keepLogin]').prop('checked') ? 1 : 0;
        var captcha   = $('#captcha').length == 1 ? $('#captcha').val() : '';

        $.get(createLink('user', 'refreshRandom'), function(data)
        {
            var rand = data;
            $.ajax
            ({
                url: link,
                dataType: 'json',
                method: 'POST',
                data:
                {
                    "account": account,
                    "password": hasMD5 ? md5(md5(password) + rand) : password,
                    'passwordStrength' : passwordStrength,
                    'verifyRand' : rand,
                    'keepLogin' : keepLogin,
                    'captcha' : captcha
                },
                success:function(data)
                {
                    if(data.result == 'fail')
                    {
                        alert(data.message);
                        if($('.captchaBox').length == 1) $('.captchaBox img').click();
                        return false;
                    }

                    location.href = data.locate;
                }
            })
        });

        return false;
    });

    /**
     *  Refresh captcha.
     */
    $('.captchaBox img').click(function()
    {
        var captchaLink = createLink('misc', 'captcha', "sessionVar=captcha");
        captchaLink += captchaLink.indexOf('?') < 0 ? '?' : '&';
        captchaLink += 's=' + Math.random();

        $(this).attr('src', captchaLink);
    })
});

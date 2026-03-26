$(function()
{
    var focused = false;
    $('.detail-action').on('click', function ()
    {
        if(!focused)
        {
            $('body').addClass('focused');
            $('.side-col').hide()
            focused = true;
        }
        else
        {
            $('body').removeClass('focused');
            $('.side-col').show()
            focused = false;
        }
    });
    $('body').addClass('fullscreen');

    $('#mainMenu .fullscreen-btn').click(function()
    {
        $('.side-col').toggleClass('hidden');
        $('body').toggleClass('fullscreen');
        if($('body').hasClass('fullscreen'))
        {
            $('#mainMenu .fullscreen-btn').attr('title', retrack);
            $('#mainMenu .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + retrack);
        }
        else
        {
            $('#mainMenu .fullscreen-btn').attr('title', fullscreen);
            $('#mainMenu .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + fullscreen);
        }
    });

    $(document).on('click', '#finishButton', function()
    {
        $.get(createLink('traincourse', 'ajaxFinishChapter', "chapter=" + chapterID + "&course=" + courseID), function(data)
        {
            data = $.parseJSON(data);
            if(data.result == false)
            {
                if(data.locate  != undefined) location.href = data.locate;
                if(data.message != undefined) alert(data.message);
            }
            else
            {
                location.href = data.locate;
            }
        })
    });
    parent.$(parent.document).on('beforereloadapp', function()
    {
        beforeAppReload();
    });
})

/** Callback before app loading */
function beforeAppReload()
{
    /* Fix video stream blocking */
    $('video').each(function()
    {
        var $video      = $(this);
        var src         = $video.prop('src');
        var currentTime = $video[0].currentTime;
        $video[0].pause();
        $video.prop('src', '');
        $video.find('source').remove();
        $video[0].load();
        $video.prop('src', src);
        $video[0].currentTime = currentTime;
        $video[0].load();
    });
}

function showError()
{
    $('.playfailed').show();
}

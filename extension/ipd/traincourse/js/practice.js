$(function()
{
    $.get(createLink('traincourse', 'ajaxupdatepractice'));

    if(!localStorage.getItem('practiceCount'))
    {
        setTimeout(function() {getData();}, 5000);
    }

    $('.block-all-practice').on('click', ".update-practice", function()
    {
        if(confirm(updatePracticeTip))
        {
            $.get(createLink('traincourse', 'updatepractice'), function(response)
            {
                if(JSON.parse(response).result === 'success') location.reload();
            });
        }
    });

    function getData()
    {
        $.get(createLink('traincourse', 'ajaxupdatepractice'), function(response)
        {
            response = JSON.parse(response);
            if(response.result === 'success' && response.count > 0 && localStorage.getItem('practiceCount') != response.count)
            {
                localStorage.setItem('practiceCount', response.count);
                $(".practice-data").load(location.href + ' .practice-data');
                setTimeout(function() {getData();}, 3000);
            }
        });
    }
});

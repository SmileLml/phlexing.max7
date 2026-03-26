$(function()
{
    if(firstModuleID > 0) $('#module' + firstModuleID).closest('li').addClass('active open in');
    if(secondModuleID > 0) $('#module' + secondModuleID).closest('li').addClass('active');

    $(".update-practice").click(function()
    {
        if(confirm(updatePracticeTip))
        {
            $.get(createLink('traincourse', 'updatepractice'), function(response)
            {
                if(JSON.parse(response).result === 'success') location.reload();
            });
        }
    });
});

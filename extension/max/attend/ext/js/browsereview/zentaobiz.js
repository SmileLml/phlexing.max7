$(document).ready(function()
{
    $(document).off('click', '.pass');
    $(document).off('click', '.reject');

    $('a.pass').each(function()
    {
        href = $(this).attr('href');
        $(this).attr('href', '###').attr('data-href', href);
    });
    $(document).on('click', '.pass', function()
    {
        if(confirm(confirmReview.pass))
        {
            var selecter = $(this);

            $.getJSON(selecter.attr('data-href'), function(data)
            {
                if(data.result == 'success')
                {
                    if(selecter.parents('#ajaxModal').size()) return $.reloadAjaxModal(1200);
                    if(data.locate) return location.href = data.locate;
                    return location.reload();
                }
                else
                {
                    alert(data.message);
                    return location.reload();
                }
            });
        }
        return false;
    });

    $('a.reject').each(function()
    {
        href = $(this).attr('href');
        $(this).attr('href', '###').attr('data-href', href);
    });
    $(document).on('click', '.reject', function()
    {
        if(confirm(confirmReview.reject))
        {
            var selecter = $(this);

            $.getJSON(selecter.attr('data-href'), function(data)
            {
                if(data.result == 'success')
                {
                    if(selecter.parents('#ajaxModal').size()) return $.reloadAjaxModal(1200);
                    if(data.locate) return location.href = data.locate;
                    return location.reload();
                }
                else
                {
                    alert(data.message);
                    return location.reload();
                }
            });
        }
        return false;
    });
});

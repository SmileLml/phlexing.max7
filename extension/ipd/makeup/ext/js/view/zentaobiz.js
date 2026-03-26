$(function()
{
    $('.page-actions a.loadInModal').each(function()
    {
        var href = $(this).attr('href');
        if(href.indexOf('onlybody=yes') < 0) href = href + (href.indexOf('?') > 0 ? '&' : '?') + 'onlybody=yes';
        $(this).removeAttr('href').attr('data-rel', href);
    });

    $(document).off('click', '.deleteMakeup');
    $(document).off('click', '.reviewPass');

    href = $('a.deleteMakeup').attr('href');
    $('a.deleteMakeup').attr('href', '###').attr('data-href', href);
    $(document).on('click', '.deleteMakeup', function()
    {
        if(confirm(lang.confirmDelete))
        {
            $(this).text(lang.deleting);
            $.getJSON($(this).attr('data-href'), function(data)
            {
                if(data.result == 'success')
                {
                    if(data.locate) return location.href = data.locate;
                    return location.reload();
                }
                else
                {
                    alert(data.message);
                    if(selecter.parents('#ajaxModal').size()) return $.reloadAjaxModal(1200);
                    return location.reload();
                }
            });
        }
        return false;
    });

    href = $('a.reviewPass').attr('href');
    $('a.reviewPass').attr('href', '###').attr('data-href', href);
    $(document).on('click', '.reviewPass', function()
    {
        if(confirm(confirmReview.pass))
        {
            var selecter = $(this);

            $.getJSON(selecter.attr('data-href'), function(data)
            {
                if(data.result == 'success')
                {
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
})

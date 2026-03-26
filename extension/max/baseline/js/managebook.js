$(document).ready(function()
{
    $('select[name*="type"]').change(function()
    {
        if($(this).val() == 'article')
        {
            $(this).closest('tr').find("select[name*='chapterType']").attr('disabled', true);
        }
        else
        {
            $(this).closest('tr').find("select[name*='chapterType']").attr('disabled', false);
        }
    })

    $('select[name*="type"]').each(function()
    {
        if($(this).val() == 'article')
        {
            $(this).closest('tr').find("select[name*='chapterType']").attr('disabled', true);
        }
        else
        {
            $(this).closest('tr').find("select[name*='chapterType']").attr('disabled', false);
        }
    })
   /* Sort up. */
    $(document).on('click', '.icon-arrow-up', function()
    {
        $(this).parents('tr').prev().before($(this).parents('tr')); 
        $('tr .order').each(function(index,obj){$(this).val(index + 1);});
    });

    /* Sort down. */
    $(document).on('click', '.icon-arrow-down', function()
    { 
        var hasNext = $(this).parents('tr').next().find('.icon-arrow-down').size() > 0;
        if(hasNext)
        {
            $(this).parents('tr').next().after($(this).parents('tr')); 
            $('tr .order').each(function(index,obj){$(this).val(index + 1);});
        }
    });

    $('tr .order').each(function(index,obj){$(this).val(index + 1);});

    var setCatalogKey = function()
    {
        maxID = maxID
        $('[value=new]').each(function()
        {
            maxID ++;
            $(this).parents('.node').find('[id*=type]').attr('name', 'type[' + maxID + ']');
            $(this).parents('.node').find('[id*=chapterType]').attr('name', 'chapterType[' + maxID + ']');
            $(this).parents('.node').find('[id*=title]').attr('name', 'title[' + maxID + ']');
            $(this).parents('.node').find('[id*=keywords]').attr('name', 'keywords[' + maxID + ']');
            $(this).parents('.node').find('[id*=order]').attr('name', 'order[' + maxID + ']');
            $(this).attr('name', 'mode[' + maxID + ']');
        })
    }

    setCatalogKey();
});

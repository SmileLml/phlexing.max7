$(document).ready(function()
{
    $('#mainNavbar li').removeClass('active');

    $('#mainNavbar > .container > nav > .navbar-nav > li').find('a[href*=browseFlow]').parent('li').addClass('active');

    if(window.status) $('#menu > .container > .nav > li').removeClass('active').find('a[href*=browseFlow][href*=' + window.status + ']').parent().addClass('active');

    $('.flow-toggle').click(function()
    {
        var obj = $(this).find('i');
        if(obj.hasClass('icon-plus'))
        {
            obj.parents('tr').next('tr').show();
            obj.removeClass('icon-plus').addClass('icon-minus');
        }
        else if(obj.hasClass('icon-minus'))
        {
            obj.parents('tr').next('tr').hide();
            obj.removeClass('icon-minus').addClass('icon-plus');
        }
        return false;
    });

    $('a.mode-toggle').click(function()
    {
        var mode = $(this).data('mode');
        $('a.mode-toggle').removeClass('active').find('i').removeClass('text-primary');
        $(this).addClass('active').find('i').addClass('text-primary');
        $('#cardMode, #listMode').hide();
        $('#' + mode + 'Mode').show();
        $('#cardMode').next().toggle(mode == 'card');
        $.cookie('flowViewType', mode, {path: "/"});
    })

    var type = $.cookie('flowViewType');
    if(typeof(type) == 'undefined' || type == '') type = 'card';
    $('#menuActions a[data-mode=' + type +']').click();

    $(document).off('click', '.deactivater');
    $('.deactivater').click(function()
    {
        const url = $(this).attr('href');
        bootbox.confirm(window.confirmToDeactivate, function(result)
        {
            if(!result) return;

            $.getJSON(url, function(data)
            {
                if(data.result == 'fail') bootbox.alert(data.message);

                return location.reload();
            })
        });
        return false;
    })

    $(document).off('click', '.activater');
    $('.activater').click(function()
    {
        var reload = $(this);
        $.getJSON(reload.attr('href'), function(data)
        {
            if(data.result == 'fail') bootbox.alert(data.message);
            return location.reload();
        });

        return false;
    })

    $.setAjaxForm('#deleteForm');

    $(document).on('click', '.delete-btn', function()
    {
        var id = $(this).data('id');
        $('#deleteForm').attr('action', createLink('workflow', 'delete', 'id=' + id));
    })
});

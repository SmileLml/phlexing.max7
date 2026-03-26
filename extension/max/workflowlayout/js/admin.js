$(function()
{
    if(config.requestType == 'GET') $('.treeview li a[href$="=' + window.action + '"]').parent('li').addClass('active');
    if(config.requestType == 'PATH_INFO') $('.treeview li a[href$="-' + window.action + '.html"]').parent('li').addClass('active');

    $('#triggerModal').addClass('layout-modal').attr('data-action', window.action);

    var $trs = $('tr');
    $trs.filter('.fixed-enabled').appendTo('#fixedEnabled');
    $trs.filter('.fixed-required').appendTo('#fixedRequired');

    $('.table-origin').remove();

    $requireTrs = $('#fixedRequired tr');
    $enableTrs  = $('#fixedEnabled tr');
    if(!$requireTrs.length) $('#fixedRequired').hide();

    if($enableTrs.length < 2)
    {
        $('#fixedEnabled').addClass('sort-disabled');
        $enableTrs.find('input[name*=width]').val('auto').attr('disabled', 'disabled');
    }
    else
    {
        $('#fixedEnabled').sortable({trigger: '.icon-move', selector: 'tr'});
    }

    $('#reversechecker').click(function()
    {
        $('.table-layout').find('tr').each(function()
        {
            if($(this).hasClass('head')) return;//exclude main table and sub table.
            if($(this).hasClass('disabled'))
            {
                $(this).removeClass('disabled').find('input[name*=show]').val('1');
            }
            else
            {
                $(this).addClass('disabled').find('input[name*=show]').val('0');
            }
        });
    });

    $(document).on('click', '#allchecker', function()
    {
        $('.table-layout').find('tr').removeClass('disabled').find('input[name*=show]').val('1');
    });

    /**
     * Show or hide a row.
     */
    $('#adminLayoutForm .table-layout').on('click', 'tr:not(.required, :first-child) .show-hide, tr:not(.required, :first-child) .title', function()
    {
        var $tr = $(this).closest('tr');

        $tr.toggleClass('disabled');
        if($tr.hasClass('disabled'))  $tr.find('input[name*=show]').val('0');
        if(!$tr.hasClass('disabled')) $tr.find('input[name*=show]').val('1');

        if($tr.data('child') != undefined)
        {
            var child = '.child-' + $tr.data('child');
            $(child).toggleClass('disabled', $tr.hasClass('disabled'));
            $(child).find('thead th').click();
            $(child).find('tr').toggleClass('disabled', $tr.hasClass('disabled'));
            $(child).find('tr').find('input[name*=show]').val($tr.hasClass('disabled') ? '0' : '1');
        }

        if(window.method != 'browse' && window.method != 'view')
        {
            if($tr.data('field') == 'subStatus' && !$tr.hasClass('disabled'))
            {
                var $statusTr = $(this).parents('.table-layout').find('tr[data-field=status]');

                $tr.before($statusTr);

                if($statusTr.hasClass('disabled'))
                {
                    $statusTr.removeClass('disabled');
                    $statusTr.find('input[name*=show]').val('1');
                }
            }
        }
    });

    $('.child').each(function()
    {
        var module = '.module-' + $(this).data('module');
        $(this).toggleClass('disabled', $(module).hasClass('disabled'));
    })

    $('input[name*=custom]').change(function()
    {
        let control = $(this).parents('tr').data('control');
        if(control == 'file') return false;

        var $group = $(this).parents('.input-group');
        if($(this).prop('checked'))
        {
            $group.find(('select[name*=defaultValue]')).attr('disabled', true).hide();
            $group.find(('input[name*=defaultValue]')).attr('disabled', false).show();
            $group.find('[id^=defaultValue][id$=chosen]').hide();
            $group.find('.picker').hide();
        }
        else
        {
            $group.find(('select[name*=defaultValue]')).attr('disabled', false);
            $group.find(('input[name*=defaultValue]')).attr('disabled', true).hide();
            $group.find('[id^=defaultValue][id$=chosen]').show();
            $group.find('.picker').show();
        }

        if(window.mode == 'view' || control == '')
        {
            $group.find(('select[name*=defaultValue]')).attr('disabled', true);
            $group.find(('input[name*=defaultValue]')).attr('disabled', true);
        }
    })

    $('.fixed-required').find('input:not([name*=width], [name*=show]), select').attr('disabled', true);

    $('.child').sortable({trigger: '.icon-move', selector: 'tr'});

    $('.child').on('click', 'tr:first-child', function()
    {
        if(window.mode == 'view') return false;

        $(this).toggleClass('disabled');

        if($(this).hasClass('disabled'))  $(this).parents('.table-layout').find('tr:not(:first-child)').hide().find('input[name*=show]').val('0');
        if(!$(this).hasClass('disabled')) $(this).parents('.table-layout').find('tr:not(:first-child)').show().find('input[name*=show]').val('1');
    });

    $.setAjaxForm('#adminLayoutForm', function(response)
    {
        if(response.result == 'success')
        {
            setTimeout(function()
            {
                $('#triggerModal').load(response.locate, function(){ $.zui.ajustModalPosition(); });
            }, 1200);
        }
    });

    $('input[name*=custom]').change();
});

$(document).ready(function()
{
    $('#app').change();

    $.setAjaxForm('#createForm', function(response)
    {
        if(response.result == 'success')
        {
            $('#triggerModal [data-dismiss="modal"]').click();
            bootbox.dialog(
            {
                title: '&nbsp;',
                message: window.createTips,
                buttons:
                {
                    no:
                    {
                        label: window.notNow,
                        className: 'btn-secondary',
                        callback: function(){location.reload();}
                    },
                    yes:
                    {
                        label: window.toDesign,
                        className: 'btn-primary',
                        callback: function(){location.href = createLink('workflow', 'ui', 'module=' + response.module);}
                    }
                },
                onEscape: function(result)
                {
                    window.location.reload();
                }
            });
        }
    });

    $('input[name=approval]').change(function()
    {
        var approval = $(this).val();
        $('.approval').toggle(approval == 'enabled');
    })

    $('input[name=approval][checked=checked]').change();

    $('.icons').on('click', function()
    {
        const icon = $(this).data('id');

        $('.control-icon').html(`<i class="icon icon-${icon}"></i>`);
        $('input[name=icon]').val(icon);
    });
});

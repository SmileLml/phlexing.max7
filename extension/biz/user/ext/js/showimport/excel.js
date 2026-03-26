/**
 * Change group by role.
 *
 * @param  string $role
 * @param  int    $i
 * @access public
 * @return void
 */
function changeGroup(role, i)
{
    if(role && roleGroup[role])
    {
        $('#group' + i).val(roleGroup[role]);
    }
    else
    {
        $('#group' + i).val('');
    }
    $('#group' + i).trigger('chosen:updated');
}

/**
 * Toggle checkbox and check password strength.
 *
 * @param  object $obj
 * @param  int    $i
 * @access public
 * @return void
 */
function toggleCheck(obj, i)
{
    var $this    = $(obj);
    var password = $this.val();
    var $ditto   = $('#ditto' + i);
    var $passwordStrength = $this.closest('.input-group').find('.passwordStrength');
    if(password == '')
    {
        $ditto.attr('checked', true);
        $ditto.closest('.input-group-addon').show();
        $passwordStrength.hide();
        $passwordStrength.html('');
    }
    else
    {
        $ditto.removeAttr('checked');
        $ditto.closest('.input-group-addon').hide();
        $passwordStrength.html(passwordStrengthList[computePasswordStrength(password)]);
        $passwordStrength.show();
    }
}

$(function()
{
    removeDitto(); //Remove 'ditto' in first row.

    window.onscroll = function()
    {
        var winHeight = $(window).height();
        var bounds    = $('#batchCreateForm')[0].getBoundingClientRect();
        if(bounds.bottom > winHeight && bounds.top < (winHeight - 64))
        {
            $('#mainContent').addClass('has-affixed-region');
        }
        else
        {
            $('#mainContent').removeClass('has-affixed-region');
        }
    }
});

$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
    if($(select).val() == 'ditto')
    {
        if($(select).attr('id').substr(0, 7) == 'visions')
        {
            /* Fix bug #19960. */
            var value = '';
        }
        else
        {
            var index = $(select).closest('td').index();
            var row   = $(select).closest('tr').index();
            var table = $(select).closest('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'ditto') break;
            }
        }

        $(select).val(value);
        $(select).trigger("chosen:updated");
    }
})

$(document).on('change', '[id^=visions]', function()
{
    if($.inArray('ditto', $(this).val()) >= 0)
    {
        $(this).val('ditto');
        $(this).trigger("chosen:updated");
    }
})

function initGroup(data)
{
    $('select[id^="visions"]').each(function()
    {
        var i        = $(this).attr('id').replace(/[^0-9]/ig, '');
        var groupVal = $('#group' + i).val();

        var dataObj = $(data);
        var dataHtml = $(dataObj).attr('id', 'group' + i).attr('name', 'group[' + i + '][]').prop('outerHTML');

        $('#group' + i).replaceWith(dataHtml);
        $('#group' + i + '_chosen').remove();
        if(i == 1) $('#group' + i).find('option[value="ditto"]').remove();
        $('#group' + i).val(groupVal);
        $('#group' + i).chosen();
        $('#role' + i).trigger('change');
    })
}

$(document).on('change', "select[id^='visions']", function()
{
    var visionsDom   = $(this);
    var visionsParam = visionsDom.val() instanceof Array ? visionsDom.val().join() : '-';
    var importRow    = parseInt(visionsDom.attr('id').replace(/[^0-9]/ig, ''));

    $.post(createLink('user', 'ajaxGetGroup', "visions=" + visionsParam + "&i=2"), function(data)
    {
        for(var n = importRow; n <= batchCreateCount; n++)
        {
            if(n != importRow && $.inArray('ditto', $('select[id="visions' + n + '"]').val()) < 0) break;

            ((function(n)
            {
                var groupVal = $('#group' + n).val();
                var dataHtml = $(data).attr('id', 'group' + n).attr('name', 'group[' + n + '][]').prop('outerHTML');
                $('#group' + n).replaceWith(dataHtml);
                $('#group' + n + '_chosen').remove();
                $('#group' + n).val(groupVal);
                $('#group' + n).chosen();
            }(n)));
        }
    });
});

/**
 * Affix Region Scrollbar.
 *
 * @param dom $region
 * @access public
 * @return void
 */
function affixRegionScrollbar($region)
{
    $region.addClass('region-affixed');
    var $container = $region.parent();
    var $scrollbar = $container.find('.region-affixed-scrollbar');

    if(!$scrollbar.length)
    {
        $scrollbar = $('<div class="region-affixed-scrollbar"><div class="region-affixed-holder"></div></div>').css('height', $.zui.getScrollbarSize() + 1).appendTo($container).on('scroll', function()
        {
            $('.table-responsive').scrollLeft($scrollbar.scrollLeft());
        });
    }
    var $userform = $region.find('.table-responsive');
    $scrollbar.width($region.outerWidth());
    $scrollbar.find('.region-affixed-holder').width($userform[0].scrollWidth);
    var scrollLeft = $userform.scrollLeft();
    if(scrollLeft !== $scrollbar.scrollLeft()) $scrollbar.scrollLeft(scrollLeft);
}

if($('#batchCreateForm').length > 0) affixRegionScrollbar($('#batchCreateForm'));

/**
 * Change option.
 *
 * @param  string $option
 * @access public
 * @return void
 */
function changeOption(option)
{
    if($(option).val() == 'fixed')
    {
        $(option).removeClass('unit-col-7').addClass('unit-col-3');
        $(option).next().val('').removeClass('hidden').addClass('unit-col-4 border-left');
    }
    else
    {
        $(option).removeClass('unit-col-3').addClass('unit-col-7');
        $(option).next().val('').removeClass('unit-col-4').addClass('hidden');
    }
    change();
}

/**
 * Add options.
 *
 * @param  string $clickedButton
 * @access public
 * @return void
 */
function addOptions(clickedButton)
{
    $(clickedButton).parent().parent().after(itemRow);
    change();
}

/**
 * Delete options.
 *
 * @param  string $clickedButton
 * @access public
 * @return void
 */
function delOptions(clickedButton)
{
    if($('td.unitBox').length == 1) return false;

    $(clickedButton).parent().parent().remove();
    change();
}

/**
 * Change version case.
 *
 * @access public
 * @return void
 */
function change()
{
    var format     = [];
    var fixedValue = [];
    var padding    = $('input[name="padding"]:checked').val();
    var version    = '1';

    if(padding == '1') version = '01';

    $("select[name='unit[]']").each(function(){
        format.push($(this).val());
    })

    $("input[name='unit[]']").each(function(){
        fixedValue.push($(this).val());
    })

    text = getVersionCase(format, fixedValue) + version;

    $('.versionCase').text(text);
}

/**
 * Get version case.
 *
 * @param  array $format
 * @param  array $value
 * @access public
 * @return string
 */
function getVersionCase(format, value)
{
    var text  = '';
    var now   = new Date();
    var year  = now.getFullYear();
    var month = now.getMonth() + 1;
    var day   = now.getDate();
    var time  = '';
    var unit  = $('input[name="unit[]"]').val();

    for(var i = 0; i < format.length; i++)
    {
        if(i%2 == 0 )
        {

            if(format[i] == 'date1') time = year + '' + month + '' + day;

            if(format[i] == 'date2') time = year + '-' + month + '-' + day;

            if(format[i] == 'fixed') time = value[i];

            if(format[i] == 'user') time = user;

            if(format[i] == '0') time = '';

            text += time + format[i+1];

        }
    }
    return text;
}

$(function() {
    change();
    $("input[name='padding']").change(function(){ change();});
    $("input[name^='unit']").live('input',function(){ change();});
});

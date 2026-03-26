function setAutoHeight()
{
    var height = $(window).height() - 105;
    $('.auto-height').each(function()
    {
        var $this = $(this);
        var offset = $this.data('offset') || 0;
        $this.height(height - offset);
        if($this.hasClass('main-row'))
        {
            $this.find('.side-col>.cell,.main-col>.cell').height(height - 20 - offset);
        }
    });
};

function toggleBelong(belong)
{
    if($('.belongBox').length == 0) return;

    let app = $('#app').val();
    $('.belongBox').addClass('hidden');
    if(!belongList[app]) return;

    $('#belong').val(belongList[app]['value']).attr('title', belongList[app]['text']);
    $('label[for=belong]').html(belongList[app]['text']);
    $('.belongBox').removeClass('hidden');
    if(typeof belong === 'undefined' || belong == belongList[app]['value'])
    {
        $('#belong').attr('checked', 'checked');
    }
    else
    {
        $('#belong').removeAttr('checked');
    }
}

$(function()
{
    $('#navigator').change(function()
    {
        if(typeof positionModule === 'undefined') positionModule = '';
        if(typeof position       === 'undefined') position       = '';
        if(typeof flowApp        === 'undefined') flowApp        = '';
        if(typeof currentModule  === 'undefined') currentModule  = '';
        if($(this).val() == 'primary')
        {
            $('#app').closest('.appTR').addClass('hidden');
            $('select#positionModule').load(createLink('workflow', 'ajaxGetApps'), function()
            {
                let $positionModule = $('#positionModule');
                $positionModule.val(positionModule);
                if(flowApp) $positionModule.find('option[value=' + flowApp + ']').remove();
                $positionModule.trigger('chosen:updated');
                $('#position').val(position);
            });
        }
        if($(this).val() == 'secondary')
        {
            $('#app').closest('.appTR').removeClass('hidden');
            $('select#positionModule').load(createLink('workflow', 'ajaxGetAppMenus', 'app=' + $('#app').val() + '&exclude=' + currentModule), function()
            {
                $('#app').val(flowApp).trigger('chosen:updated');
                $('#positionModule').val(positionModule).trigger('chosen:updated');
                $('#position').val(position);
            });
        }
        $('.belongBox').addClass('hidden');
    });

    $('#app').change(function()
    {
        let $this = $(this);
        let app   = $this.val();
        if($this.closest('.appTR').hasClass('hidden')) return;

        let positionModule = $('#positionModule').val();
        $('#positionModule').load(createLink('workflow', 'ajaxGetAppMenus', 'app=' + app + '&exclude=' + window.currentModule), function()
        {
            $('#positionModule').val(positionModule).trigger('chosen:updated');
        });

        if(typeof flowApp !== 'undefined' && flowApp == app) return toggleBelong(flowBelong);
        toggleBelong();
    });
});

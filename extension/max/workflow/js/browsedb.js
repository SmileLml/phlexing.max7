$(document).ready(function()
{
    $panelHeadingHeight = $('.panel-heading').outerHeight(true);
    $panelMarginBottom  = $('.panel').css('margin-bottom').replace('px', '');
    $editorNavHeight    = $('#editorNav').outerHeight(true);
    $editorMenuHeight   = $('#editorMenu').outerHeight();
    $spaceHeight        = $('.space.space-sm').outerHeight(true);

    $maxHeight = $(window).height() - $panelHeadingHeight - $panelMarginBottom - $editorNavHeight - $editorMenuHeight - $spaceHeight;
    $('.panel-body').css('max-height', $maxHeight + 'px');

    $('.confirmer').click(function()
    {
        const $this    = $(this);
        const url      = $this.attr('href');
        const role     = $this.data('role');
        const hasQuote = $this.data('hasquote');

        let message = defaultConfirmDelete;
        if(role == 'quote') message = confirmDeleteInQuote;
        if(hasQuote == '1') message = confirmDeleteHasQuote;

        bootbox.confirm(message, function(result)
        {
            if(!result) return;

            $.getJSON(url, function(data)
            {
                if(data.result == 'fail') bootbox.alert(data.message);

                return location.reload();
            })
        });
        return false;
    });
});

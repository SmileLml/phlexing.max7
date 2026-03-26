window.openInModal = function(url, alert)
{
    let $modal    = $('.modal');
    let loadModal = function()
    {
        setTimeout(function()
        {
            $('.popover.popover-in-modal').remove();
            $modal.load(url, function(){$(this).find('.modal-dialog').css('width', $(this).data('width')); $.zui.ajustModalPosition()})
        }, 1000);
    }
    if(alert) return bootbox.alert(alert, function(){loadModal()});
    loadModal();
}

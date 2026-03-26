window.togglePathBoxByType = function(e)
{
    let $this = $(e.target);
    if(!$this.prop('checked')) return;

    let type = $this.val();
    $('.libreofficeBox').toggleClass('hidden', type != 'libreoffice');
    $('.collaboraBox').toggleClass('hidden', type != 'collabora');
}

$(function()
{
    $('[name=convertType]').trigger('change');
})

$(document).ready(function()
{
    var showSearchModal = function($chosen)
    {
        var $select = $chosen.prev();
        var module  = $select.data('module');
        var field   = $select.data('field');
        var search  = window.btoa(encodeURI($chosen.find('.chosen-results > li.no-results > span').text()));
        var link    = createLink('flow', 'ajaxSearchMore', 'module=' + module + '&field=' + field + '&search=' + search);
//        $.zui.modalTrigger.show({name: 'searchModal', url: link, backdrop: 'static'});
    };

    $(document).on('change', 'select.ajaxSearchMore', function()
    {
        if($(this).val() === 'ajax_search_more' || $.inArray('ajax_search_more', $(this).val()) > -1)
        {
            var $chosen = $(this).next('.chosen-container');
            var $select = $(this);
            
            $('.ajax-searching').removeClass('ajax-searching');
            $select.addClass('ajax-searching');

            showSearchModal($chosen);
        }
    });

    $(document).on('click', '.chosen-container.ajaxSearchMore .chosen-results > li.no-results', function()
    {
        var $chosen = $(this).parents('.chosen-container.ajaxSearchMore');
        var $select = $chosen.prev();

        $('.ajax-searching').removeClass('ajax-searching');
        $select.addClass('ajax-searching');

        showSearchModal($chosen);
    });

    $(document).on('hide.zui.modal', '#searchModal', function()
    {
        var key     = '';
        var $select = $('.ajax-searching');
        if($selectedItem && $selectedItem.length)
        {
            key = $selectedItem.data('key');
            if(!$select.children('option[value="' + key + '"]').length)
            {
                $select.prepend('<option value="' + key + '">' + $selectedItem.text() + '</option>');
            }
        }
        $select.removeClass('ajax-searching').val(key).trigger("chosen:updated");
        $selectedItem = null;
    });

    $('select.ajaxSearchMore').next('.chosen-container').addClass('ajaxSearchMore');
})

var $selectedItem;
var selectItem = function(item)
{
    $selectedItem = $(item).first();
    $('#searchModal').modal('hide');
};

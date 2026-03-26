<style>
.picker {min-height: 28px;}
.picker-selections {line-height: 26px; min-height: 28px; border-color: #d6dae3; box-shadow: none;}
.picker-selection-single {line-height: 16px;}
.picker-multi .picker-selections {padding: 0px;}
.picker-multi .picker-selections .picker-search {margin: 0px;}
.picker-placeholder {top: 0px; color: #a6aab8;}
</style>
<script>
$(function()
{
    var defaults = {disableEmptySearch: true, maxListCount: 0, searchDelay: 500};

    initPicker = function($element, options)
    {
        var picker = $element.data('zui.picker');
        if(picker) picker.destroy();

        var options = $.extend({}, defaults, options);
        $element.picker(options);
    };

    initSelect = function($element)
    {
        var isDisabled = !!$element.attr('disabled');

        if(!isDisabled && $element.find('option[value=ajax_search_more]').length == 1)
        {
            $element.find('option[value=ajax_search_more]').remove();

            var url = $element.data('url');
            if(!url)
            {
                var module  = $element.data('module');
                var field   = $element.data('field');
                var options = $element.data('options') || '';
                var url     = createLink('flow', 'ajaxGetPairs', 'module=' + module + '&field=' + field + '&options=' + options + '&search={search}');
            }
            initPicker($element, {remote: url});
        }
        else
        {
            var picker = $element.data('zui.picker');
            if(!picker)
            {
                var chosen = $element.data('chosen');
                if(chosen)
                {
                    $element.trigger('chosen:updated');
                }
                else
                {
                    $element.chosen();
                }
            }
        }
    }

    $('.picker-select').each(function()
    {
        initSelect($(this));
    });
});
</script>

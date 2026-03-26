window.changeModule = function(e)
{
    const module = e.target.value;
    const url    = $.createLink('deliverable', 'ajaxGetModelList', `type=${module}`);

    let $methodPicker = $('[name="method"]').zui('picker');
    //$methodPicker.render({disabled: true});
    //$methodPicker.$.setValue(module == 'execution' ? 'close' : 'create');

    $.getJSON(url, function(data)
    {
        let $modelPicker = $('.modelBox .picker-box').zui('picker');
        $modelPicker.render({items: data});
        $modelPicker.$.setValue('');
    });
}

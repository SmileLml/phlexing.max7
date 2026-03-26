<?php
namespace zin;

global $lang;

$bug          = data('bug');
$identifyList = data('identifyList');

query('.browserTR')->append(
    h::tr
    (
        h::th
        (
            set::className('py-1.5 pr-2 font-normal nowrap text-right'),
            $lang->bug->injection
        ),
        h::td
        (
            set::className('py-1.5 pl-2 w-full'),
            picker
            (
                set::name('injection'),
                set::items($identifyList),
                set::value($bug->injection)
            )
        )
    ),
    h::tr
    (
        h::th
        (
            set::className('py-1.5 pr-2 font-normal nowrap text-right'),
            $lang->bug->identify
        ),
        h::td
        (
            set::className('py-1.5 pl-2 w-full'),
            picker
            (
                set::name('identify'),
                set::items($identifyList),
                set::value($bug->identify)
            )
        )
    )
);

pageJS(<<<'JS'
window.loadIdentify = function()
{
    const injectionID = $('[name=injection]').val();
    const identifyID  = $('[name=identify]').val();
    $.getJSON($.createLink('bug', 'ajaxGetIdentify', 'productID=' + $('[name=product]').val() + '&projectID=' + $('[name=project]').val()),function(identifies)
    {
        const $injectionPicker = $('[name="injection"]').zui('picker');
        $injectionPicker.render({items: identifies});
        $injectionPicker.$.setValue(injectionID);

        const $identifyPicker  = $('[name="identify"]').zui('picker');
        $identifyPicker.render({items: identifies});
        $identifyPicker.$.setValue(identifyID);
    });
}
JS
);

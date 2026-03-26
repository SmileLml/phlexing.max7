<?php
/**
 * The query dictionary view file of bi module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@chandao.com>
 * @package     bi
 * @link        https://www.zentao.net
 */
namespace zin;

h::js(<<<JAVASCRIPT
window.handleClickItem = function()
{
    let target = $(event.target);
    if(!target.hasClass('caret-down') && !target.hasClass('caret-right'))
    {
        const sqlForm   = $('textarea[name="sql"]');
        const fieldText = target.text();

        sqlForm.val(sqlForm.val() + fieldText.substring(0, fieldText.indexOf('(')));
        sqlForm.trigger('change');
    }
}
JAVASCRIPT
);

$fnGenerateDictionary = function($show = true)
{
    return sidebar
    (
        setID('dictionarySideBar'),
        set::style(array('max-height' => '600px', 'max-width' => '235px', 'overflow-y' => 'auto', 'overflow-x' => 'hidden')),
        setClass('bg-canvas', array('hidden' => !$show)),
        div(
            setClass('bg-canvas mx-5 my-3 text-xl font-semibold text-ellipsis h-7 flex-none'),
            span($this->lang->bi->dictionary)
        ),
        $show ? zui::menu
        (
            setID('dictionary'),
            setClass('dictionary'),
            set::items(createLink('bi', 'ajaxGetTableFieldsMenu')),
            set::onClickItem(jsRaw('window.handleClickItem'))
        ) : null
    );
};

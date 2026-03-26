<?php
/**
 * The edit view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('issue.edit');
$fields->orders('type,project');

$projectChange = jsCallback()
    ->const('projectID', $issue->project)
    ->do(<<<'JS'
    const currentProjectID = $element.find('[name=project]').val();
    const loadExecutionUrl = $.createLink('project', 'ajaxGetExecutions', 'project=' + currentProjectID + '&mode=leaf');
    $.getJSON(loadExecutionUrl, function(data)
    {
        const $executionPicker = $element.find('[name=execution]').zui('picker');
        const executionID      = $element.find('[name=execution]').val();
        $executionPicker.render({items: data.items});
        $executionPicker.$.setValue(executionID);

        $('[data-name=execution]').toggleClass('hidden', !data.multiple);
    });
JS
);

formGridPanel(on::change('[name=project]', $projectChange), set::title($lang->issue->edit), set::modeSwitcher(false), set::fields($fields));

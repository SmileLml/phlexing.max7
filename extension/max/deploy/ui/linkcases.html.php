<?php
/**
 * The linkcases view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

$testSuiteItems = array();
if(!empty($suiteList))
{
    foreach($suiteList as $suiteID => $suite)
    {
        $active = ($type == 'bysuite' and (int)$param == $suiteID) ? "active'" : '';
        $testSuiteItems[] = array(
            'text' => $suite->name,
            'innerClass' => $active,
            'subtitle' => $suite->type == 'public' ? $lang->testsuite->authorList[$suite->type] : '',
            'subtitleClass' => 'label secondary',
            'url' => helper::createLink('deploy', 'linkCases', "deployID={$deploy->id}&type=bysuite&parm=$suiteID")
        );
    }
}
else
{
    $testSuiteItems[] = array('text' => $lang->testsuite->noticeNone, 'url' => '###');
}

featureBar(
li
(
    setClass('nav-item'),
    span(set::className('label label-id'), $deploy->id),
    span(set::className('font-bold'), $deploy->name),
    span(set::className('text-muted'), $lang->deploy->linkCases),
    icon($lang->icons['link']),
    dropDown
    (
        btn($lang->testtask->linkBySuite, set::className('btn primary size-sm')),
        set::items($testSuiteItems)
    )
)
);

toolbar
(
    backBtn
    (
        setClass('secondary'),
        set::icon('back'),
        $lang->goback
    )
);

searchForm(set::module('testcase'), set::show(true));

$footToolbar = array('items' => array(
    array('text' => $lang->deploy->linkCases, 'className' => 'batch-btn ajax-btn', 'data-url' => createLink('deploy', 'linkCases', "deployID={$deploy->id}")))
);

unset($config->deploy->dtable->cases->fieldList['actions']);
$tableData = initTableData($cases, $config->deploy->dtable->cases->fieldList);
dtable
(
    set::cols($config->deploy->dtable->cases->fieldList),
    set::data($tableData),
    set::checkable('true'),
    set::footToolbar($footToolbar)
);

<?php
/**
 * The storygrade view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

include $app->getModuleRoot() . 'custom/ui/sidebar.html.php';

jsVar('enableLang', $lang->custom->gradeStatusList['enable']);

$tbody = array();
foreach($storyGrades as $grade)
{
    $items   = array();
    $hidden  = ($grade->grade == 1) ? 'hidden' : '';
    $hideAdd = (end($storyGrades) == $grade) ? '' : 'hidden';
    $items[] = array('icon' => 'plus',  'class' => "btn ghost btn-add-grade $hideAdd");
    if($grade->status == 'enable' && common::hasPriv('custom', 'closeGrade'))     $items[] = array('icon' => 'off',   'class' => "btn ghost btn-close ajax-submit $hidden", 'url' => inlink('closeGrade', "type={$module}&id={$grade->grade}"), 'data-confirm' => $lang->custom->notice->closeGrade);
    if($grade->status == 'disable' && common::hasPriv('custom', 'activateGrade')) $items[] = array('icon' => 'magic', 'class' => "btn ghost btn-active ajax-submit", 'url' => inlink('activateGrade', "type={$module}&id={$grade->grade}"), 'data-confirm' => $lang->custom->notice->activateGrade);
    if(common::hasPriv('custom', 'deleteGrade')) $items[] = array('icon' => 'trash', 'class' => "btn ghost btn-delete-grade ajax-submit $hidden", 'url' => inlink('deleteGrade', "type={$module}&id={$grade->grade}"));

    $tbody[] = h::tr(
        setClass('gradeTr'),
        formHidden('grade[]', $grade->grade),
        h::td($grade->grade, set::width('100px'), setClass('index')),
        h::td(input(set::name('gradeName[]'), set::value($grade->name))),
        h::td(zget($lang->custom->gradeStatusList, $grade->status), set::width('80px'), setClass('grade-status')),
        h::td(
            set::width('100px'),
            btnGroup
            (
                set::items($items)
            )
        )
    );
}

div
(
    setClass('row has-sidebar-left'),
    $sidebarMenu,
    formPanel(
        set::title($lang->custom->story->fields['storyGrade']),
        setClass('ml-0.5 flex-auto'),
        on::click('.btn-add-grade', 'addGrade'),
        set::actions(array()),
        h::table(
            setID('gradeList'),
            setClass('table table-form borderless'),
            h::tr(
                h::td($lang->story->grade),
                h::td($lang->story->gradeName, setClass('required')),
                h::td($lang->story->statusAB),
                h::td($lang->actions)
            ),
            $tbody,
            h::tfoot(
                h::tr(
                    h::td(),
                    h::td(
                        btn(
                            $lang->save,
                            setClass('primary btn-wide'),
                            set::btnType('submit')
                        )
                    )
                )
            )
        )
    )
);

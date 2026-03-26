<?php
/**
 * The cancel project approval file of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
 * @package     charter
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('browseType', $this->session->browseType);
jsVar('vision', $config->vision);

$fields = defineFieldList('charter.cancelProjectApproval');

$fields->field('appliedBy')
    ->wrapAfter()
    ->control('picker', array('required' => true))
    ->width('1/3')
    ->items($users)
    ->value($app->user->account);

foreach(data('fileList') as $key => $fileName)
{
    $fields->field($key)
        ->name("files[$key]")
        ->label($fileName)
        ->width('1/3')
        ->multiple(false)
        ->control('fileSelector', array('multiple' => false, 'maxFileCount' => 1, 'defaultFiles' => $charter->files, 'extra' => "cancelApproval-$key", 'renameBtn' => false, 'deleteName' => 'deleteFiles'));
}

$fields->field('desc')
    ->width('full')
    ->control('editor');

$loadReviewers = function()
{
    $cols = array();
    $cols['node']     = array('name' => 'approval_node',     'type' => 'text',    'title' => $this->lang->approval->node, 'width' => '200', 'fixed' => 'left');
    $cols['reviewer'] = array('name' => 'approval_reviewer', 'type' => 'control', 'title' => $this->lang->approval->reviewer, 'control' => array('type' => 'picker', 'props' => "RAWJS<window.getReviewerCellProps>RAWJS"));
    $cols['ccer']     = array('name' => 'approval_ccer',     'type' => 'control', 'title' => $this->lang->approval->ccer,     'control' => array('type' => 'picker', 'props' => "RAWJS<window.getCcerCellProps>RAWJS"));

    return dtable
    (
        set::bordered(true),
        set::rowHeight(45),
        set::cols(array_values($cols)),
        set::data(data('approvalReviewDatas')),
        set::onRenderCell(jsRaw('window.renderReviewerItem')),
        set::plugins(array('form'))
    );
};

$fields->field('reviewerBox')->className('reviewerBox')->width('full')->label($this->lang->charter->approval)->control($loadReviewers)->readonly(false)->layoutRules('');

formGridPanel
(
    set::title($lang->charter->cancelProjectApproval),
    set::fields($fields),
    set::modeSwitcher(false),
    set::submitBtnText($lang->charter->approvalAction)
);

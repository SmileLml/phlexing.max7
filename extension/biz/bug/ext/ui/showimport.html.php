<?php
namespace zin;

jsVar('bugLang',        $lang->bug);
jsVar('noticeLang',     $lang->bug->noRequire);
jsVar('requiredFields', $config->bug->create->requiredFields);
jsVar('modules',        $modules);
jsVar('stories',        $stories);

$fields = array();
$fields['id']      = array('name' => 'id', 'label' => $lang->transfer->id, 'control' => 'input', 'required' => false, 'width' => '64px', 'hidden' => true);
$fields['idIndex'] = array('name' => 'id', 'label' => $lang->transfer->id, 'control' => 'index', 'required' => false, 'width' => '64px');
$fields           += $datas->fields;
$fields['module']['control'] = array('control' => 'picker', 'required' => true);
if(isset($fields['pri']) && isset($fields['pri']['control']) && $fields['pri']['control'] == 'picker') $fields['pri']['control'] = 'priPicker';
unset($fields['product'], $fields['severity']['items'][0]);

$requiredFields = $datas->requiredFields;
$allCount       = $datas->allCount;
$allPager       = $datas->allPager;
$pagerID        = $datas->pagerID;
$isEndPage      = $datas->isEndPage;
$maxImport      = $datas->maxImport;
$dataInsert     = $datas->dataInsert;
$suhosinInfo    = $datas->suhosinInfo;
$module         = $datas->module;
$datas          = $datas->datas;
$appendFields   = $this->session->appendFields;
$notEmptyRule   = $this->session->notEmptyRule;

if(!empty($suhosinInfo))
{
    div(setClass('alert secondary'), html($suhosinInfo));
}
elseif(empty($maxImport) and $allCount > $this->config->file->maxImport)
{
    panel
    (
        on::keyup('[name=maxImport]', 'recomputeTimes'),
        set::title($lang->transfer->import),
        html(sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px'"), ceil($allCount / $config->file->maxImport))),
        btn(setID('import'), setClass('primary'), on::click('setMaxImport'), $lang->import)
    );
}
else
{
    $submitText  = $isEndPage ? $lang->save : $lang->file->saveAndNext;
    $isStartPage = $pagerID == 1;

    $index = 1;
    foreach($datas as $data)
    {
        if(empty($data->id)) $data->id = $index ++;
    }

    formBatchPanel
    (
        set::title($lang->transfer->import),
        set::mode('edit'),
        set::items($fields),
        set::data(array_values($datas)),
        set::actions(array()),
        set::showExtra(false),
        set::onRenderRow(jsRaw('renderRowData')),
        on::change('[data-name="branch"]', 'changeBranch'),
        on::change('[data-name="module"]', 'changeModule'),
        div
        (
            setClass('toolbar form-actions form-group no-label'),
            $this->session->insert ? btn(set::btnType('submit'), setClass('primary btn-wide'), $submitText) : btn(set('data-toggle', 'modal'), set('data-target', '#importNoticeModal'), setClass('primary btn-wide'), $submitText),
            btn(set::url($backLink), setClass('btn-back btn-wide'), $lang->goback),
            $this->session->insert ? formHidden('insert', $dataInsert != '' ? $dataInsert : 1) : null,
            formHidden('isEndPage', $isEndPage ? 1 : 0),
            formHidden('pagerID', $pagerID),
            html(sprintf($lang->file->importPager, $allCount, $pagerID, $allPager))
        ),
        $this->session->insert ? null : modal
        (
            set::size('sm'),
            setID('importNoticeModal'),
            set::title($lang->importConfirm),
            formHidden('insert', 0),
            div
            (
                setClass('alert flex items-center'),
                icon(setClass('icon-2x alert-icon'), 'exclamation-sign'),
                div($lang->noticeImport)
            ),
            to::footer
            (
                btn(setClass('danger btn-wide'), set('onclick', 'submitForm("cover")'), $lang->importAndCover, set::btnType('submit')),
                btn(setClass('primary btn-wide'), set('onclick', 'submitForm("insert")'), $lang->importAndInsert, set::btnType('submit'))
            )
        )
    );
    pageCSS('.form-batch-container .form-batch-control .check-list-inline {padding-top: 0;}');
}

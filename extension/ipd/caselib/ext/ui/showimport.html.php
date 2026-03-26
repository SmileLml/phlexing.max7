<?php
namespace zin;

$fields = array();
$fields['idIndex'] = array('name' => 'id',  'label' => $lang->transfer->id, 'control' => 'index', 'required' => false, 'width' => '64px');
$fields['lib']     = array('name' => 'lib', 'label' => $lang->transfer->id, 'control' => 'input', 'required' => false, 'width' => '64px', 'hidden' => true);
$fields           += $datas->fields;
$fields['steps']['width']    = '300px';
$fields['stage']['multiple'] = true;
unset($fields['stepDesc'], $fields['stepExpect'], $fields['pri']['items'][0]);

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

if(!empty($suhosinInfo))
{
    div(setClass('alert secondary'), $suhosinInfo);
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
    pageJS(<<<JAVASCRIPT
window.recomputeTimes = function()
{
    if(parseInt(\$('#maxImport').val())) \$('#times').html(Math.ceil(parseInt({$allCount}) / parseInt(\$('#maxImport').val())));
};

window.setMaxImport = function()
{
    \$.cookie.set('maxImport', \$('#maxImport').val(), {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
};
JAVASCRIPT

    );
}
else
{
    $submitText  = $isEndPage ? $lang->save : $lang->file->saveAndNext;
    $isStartPage = $pagerID == 1;

    $items['id'] = array
    (
        'name'  => 'id',
        'control' => 'hidden',
    );

    $items['idIndex'] = array
    (
        'name'  => 'index',
        'label' => $lang->idAB,
        'control' => 'index',
        'width' => '32px'
    );

    $items['lib'] = array
    (
        'name'  => 'lib',
        'label' => '',
        'hidden' => true,
        'control' => 'hidden',
        'width' => '32px'
    );

    $items['module'] = array
    (
        'name'       => 'module',
        'label'      => $lang->testcase->module,
        'control'    => 'picker',
        'items'      => $fields['module']['items'],
        'width'      => '136px',
        'required'   => strpos(",$requiredFields,", ',module,') !== false
    );


    $items['title'] = array
    (
        'name'  => 'title',
        'label' => $lang->testcase->title,
        'width' => '240px',
        'required' => strpos(",$requiredFields,", ',title,') !== false
    );

    $items['precondition'] = array
    (
        'name'    => 'precondition',
        'label'   => $lang->testcase->precondition,
        'control' => 'textarea',
        'width'   => '240px',
        'required' => strpos(",$requiredFields,", ',precondition,') !== false
    );

    $items['keywords'] = array
    (
        'name'  => 'keywords',
        'label' => $lang->testcase->keywords,
        'width' => '240px',
        'required' => strpos(",$requiredFields,", ',keywords,') !== false
    );

    $items['pri'] = array
    (
        'name'    => 'pri',
        'label'   => $lang->testcase->pri,
        'control' => 'pripicker',
        'items'   => $fields['pri']['items'],
        'width'   => '80px',
        'required' => strpos(",$requiredFields,", ',pri,') !== false
    );

    $items['type'] = array
    (
        'name'    => 'type',
        'label'   => $lang->testcase->type,
        'control' => 'picker',
        'items'   => $lang->testcase->typeList,
        'width'   => '160px',
        'required' => strpos(",$requiredFields,", ',type,') !== false
    );

    $items[] = array
    (
        'name'    => 'stage',
        'label'   => $lang->testcase->stage,
        'control' => 'picker',
        'multiple' => true,
        'items'   => $lang->testcase->stageList,
        'width'   => '240px',
        'required' => strpos(",$requiredFields,", ',stage,') !== false
    );

    /* Field of steps. */
    $items[] = array
    (
        'name'     => 'steps',
        'control'  => array('control' => 'textarea', 'class' => 'form-control form-batch-input text-3-row', 'placeholder' => $lang->testcase->stepsPlaceholder),
        'label'    => $lang->testcase->steps,
        'width'    => '256px',
        'required' => isset($requiredFields['steps'])
    );

    /* Field of expects. */
    $items[] = array
    (
        'name'     => 'expects',
        'control'  => array('control' => 'textarea', 'class' => 'form-control form-batch-input text-3-row'),
        'label'    => $lang->testcase->expect,
        'width'    => '256px',
        'required' => isset($requiredFields['expects'])
    );

    formBatchPanel
    (
        set::title($lang->transfer->import),
        set::mode('edit'),
        set::items($items),
        set::data(array_values($datas)),
        set::actions(array()),
        div
        (
            setClass('toolbar form-actions form-group no-label'),
            $this->session->insert ? btn(set::btnType('submit'), setClass('primary btn-wide'), $submitText) : btn(set('data-toggle', 'modal'), set('data-target', '#importNoticeModal'), setClass('primary btn-wide'), $submitText),
            btn(set::url($backLink), setClass('btn-back btn-wide'), $lang->goback),
            $this->session->insert && $dataInsert != '' ? formHidden('insert', $dataInsert) : null,
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
                btn(setClass('danger btn-wide'), set('onclick', 'submitForm("cover")'), $lang->importAndCover),
                btn(setClass('primary btn-wide'), set('onclick', 'submitForm("insert")'), $lang->importAndInsert)
            )
        )
    );
    pageJS(<<<JAVASCRIPT
window.submitForm = function(type)
{
    \$('[name=insert]').val(type == 'insert' ? 1 : 0);
    \$('#importNoticeModal .modal-footer .btn').addClass('disabled');

    const formUrl  = \$("button[data-target='#importNoticeModal']").closest('form').attr('action');
    const formData = new FormData(\$("button[data-target='#importNoticeModal']").closest('form')[0]);

    \$.ajaxSubmit({url: formUrl, data: formData});
};
JAVASCRIPT

    );
}

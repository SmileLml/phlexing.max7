<?php
/**
 * The query view file of dataview module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@easycorp.ltd>
 * @package     dataview
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('rawMethod',  $app->rawMethod);
jsVar('warningDesign', $lang->dataview->error->warningDesign);
jsVar('langSettings', isset($data->langs) ? $data->langs : '');
jsVar('objectFields', isset($objectFields) ? $objectFields : array());
if($app->rawMethod == 'query' && isset($viewID)) jsVar('viewID', $viewID);

$biPath = $this->app->getModuleExtPath('bi', 'ui');
include $biPath['common'] . 'query.dictionary.html.php';

featureBar
(
    backBtn
    (
        set::icon('back'),
        set::type('ghost'),
        set::url($backLink),
        $lang->goback
    )
);

toolbar
(
    modalTrigger
    (
        setClass(array('hidden' => $app->rawMethod != 'create')),
        btn
        (
            setClass('primary pull-right'),
            set::icon('save'),
            $lang->save
        ),
        set::target('#createModal')
    ),
    btn
    (
        setClass('primary pull-right', array('hidden' => $app->rawMethod != 'query')),
        set::icon('save'),
        $lang->save,
        on::click()->do('saveQuery()')
    )
);


$tableOptions = $this->loadModel('bi')->prepareFieldObjects();

div
(
    setID('state'),
    set('data-state', $state),
    set('data-changed', array()),
    set('data-url', createLink('dataview', $app->rawMethod, $app->rawMethod == 'query' ? "viewID=$viewID" : ''))
);

$mode          = $state->mode;
$isTextMode    = $mode == 'text';
$canChangeMode = $state->canChangeMode;

$fnGenerateDictionary($isTextMode);
div
(
    setID('queryBase'),
    queryBase
    (
        set::title($lang->dataview->sqlQuery),
        set::mode($state->mode),
        set::sql($state->sql),
        set::cols($state->queryCols),
        set::data($state->queryData),
        set::settings($state->fieldSettings),
        set::tableOptions($tableOptions),
        set::error($state->errorMsg),
        set::pager(usePager('pager', 'customLink', null, null, 'window.postQueryResult')),
        set::onQuery('ajaxQuery()'),
        set::onSqlChange('handleSqlChange()'),
        set::onSaveFields('saveFields()'),
        to::heading
        (
            div
            (
                setClass('absolute right-4 top-2'),
                modalTrigger
                (
                    btn
                    (
                        setClass('ghost', array('hidden' => $isTextMode)),
                        $lang->bi->previewSql
                    ),
                    set::target('#sqlModal')
                ),
                span(setClass('divider', array('hidden' => $isTextMode))),
                btn
                (
                    setID('changeMode'),
                    setClass('ghost', array('hidden' => !$canChangeMode)),
                    set('data-mode', $mode),
                    set('data-tip', $lang->bi->changeModeTip),
                    set::icon('exchange'),
                    $isTextMode ? $lang->bi->toggleSqlBuilder : $lang->bi->toggleSqlText,
                    on::click()->do('changeMode(event)')
                ),
                btn
                (
                    setID('changeModeDisabled'),
                    setClass('ghost', array('hidden' => $canChangeMode)),
                    set('data-mode', $mode),
                    set::hint($lang->bi->modeDisableTip),
                    set::icon('exchange'),
                    set::disabled(),
                    $isTextMode ? $lang->bi->toggleSqlBuilder : $lang->bi->toggleSqlText
                )
            )
        ),
        to::builder
        (
            !$isTextMode ? sqlBuilder
            (
                set::data($state->sqlbuilder),
                set::onUpdate('updatePage'),
                set::tableList($tableList),
                set::steps(array('table', 'field', 'func', 'where', 'group'))
            ) : null
        )
    ),
    modal
    (
        setID('sqlModal'),
        set::title($lang->bi->previewSql),
        div
        (
           html(str_replace(PHP_EOL, '<br/ >', $state->sql))
        )
    )
);

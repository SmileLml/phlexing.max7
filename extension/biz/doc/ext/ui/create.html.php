<?php
/**
 * The create view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$isOfficeType    = ($docType && strpos($config->doc->officeTypes, $docType) !== false);
$openedCollabora = (!empty($config->file->libreOfficeTurnon) && $config->file->convertType == 'collabora');
if($config->requestType == 'PATH_INFO' && $isOfficeType && $openedCollabora)
{
    jsVar('libType', $objectType);
    jsVar('docType', $docType);
    jsVar('officeTypes', $config->doc->officeTypes);
    formPanel(set::title($lang->doc->create), set::submitBtnText($lang->save), on::change('[name=lib]',    'checkLibPriv'), on::change('[name^=users]', 'checkLibPriv'), $objectType == 'project' ? formRow
    (
        formGroup
        (
            setClass('projectBox'),
            set::label($lang->doc->project),
            set::name('project'),
            set::items($objects),
            set::value($objectID),
            on::change('loadExecutions'),
            set::required(true)
        ),
        $app->tab == 'doc' ? formGroup
        (
            set::width('1/2'),
            setClass('executionBox'),
            set::label($lang->doc->execution),
            set::name('execution'),
            set::items($executions),
            set::placeholder($lang->doc->placeholder->execution),
            on::change('loadObjectModules')
        ) : null
    ) : null, $objectType == 'execution' ? formGroup
    (
        set::label($lang->doc->execution),
        set::name('execution'),
        set::items($objects),
        set::value($objectID),
        on::change('loadObjectModules'),
        set::required(true)
    ) : null, $objectType == 'product' ? formGroup
    (
        set::label($lang->doc->product),
        set::name('product'),
        set::items($objects),
        set::value($objectID),
        on::change('loadObjectModules'),
        set::required(true)
    ) : null, isset($spaces) ? formGroup
    (
        set::label($lang->doc->space),
        set::name('space'),
        set::items($spaces),
        set::value($objectID),
        set::disabled($lib->type == 'mine'),
        set::required(true),
        on::change('loadObjectModules')
    ) : null, formGroup
    (
        set::label($lang->doc->lib),
        set::name('lib'),
        set::items($libs),
        set::value($libID),
        on::change('loadLibModules'),
        set::required(true)
    ), formGroup
    (
        setClass('moduleBox'),
        set::label($lang->doc->module),
        set::name('module'),
        set::items($optionMenu),
        set::value($moduleID),
        set::required(true)
    ), formGroup
    (
        set::label($lang->doc->title),
        set::name('title'),
        set::required(true)
    ), formGroup
    (
        set::label($lang->doc->keywords),
        set::name('keywords')
    ), formRow
    (
        setID('aclBox'),
        formGroup
        (
            set::label($lang->doclib->control),
            radioList
            (
                set::name('acl'),
                set::items($lang->doc->aclList),
                set::value($objectType == 'mine' ? 'private' : 'open'),
                on::change("toggleAcl('doc')")
            )
        )
    ), formRow
    (
        setID('readListBox'),
        setClass('hidden'),
        formGroup
        (
            set::label($lang->doc->readonly),
            div
            (
                setClass('w-full check-list'),
                inputGroup
                (
                    setClass('w-full'),
                    $lang->doc->groupLabel,
                    picker
                    (
                        set::name('readGroups[]'),
                        set::items($groups),
                        set::multiple(true)
                    )
                ),
                div
                (
                    setClass('w-full'),
                    userPicker
                    (
                        set::label($lang->doc->userLabel),
                        set::name('readUsers[]'),
                        set::items($users)
                    )
                )
            )
        )
    ), formRow
    (
        setID('whiteListBox'),
        setClass('hidden'),
        formGroup
        (
            set::label($lang->doc->editable),
            div
            (
                setClass('w-full check-list'),
                div
                (
                    setClass('w-full'),
                    inputGroup
                    (
                        $lang->doclib->group,
                        picker
                        (
                            set::name('groups[]'),
                            set::items($groups),
                            set::multiple(true)
                        )
                    )
                ),
                div
                (
                    setClass('w-full'),
                    userPicker(set::label($lang->doclib->user), set::items($users))
                )
            )
        )
    ), formHidden('status', 'normal'), formHidden('type', $docType), formHidden('contentType', 'html'));
}
else
{
    include $app->getModuleRoot() . 'doc/ui/create.html.php';
}

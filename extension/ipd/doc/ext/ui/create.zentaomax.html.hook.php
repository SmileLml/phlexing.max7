<?php
/**
 * The create view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

global $lang, $config;

$docType   = data('docType');
$templates = data('templates');
if(isset($docType) && $docType == 'html' && $config->vision == 'rnd')
{
    jsVar('replaceContentTip', $lang->doc->replaceContentTip);

    query('.save-draft')->before(
        btn
        (
            setClass('btn btn-default ghost mr-2'),
            set::icon('template', setClass('text-primary')),
            set::url('#modalTemplate'),
            setData('toggle', 'modal'),
            setData('size', 'sm'),
            $lang->doc->template
        )
    );

    query('#modalBasicInfo')->after(
        modal
        (
            setID('modalTemplate'),
            set::title($lang->doc->template),
            set::bodyClass('form form-horz'),
            formGroup
            (
                setClass('mt-6 mb-8'),
                set::label($lang->doc->selectTemplate),
                set::name('template'),
                set::items($templates)
            ),
            formRow
            (
                div
                (
                    setClass('form-actions form-group no-label'),
                    btn
                    (
                        setClass('btn primary confirm-btn'),
                        on::click('loadContent'),
                        $lang->confirm
                    ),
                    btn
                    (
                        setClass('btn'),
                        setData('dismiss', 'modal'),
                        $lang->cancel
                    )
                )
            )
        )
    );
}

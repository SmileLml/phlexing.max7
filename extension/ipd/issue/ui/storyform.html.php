<?php
/**
 * The storyform view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('projectID', $projectID);
jsVar('issueID', $issue->id);
jsVar('from', $from);
form
(
    setID('resolveForm'),
    set::submitBtnText($lang->issue->resolve),
    set::actions(array('submit')),
    set::url(createLink('issue', 'resolve', "issueID={$issue->id}&from={$from}")),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->issue->resolution),
        picker
        (
            set::name('resolution'),
            set::items($lang->issue->resolveMethods),
            set::value($resolution),
            set::required(true),
            on::change('getSolutions')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->story->product),
            inputGroup
            (
                picker
                (
                    set::width('2/3'),
                    set::name('product'),
                    set::items($products),
                    set::value($productID),
                    set::required(true),
                    on::change('loadProduct')
                ),
                $product->type != 'normal' && isset($products[$productID]) ? picker
                (
                    set::width('120px'),
                    set::name('branch'),
                    set::items($branches),
                    set::value($branch),
                    set::required(true),
                    on::change('loadBranch')
                ) : null
            )
        ),
        formGroup
        (
            set::label($lang->story->reviewedBy),
            set::required(true),
            inputGroup
            (
                picker
                (
                    set::name('reviewer[]'),
                    set::items($users),
                    set::multiple(true)
                ),
                !$this->story->checkForceReview() ? checkbox
                (
                    set::name('needNotReview'),
                    set::id('needNotReview'),
                    set::rootClass('ml-4 needNotReviewBox'),
                    set::value(1),
                    set::text($lang->story->needNotReview)
                ) : null
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->story->planAB),
            set::required(strpos(",{$config->story->create->requiredFields},", ",plan,") !== false),
            inputGroup
            (
                picker
                (
                    set::name('plan'),
                    set::items($plans)
                ),
                empty($plans) ? btn
                (
                    set::icon('plus'),
                    set::url(createLink('productplan', 'create', "productID={$productID}&branch={$branch}")),
                    set::title($lang->productplan->create),
                    setData('toggle', 'modal'),
                    setData('size', 'lg')
                ): null,
                empty($plans) ? btn
                (
                    set::icon('refresh'),
                    set::title($lang->refresh)
                ): null
            )
        ),
        formGroup
        (
            set::label($lang->story->module),
            set::required(strpos(",{$config->story->create->requiredFields},", ",module,") !== false),
            modulePicker
            (
                set::manageLink(createLink('tree', 'browse', "rootID={$productID}&view=story&currentModuleID=0&branch={$branch}")),
                set::items($moduleOptionMenu),
                set::value($moduleID),
                set::required(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('3/5'),
            set::label($lang->story->title),
            set::name('title'),
            set::value($issue->title),
            set::required(true)
        ),
        formGroup
        (
            set::width('2/5'),
            inputGroup
            (
                $lang->story->pri,
                priPicker(set::name('pri'), set::items(array_filter($lang->story->priList)), set::value($issue->pri ? $issue->pri : 3), set::required(true)),
                $lang->story->estimateAB,
                input(set::name('estimate'), set::placeholder($lang->story->hour))
            )
        )
    ),
    formGroup
    (
        set::label($lang->story->spec),
        set::required(strpos(",{$config->story->create->requiredFields},", ",spec,") !== false),
        editor
        (
            set::name('spec'),
            set::value($issue->desc),
            set::placeholder($lang->story->specTemplate . "\n" . $lang->noticePasteImg)
        )
    ),
    formGroup
    (
        set::label($lang->story->verify),
        set::required(strpos(",{$config->story->create->requiredFields},", ",verify,") !== false),
        editor(set::name('verify'))
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->story->source),
            set::required(strpos(",{$config->story->create->requiredFields},", ",source,") !== false),
            picker
            (
                set::name('source'),
                set::items($lang->story->sourceList)
            )
        ),
        formGroup
        (
            set::label($lang->story->sourceNote),
            set::name('sourceNote')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->issue->resolvedBy),
            set::name('resolvedBy'),
            set::items($users),
            set::value($app->user->account)
        ),
        formGroup
        (
            set::label($lang->issue->resolvedDate),
            datePicker
            (
                set::name('resolvedDate'),
                set::value(date('Y-m-d'))
            )
        )
    ),
    formHidden('type', 'story')
);

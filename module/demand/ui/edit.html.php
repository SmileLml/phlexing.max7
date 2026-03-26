<?php
namespace zin;

jsVar('demandStatus', $demand->status);
jsVar('page', 'edit');
jsVar('distributedProducts', $distributedProducts);
jsVar('confirmChangProduct', $lang->demand->confirmChangProduct);
jsVar('reviewedBy', explode(',', trim($demand->reviewedBy, ',')));
jsVar('notDeleted', $lang->demand->notice->notDeleted);
jsVar('reviewerNotEmpty', $lang->demand->notice->reviewerNotEmpty);
jsVar('demandReviewers', $demand->reviewer);

$canEditContent = strpos(',draft,changing,', ",{$demand->status},") !== false;
$forceReview    = $this->demand->checkForceReview();
$assignedToList = $demand->status == 'closed' ? array('closed' => 'Closed') : $users;

$undetermined   = empty(trim($demand->product, ',')) ? true : false;
$hiddenTbdClass = $undetermined ? '' : 'hidden';

$fields = $config->demand->form->edit;

detailHeader
(
    to::prefix($lang->demand->edit),
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::entityID($demand->id),
            set::reverse(true),
            span(setID('demandTitle'), $demand->title)
        )
    )
);

detailBody
(
    setID('dataform'),
    set::isForm(true),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    $canEditContent ? set::actions(array
    (
        array('btnType' => 'submit', 'class' => 'primary',   'data-status' => 'active', 'text' => $lang->save),
        array('btnType' => 'submit', 'class' => 'secondary', 'data-status' => 'draft',  'text' => $demand->status == 'changing' ? $lang->story->doNotSubmit : $lang->demand->saveDraft),
        isInModal() ? null : array('text' => $lang->goback, 'back' => 'APP')
    )) : null,
    sectionList
    (
        section
        (
            set::required(true),
            set::title($lang->demand->title),
            inputControl
            (
                input
                (
                    setClass('filter-none'),
                    set::name('title'),
                    set::value($demand->title),
                    set::readonly(!$canEditContent)
                ),
                set::suffixWidth('40'),
                to::suffix
                (
                    colorPicker
                    (
                        set::heading($lang->demand->colorTag),
                        set::name('color'),
                        set::value($demand->color),
                        set::syncColor('#title, #demandTitle')
                    )
                )
            )
        ),
        $canEditContent ? section
        (
            set::required(true),
            set::title($lang->demand->reviewers),
            inputGroup
            (
                picker
                (
                    setID('reviewer'),
                    set::name('reviewer[]'),
                    set::items($reviewers),
                    set::value($demand->reviewers),
                    set::multiple(true)
                ),
                $forceReview ? null : span
                (
                    setClass('input-group-addon'),
                    checkbox
                    (
                        setID('needNotReview'),
                        set::name('needNotReview'),
                        set::checked(empty($demand->reviewer)),
                        set::value(1),
                        set::text($lang->demand->needNotReview),
                        on::change('changeNeedNotReview')
                    )
                ),
                $forceReview ? formHidden('needNotReview', $forceReview ? 0 : 1) : null
            )
        ) : null,
        section
        (
            set::title($lang->demand->legendSpec),
            $canEditContent ? formGroup(editor(set::name('spec'), html($demand->spec))) : set::content($demand->spec),
            $canEditContent ? null : set::useHtml(true),
            $canEditContent ? null : formHidden('spec', $demand->spec)
        ),
        section
        (
            set::title($lang->demand->verify),
            $canEditContent ? formGroup(editor(set::name('verify'), html($demand->verify))) : set::content($demand->verify),
            $canEditContent ? null : set::useHtml(true),
            $canEditContent ? null : formHidden('verify', $demand->verify)
        ),
        $canEditContent || $demand->files ? section
        (
            set::title($lang->demand->legendAttach),
            $canEditContent ? fileSelector() : null,
            $demand->files ? fileList
            (
                set::files($demand->files),
                set::fieldset(false),
                set::showEdit(true),
                set::showDelete(true),
                set::extra($demand->version),
                set::object($demand)
            ) : null
        ) : null,
        section
        (
            set::title($lang->demand->comment),
            formGroup(editor(set::name('comment')))
        )
    ),
    history(),
    detailSide
    (
        set::isForm(true),
        tableData(setClass('mt-5'), set::title($lang->demand->legendBasicInfo), item
        (
            set::trClass(zget($fields['pool'], 'className', '')),
            set::name($lang->demand->pool),
            picker
            (
                set::name('pool'),
                set::items($demandpools),
                set::value($demand->pool),
                on::change('changePool'),
                set::required(true)
            )
        ), item
        (
            set::trClass('productBox'),
            set::name($lang->demand->product),
            inputGroup
            (
                picker
                (
                    set::name('product[]'),
                    set::items($products),
                    set::value($demand->product),
                    set::multiple(true),
                    set::disabled($undetermined),
                    set::required(true),
                    set::onDeselect(jsRaw('deselectProduct'))
                ),
                input
                (
                    set::name('undeterminedProduct'),
                    set::value(),
                    set::readonly(true),
                    set::hidden(true)
                ),
                span
                (
                    setClass('input-group-addon ' . $hiddenTbdClass),
                    checkbox
                    (
                        setID('undetermined'),
                        set::name('undetermined'),
                        set::checked($undetermined),
                        set::value(1),
                        set::text($lang->demand->undetermined),
                        on::change('toggleProductDropdown'),
                        set::disabled($demand->parent < 0)
                    )
                )
            )
        ), item
        (
            set::name($lang->demand->source),
            picker(setID('source'), set::name('source'), set::items($fields['source']['options']), set::value($demand->source))
        ), item
        (
            set::name($lang->demand->sourceNote),
            input(set::name('sourceNote'), set::value($demand->sourceNote))
        ), item
        (
            set::name($lang->demand->duration),
            picker(setID('duration'), set::name('duration'), set::items($fields['duration']['options']), set::value($demand->duration))
        ), item
        (
            set::name($lang->demand->BSA),
            picker(setID('BSA'), set::name('BSA'), set::items($fields['BSA']['options']), set::value($demand->BSA))
        ), item
        (
            set::trClass(in_array($demand->source, $config->demand->feedbackSource) ? 'feedbackBox' : 'feedbackBox hidden'),
            set::name($lang->demand->feedbackedBy),
            input(set::name('feedbackedBy'), set::value($demand->feedbackedBy))
        ), item
        (
            set::trClass(in_array($demand->source, $config->demand->feedbackSource) ? 'feedbackBox' : 'feedbackBox hidden'),
            set::name($lang->demand->email),
            input(set::name('email'), set::value($demand->email))
        ), $demand->parent >= 0 ? item
        (
            set::name($lang->demand->parent),
            picker(setID('parent'), set::name('parent'), set::items($parents), set::value($demand->parent))
        ) : null, item
        (
            set::name($lang->demand->status),
            span(setClass("status-{$demand->status}"), $this->processStatus('demand', $demand)),
            formHidden('status', $demand->status)
        ), item
        (
            set::name($lang->demand->category),
            picker(setID('category'), set::name('category'), set::items($fields['category']['options']), set::value($demand->category))
        ), item
        (
            set::name($lang->demand->pri),
            priPicker(set::name('pri'), set::items($fields['pri']['options']), set::value($demand->pri))
        ), item
        (
            set::name($lang->demand->keywords),
            input(set::name('keywords'), set::value($demand->keywords))
        ), item
        (
            set::name($lang->demand->mailto),
            mailto(set::items($users), set::value($demand->mailto))
        )),
        tableData(set::title($lang->demand->legendLifeTime), item
        (
            set::name($lang->demand->createdBy),
            zget($users, $demand->createdBy)
        ), item
        (
            set::name($lang->demand->assignedTo),
            picker
            (
                setID('assignedTo'),
                set::name('assignedTo'),
                set::items($assignedToList),
                set::value($demand->assignedTo)
            )
        ), $demand->status == 'reviewing' ? item
        (
            set::name($lang->demand->reviewers),
            picker
            (
                setID('reviewer'),
                set::name('reviewer[]'),
                set::items($reviewers),
                set::value($demand->reviewer),
                set::multiple(true),
                on::change('changeReviewer')
            )
        ) : null, $demand->status == 'closed' ? item
        (
            set::name($lang->demand->closedBy),
            picker(setID('closedBy'), set::name('closedBy'), set::items($users), set::value($demand->closedBy))
        ) : null, $demand->status == 'closed' ? item
        (
            set::name($lang->demand->closedReason),
            picker(setID('closedReason'), set::name('closedReason'), set::items($fields['closedReason']['options']), set::value($demand->closedReason), on::change('setdemand'))
        ) : null)
    )
);

render();

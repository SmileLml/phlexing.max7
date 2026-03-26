<?php
namespace zin;

jsVar('iconCheck', $config->ai->miniPrograms->iconCheck);
jsVar('iconName', $iconName);
jsVar('iconTheme', $iconTheme);
reset($models);

formPanel(div(set::className('flex gap-2 items-center'),div(set::className('panel-title text-lg'),$lang->ai->assistant->create),div(set::className('flex gap-1 items-center'),h::i(setClass('icon icon-help text-warning')),div(set::className('text-gray'),$lang->ai->assistant->createTip))), set::id('assistant-form'), set::actions(
    array(
        common::hasPriv('ai', 'assistantPublish') ? array('text' => $lang->ai->prompts->action->publish, 'id' => 'save-publish-assistant-button', 'class' => 'btn primary') : null,
        array('text' => $lang->save, 'class' => 'btn secondary', 'id' => 'save-assistant-button', 'btnType' => 'submit'),
        array('text' => $lang->goback, 'class' => 'toolbar-item btn open-url', 'url' => helper::createLink('ai', 'assistants'))
    )
), formGroup
(
    set::label($lang->ai->models->common),
    set::width('1/2'),
    set::required(true),
    select
    (
        set::name('modelId'),
        set::items($models),
        set::value(key($models)),
        set::required(true)
    )
), formGroup
(
    set::label($lang->ai->assistant->name),
    set::width('1/2'),
    set::required(true),
    input
    (
        set::name('name'),
        set('maxlength', 20)
    )
), formGroup
(
    set::label($lang->ai->assistant->desc),
    textarea
    (
        set::name('desc'),
        set::rows(3),
        set::placeholder($lang->ai->assistant->descPlaceholder)
    )
), formGroup
(
    set::label($lang->ai->assistant->systemMessage),
    set::required(true),
    textarea
    (
        set::name('systemMessage'),
        set::rows(3),
        set::placeholder($lang->ai->assistant->systemMessagePlaceholder)
    )
), formGroup
(
    set::label($lang->ai->assistant->greetings),
    set::required(true),
    textarea
    (
        set::name('greetings'),
        set::rows(3),
        set::placeholder($lang->ai->assistant->greetingsPlaceholder)
    )
), formGroup
(
    set::label($lang->ai->miniPrograms->icon),
    set::width('1/2'),
    set::hight('50px'),
    button
    (
        set('id', 'ai-edit-icon'),
        setClass('btn btn-icon'),
        setStyle(array(
            'width' => '46px',
            'height' => '46px',
            'border-radius' => '50%',
            'border' => "1px solid {$config->ai->miniPrograms->themeList[$iconTheme][1]}",
            'background-color' => $config->ai->miniPrograms->themeList[$iconTheme][0],
            'padding' => '0',
        )),
        setData('toggle', 'modal'),
        setData('target', '#edit-icon-modal'),
        html($config->ai->assistants->iconList[$iconName]),
        div
        (
            setID('edit-icon'),
            html($config->ai->miniPrograms->iconEdit)
        )
    )
), input
(
    set::name('iconName'),
    set::type('hidden'),
    set::value($iconName)
), input
(
    set::name('iconTheme'),
    set::type('hidden'),
    set::value($iconTheme)
));


$ai = $config->ai;

div(
    setClass('modal fade'),
    setData('backdrop', 'static'),
    setID('edit-icon-modal'),
    div(
        setClass('modal-dialog shadow size-sm bd-none'),
        div(
            setClass('modal-content'),
            div(
                setClass('modal-header items-center'),
                span
                (
                    setStyle(array(
                        'font-size' => '20px',
                        'font-weight' => 'bold',
                    )),
                    $lang->ai->miniPrograms->iconModification
                ),
                span
                (
                    setClass('text-muted'),
                    'Emoji icons by Twemoji with CC-BY4.0'
                )
            ),
            div
            (
              setClass('modal-actions'),
              button
              (
                  setClass('btn square ghost'),
                  setData('dismiss', 'modal'),
                  span
                  (
                      setClass('close')
                  )
              )
            ),
            div(
                setClass('modal-body'),
                div
                (
                    setStyle(array(
                        'display' => 'flex',
                        'gap' => '42px',
                    )),
                    div
                    (
                        setClass('icon-preview-container p-1'),
                        button
                        (
                            setID('preview-icon'),
                            setClass('btn btn-icon'),
                            setStyle(array(
                                'width' => '46px',
                                'height' => '46px',
                                'border-radius' => '50%',
                                'border' => "1px solid {$ai->miniPrograms->themeList[$iconTheme][1]}",
                                'background-color' => $ai->miniPrograms->themeList[$iconTheme][0],
                                'padding' => '0',
                            )),
                            setData('toggle', 'modal'),
                            setData('target', '#edit-icon-modal'),
                            html($config->ai->assistants->iconList[$iconName])
                        )
                    ),
                    div
                    (
                        setClass('icon-setting-container'),
                        div
                        (
                            setClass('mb-4'),
                            $lang->ai->miniPrograms->customBackground
                        ),
                        div
                        (
                            setID('theme-buttons'),
                            setStyle(array(
                                    'display' => 'flex',
                                    'gap' => '20px',
                                    'width' => '400px'
                                )
                            ),
                            array_map(function ($theme) use($iconTheme, $ai)
                            {
                                return button
                                (
                                    setClass('btn btn-icon theme-checked'),
                                    setStyle(array(
                                        'width' => '32px',
                                        'height' => '32px',
                                        'border-radius' => '50%',
                                        'border' => "1px solid {$theme[1]}",
                                        'background-color' => $theme[0],
                                    )),
                                    $ai->miniPrograms->themeList[$iconTheme][0] === $theme[0] ? html($ai->miniPrograms->iconCheck) : null
                                );
                            },$config->ai->miniPrograms->themeList)
                        ),
                        div
                        (
                            setClass('mt-6'),
                            div
                            (
                                setClass('mb-4'),
                                $lang->ai->miniPrograms->customIcon
                            ),
                            div
                            (
                                setID('icon-buttons'),
                                setStyle(array(
                                        'display' => 'grid',
                                        'column-gap' => '20px',
                                        'row-gap' => '16px',
                                        'grid-template-columns' => 'repeat(8, 1fr)',
                                        'justify-items'=> 'center',
                                        'align-items' => 'center',
                                    )
                                ),
                                array_map(function ($icon)
                                {
                                    return html($icon);
                                },$config->ai->assistants->iconList)
                            )
                        )
                    )
                )
            ),
            div(
                setClass('modal-footer flex items-center justify-center'),
                btn(
                    setID('save-icon-button'),
                    setClass('btn btn-wide primary'),
                    setData('dismiss', 'modal'),
                    $lang->save
                )
            )
        )
    )
);

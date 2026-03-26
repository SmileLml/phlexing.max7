<?php
namespace zin;

formPanel(
    set::labelWidth('11em'),
    set::size('sm'),
    setClass('pt-4'),
    formGroup(
        set::width('1/2'),
        set::label($lang->mail->inputFromEmail),
        inputGroup(
            input(
                set::name('fromAddress'),
                set::value($fromAddress),
                set::required(true),
                set::autofocus(true)
            )
        )
    ),
    set::actions(array('submit')),
    set::actionsClass('w-1/2'),
    set::submitBtnText($lang->mail->nextStep)
);

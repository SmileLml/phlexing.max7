<?php
namespace zin;
global $lang;

jsVar('reviewedPoints', data('reviewedPoints'));
query('#objectList')->replaceWith
(
    formGroup
    (
        set::label($lang->review->object),
        set::width('1/2'),
        set::id('objectList'),
        picker
        (
            set::name('object'),
            set::items($lang->baseline->objectList),
            bind::change('objectChange')
        )
    )
);

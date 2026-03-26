<?php
namespace zin;

global $lang;
query('.moduleTR')->append(
    h::tr
    (
        h::th
        (
            set::className('py-1.5 pr-2 font-normal nowrap text-right'),
            $lang->task->design
        ),
        h::td
        (
            set::className('py-1.5 pl-2 w-full'),
            picker
            (
                set::name('design'),
                set::items(array()),
                set::value(data('task.design'))
            )
        )
    )
);

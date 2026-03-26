<?php
namespace zin;
global $app, $lang;

$docs    = array();
$docList = $app->control->loadModel('doc')->getMySpaceDocs('all', 'bykeyword');
foreach($docList as $doc) $docs[] = array('text' => $doc->title, 'value' => $doc->id);

$docField = array();
$docField[] = formRow
(
    formGroup
    (
        set::label($lang->design->docs),
        picker
        (
            set::name('docs'),
            set::items($docs),
            set::multiple(true),
            set::maxItemsCount(50),
            set::menu(array('checkbox' => true)),
            set::toolbar(true)
        )
    )
);
query('#files')->before($docField);

<?php
namespace zin;

global $lang, $config;

$zentaoApp    = data('zentaoApp');
if($zentaoApp) jsVar('zentaoApp', $zentaoApp);
$instance = data('instance');
jsVar('instanceID', $instance->id);
query('#backupSection')->before(section
(
    setClass('hidden instance-custom-fields-block'),
    set::title($lang->instance->custom->title),
    formbase
    (
        set::url(createLink('instance', 'setting', "instanceID={$instance->id}&type=custom")),
        set::actions(array()),
        div(setID('instanceCustomFieldsBlock'))
    )
));

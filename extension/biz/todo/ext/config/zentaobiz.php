<?php
$config->todo->moduleList[] = 'feedback';

$config->todo->getUserObjectsMethod['feedback'] = 'ajaxGetUserFeedback';

$config->todo->objectList['feedback'] = 'feedbacks';

$config->todo->create->form['feedback'] = array('required' => false, 'type' => 'string', 'default'  => 0);

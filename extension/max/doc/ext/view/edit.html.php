<?php
$oldDir = getcwd();
chdir($app->getModuleRoot() . 'doc/view');
include './edit.html.php';
chdir($oldDir);
?>

<?php
namespace zin;

global $app;
$charterList = $app->control->loadModel('charter')->getPairs('all');
jsVar('charterList', $charterList);
jsVar('hasCharterViewPriv', hasPriv('charter', 'view'));

<?php
if(isset($flowAction)) $action = $flowAction;   // The view method has the $flowAction property instead of the $action property.

include $app->getModuleRoot() . 'common/view/header.html.php';

if(!empty($flow->css))   css::internal($flow->css);
if(!empty($action->css)) css::internal($action->css);
js::set('buildin', $flow->buildin);

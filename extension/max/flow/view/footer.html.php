<?php
if(isset($flowAction)) $action = $flowAction;   // The view method has the $flowAction property instead of the $action property.
if(!empty($flow->js))   js::execute($flow->js);
if(!empty($action->js)) js::execute($action->js);
if($action->open == 'modal')
{
    include '../../common/view/footer.modal.html.php';
}
else
{
    if(isset($lang->apps->{$flow->app}))
    {
        include $this->app->getModuleRoot($flow->app) . 'common/view/footer.html.php';
    }
    else
    {
        include $app->getModuleRoot() . 'common/view/footer.html.php';
    }
}

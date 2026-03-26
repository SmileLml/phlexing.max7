<style>
#editorSteps .nav-primary>li.active>a,#editorSteps .nav-primary>li.active>a:focus,#editorSteps .nav-primary>li.active>a:hover {color: #fff;}
.menu.leftmenu .nav-primary >li{width:100%}
.menu.leftmenu .nav-primary >li > a {border:none;}
</style>
<?php if($app->isServing()):?>
<?php
$customFlows = $this->dao->select('id,app,module')->from(TABLE_WORKFLOW)->where('buildin')->eq(0)->andWhere('status')->eq('normal')->andWhere('type')->eq('flow')->andWhere('vision')->eq($this->config->vision)->fetchAll();
$workflowNavGroup = new stdclass();
foreach($customFlows as $customFlow)
{
    if(!empty($customFlow->app))
    {
        $flowApp = $customFlow->app;
        if($flowApp == 'scrum' or $flowApp == 'waterfall') $flowApp = 'project';
        $workflowNavGroup->{$customFlow->module} = $flowApp;
    }
}
js::set('workflowNavGroup', $workflowNavGroup);
?>
<?php if($customFlows):?>
<script>
if(typeof(parent.window.navGroup) == 'object') $.extend(parent.window.navGroup, workflowNavGroup);
</script>
<?php endif;?>
<?php endif;?>

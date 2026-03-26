<?php
if(!empty($argv[1]) and is_dir($argv[1]))
{
    $modulePath = rtrim($argv[1], '/') . '/extension/biz';
}
else
{
    $modulePath = dirname(dirname(__FILE__)) . '/extension/biz';
}

$modules = array('flow', 'workflow', 'workflowaction', 'workflowcondition', 'workflowdatasource', 'workflowfield', 'workflowlabel', 'workflowlayout', 'workflowlinkage', 'workflowhook', 'workflowrelation', 'workflowrule', 'workflowreport');
foreach($modules as $moduleName)
{
    $files = array();
    exec("find $modulePath/$moduleName -name '*.php'", $files);
    foreach($files as $file)
    {
        $lines  = file($file);
        $delete = false;
        foreach($lines as $i => $line)
        {
            if(strpos($file, 'list.html.php') !== false and $moduleName == 'workflow')
            {
                $line = str_replace("if(\$type == 'flow') commonModel::printLink(\$flow->module, 'browse', '', \$lang->preview);", '', $line);
            }
            if(strpos($file, 'card.html.php') !== false and $moduleName == 'workflow')
            {
                $line = str_replace("if(\$type == 'flow') commonModel::printLink(\$flow->module, 'browse', '', \$lang->preview, \"class='btn btn-primary'\");", '', $line);
            }
            if(strpos($file, 'browse.html.php') !== false and $moduleName == 'flow')
            {
                $line = str_replace("main-table", "main-col", $line);
                if(strpos($line, '<table class') !== false) $line = "  <div class='main-table'>\n" . $line;
                if(strpos($line, '</table>') !== false) $line .= "  </div>\n";
            }
            if(strpos($file, 'model.php') !== false and $moduleName == 'flow')
            {
                $line = str_replace("\$this->tree->getTreeMenu(\$type, 0, array('flowModel', 'createCategoryLink'))", "\$this->tree->getTreeMenu(0, \$type, 0, array('flowModel', 'createCategoryLink'))", $line);
            }
            if(strpos($file, 'export.html.php') !== false and $moduleName == 'flow')
            {
                $line = str_replace("<?php include '../../file/view/export2excel.html.php';?>", "<?php unset(\$lang->exportTypeList['selected']);?>\n<?php include '../../file/view/export.html.php';?>", $line);
            }
            if(strpos($file, 'sendmail.html.php') !== false and strpos($line, 'mail.header.html.php') !== false)
            {
                $line .= "\n<style>\n.w-100px{width:100px;}\n.table-info{font-size:13px;}\n.table-info tr th{text-align:right;width:100px;}\n</style>\n";
            }
            $line = str_replace(array('icon-grid', 'icon-list', 'icon-remove', 'icon-upload-alt', 'icon-more-v', 'icon-ok'), array('icon-cards-view', 'icon-bars', 'icon-close', 'icon-export', 'icon-ellipsis-v', 'icon-checked'), $line);
            $line = str_replace('TABLE_CATEGORY', 'TABLE_DEPT', $line);
            $line = str_replace('loadInModal', 'loadInModal iframe', $line);
            $line = str_replace("('type')->eq('dept')->andWhere", '', $line);
            $line = str_replace(array('../../../sys/', 'moderators'), array('../../', 'manager'), $line);
            $line = str_replace('commonModel::printLink(', 'extCommonModel::printLink(', $line);
            $line = str_replace('menu leftmenu affix', 'menu leftmenu', $line);
            $line = str_replace(array('html::a(', 'html::submitButton(', 'html::commonButton(', 'html::linkButton('), array('baseHTML::a(', 'baseHTML::submitButton(', 'baseHTML::commonButton(', 'baseHTML::linkButton('), $line);
            $line = str_replace("this->loadModel('tree')->getPairs", "this->loadModel('dept')->getPairs", $line);
            $line = str_replace("this->tree", "this->loadModel('tree')", $line);
            $line = str_replace("this->loadModel('tree')->getOptionMenu(", "this->loadModel('tree')->getOptionMenu(0, ", $line);
            $line = preg_replace("/createLink\(('|\")[^\"']+\.([^\"']+)\\1, /i", "createLink(\$1\$2\$1, ", $line);
            $line = preg_replace("/\:\:hasPriv\(\$currentModule->app\./i", '::hasPriv(\\1', $line);
            $line = str_replace('pager->show()', "pager->show('right', 'pagerjs')", $line);
            $line = str_replace('this->app->getAppRoot()', "this->app->getModuleRoot()", $line);
            $line = str_ireplace("this->fetch('usercontact', 'buildContactList')", "this->fetch('my', 'buildContactLists')", $line);
            $line = str_ireplace("this->fetch('file', 'buildForm', \"filesName={\$fileField}&labelsName={\$labelsName}\")", "this->fetch('file', 'buildForm', \"fileCount=1&percent=0.9&filesName={\$fileField}&labelsName={\$labelsName}\")", $line);
            $line = str_replace("\$app->getModuleRoot(\$currentModule->app) . 'common/view/", "'../../common/view/", $line);
            $line = str_replace("Link('tree', 'browse', \"type=\$type&startModule=&root=&from=\$flow->module\"", "Link('tree', 'browse', \"rootID=0&type=\$type&currentModuleID=0&branch=&from=\$flow->module\"", $line);
            $line = str_replace("Link('tree', 'browse', \"type=datasource_{\$datasource->id}&startModule=&root=&from=workflow\"", "Link('tree', 'browse', \"rootID=0&type=datasource_{\$datasource->id}&currentModuleID=0&branch=&from=workflow\"", $line);
            $line = str_replace('common/view/editor.html.php', 'common/view/kindeditor.html.php', $line);
            if(strpos($line, '../../common/view') !== false and strpos($line, 'common/view/header.modal.html.php') === false and strpos($line, 'common/view/footer.modal.html.php') === false and strpos($line, 'common/view/codeeditor.html.php') === false and strpos($line, 'common/view/picker.html.php') === false and strpos($line, 'common/view/flowchart.html.php') === false) $line = str_replace("include '../../common/view/", "include \$app->getModuleRoot() . 'common/view/", $line);
            if(strpos($line, '$this->send(') !== false and strpos($line, 'return ') === false) $line = str_replace('$this->send(', 'return $this->send(', $line);
            if(strpos($line, "class='tree'") !== false) $line = str_replace("class='tree'", "class='tree' data-ride='tree'", $line);
            if(preg_match("/this\->loadModel\('user'\)\->getPairs\(/", $line) or preg_match("/this\->user\->getPairs\(/", $line)) $line = str_replace('getPairs(', 'getDeptPairs(', $line);
            if(strpos($line, 'treeview.html.php') !== false) $line = '';
            if(strpos($line, "this->fetch('action', 'history'") !== false)
            {
                if($moduleName == 'flow')     $line  = "    <?php \$actions = \$this->loadModel('action')->getList(\$flow->module, \$data->id);?>\n";
                if($moduleName == 'workflow') $line  = "    <?php \$actions = \$this->loadModel('action')->getList('workflow', \$flow->id);?>\n";
                $line .= "    <div class='cell'><?php include \$app->getModuleRoot() . 'common/view/action.html.php';?></div>\n";
            }
            if(strpos($line, "die(\$this->fetch(") !== false) $line = str_replace(", 'flow'))", '))', $line);

            if(strpos($line, "<ul id='menuTitle'>") !== false)
            {
                $line   = '';
                $delete = true;
            }
            if($delete && strpos($line, '</ul>') !== false)
            {
                $line   = '';
                $delete = false;
            }
            if($delete) $line = '';

            $lines[$i] = $line;
        }
        file_put_contents($file, join($lines));
    }

    $files = array();
    exec("find $modulePath/$moduleName -name '*.js'", $files);
    foreach($files as $file)
    {
        $lines = file($file);
        foreach($lines as $i => $line)
        {
            $line = preg_replace("/ v\.(\w+)/", " window.\$1", $line);
            $line = preg_replace("/^v\.(\w+)/", "window.\$1", $line);
            $line = preg_replace("/\(v\.(\w+)/", "(window.\$1", $line);
            $line = str_replace('#ajaxModal', "#triggerModal", $line);
            if(strpos($line, 'zui.modalTrigger.show(') !== false) $line = "//{$line}";
            if(strpos($line, 'fixTableHeader') !== false) $line = '';
            $lines[$i] = $line;
        }
        file_put_contents($file, join($lines));
    }
}

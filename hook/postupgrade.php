<?php
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '6.0', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '9.0.3', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update9.0.3.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '5.8', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '8.9.3', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update8.9.3.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '5.3', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '8.6', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update8.6.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '4.8', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '8.3', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update8.3.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '4.7', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '8.2', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update8.2.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '4.1', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '7.0.beta', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update7.0.beta.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '4.0', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '6.6.1', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update6.6.1.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '3.8', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '6.5.1', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update6.5.1.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '3.7', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '6.4', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update6.4.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '2.8', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '5.0.1', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update5.0.1.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '1.3', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '2.0', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update1.3.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'effort') !== false and version_compare($this->post->installedVersion, '1.2', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '1.1.1', '<=')))
{
    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update1.1.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'excel') !== false and version_compare($this->post->installedVersion, '5.5', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '8.3', '<=')))
{
    $basePath = dirname(dirname(__FILE__)) . '/';
    unlink($basePath . 'module/bug/ext/lang/en/execel.php');
    unlink($basePath . 'module/bug/ext/lang/zh-cn/execel.php');
    unlink($basePath . 'module/bug/ext/lang/zh-tw/execel.php');
    unlink($basePath . 'module/story/ext/lang/en/execel.php');
    unlink($basePath . 'module/story/ext/lang/zh-cn/execel.php');
    unlink($basePath . 'module/story/ext/lang/zh-tw/execel.php');
    unlink($basePath . 'module/task/ext/lang/en/execel.php');
    unlink($basePath . 'module/task/ext/lang/zh-cn/execel.php');
    unlink($basePath . 'module/task/ext/lang/zh-tw/execel.php');
    unlink($basePath . 'module/testcase/ext/lang/en/execel.php');
    unlink($basePath . 'module/testcase/ext/lang/zh-cn/execel.php');
    unlink($basePath . 'module/testcase/ext/lang/zh-tw/execel.php');
}
if((strpos($extension, 'gantt') !== false and version_compare($this->post->installedVersion, '4.0', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '9.0.3', '<=')))
{
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update9.0.3.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'gantt') !== false and version_compare($this->post->installedVersion, '2.2', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '5.0.1', '<=')))
{
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update5.0.1.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
}
if((strpos($extension, 'gantt') !== false and version_compare($this->post->installedVersion, '1.5', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '2.1', '<=')))
{
    if(!isset($execedSql['2.1']))
    {
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update2.1.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
        $execedSql['2.1'] = true;
    }
}

if(version_compare($this->config->version, '3.1', '<='))
{
    $sql = "ALTER TABLE `zt_task` CHANGE `estimateStartDate` `estStarted` DATE NOT NULL , CHANGE `actualStartDate` `realStarted` DATE NOT NULL";
    $sql = str_replace('zt_', $this->config->db->prefix, $sql);
    try
    {
        $this->dbh->exec($sql);
    }
    catch (PDOException $e)
    {
    }
}

if((strpos($extension, 'gantt') !== false and version_compare($this->post->installedVersion, '1.8', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '3.3', '<=')))
{
    $results    = $this->dbh->query("show Variables like '%table_names'")->fetchAll();
    $hasLowered = false;
    foreach($results as $result)
    {
        if(strtolower($result->Variable_name) == 'lower_case_table_names' and $result->Value == 1)
        {
            $hasLowered = true;
            break;
        }
    }

    if(!$hasLowered)
    {
        $tablesExists = $this->dbh->query('SHOW TABLES')->fetchAll();
        foreach($tablesExists as $key => $table) $tablesExists[$key] = current((array)$table);
        $tablesExists = array_flip($tablesExists);

        $oldTable = $this->config->db->prefix . 'relationOfTasks';
        $newTable = $this->config->db->prefix . 'relationoftasks';

        if(isset($tablesExists[$oldTable]))
        {
            $upgradebak = $newTable . '_othertablebak';
            if(isset($tablesExists[$upgradebak])) $this->dbh->query("DROP TABLE `$upgradebak`");
            if(isset($tablesExists[$newTable])) $this->dbh->query("RENAME TABLE `$newTable` TO `$upgradebak`");

            $tempTable = $oldTable . '_zentaotmp';
            $this->dbh->query("RENAME TABLE `$oldTable` TO `$tempTable`");
            $this->dbh->query("RENAME TABLE `$tempTable` TO `$newTable`");
        }
    }
}
if((strpos($extension, 'repo') !== false and version_compare($this->post->installedVersion, '5.7', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '8.5.1', '<=')))
{
    if(!isset($execedSql['8.5.1']))
    {
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update8.5.1.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
        $execedSql['8.5.1'] = true;
    }
}
if((strpos($extension, 'repo') !== false and version_compare($this->post->installedVersion, '4.9', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '6.5.1', '<=')))
{
    if(!isset($execedSql['6.5.1']))
    {
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update6.5.1.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
        $execedSql['6.5.1'] = true;
    }
}
if((strpos($extension, 'repo') !== false and version_compare($this->post->installedVersion, '4.1', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '5.0.1', '<=')))
{
    if(!isset($execedSql['5.0.1']))
    {
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update5.0.1.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
        $execedSql['5.0.1'] = true;
    }
}
if((strpos($extension, 'repo') !== false and version_compare($this->post->installedVersion, '2.2', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '2.1', '<=')))
{
    if(!isset($execedSql['2.1']))
    {
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update2.1.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
        $execedSql['2.1'] = true;
    }
}
if((strpos($extension, 'repo') !== false and version_compare($this->post->installedVersion, '3.1', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '3.1', '<=')))
{
    if(!isset($execedSql['3.0']))
    {
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update3.0.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
        $execedSql['3.0'] = true;
    }
}
if((strpos($extension, 'repo') !== false and version_compare($this->post->installedVersion, '3.4', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '4.0', '<=')))
{
    if(!isset($execedSql['3.4']))
    {
        $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'update4.0.sql';
        $this->loadModel('upgrade')->execSQL($extensionPath);
        $execedSql['3.4'] = true;
    }
}
if((strpos($extension, 'repo') !== false and version_compare($this->post->installedVersion, '3.2', '<=')) or (strpos($extension, 'bizext') !== false and version_compare($this->post->installedVersion, '3.3', '<=')))
{
    $results    = $this->dbh->query("show Variables like '%table_names'")->fetchAll();
    $hasLowered = false;
    foreach($results as $result)
    {
        if(strtolower($result->Variable_name) == 'lower_case_table_names' and $result->Value == 1)
        {
            $hasLowered = true;
            break;
        }
    }

    if(!$hasLowered)
    {
        $tablesExists = $this->dbh->query('SHOW TABLES')->fetchAll();
        foreach($tablesExists as $key => $table) $tablesExists[$key] = current((array)$table);
        $tablesExists = array_flip($tablesExists);

        $oldTable = $this->config->db->prefix . 'repoHistory';
        $newTable = $this->config->db->prefix . 'repohistory';

        if(isset($tablesExists[$oldTable]))
        {
            $upgradebak = $newTable . '_othertablebak';
            if(isset($tablesExists[$upgradebak])) $this->dbh->query("DROP TABLE `$upgradebak`");
            if(isset($tablesExists[$newTable])) $this->dbh->query("RENAME TABLE `$newTable` TO `$upgradebak`");

            $tempTable = $oldTable . '_zentaotmp';
            $this->dbh->query("RENAME TABLE `$oldTable` TO `$tempTable`");
            $this->dbh->query("RENAME TABLE `$tempTable` TO `$newTable`");
        }
    }
}

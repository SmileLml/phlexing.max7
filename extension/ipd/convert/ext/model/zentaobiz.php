<?php
/**
 * 获取禅道对象列表。
 * Get zentao object list.
 *
 * @access public
 * @return array
 */
public function getZentaoObjectList()
{
    $objectList     = array();
    $workflowObject = $this->dao->dbh($this->dbh)->select('module,name')->from(TABLE_WORKFLOW)->where('buildin')->eq('0')->andWhere('status')->eq('normal')->andWhere('type')->eq('flow')->fetchPairs();
    if(!$this->config->enableER) unset($this->lang->convert->jira->zentaoObjectList['epic']);
    if(!$this->config->URAndSR)  unset($this->lang->convert->jira->zentaoObjectList['requirement']);
    foreach($this->lang->convert->jira->zentaoObjectList as $type => $text)
    {
        if($type == 'add_custom') $objectList = array_merge($objectList, $workflowObject);
        $objectList[$type] = $text;
    }
    return $objectList;
}

/**
 * 获取禅道关联关系列表。
 * Get zentao relation list.
 *
 * @access public
 * @return array
 */
public function getZentaoRelationList()
{
    $customRelationList = $this->loadModel('custom')->getRelationList(true);
    return arrayUnion($customRelationList, $this->lang->convert->jira->zentaoLinkTypeList);
}

/**
 * 获取禅道字段列表。
 * Get zentao fields.
 *
 * @param  string $module
 * @access public
 * @return array
 */
public function getZentaoFields($module)
{
    $this->app->loadLang($module);

    $fields = array();
    if(isset($this->config->convert->objectFields[$module]))
    {
        foreach($this->config->convert->objectFields[$module] as $field)
        {
            $fields[$field] = $this->lang->{$module}->$field;
        }
    }

    $workflowFields = $this->dao->dbh($this->dbh)->select('field,name')->from(TABLE_WORKFLOWFIELD)
        ->where('module')->eq($module)
        ->andWhere('field')->notin('subStatus,status,closedReason')
        ->andWhere('buildin')->eq('0')
        ->fetchPairs();

    $fields = arrayUnion($fields, $workflowFields);

    $fields['add_field'] = $this->lang->convert->add;

    return $fields;
}

/**
 * 获取禅道状态列表。
 * Get zentao status.
 *
 * @param  string $module
 * @access public
 * @return array
 */
public function getZentaoStatus($module)
{
    $this->loadModel($module);

    $flow = $this->dao->select('id')->from(TABLE_WORKFLOW)->where('buildin')->eq('0')->andWhere('module')->eq($module)->fetch();
    if($flow)
    {
        $fieldOptions = $this->dao->select('options')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->eq('status')->fetch('options');
        $statusList   = json_decode($fieldOptions, true);
        $statusList   = array_merge($statusList, array('add_flow_status' => $this->lang->convert->add));
    }
    else
    {
        $statusList = $this->lang->{$module}->statusList;
        if($module == 'testcase') $statusList = array_merge($statusList, array('add_case_status' => $this->lang->convert->add));
    }
    return $statusList;
}

/**
 * 获取禅道动作列表。
 * Get zentao actions.
 *
 * @param  string $module
 * @access public
 * @return array
 */
public function getZentaoActions($module)
{
    $workflowActions = $this->dao->select('action,name')->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->fetchPairs();
    $workflowActions = array_merge($workflowActions, array('add_action' => $this->lang->convert->add));
    return $workflowActions;
}

/**
 * 获取禅道文档目录。
 * Get zentao doc lib.
 *
 * @param  string $spaceType
 * @access public
 * @return array
 */
public function getZentaoDocLib($spaceType = 'mine')
{
    $defaultValue = '';
    if($spaceType == 'product')
    {
        $programs = $this->loadModel('program')->getPairs(true);
        $products = $this->loadModel('product')->getList();
        $programProducts = array();
        foreach($products as $product)
        {
            if(empty($defaultValue)) $defaultValue = $product->id;
            $programProducts[$product->program][] = $product;
        }

        $items = array();
        foreach($programProducts as $programID => $products)
        {
            $childItems = array();
            foreach($products as $product) $childItems[] = array('text' => $product->name, 'value' => $product->id);
            $items[] = array('text' => isset($programs[$programID]) ? $programs[$programID] : $this->lang->product->emptyProgram, 'subtitle' => $this->lang->program->common, 'items' => $childItems);
        }
    }
    elseif($spaceType == 'project')
    {
        $programs = $this->loadModel('program')->getPairs(true);
        $projects = $this->loadModel('project')->getListByCurrentUser();
        $orderedProjects = array();
        foreach($projects as $project)
        {
            if(empty($defaultValue)) $defaultValue = $project->id;
            $project->parent = $this->program->getTopByID($project->parent);
            $project->parent = isset($programs[$project->parent]) ? $project->parent : 0;

            $orderedProjects[$project->parent][] = $project;
        }

        $items = array();
        foreach($orderedProjects as $programID => $projects)
        {
            $childItems = array();
            foreach($projects as $project) $childItems[] = array('text' => $project->name, 'value' => $project->id, 'icon' => $project->model == 'scrum' ? 'sprint' : $project->model);
            $items[] = array('text' => isset($programs[$programID]) ? $programs[$programID] : $this->lang->project->emptyProgram, 'subtitle' => $this->lang->program->common, 'items' => $childItems);
        }
    }
    else
    {
        $items[] = array('text' => $this->lang->convert->confluence->defaultSpace, 'value' => 'defaultSpace');
    }

    return array('items' => $items, 'defaultValue' => $defaultValue);
}

/**
 * 从DB文件中导入Confluence数据。
 * Import Confluence from db.
 *
 * @param  string $type user|space
 * @param  string $nextObject
 * @param  bool   $createTable
 * @access public
 * @return array
 */
public function importConfluenceData($type = '', $nextObject = 'no', $createTable = false)
{
    if($createTable) $this->createTmpTable4Confluence();

    if(empty($type)) $type = key($this->lang->convert->confluence->objectList);

    foreach(array_keys($this->lang->convert->confluence->objectList) as $module)
    {
        if($module != $type && $nextObject == 'yes') continue;
        if($module == $type && $nextObject == 'yes')
        {
            $nextObject = 'no';
            continue;
        }

        $dataList = $this->convertTao->getConfluenceData($module);
        if(empty($dataList)) continue;

        if($module == 'user')       $this->convertTao->importConfluenceUser($dataList);
        if($module == 'space')      $this->convertTao->importConfluenceSpace($dataList);
        if($module == 'folder')     $this->convertTao->importConfluenceFolder($dataList);
        if($module == 'page')       $this->convertTao->importConfluencePage($dataList, $module);
        if($module == 'embed')      $this->convertTao->importConfluencePage($dataList, $module);
        if($module == 'database')   $this->convertTao->importConfluencePage($dataList, $module);
        if($module == 'whiteboard') $this->convertTao->importConfluencePage($dataList, $module);
        if($module == 'blogpost')   $this->convertTao->importConfluenceBlogPost($dataList);
        if($module == 'draft')      $this->convertTao->importConfluenceDraftPage($dataList);
        if($module == 'archived')   $this->convertTao->importConfluenceArchivedPage($dataList);
        if($module == 'version')    $this->convertTao->importConfluenceVersion($dataList);
        if($module == 'comment')    $this->convertTao->importConfluenceComment($dataList);
        if($module == 'attachment') $this->convertTao->importConfluenceAttachment($dataList);

        if(in_array($module, array('folder', 'page', 'embed', 'database', 'whiteboard'))) $count = $this->dao->dbh($this->dbh)->select('id')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq("c$module")->count();
        if($module == 'version') $count = $this->dao->dbh($this->dbh)->select('id')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq("zdoccontent")->andWhere('extra')->gt(1)->count();

        return array('type' => $module, 'count' => !empty($count) ? $count : count($dataList));
    }

    unset($_SESSION['confluenceRelation']);
    unset($_SESSION['confluenceUser']);
    unset($_SESSION['confluenceUsers']);
    unset($_SESSION['confluenceUserGroup']);
    return array('finished' => true);
}

/**
 * 创建confluence导入数据表。
 * Create tmp table for import confluence.
 *
 * @access public
 * @return void
 */
public function createTmpTable4Confluence()
{
    $sql = <<<EOT
CREATE TABLE `confluencetmprelation`(
    `id` int(8) NOT NULL AUTO_INCREMENT,
    `AType` char(30) NOT NULL,
    `AID` char(100) NOT NULL,
    `BType` char(30) NOT NULL,
    `BID` char(100) NOT NULL,
    `extra` char(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;

    try
    {
        $this->dbh->exec($sql);
    }
    catch(Exception $e){}
}

/**
 * 获取禅道空间列表。
 * Get zentao space list.
 *
 * @access public
 * @return array
 */
public function getZentaoSpace()
{
    return $this->lang->convert->confluence->zentaoSpaceList;
}

/**
 * 验证Confluence Api接口能否访问。
 * Check confluence api.
 *
 * @access public
 * @return bool
 */
public function checkConfluenceApi()
{
    $confluenceApi = json_decode($this->session->confluenceApi, true);
    if(empty($confluenceApi['domain']))
    {
        dao::$errors['message'] = $this->lang->convert->confluence->apiError;
        return false;
    }

    $token     = base64_encode("{$confluenceApi['admin']}:{$confluenceApi['token']}");
    $url       = $confluenceApi['domain'] . '/rest/api/space';
    $spaceList = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));
    if(empty($spaceList->results))
    {
        dao::$errors['message'] = $this->lang->convert->confluence->apiError;
        return false;
    }
    return true;
}

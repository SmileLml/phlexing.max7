<?php
/**
 * 创建自定义关联关系。
 * Create relation.
 *
 * @param  array  $relations
 * @access public
 * @return array
 */
public function createRelation($relations)
{
    $this->loadModel('custom');
    $issueLink    = $this->getJiraData($this->session->jiraMethod, 'issuelinktype');
    $maxKey       = $this->dao->select('max(cast(`key` as SIGNED)) as maxKey')->from(TABLE_LANG)->where('section')->eq('relationList')->andWhere('module')->eq('custom')->fetch('maxKey');
    $maxKey       = $maxKey ? $maxKey : 1;
    foreach($relations['zentaoLinkType'] as $jiraCode => $zentaoCode)
    {
        if($zentaoCode != 'add_relation') continue;

        $maxKey  += 1;
        $relation = new stdclass();
        $relation->relation         = $issueLink[$jiraCode]->inward;
        $relation->relativeRelation = $issueLink[$jiraCode]->outward;

        $this->custom->setItem("all.custom.relationList.{$maxKey}.0", json_encode($relation));

        $relations['zentaoLinkType'][$jiraCode] = $maxKey;
    }

    return $relations;
}


/**
 * 更新对象关联关系。
 * Update object relation.
 *
 * @param  array  $issueLinkTypeList
 * @param  array  $dataList
 * @param  array  $issueList
 * @access public
 * @return bool
 */
public function updateObjectRelation($issueLinkTypeList = array(), $dataList = array(), $issueList = array())
{
    if(empty($issueLinkTypeList) || empty($dataList) || empty($issueList)) return false;

    $linkRelation = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jissuelink')->fetchAll('AID');
    foreach($dataList as $data)
    {
        if(!empty($linkRelation[$data->id])) continue;

        $issueRelation = zget($issueLinkTypeList, $data->linktype, '');
        if(in_array($issueRelation, array('subTaskLink', 'subStoryLink'))) $issueRelation = 'subdivideinto';
        if(in_array($issueRelation, array('duplicate', 'relates'))) $issueRelation = '1';

        $relation = new stdClass();
        $relation->relation = $issueRelation;
        $relation->AID      = zget($issueList[$data->source], 'BID', 0);
        $relation->AType    = preg_replace('/^z/', '', zget($issueList[$data->source], 'BType', 'z'), 1);
        $relation->BID      = zget($issueList[$data->destination], 'BID', 0);
        $relation->BType    = preg_replace('/^z/', '', zget($issueList[$data->destination], 'BType', 'z'), 1);
        $relation->product  = 0;
        if(!in_array($relation->AType, array_keys($this->lang->convert->jira->zentaoObjectList))) $relation->AType = 'workflow_' . $relation->AType;
        if(!in_array($relation->BType, array_keys($this->lang->convert->jira->zentaoObjectList))) $relation->BType = 'workflow_' . $relation->BType;

        if(empty($relation->relation) || empty($relation->AID) || empty($relation->AType) || empty($relation->BID) || empty($relation->BType)) continue;
        $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        $relationID = $this->dao->dbh($this->dbh)->lastInsertID();

        $relation = array();
        $relation['AType'] = 'jissuelink';
        $relation['BType'] = 'zrelation';
        $relation['AID']   = $data->id;
        $relation['BID']   = $relationID;
        $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($relation)->exec();
    }

    return true;
}

/**
 * 创建自定义工作流数据。
 * Create flow data.
 *
 * @param  int    $productID
 * @param  int    $projectID
 * @param  string $issueType
 * @param  object $data
 * @param  array  $relations
 * @param  array  $workflows
 * @access public
 * @return bool
 */
public function createFlowData($productID, $projectID, $issueType, $data, $relations, $workflows)
{
    if(empty($workflows[$issueType])) return false;

    $flow = new stdclass();
    $flow = $this->processBuildinFieldData($issueType, $data, $flow, $relations, true);

    $flow->product     = $productID;
    $flow->project     = $projectID;
    $flow->status      = $this->convertStatus('flow', $data->issuestatus, $data->issuetype, $relations);
    $flow->createdBy   = $this->getJiraAccount(isset($data->creator) ? $data->creator : '');
    $flow->createdDate = isset($data->created) ? substr($data->created, 0, 19) : null;
    $flow->assignedTo  = $flow->status == 'closed' ? 'closed' : $this->getJiraAccount(isset($data->assignee) ? $data->assignee : '');

    $this->dao->dbh($this->dbh)->insert($workflows[$issueType])->data($flow)->exec();
    $flowID = $this->dao->dbh($this->dbh)->lastInsertID();

    /* Create opened action from openedDate. */
    $action = new stdclass();
    $action->objectType = $issueType;
    $action->objectID   = $flowID;
    $action->actor      = $flow->createdBy;
    $action->action     = 'create';
    $action->date       = $flow->createdDate;
    $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();

    $flowRelation['AType'] = 'jissue';
    $flowRelation['BType'] = 'z' . $issueType;
    $flowRelation['AID']   = $data->id;
    $flowRelation['BID']   = $flowID;
    $flowRelation['extra'] = 'issue';
    $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($flowRelation)->exec();

    $issueRelation['AType'] = 'jissueid';
    $issueRelation['BType'] = 'zissuetype';
    $issueRelation['AID']   = $data->id;
    $issueRelation['extra'] = $issueType;
    $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($issueRelation)->exec();

    return true;
}

/**
 * 获取confluence用户。
 * Get confluence account.
 *
 * @param  string $userKey
 * @access public
 * @return string
 */
public function getConfluenceAccount($userKey)
{
    if(empty($userKey) || empty($_SESSION['confluenceUsers'])) return '';

    $confluenceUsers = json_decode($this->session->confluenceUsers, true);
    return zget($confluenceUsers, $userKey);
}

/**
 * 处理Confluence用户名为禅道格式。
 * Process confluence user.
 *
 * @param  string $confluenceAccount
 * @param  string $confluenceEmail
 * @param  int    $i
 * @access public
 * @return string
 */
public function processConfluenceUser($confluenceAccount, $confluenceEmail, $i)
{
    $userConfig = $this->session->confluenceUser;
    $account    = substr($confluenceAccount, 0, 30);
    if($userConfig['mode'] == 'email')
    {
        if(strpos($confluenceEmail, '@') !== false)
        {
            $account = substr(substr($confluenceEmail, 0, strpos($confluenceEmail, '@')), 0, 30);
        }
        else
        {
            $account = 'zentao' . $i;
        }
    }

    return $account;
}

/**
 * 导入user数据。
 * Import confluence user.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 */
public function importConfluenceUser($dataList)
{
    $localUsers   = $this->dao->dbh($this->dbh)->select('account')->from(TABLE_USER)->where('deleted')->eq('0')->fetchPairs();
    $userEmail    = $this->dao->dbh($this->dbh)->select('email,account')->from(TABLE_USER)->where('deleted')->eq('0')->andWhere('email')->ne('')->fetchPairs();
    $userRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('cuser')->fetchPairs();
    $userConfig   = $this->session->confluenceUser;
    $i            = 1;

    foreach($dataList as $data)
    {
        $accountKey = !empty($data->accountId) ? $data->accountId : zget($data, 'userKey', '');
        $accountID  = !empty($data->accountId) ? $data->accountId : zget($data, 'username', '');
        if(!empty($userRelation[$accountKey])) continue;

        if(!isset($data->email)) $data->email = '';

        $user = new stdclass();
        $user->account = $this->processConfluenceUser($accountID, $data->email, $i);
        if(!isset($localUsers[$user->account]) && !isset($userEmail[$data->email]))
        {
            $user->realname = isset($data->publicName) ? $data->publicName : zget($data, 'displayName', '');
            $user->password = $userConfig['password'];
            $user->email    = isset($data->email) ? $data->email : '';
            $user->gender   = 'm';
            $user->type     = 'inside';
            $user->join     = helper::now();

            $this->dao->dbh($this->dbh)->replace(TABLE_USER)->data($user, 'group')->exec();

            if(!dao::isError() && !empty($userConfig['group']))
            {
                $group = new stdclass();
                $group->account = $user->account;
                $group->group   = $userConfig['group'];
                $group->project = '';

                $this->dao->dbh($this->dbh)->replace(TABLE_USERGROUP)->data($group)->exec();
            }
        }

        $relation['AType'] = 'cuser';
        $relation['BType'] = 'zuser';
        $relation['AID']   = $accountKey;
        $relation['BID']   = $user->account;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($relation)->exec();

        $userRelation[$accountKey] = $user->account;
        $i ++;
    }

    $this->session->set('confluenceUsers', json_encode($userRelation));
    return true;
}

/**
 * 创建导入所需的我的默认空间。
 * Create default my space.
 *
 * @param  object $space
 * @param  array  $spaceRelation
 * @access public
 * @return array
 */
public function createDefaultMineSpace($space, $spaceRelation)
{
    $addedBy = $this->app->user->account;
    if(!empty($space->history->createdBy->accountId)) $addedBy = $this->getConfluenceAccount($space->history->createdBy->accountId);
    if(!empty($space->creator->userKey))              $addedBy = $this->getConfluenceAccount($space->creator->userKey);

    /* 我的空间下创建默认空间。 */
    $docSpace = new stdclass();
    $docSpace->name      = $this->lang->convert->confluence->defaultSpace;
    $docSpace->parent    = '0';
    $docSpace->type      = 'mine';
    $docSpace->acl       = 'open';
    $docSpace->vision    = $this->config->vision;
    $docSpace->addedDate = helper::now();
    $docSpace->addedBy   = $addedBy;

    if(!empty($spaceRelation[$docSpace->addedBy])) return $spaceRelation;

    $mineSpaceID = $this->loadModel('doc')->doInsertLib($docSpace);
    $this->loadModel('action')->create('docspace', $mineSpaceID, 'created');

    $relation['AType'] = 'cspace';
    $relation['BType'] = 'zspace';
    $relation['AID']   = $space->id;
    $relation['BID']   = $mineSpaceID;
    $relation['extra'] = $docSpace->addedBy;
    $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($relation)->exec();

    $spaceRelation[$docSpace->addedBy] = $mineSpaceID;
    return $spaceRelation;
}

/**
 * 创建导入所需的默认团队空间。
 * Create default custom space.
 *
 * @access public
 * @return int
 */
public function createDefaultCustomSpace()
{
    $spaceID = $this->dao->dbh($this->dbh)->select('BID')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('cspace')->andWhere('BType')->eq('zspace')->andWhere('extra')->eq('custom')->andWhere('AID')     ->eq('0')->fetch('BID');
    if($spaceID) return $spaceID;

    /* 团队空间下创建默认空间。 */
    $space = new stdclass();
    $space->name      = $this->lang->convert->confluence->defaultSpace;
    $space->parent    = '0';
    $space->type      = 'custom';
    $space->acl       = 'open';
    $space->vision    = $this->config->vision;
    $space->addedDate = helper::now();
    $space->addedBy   = $this->app->user->account;

    $customSpaceID = $this->doc->doInsertLib($space);
    $this->loadModel('action')->create('docspace', $customSpaceID, 'created');

    $relation['AType'] = 'cspace';
    $relation['BType'] = 'zspace';
    $relation['AID']   = '0';
    $relation['BID']   = $customSpaceID;
    $relation['extra'] = 'custom';
    $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($relation)->exec();

    return $customSpaceID;
}

/**
 * 导入Confluence空间。
 * Import confluence space.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 */
public function importConfluenceSpace($dataList)
{
    $this->loadModel('doc');
    $this->loadModel('action');
    $customSpaceID      = $this->createDefaultCustomSpace();
    $confluenceRelation = $this->session->confluenceRelation;
    $confluenceRelation = $confluenceRelation ? json_decode($confluenceRelation, true) : array();
    $spaceRelation      = $this->dao->dbh($this->dbh)->select('extra,BID')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('cspace')->andWhere('BType')->eq('zspace')->andWhere('AID')->ne('0')->fetchPairs();
    $libRelation        = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('cspace')->andWhere('Btype')->eq('zlib')->fetchPairs();
    foreach($dataList as $space)
    {
        if(!empty($libRelation[$space->id])) continue;

        $spaceType = $this->checkConfluenceSpacePriv($space);
        $spaceType = !empty($confluenceRelation['zentaoSpace'][$space->id]) ? $confluenceRelation['zentaoSpace'][$space->id] : $spaceType;
        if($spaceType == 'mine') $spaceRelation = $this->createDefaultMineSpace($space, $spaceRelation);

        $lib = new stdclass();
        $lib->name      = mb_substr($space->name, 0, 50) . ($space->status == 'archived' ? "({$this->lang->convert->confluence->archived})" : '');
        $lib->spaceName = '';
        $lib->parent    = '0';
        $lib->baseUrl   = '';
        $lib->acl       = 'private';
        $lib->type      = $spaceType;
        $lib->product   = $lib->type == 'product' && !empty($confluenceRelation['zentaoDocLib'][$space->id]) ? $confluenceRelation['zentaoDocLib'][$space->id] : '0';
        $lib->project   = $lib->type == 'project' && !empty($confluenceRelation['zentaoDocLib'][$space->id]) ? $confluenceRelation['zentaoDocLib'][$space->id] : '0';
        $lib->execution = '0';
        $lib->groups    = '';
        $lib->users     = '';
        $lib->archived  = $space->status == 'archived' ? '1' : '0';
        $lib->vision    = $this->config->vision;
        $lib->orderBy   = 'id_asc';
        $lib->addedBy   = $this->app->user->account;
        $lib->addedDate = helper::now();
        if(!empty($space->history->createdBy->accountId)) $lib->addedBy   = $this->getConfluenceAccount($space->history->createdBy->accountId);
        if(!empty($space->creator->userKey))              $lib->addedBy   = $this->getConfluenceAccount($space->creator->userKey);
        if(!empty($space->history->createdDate))          $lib->addedDate = date('Y-m-d H:i:s', strtotime($space->history->createdDate));
        if(!empty($space->creationDate))                  $lib->addedDate = date('Y-m-d H:i:s', strtotime($space->creationDate));
        if($lib->type == 'mine')                          $lib->parent    = $spaceRelation[$lib->addedBy];
        if($lib->type == 'custom')                        $lib->parent    = $customSpaceID;
        if($lib->type != 'mine')                          $lib->users     = $this->getConfluenceSpaceUsers($space);

        $libID = $this->doc->createLib($lib);
        $this->action->create('docLib', $libID, 'Created');

        $tmpRelation['AType'] = 'cspace';
        $tmpRelation['BType'] = 'zlib';
        $tmpRelation['AID']   = $space->id;
        $tmpRelation['BID']   = $libID;
        $tmpRelation['extra'] = !empty($space->homepage->id) ? $space->homepage->id : '';
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();
    }

    return true;
}

/**
 * 创建Confluence文档内容，便于存储到 zt_doc 表 rawContent 字段。
 * Create confluence doc content, for store to zt_doc rawContent field.
 *
 * @param  string $content
 * @access public
 * @return string
 */
public function createConfluenceDocContent($content)
{
    return json_encode(array('$migrate' => 'confluence', '$data' => $content));
}

/**
 * 导入Confluence文件夹。
 * Import confluence folder.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 */
public function importConfluenceFolder($dataList)
{
    $libList      = $this->dao->dbh($this->dbh)->select('id,parent,type,project,product,execution,archived,name')->from(TABLE_DOCLIB)->fetchAll('id');
    $docRelation  = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoc')->fetchPairs();
    foreach($dataList as $libID => $folderList)
    {
        $this->loadModel('action');
        $archived = zget($libList[$libID], 'archived', 0);
        $space    = $libList[$libList[$libID]->parent];
        foreach($folderList as $folder)
        {
            if(!empty($docRelation[$folder->id]))
            {
                $this->importConfluenceChild($libID, 0, (int)$folder->id, $docRelation[$folder->id], 2, $libList, $docRelation);
                continue;
            }

            $doc = new stdclass();
            $doc->title      = mb_substr($folder->title, 0, 50)  . ($archived ? "({$this->lang->convert->confluence->archived})" : '');
            $doc->version    = '1';
            $doc->lib        = $libID;
            $doc->product    = $doc->lib ? zget($libList[$doc->lib], 'product',   '0') : '0';
            $doc->project    = $doc->lib ? zget($libList[$doc->lib], 'project',   '0') : '0';
            $doc->execution  = $doc->lib ? zget($libList[$doc->lib], 'execution', '0') : '0';
            $doc->status     = 'normal';
            $doc->type       = 'chapter';
            $doc->path       = '';
            $doc->grade      = '1';
            $doc->order      = 0;
            $doc->editedBy   = !empty($folder->version->by->accountId) ? $this->getConfluenceAccount($folder->version->by->accountId) : $this->app->user->account;
            $doc->editedDate = !empty($folder->version->when) ? date('Y-m-d H:i:s', strtotime($folder->version->when)) : helper::now();
            $doc->addedBy    = !empty($folder->history->createdBy->accountId) ? $this->getConfluenceAccount($folder->history->createdBy->accountId) : $doc->editedBy;
            $doc->addedDate  = !empty($folder->history->createdDate) ? date('Y-m-d H:i:s', strtotime($folder->history->createdDate)) : $doc->editedDate;
            $doc->acl        = $space->type == 'mine' ? 'private' : '';
            if($space->type != 'mine') $doc = $this->setDocPrivUsers($folder, $doc);

            $this->dao->insert(TABLE_DOC)->data($doc)->exec();
            $docID = $this->dao->lastInsertID();

            $this->action->create('doc', $docID, 'Created');

            $tmpRelation['AType'] = "cfolder";
            $tmpRelation['BType'] = 'zdoc';
            $tmpRelation['AID']   = $folder->id;
            $tmpRelation['BID']   = $docID;
            $tmpRelation['extra'] = $doc->grade;
            $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

            $docContent = new stdclass();
            $docContent->title      = $doc->title;
            $docContent->content    = !empty($folder->body->view->value) ? $folder->body->view->value : '';
            $docContent->type       = 'doc';
            $docContent->rawContent = $this->createConfluenceDocContent(!empty($folder->body->storage->value) ? $folder->body->storage->value : '');
            $docContent->digest     = !empty($folder->version->message) ? $folder->version->message : '';
            $docContent->version    = $doc->version;
            $docContent->doc        = $docID;
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();

            $this->importConfluenceChild($libID, 0, (int)$folder->id, $docID, 2, $libList, $docRelation);
        }
    }

    return true;
}

/**
 * 导入Confluence页面。
 * Import confluence page.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 * @param string $module
 */
public function importConfluencePage($dataList, $module)
{
    $this->loadModel('action');
    $libList     = $this->dao->dbh($this->dbh)->select('id,parent,type,project,product,execution,archived,name')->from(TABLE_DOCLIB)->fetchAll('id');
    $docRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoc')->fetchPairs();
    foreach($dataList as $libID => $pageList)
    {
        $order    = 0;
        $archived = zget($libList[$libID], 'archived', 0);
        $space    = $libList[$libList[$libID]->parent];
        foreach($pageList as $page)
        {
            if(!empty($docRelation[$page->id]))
            {
                $this->importConfluenceChild($libID, 0, (int)$page->id, $docRelation[$page->id], 2, $libList, $docRelation);
                continue;
            }

            $order = $order + 10;

            $doc = new stdclass();
            $doc->title      = $page->title . ($archived ? "({$this->lang->convert->confluence->archived})" : '');
            $doc->version    = !empty($page->version->number) ? $page->version->number : '1';
            $doc->module     = '0';
            $doc->lib        = $libID;
            $doc->product    = $doc->lib ? zget($libList[$doc->lib], 'product',   '0') : '0';
            $doc->project    = $doc->lib ? zget($libList[$doc->lib], 'project',   '0') : '0';
            $doc->execution  = $doc->lib ? zget($libList[$doc->lib], 'execution', '0') : '0';
            $doc->status     = 'normal';
            $doc->type       = 'text';
            $doc->parent     = '0';
            $doc->path       = '';
            $doc->grade      = '0';
            $doc->order      = $order;
            $doc->acl        = $space->type == 'mine' ? 'private' : '';
            $doc->addedBy    = $this->app->user->account;
            $doc->addedDate  = !empty($page->history->createdDate) ? date('Y-m-d H:i:s', strtotime($page->history->createdDate)) : helper::now();
            $doc->editedBy   = $this->app->user->account;
            $doc->editedDate = !empty($page->version->when) ? date('Y-m-d H:i:s', strtotime($page->version->when)) : helper::now();
            if(!empty($page->history->createdBy->accountId)) $doc->addedBy  = $this->getConfluenceAccount($page->history->createdBy->accountId);
            if(!empty($page->history->createdBy->userKey))   $doc->addedBy  = $this->getConfluenceAccount($page->history->createdBy->userKey);
            if(!empty($page->version->by->accountId))        $doc->editedBy = $this->getConfluenceAccount($page->version->by->accountId);
            if(!empty($page->version->by->userKey))          $doc->editedBy = $this->getConfluenceAccount($page->version->by->userKey);
            if($space->type != 'mine') $doc = $this->setDocPrivUsers($page, $doc);

            if(!$doc->title) $doc->title = $this->lang->convert->confluence->undefined;
            $this->dao->insert(TABLE_DOC)->data($doc)->exec();
            $docID = $this->dao->lastInsertID();

            $this->action->create('doc', $docID, 'Created');


            $tmpRelation['AType'] = "c$module";
            $tmpRelation['BType'] = 'zdoc';
            $tmpRelation['AID']   = $page->id;
            $tmpRelation['BID']   = $docID;
            $tmpRelation['extra'] = $doc->grade;
            $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

            $docContent = new stdclass();
            $docContent->title      = $doc->title ? $doc->title : $this->lang->convert->confluence->undefined;
            $docContent->content    = !empty($page->body->view->value) ? $page->body->view->value : '';
            $docContent->type       = 'doc';
            $docContent->rawContent = $this->createConfluenceDocContent(!empty($page->body->storage->value) ? $page->body->storage->value : '');
            $docContent->digest     = !empty($page->version->message) ? $page->version->message : '';
            $docContent->version    = $doc->version;
            $docContent->doc        = $docID;
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();

            $docContentID = $this->dao->lastInsertID();

            $tmpRelation['AType'] = "c$module";
            $tmpRelation['BType'] = 'zdoccontent';
            $tmpRelation['AID']   = $page->id;
            $tmpRelation['BID']   = $docContentID;
            $tmpRelation['extra'] = $docContent->version;
            $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

            $this->importConfluenceChild($libID, 0, (int)$page->id, $docID, $doc->grade + 1, $libList, $docRelation);
        }
    }

    return true;
}

/**
 * 导入Confluence子元素。
 * Import confluence child.
 *
 * @param  int    $libID
 * @param  int    $moduleID
 * @param  int    $objectID
 * @param  int    $parent
 * @param  int    $grade
 * @param  array  $libList
 * @param  int    $start
 * @access public
 * @return bool
 * @param mixed[] $docRelation
 */
public function importConfluenceChild($libID, $moduleID, $objectID, $parent = 0, $grade = 0, $libList = array(), $docRelation = array(), $start = 0)
{
    $this->loadModel('action');
    if(empty($_SESSION['confluenceApi'])) return false;
    $confluenceApi = json_decode($this->session->confluenceApi, true);
    if(empty($confluenceApi['domain'])) return false;

    $archived = zget($libList[$libID], 'archived', 0);
    $space    = $libList[$libList[$libID]->parent];
    foreach(array('folder', 'page', 'embed', 'database', 'whiteboard') as $objectType)
    {
        $token  = base64_encode("{$confluenceApi['admin']}:{$confluenceApi['token']}");
        $url    = $confluenceApi['domain'] . "/rest/api/content/{$objectID}/child/{$objectType}?start={$start}&limit=500&expand=body.storage,body.view,version,history,restrictions.read.restrictions.user,restrictions.update.restrictions.user,restrictions.read.restrictions.group,restrictions.update.restrictions.group";
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));
        if(!empty($result->results))
        {
            $order = 0;
            foreach($result->results as $object)
            {
                if(!empty($docRelation[$object->id]))
                {
                    $this->importConfluenceChild($libID, $moduleID, (int)$object->id, $docRelation[$object->id], $grade + 1, $libList, $docRelation);
                    continue;
                }

                $order = $order + 10;

                $doc = new stdclass();
                $doc->title      = $object->title . ($archived ? "({$this->lang->convert->confluence->archived})" : '');
                $doc->version    = !empty($object->version->number) ? $object->version->number : '1';
                $doc->module     = $moduleID;
                $doc->lib        = $libID;
                $doc->product    = $doc->lib ? zget($libList[$doc->lib], 'product',   '0') : '0';
                $doc->project    = $doc->lib ? zget($libList[$doc->lib], 'project',   '0') : '0';
                $doc->execution  = $doc->lib ? zget($libList[$doc->lib], 'execution', '0') : '0';
                $doc->status     = 'normal';
                $doc->type       = $objectType == 'folder' ? 'chapter' : 'text';
                $doc->parent     = $parent;
                $doc->path       = '';
                $doc->grade      = $grade;
                $doc->order      = $order;
                $doc->acl        = $space->type == 'mine' ? 'private' : '';
                $doc->addedBy    = $this->app->user->account;
                $doc->addedDate  = !empty($object->history->createdDate) ? date('Y-m-d H:i:s', strtotime($object->history->createdDate)) : helper::now();
                $doc->editedBy   = $this->app->user->account;
                $doc->editedDate = !empty($object->version->when) ? date('Y-m-d H:i:s', strtotime($object->version->when)) : helper::now();
                if(!empty($object->history->createdBy->accountId)) $doc->addedBy  = $this->getConfluenceAccount($object->history->createdBy->accountId);
                if(!empty($object->history->createdBy->userKey))   $doc->addedBy  = $this->getConfluenceAccount($object->history->createdBy->userKey);
                if(!empty($object->version->by->accountId))        $doc->editedBy = $this->getConfluenceAccount($object->version->by->accountId);
                if(!empty($object->version->by->userKey))          $doc->editedBy = $this->getConfluenceAccount($object->version->by->userKey);
                if($space->type != 'mine') $doc = $this->setDocPrivUsers($object, $doc);

                if(!$doc->title) $doc->title = $this->lang->convert->confluence->undefined;

                $this->dao->insert(TABLE_DOC)->data($doc)->exec();
                $docID = $this->dao->lastInsertID();

                $this->action->create('doc', $docID, 'Created');

                $tmpRelation['AType'] = "c$objectType";
                $tmpRelation['BType'] = "zdoc";
                $tmpRelation['AID']   = $object->id;
                $tmpRelation['BID']   = $docID;
                $tmpRelation['extra'] = $doc->grade;
                $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

                if($doc->type == 'text')
                {
                    $docContent = new stdclass();
                    $docContent->title      = $doc->title ? $doc->title : $this->lang->convert->confluence->undefined;
                    $docContent->content    = !empty($object->body->view->value) ? $object->body->view->value : '';
                    $docContent->type       = 'doc';
                    $docContent->rawContent = json_encode(array('$migrate' => 'confluence', '$data' => !empty($object->body->storage->value) ? $object->body->storage->value : ''));
                    $docContent->digest     = !empty($object->version->message) ? $object->version->message : '';
                    $docContent->version    = $doc->version;
                    $docContent->doc        = $docID;
                    $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();

                    $docContentID = $this->dao->lastInsertID();

                    $tmpRelation['AType'] = "c$objectType";
                    $tmpRelation['BType'] = 'zdoccontent';
                    $tmpRelation['AID']   = $object->id;
                    $tmpRelation['BID']   = $docContentID;
                    $tmpRelation['extra'] = $docContent->version;
                    $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();
                }

                $this->importConfluenceChild($libID, $moduleID, (int)$object->id, $docID, $doc->grade + 1, $libList, $docRelation);
            }
            if($result->size == $result->limit) $this->importConfluenceChild($libID, $moduleID, $objectID, $parent, $grade, $libList, $docRelation, $result->start + $result->limit);
        }
    }

    return true;
}

/**
 * 创建Confluence博文所需的年份目录。
 * Create confluence blog dir
 *
 * @param  object $blogPost
 * @param  array  $blogDirList
 * @param  array  $spaceRelation
 * @param  bool   $archived
 * @access public
 * @return array
 */
public function createConfluenceBlogDir($blogPost, $blogDirList, $spaceRelation, $archived = false)
{
    if(empty($blogDirList[$blogPost->space->id]['module']))
    {
        $module = new stdclass();
        $module->root   = $spaceRelation[$blogPost->space->id];
        $module->type   = 'doc';
        $module->parent = '0';
        $module->name   = $this->lang->convert->confluence->objectList['blogpost'] . ($archived ? "({$this->lang->convert->confluence->archived})" : '');
        $module->branch = '0';
        $module->short  = '';
        $module->order  = '999';
        $module->grade  = '1';
        $this->dao->insert(TABLE_MODULE)->data($module)->exec();

        $moduleID = $this->dao->lastInsertID();

        $tmpRelation['AType'] = 'cblogpost';
        $tmpRelation['BType'] = 'zmodule';
        $tmpRelation['AID']   = $blogPost->id;
        $tmpRelation['BID']   = $moduleID;
        $tmpRelation['extra'] = $module->grade;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

        $blogDirList[$blogPost->space->id]['module'] = $moduleID;
    }

    $year = !empty($blogPost->history->createdDate) ? date('Y', strtotime($blogPost->history->createdDate)) : date('Y');
    if(empty($blogDirList[$blogPost->space->id][$year]))
    {
        $module = new stdclass();
        $module->root   = $spaceRelation[$blogPost->space->id];
        $module->type   = 'doc';
        $module->parent = $blogDirList[$blogPost->space->id]['module'];
        $module->name   = $year . ($archived ? "({$this->lang->convert->confluence->archived})" : '');
        $module->branch = '0';
        $module->short  = '';
        $module->order  = $year;
        $module->grade  = '2';
        $this->dao->insert(TABLE_MODULE)->data($module)->exec();

        $moduleID = $this->dao->lastInsertID();

        $tmpRelation['AType'] = 'cblogpost';
        $tmpRelation['BType'] = 'zmodule';
        $tmpRelation['AID']   = $blogPost->id;
        $tmpRelation['BID']   = $moduleID;
        $tmpRelation['extra'] = $module->grade;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

        $blogDirList[$blogPost->space->id][$year] = $moduleID;
    }

    return $blogDirList;
}

/**
 * 导入Confluence博文。
 * Import confluence blog post.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 */
public function importConfluenceBlogPost($dataList)
{
    $this->loadModel('action');
    $libList       = $this->dao->dbh($this->dbh)->select('id,parent,type,project,product,execution,archived,name')->from(TABLE_DOCLIB)->fetchAll('id');
    $spaceRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('cspace')->andWhere('BType')->eq('zlib')->fetchPairs();
    $docRelation   = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoc')->fetchPairs();
    $order         = 0;
    $blogDirList   = array();
    foreach($dataList as $blogPost)
    {
        if(!empty($docRelation[$blogPost->id])) continue;

        $order       = $order + 10;
        $libID       = $spaceRelation[$blogPost->space->id];
        $lib         = $libList[$libID];
        $archived    = !empty($lib->archived) ? $lib->archived : 0;
        $blogDirList = $this->createConfluenceBlogDir($blogPost, $blogDirList, $spaceRelation, $archived ? true : false);
        $space       = $libList[$libList[$libID]->parent];

        $doc = new stdclass();
        $doc->title      = (!empty($blogPost->title) ? $blogPost->title : $this->lang->convert->confluence->undefined) . ($archived ? "({$this->lang->convert->confluence->archived})" : '');
        $doc->version    = !empty($blogPost->version->number) ? $blogPost->version->number : '1';
        $doc->module     = $blogDirList[$blogPost->space->id][!empty($blogPost->history->createdDate) ? date('Y', strtotime($blogPost->history->createdDate)) : date('Y')];
        $doc->lib        = $libID;
        $doc->product    = $doc->lib ? zget($libList[$doc->lib], 'product',   '0') : '0';
        $doc->project    = $doc->lib ? zget($libList[$doc->lib], 'project',   '0') : '0';
        $doc->execution  = $doc->lib ? zget($libList[$doc->lib], 'execution', '0') : '0';
        $doc->status     = $blogPost->status == 'draft' ? 'draft' : 'normal';
        $doc->type       = 'text';
        $doc->parent     = '0';
        $doc->path       = '';
        $doc->grade      = '0';
        $doc->order      = $order;
        $doc->acl        = $space->type == 'mine' ? 'private' : '';
        $doc->addedBy    = $this->app->user->account;
        $doc->addedDate  = !empty($blogPost->history->createdDate) ? date('Y-m-d H:i:s', strtotime($blogPost->history->createdDate)) : helper::now();
        $doc->editedBy   = $this->app->user->account;
        $doc->editedDate = !empty($blogPost->version->when) ? date('Y-m-d H:i:s', strtotime($blogPost->version->when)) : helper::now();
        if(!empty($blogPost->history->createdBy->accountId)) $doc->addedBy  = $this->getConfluenceAccount($blogPost->history->createdBy->accountId);
        if(!empty($blogPost->history->createdBy->userKey))   $doc->addedBy  = $this->getConfluenceAccount($blogPost->history->createdBy->userKey);
        if(!empty($blogPost->version->by->accountId))        $doc->editedBy = $this->getConfluenceAccount($blogPost->version->by->accountId);
        if(!empty($blogPost->version->by->userKey))          $doc->editedBy = $this->getConfluenceAccount($blogPost->version->by->userKey);
        if($blogPost->status == 'draft') $doc->addedBy = $doc->editedBy;
        if($space->type != 'mine') $doc = $this->setDocPrivUsers($blogPost, $doc);
        $this->dao->insert(TABLE_DOC)->data($doc)->exec();

        $docID = $this->dao->lastInsertID();
        $this->action->create('doc', $docID, 'Created');

        $tmpRelation['AType'] = 'cblogpost';
        $tmpRelation['BType'] = 'zdoc';
        $tmpRelation['AID']   = $blogPost->id;
        $tmpRelation['BID']   = $docID;
        $tmpRelation['extra'] = $doc->grade;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

        $docContent = new stdclass();
        $docContent->title      = $doc->title ? $doc->title : $this->lang->convert->confluence->undefined;
        $docContent->content    = !empty($blogPost->body->view->value) ? $blogPost->body->view->value : '';
        $docContent->type       = 'doc';
        $docContent->rawContent = $this->createConfluenceDocContent(!empty($blogPost->body->storage->value) ? $blogPost->body->storage->value : '');
        $docContent->digest     = !empty($blogPost->version->message) ? $blogPost->version->message : '';
        $docContent->version    = $doc->version;
        $docContent->doc        = $docID;
        $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();

        $docContentID = $this->dao->lastInsertID();

        $tmpRelation['AType'] = 'cblogpost';
        $tmpRelation['BType'] = 'zdoccontent';
        $tmpRelation['AID']   = $blogPost->id;
        $tmpRelation['BID']   = $docContentID;
        $tmpRelation['extra'] = $docContent->version;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();
    }
    return true;
}

/**
 * 导入Confluence草稿页面。
 * Import confluence draft page.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 */
public function importConfluenceDraftPage($dataList)
{
    $this->loadModel('action');
    $docList     = $this->dao->dbh($this->dbh)->select('id,lib,module')->from(TABLE_DOC)->fetchAll('id');
    $libList     = $this->dao->dbh($this->dbh)->select('id,parent,type,project,product,execution,archived,name')->from(TABLE_DOCLIB)->fetchAll('id');
    $relations   = $this->dao->dbh($this->dbh)->select('*')->from(CONFLUENCE_TMPRELATION)->where('BType')->in('zmodule,zlib,zdoc')->fetchAll();
    $docRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoc')->fetchPairs();
    $parentList  = array();
    foreach($relations as $relation)
    {
        $parentList[$relation->AID]   = $relation;
        $parentList[$relation->extra] = $relation;
    }

    foreach($dataList as $page)
    {
        if(!empty($docRelation[$page->id])) continue;
        if(empty($page->ancestors)) continue;

        $parent = end($page->ancestors);
        if(!isset($parentList[$parent->id])) continue;
        $parent = $parentList[$parent->id];

        $docModule = '0';
        $docLib    = '0';
        if($parent->BType == 'zlib')    $docLib    = $parent->BID;
        if($parent->BType == 'zmodule') $docModule = $parent->BID;
        if($parent->BType == 'zdoc')
        {
            $parentDoc = $docList[$parent->BID];
            $docLib    = $parentDoc->lib;
            $docModule = $parentDoc->module;
        }

        $doc = new stdclass();
        $doc->title      = !empty($page->title) ? $page->title : $this->lang->convert->confluence->undefined;
        $doc->version    = '0';
        $doc->module     = $docModule;
        $doc->lib        = $docLib;
        $doc->product    = $doc->lib ? zget($libList[$doc->lib], 'product',   '0') : '0';
        $doc->project    = $doc->lib ? zget($libList[$doc->lib], 'project',   '0') : '0';
        $doc->execution  = $doc->lib ? zget($libList[$doc->lib], 'execution', '0') : '0';
        $doc->status     = 'draft';
        $doc->type       = 'text';
        $doc->parent     = $parent->BType == 'zdoc' ? $parent->BID : '0';
        $doc->path       = '';
        $doc->grade      = $parent->BType == 'zdoc' ? ((int)$parent->extra + 1) : '0';
        $doc->order      = '0';
        $doc->addedBy    = $this->app->user->account;
        $doc->addedDate  = !empty($page->history->createdDate) ? date('Y-m-d H:i:s', strtotime($page->history->createdDate)) : helper::now();
        $doc->editedBy   = $this->app->user->account;
        $doc->editedDate = !empty($page->version->when) ? date('Y-m-d H:i:s', strtotime($page->version->when)) : helper::now();
        if(!empty($page->history->createdBy->accountId)) $doc->addedBy  = $this->getConfluenceAccount($page->history->createdBy->accountId);
        if(!empty($page->history->createdBy->userKey))   $doc->addedBy  = $this->getConfluenceAccount($page->history->createdBy->userKey);
        if(!empty($page->version->by->accountId))        $doc->editedBy = $this->getConfluenceAccount($page->version->by->accountId);
        if(!empty($page->version->by->userKey))          $doc->editedBy = $this->getConfluenceAccount($page->version->by->userKey);

        $this->dao->insert(TABLE_DOC)->data($doc)->exec();
        $docID = $this->dao->lastInsertID();
        $this->action->create('doc', $docID, 'Created');

        $tmpRelation['AType'] = 'cpage';
        $tmpRelation['BType'] = 'zdoc';
        $tmpRelation['AID']   = $page->id;
        $tmpRelation['BID']   = $docID;
        $tmpRelation['extra'] = $doc->grade;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

        $docContent = new stdclass();
        $docContent->title      = $doc->title ? $doc->title : $this->lang->convert->confluence->undefined;
        $docContent->content    = !empty($page->body->view->value) ? $page->body->view->value : '';
        $docContent->type       = 'doc';
        $docContent->rawContent = $this->createConfluenceDocContent(!empty($page->body->storage->value) ? $page->body->storage->value : '');
        $docContent->digest     = !empty($page->version->message) ? $page->version->message : '';
        $docContent->version    = '0';
        $docContent->doc        = $docID;
        $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();

        $docContentID = $this->dao->lastInsertID();

        $tmpRelation['AType'] = 'cpage';
        $tmpRelation['BType'] = 'zdoccontent';
        $tmpRelation['AID']   = $page->id;
        $tmpRelation['BID']   = $docContentID;
        $tmpRelation['extra'] = $docContent->version;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();
    }
    return true;
}

/**
 * 导入Confluence归档页面。
 * Import confluence archived page.
 *
 * @param  array  dataList
 * @access public
 * @return bool
 */
public function importConfluenceArchivedPage($dataList)
{
    $this->loadModel('action');
    $libList       = $this->dao->dbh($this->dbh)->select('id,parent,type,project,product,execution,archived,name')->from(TABLE_DOCLIB)->fetchAll('id');
    $spaceRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('cspace')->andWhere('BType')->eq('zlib')->fetchPairs();
    $docRelation   = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoc')->fetchPairs();
    foreach($dataList as $page)
    {
        if(!empty($docRelation[$page->id])) continue;
        if(empty($page->space->id)) continue;
        if(empty($spaceRelation[$page->space->id])) continue;

        $space = $libList[$libList[$spaceRelation[$page->space->id]]->parent];

        $doc = new stdclass();
        $doc->title      = $page->title . "({$this->lang->convert->confluence->archived})";
        $doc->version    = !empty($page->version->number) ? $page->version->number : '1';
        $doc->module     = '0';
        $doc->lib        = $spaceRelation[$page->space->id];
        $doc->product    = $doc->lib ? zget($libList[$doc->lib], 'product',   '0') : '0';
        $doc->project    = $doc->lib ? zget($libList[$doc->lib], 'project',   '0') : '0';
        $doc->execution  = $doc->lib ? zget($libList[$doc->lib], 'execution', '0') : '0';
        $doc->status     = $page->status == 'draft' ? 'draft' : 'normal';
        $doc->type       = 'text';
        $doc->parent     = '0';
        $doc->path       = '';
        $doc->grade      = '1';
        $doc->order      = '999';
        $doc->acl        = $space->type == 'mine' ? 'private' : '';
        $doc->addedBy    = $this->app->user->account;
        $doc->addedDate  = !empty($page->history->createdDate) ? date('Y-m-d H:i:s', strtotime($page->history->createdDate)) : helper::now();
        $doc->editedBy   = $this->app->user->account;
        $doc->editedDate = !empty($page->version->when) ? date('Y-m-d H:i:s', strtotime($page->version->when)) : helper::now();
        if(!empty($page->history->createdBy->accountId)) $doc->addedBy  = $this->getConfluenceAccount($page->history->createdBy->accountId);
        if(!empty($page->history->createdBy->userKey))   $doc->addedBy  = $this->getConfluenceAccount($page->history->createdBy->userKey);
        if(!empty($page->version->by->accountId))        $doc->editedBy = $this->getConfluenceAccount($page->version->by->accountId);
        if(!empty($page->version->by->userKey))          $doc->editedBy = $this->getConfluenceAccount($page->version->by->userKey);
        if($space->type != 'mine') $doc = $this->setDocPrivUsers($page, $doc);

        if(!$doc->title) $doc->title = $this->lang->convert->confluence->undefined;

        $this->dao->insert(TABLE_DOC)->data($doc)->exec();
        $docID = $this->dao->lastInsertID();
        $this->action->create('doc', $docID, 'Created');

        $tmpRelation['AType'] = 'cpage';
        $tmpRelation['BType'] = 'zdoc';
        $tmpRelation['AID']   = $page->id;
        $tmpRelation['BID']   = $docID;
        $tmpRelation['extra'] = $doc->grade;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();

        $docContent = new stdclass();
        $docContent->title      = $doc->title;
        $docContent->content    = !empty($page->body->view->value) ? $page->body->view->value : '';
        $docContent->type       = 'doc';
        $docContent->rawContent = $this->createConfluenceDocContent(!empty($page->body->storage->value) ? $page->body->storage->value : '');
        $docContent->digest     = !empty($page->version->message) ? $page->version->message : '';
        $docContent->version    = $doc->version;
        $docContent->doc        = $docID;
        $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();

        $docContentID = $this->dao->lastInsertID();

        $tmpRelation['AType'] = 'cpage';
        $tmpRelation['BType'] = 'zdoccontent';
        $tmpRelation['AID']   = $page->id;
        $tmpRelation['BID']   = $docContentID;
        $tmpRelation['extra'] = $docContent->version;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();
    }
    return true;
}

/**
 * 导入Confluence文档的历史版本。
 * Import confluence history version.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 */
public function importConfluenceVersion($dataList)
{
    $contentRelation = $this->dao->dbh($this->dbh)->select('*')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoc')->andWhere('AType')->ne('cfolder')->fetchAll('AID');
    foreach($dataList as $contentID => $versions)
    {
        $currentContent = $contentRelation[$contentID];
        foreach($versions as $version => $content)
        {
            if(empty($content)) continue;

            $docContent = new stdclass();
            $docContent->title      = $content->title;
            $docContent->content    = !empty($content->body->view->value) ? $content->body->view->value : '';
            $docContent->type       = 'doc';
            $docContent->rawContent = $this->createConfluenceDocContent(!empty($content->body->storage->value) ? $content->body->storage->value : '');
            $docContent->digest     = !empty($content->version->message) ? $content->version->message : '';
            $docContent->version    = $version;
            $docContent->doc        = $currentContent->BID;
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();

            $docContentID = $this->dao->lastInsertID();

            $tmpRelation['AType'] = $currentContent->AType;
            $tmpRelation['BType'] = 'zdoccontent';
            $tmpRelation['AID']   = $contentID;
            $tmpRelation['BID']   = $docContentID;
            $tmpRelation['extra'] = $docContent->version;
            $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($tmpRelation)->exec();
        }
    }
    return true;
}

/**
 * 导入Confluence文档的附件。
 * Import confluence attachment.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 */
public function importConfluenceAttachment($dataList)
{
    $this->loadModel('file');

    $docRelation  = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoc')->fetchPairs();
    $fileRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('cfile')->andWhere('BType')->eq('zfile')->fetchPairs();
    foreach($dataList as $attachment)
    {
        if(!empty($fileRelation[$attachment->id])) continue;
        if(empty($attachment->container->id)) continue;
        if(empty($docRelation[$attachment->container->id])) continue;

        $fileID    = $attachment->id;
        $fileName  = $attachment->title;
        $parts     = explode('.', $fileName);
        $extension = array_pop($parts);

        $confluenceFile = $this->app->getTmpRoot() . 'attachments/' . $attachment->container->id . '/' . str_replace('att', '', $attachment->id) . '/1';
        if(!is_file($confluenceFile)) continue;

        $file = new stdclass();
        $file->pathname   = $this->file->setPathName((int)$fileID, $extension);
        $file->title      = $fileName;
        $file->extension  = substr($extension, 0, 30); // 防止超长报错
        $file->size       = $attachment->extensions->fileSize;
        $file->objectType = 'doc';
        $file->objectID   = $docRelation[$attachment->container->id];
        $file->addedBy    = $this->app->user->account;
        $file->addedDate  = !empty($attachment->history->createdDate) ? date('Y-m-d H:i:s', strtotime($attachment->history->createdDate)) : helper::now();
        if(!empty($attachment->history->createdBy->accountId)) $file->addedBy = $this->getConfluenceAccount($attachment->history->createdBy->accountId);
        if(!empty($attachment->history->createdBy->userKey))   $file->addedBy = $this->getConfluenceAccount($attachment->history->createdBy->userKey);
        $this->dao->insert(TABLE_FILE)->data($file)->exec();

        $fileID = $this->dao->lastInsertID();
        $this->dao->update(TABLE_DOCCONTENT)->set("`files`=IF(`files` IS NOT NULL, CONCAT(`files`,',{$fileID},'), '{$fileID}')")->where('doc')->eq($file->objectID)->exec();

        copy($confluenceFile, $this->file->savePath . $file->pathname);

        $relation['AType'] = 'cfile';
        $relation['BType'] = 'zfile';
        $relation['AID']   = $attachment->id;
        $relation['BID']   = $fileID;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($relation)->exec();
    }
    return true;
}

/**
 * 导入Confluence的文档批注。
 * Import confluence comment.
 *
 * @param  array  $dataList
 * @access public
 * @return bool
 */
public function importConfluenceComment($dataList)
{
    $docRelation     = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoc')->fetchPairs();
    $commentRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('ccomment')->andWhere('BType')->eq('zaction')->fetchPairs();
    foreach($dataList as $comment)
    {
        if(!empty($commentRelation[$comment->id])) continue;
        if(empty($comment->container->id)) continue;
        if(empty($docRelation[$comment->container->id])) continue;
        if(empty($comment->body->storage->value)) continue;

        $action = new stdclass();
        $action->objectID   = $docRelation[$comment->container->id];
        $action->objectType = 'doc';
        $action->actor      = !empty($comment->history->createdBy->accountId) ? $this->getConfluenceAccount($comment->history->createdBy->accountId) : $this->app->user->account;
        $action->action     = 'commented';
        $action->date       = !empty($comment->history->createdDate) ? date('Y-m-d H:i:s', strtotime($comment->history->createdDate)) : helper::now();
        $action->comment    = $comment->body->storage->value;
        $action->comment    = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $action->comment);

        $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();
        $actionID = $this->dao->lastInsertID();

        $relation['AType'] = 'ccomment';
        $relation['BType'] = 'zaction';
        $relation['AID']   = $comment->id;
        $relation['BID']   = $actionID;
        $this->dao->dbh($this->dbh)->insert(CONFLUENCE_TMPRELATION)->data($relation)->exec();
    }

    return true;
}

/**
 * 获取Confluence接口数据。
 * Get confluence data.
 *
 * @param  string $module
 * @param  object $group
 * @param  int    $start
 * @access public
 * @return array
 */
public function getConfluenceData($module, $group = null, $start = 0)
{
    if(empty($_SESSION['confluenceApi'])) return array();
    $confluenceApi = json_decode($this->session->confluenceApi, true);
    if(empty($confluenceApi['domain'])) return array();

    $token  = base64_encode("{$confluenceApi['admin']}:{$confluenceApi['token']}");
    $isSaas = strpos($confluenceApi['domain'], 'atlassian.net') !== false ? true : false;
    if($module == 'user')
    {
        $url    = $confluenceApi['domain'] . ($isSaas ? "/rest/api/group/member?start={$start}" : "/rest/api/user/list?start={$start}");
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $users = array();
        if(!empty($result->results))
        {
            $users = $result->results;
            if($result->size == $result->limit) $users = array_merge($users, $this->getConfluenceData($module, $group, $result->start + $result->limit));
        }
        return $users;
    }
    elseif($module == 'group')
    {
        $url    = $confluenceApi['domain'] . ($isSaas ? "/rest/api/group/{$group->id}/membersByGroupId?start={$start}" : "/rest/api/group/{$group->name}/member?start={$start}");
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $groups = array();
        if(!empty($result->results))
        {
            $groups = $result->results;
            if($result->size == $result->limit) $groups = array_merge($groups, $this->getConfluenceData($module, $group, $result->start + $result->limit));
        }
        return $groups;
    }
    elseif(in_array($module, array('folder', 'page', 'embed', 'database', 'whiteboard')))
    {
        $spaceRelation = $this->dao->dbh($this->dbh)->select('*')->from(CONFLUENCE_TMPRELATION)->where('AType')->eq('cspace')->andWhere('BType')->eq('zlib')->fetchAll();

        $dataList = array();
        foreach($spaceRelation as $space)
        {
            $url    = $confluenceApi['domain'] . "/rest/api/content/{$space->extra}/child/{$module}?start={$start}&limit=500&expand=body.storage,body.view,version,history,restrictions.read.restrictions.user,restrictions.update.restrictions.user,restrictions.read.restrictions.group,restrictions.update.restrictions.group";
            $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));
            if(!empty($result->results))
            {
                $dataList[$space->BID] = $result->results;
                if($result->size == $result->limit) $dataList[$space->BID] = array_merge($dataList[$space->BID], $this->getConfluenceData($module, $group, $result->start + $result->limit));
            }
        }
        return $dataList;
    }
    elseif($module == 'blogpost')
    {
        $url    = $confluenceApi['domain'] . "/rest/api/content?start={$start}&limit=1000&type=blogpost&status=any&expand=body.storage,body.view,version,history,space,restrictions.read.restrictions.user,restrictions.update.restrictions.user,restrictions.read.restrictions.group,restrictions.update.restrictions.group";
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $blogPost = array();
        if(!empty($result->results))
        {
            $blogPost = $result->results;
            if($result->size == $result->limit) $blogPost = array_merge($blogPost, $this->getConfluenceData($module, $group, $result->start + $result->limit));
        }
        return $blogPost;
    }
    elseif($module == 'draft')
    {
        $url    = $confluenceApi['domain'] . "/rest/api/content?start={$start}&limit=1000&type=page&status=draft&expand=body.storage,body.view,version,history,space,ancestors";
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $draftPage = array();
        if(!empty($result->results))
        {
            $draftPage = $result->results;
            if($result->size == $result->limit) $draftPage = array_merge($draftPage, $this->getConfluenceData($module, $group, $result->start + $result->limit));
        }
        return $draftPage;
    }
    elseif($module == 'archived')
    {
        $url    = $confluenceApi['domain'] . "/rest/api/content?start={$start}&limit=1000&type=page&status=archived&expand=body.storage,body.view,version,history,space,ancestors,restrictions.read.restrictions.user,restrictions.update.restrictions.user,restrictions.read.restrictions.group,restrictions.update.restrictions.group";
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $archivedPage = array();
        if(!empty($result->results))
        {
            $archivedPage = $result->results;
            if($result->size == $result->limit) $archivedPage = array_merge($archivedPage, $this->getConfluenceData($module, $group, $result->start + $result->limit));
        }
        return $archivedPage;
    }
    elseif($module == 'version')
    {
        $contentRelation = $this->dao->dbh($this->dbh)->select('*')->from(CONFLUENCE_TMPRELATION)->where('BType')->eq('zdoccontent')->orderBy('extra_desc')->fetchAll('AID');
        $versionList     = array();
        foreach($contentRelation as $content)
        {
            for($version = $content->extra - 1; $version >= 1; $version --)
            {
                $url    = $confluenceApi['domain'] . "/rest/api/content/{$content->AID}?version={$version}&expand=body.storage,body.view,version,history";
                $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));
                $versionList[$content->AID][$version] = !empty($result->id) ? $result : array();
            }
        }
        return $versionList;
    }
    elseif($module == 'space')
    {
        $url    = $confluenceApi['domain'] . "/rest/api/space?start={$start}&limit=1000&expand=homepage,history,permissions";
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $spaces = array();
        if(!empty($result->results))
        {
            $spaces = $result->results;
            if($result->size == $result->limit) $spaces = array_merge($spaces, $this->getConfluenceData($module, $group, $result->start + $result->limit));
        }
        return $spaces;
    }
    elseif($module == 'comment')
    {
        $url    = $confluenceApi['domain'] . "/rest/api/content/search?cql=type=comment&start={$start}&limit=1000&expand=body.storage,history,container";
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $comments = array();
        if(!empty($result->results))
        {
            $comments = $result->results;
            if($result->size == $result->limit) $comments = array_merge($comments, $this->getConfluenceData($module, $group, $result->start + $result->limit));
        }

        return $comments;
    }
    elseif($module == 'attachment')
    {
        $url    = $confluenceApi['domain'] . "/rest/api/content/search?cql=type=attachment&start={$start}&limit=1000&expand=container,history";
        $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $attachments = array();
        if(!empty($result->results))
        {
            $attachments = $result->results;
            if($result->size == $result->limit) $attachments = array_merge($attachments, $this->getConfluenceData($module, $group, $result->start + $result->limit));
        }
        return $attachments;
    }

    return array();
}

/**
 * 检查Conffluence空间权限类型。
 * Check Confluence space priv.
 *
 * @param  object $space
 * @access public
 * @return string
 */
public function checkConfluenceSpacePriv($space)
{
    $userList    = array();
    $spaceType   = 'mine';
    $permissions = !empty($space->permissions) ? $space->permissions : array();
    if(!empty($permissions->data))
    {
        foreach($permissions->data as $permission)
        {
            if($permission->operation->operationKey != 'read' || $permission->operation->targetType != 'space') continue;
            if($permission->subject->type == 'group')
            {
                $spaceType = 'custom';
                break;
            }
            if($permission->subject->type == 'user')
            {
                $userKey = $permission->subject->userKey;
                $userList[$userKey] = $userKey;
            }
        }
    }
    else
    {
        foreach($permissions as $permission)
        {
            if($permission->operation->operation != 'read' || $permission->operation->targetType != 'space') continue;
            if(!empty($permission->subjects->group))
            {
                $groupList = $permission->subjects->group->results;
                foreach($groupList as $group)
                {
                    if($group->name != 'atlassian-addons-admin')
                    {
                        $spaceType = 'custom';
                        break;
                    }
                }
            }
            if(!empty($permission->subjects->user))
            {
                $userGroup = $permission->subjects->user->results;
                foreach($userGroup as $user)
                {
                    if($user->accountType == 'atlassian') $userList[$user->accountId] = $user->accountId;
                }
            }
        }
    }

    if(count($userList) > 1 || empty($permissions)) $spaceType = 'custom';

    return $spaceType;
}

/**
 * 获取有权限访问空间的用户列表。
 * Get Confluence space users.
 *
 * @param  object $space
 * @access public
 * @return string
 */
public function getConfluenceSpaceUsers($space)
{
    $userGroup   = !empty($_SESSION['confluenceUserGroup']) ? json_decode($this->session->confluenceUserGroup, true) : array();
    $userList    = array();
    $permissions = !empty($space->permissions) ? $space->permissions : array();
    if(!empty($permissions->data))
    {
        foreach($permissions->data as $permission)
        {
            if($permission->operation->operationKey != 'read' || $permission->operation->targetType != 'space') continue;
            if($permission->subject->type == 'group')
            {
                $group = new stdclass();
                $group->name = $permission->subject->name;
                if(empty($userGroup[$group->name]))
                {
                    $userGroup[$group->name] = $this->getConfluenceData('group', $group);
                }
                foreach($userGroup[$group->name] as $user)
                {
                    $accountID = zget($user, 'userKey');
                    $userList[$accountID] = $this->getConfluenceAccount($accountID);
                }
            }
            if($permission->subject->type == 'user')
            {
                $userKey = $permission->subject->userKey;
                $userList[$userKey] = $this->getConfluenceAccount($userKey);
            }
        }
    }
    else
    {
        foreach($permissions as $permission)
        {
            if($permission->operation->operation != 'read' || $permission->operation->targetType != 'space') continue;
            if(!empty($permission->subjects->group))
            {
                $groupList = $permission->subjects->group->results;
                foreach($groupList as $group)
                {
                    if($group->name == 'atlassian-addons-admin') continue;
                    if(empty($userGroup[$group->name]))
                    {
                        $userGroup[$group->name] = $this->getConfluenceData('group', $group);
                    }
                    foreach($userGroup[$group->name] as $user)
                    {
                        $accountID = zget($user, 'accountId');
                        $userList[$accountID] = $this->getConfluenceAccount($accountID);
                    }
                }
            }
            if(!empty($permission->subjects->user))
            {
                $users = $permission->subjects->user->results;
                foreach($users as $user) $userList[$user->accountId] = $this->getConfluenceAccount($user->accountId);
            }
        }
    }

    $this->session->set('confluenceUserGroup', json_encode($userGroup));
    return implode(',', $userList);
}

/**
 * 设置Confluence导入文档的权限。
 * Get doc priv users.
 *
 * @param  object $content
 * @param  object $doc
 * @access public
 * @return object
 */
public function setDocPrivUsers($content, $doc)
{
    $userGroup    = !empty($_SESSION['confluenceUserGroup']) ? json_decode($this->session->confluenceUserGroup, true) : array();
    $users        = !empty($_SESSION['confluenceUsers'])     ? json_decode($this->session->confluenceUsers, true)     : array();
    $readUsers    = $content->restrictions->read->restrictions->user->results;
    $readGroups   = $content->restrictions->read->restrictions->group->results;
    $updateUsers  = $content->restrictions->update->restrictions->user->results;
    $updateGroups = $content->restrictions->update->restrictions->group->results;

    $readUserList = array();
    foreach($readUsers as $user)
    {
        $accountID = !empty($user->accountId) ? $user->accountId : $user->userKey;
        $readUserList[$accountID] = $this->getConfluenceAccount($accountID);
    }
    foreach($readGroups as $group)
    {
        if(empty($userGroup[$group->name]))
        {
            $userGroup[$group->name] = $this->getConfluenceData('group', $group);
        }
        foreach($userGroup[$group->name] as $user)
        {
            $accountID = !empty($user->accountId) ? $user->accountId : $user->userKey;
            $readUserList[$accountID] = $this->getConfluenceAccount($accountID);
        }
    }

    $editUsers = array();
    foreach($updateUsers as $user)
    {
        $accountID = !empty($user->accountId) ? $user->accountId : $user->userKey;
        $editUsers[$accountID] = $this->getConfluenceAccount($accountID);
    }
    foreach($updateGroups as $group)
    {
        if(empty($userGroup[$group->name]))
        {
            $userGroup[$group->name] = $this->getConfluenceData('group', $group);
        }
        foreach($userGroup[$group->name] as $user)
        {
            $accountID = !empty($user->accountId) ? $user->accountId : $user->userKey;
            $editUsers[$accountID] = $this->getConfluenceAccount($accountID);
        }
    }

    if($doc->type == 'chapter')
    {
        $doc->acl = $readUserList && $editUsers ? 'private' : 'open';
        if($doc->acl == 'private')
        {
            /* 章节的白名单用的是editUsers。 */
            $editUsers    = array_unique(array_merge($readUserList, $editUsers));
            $readUserList = array();
        }
    }
    else
    {
        $doc->acl = $readUserList || $editUsers ? 'private' : 'open';
        if($doc->acl == 'private' && empty($readUserList)) $readUserList = $users;
    }

    $doc->readUsers = implode(',', $readUserList);
    $doc->users     = implode(',', $editUsers);

    $this->session->set('confluenceUserGroup', json_encode($userGroup));
    return $doc;
}

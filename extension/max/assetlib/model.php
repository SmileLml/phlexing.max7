<?php
/**
 * The model file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: model.php 5079 2021-06-23 10:34:34Z tsj $
 * @link        https://www.zentao.net
 */
class assetlibModel extends model
{
    /**
     * Get asset lib info by id.
     *
     * @param  int    $libID
     * @access public
     * @return object
     */
    public function getById($libID)
    {
        $lib = $this->dao->select('*')->from(TABLE_ASSETLIB)->where('id')->eq((int)$libID)->fetch();
        $lib = $this->loadModel('file')->replaceImgURL($lib, 'desc');
        return $lib;
    }

    /**
     * Get asset lib list.
     *
     * @param  string $type story|issue|risk|object|practice|component
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($type = 'story', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_ASSETLIB)
            ->where('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get asset library pairs.
     *
     * @param  int    $type
     * @access public
     * @return array
     */
    public function getPairs($type)
    {
        return $this->dao->select('id,name')->from(TABLE_ASSETLIB)
            ->where('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy('order_desc, id_desc')
            ->fetchPairs('id');
    }

    /**
     * Get can approve users.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getApproveUsers($type = 'story')
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)
            ->where('module')->eq('assetlib')
            ->andWhere('method')->in($this->config->assetlib->approveMethods[$type])
            ->fetchPairs();

        return $this->dao->select('t1.account,t1.realname')->from(TABLE_USER)->alias('t1')
            ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.account=t2.account')
            ->where('(t2.group')->in($groups)
            ->orWhere('t1.account')->in(trim($this->app->company->admins, ','))
            ->markRight(1)
            ->andWhere('t1.deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Create asset lib.
     *
     * @param  string    $type story|issue|risk|object|practice|component|case
     * @access public
     * @return int|bool
     */
    public function create($type)
    {
        $lib = fixer::input('post')
            ->stripTags($this->config->assetlib->editor->create['id'], $this->config->allowedTags)
            ->add('type', $type)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('uid')
            ->get();

        $lib = $this->loadModel('file')->processImgURL($lib, $this->config->assetlib->editor->create['id'], $this->post->uid);

        $this->dao->insert(TABLE_ASSETLIB)->data($lib)
            ->batchcheck($this->config->assetlib->create->requiredFields, 'notempty')
            ->check('name', 'unique', "deleted = '0'")
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();

        return false;
    }

    /**
     * Update asset lib.
     *
     * @param  int       $libID
     * @access public
     * @return array|bool
     */
    public function update($libID)
    {
        $oldLib = $this->getById($libID);

        $lib = fixer::input('post')
            ->stripTags($this->config->assetlib->editor->edit['id'], $this->config->allowedTags)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('uid')
            ->get();

        $lib = $this->loadModel('file')->processImgURL($lib, $this->config->assetlib->editor->edit['id'], $this->post->uid);

        $this->dao->update(TABLE_ASSETLIB)->data($lib)
            ->batchcheck($this->config->assetlib->create->requiredFields, 'notempty')
            ->check('name', 'unique', "id != $libID and deleted = '0'")
            ->where('id')->eq($libID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldLib, $lib);

        return false;
    }

    /**
     * Update asset.
     *
     * @param  int    $oldAsset
     * @param  int    $type
     * @access public
     * @return void
     */
    public function updateAsset($oldAsset, $type)
    {
        $now   = helper::now();
        $asset = fixer::input('post')
            ->addIF($type != 'story', 'editedBy', $this->app->user->account)
            ->addIF($type != 'story', 'editedDate', $now)
            ->addIF($type == 'story', 'lastEditedBy', $this->app->user->account)
            ->addIF($type == 'story', 'lastEditedDate', $now)
            ->stripTags($this->config->assetlib->editor->editasset['id'], $this->config->allowedTags)
            ->get();

        if($type == 'story')
        {
            $oldSpec = $this->dao->select('spec,verify')->from(TABLE_STORYSPEC)->where('story')->eq((int)$oldAsset->id)->fetch();
            $oldAsset->spec   = isset($oldSpec->spec)   ? $oldSpec->spec   : '';
            $oldAsset->verify = isset($oldSpec->verify) ? $oldSpec->verify : '';

            $storySpec   = $asset->spec;
            $storyVerify = $asset->verify;
            unset($asset->spec);
            unset($asset->verify);
        }

        $changes = common::createChanges($oldAsset, $asset);
        $asset->version = $changes ? $oldAsset->version + 1 : $oldAsset->version;

        if($type == 'opportunity') $this->config->assetlib->editasset->requiredFields = str_replace(",type,", ",", $this->config->assetlib->editasset->requiredFields);
        $asset = $this->loadModel('file')->processImgURL($asset, $this->config->assetlib->editor->editasset['id'], $this->post->uid);
        $table = $this->config->objectTables[$type];
        $this->dao->update($table)->data($asset)
            ->autoCheck()
            ->batchCheck($this->config->assetlib->editasset->requiredFields, 'notempty')
            ->where('id')->eq($oldAsset->id)
            ->exec();
        if(dao::isError()) return false;

        if($type == 'story')
        {
            $specData = new stdclass();
            $specData->version = $asset->version;
            $specData->title   = $asset->title;
            $specData->spec    = $storySpec;
            $specData->verify  = $storyVerify;
            $this->dao->update(TABLE_STORYSPEC)->data($specData)->where('story')->eq($oldAsset->id)->exec();
            if(dao::isError()) return false;
        }

        return $changes;
    }

    /**
     * Filter have no product project.
     *
     * @param  array  $projectPairs
     * @access public
     * @return array
     */
    public function filterNoProductProject($projectPairs)
    {
        $projectProducts = $this->dao->select('t1.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.project')->in(array_keys($projectPairs))
            ->fetchAll();

        $projectIDList = array_column($projectProducts, 'project');

        $filterProjects = array();
        foreach($projectIDList as $projectID)
        {
            if(isset($projectPairs[$projectID])) $filterProjects[$projectID] = $projectPairs[$projectID];
        }

        return $filterProjects;
    }

    /**
     * Get not import stories from project.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getNotImportedStories($libID, $projectID = 0, $productID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0)
    {
        $hasExistedStories = $this->dao->select('fromStory,fromVersion')->from(TABLE_STORY)
            ->where('lib')->eq($libID)
            ->andWhere('deleted')->eq('0')
            ->fetchAll();
        $notDeletedProject = $this->loadModel('project')->getPairsByIdList();

        $query = '';
        if($browseType == 'bysearch')
        {
            $query = $this->getAssetBySearch('story', $query, $queryID, $projectID, $productID);
        }

        $allStories = $this->dao->select('t1.*,t2.*,t2.version as latestVersion')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id and t1.version=t2.version')
            ->where('t2.deleted')->eq(0)
            ->beginIF($browseType != 'bysearch' and $projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF($browseType != 'bysearch' and $productID)->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
            ->andWhere('t1.project')->in(array_keys($notDeletedProject))
            ->andWhere('t2.lib')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll('story');

        foreach($allStories as $storyID => $story)
        {
            foreach($hasExistedStories as $existedStory)
            {
                if($existedStory->fromStory == $storyID and $existedStory->fromVersion == $story->latestVersion) unset($allStories[$storyID]);
            }
        }

        return $allStories;
    }

    /**
     * Get not import issues from project.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getNotImportedIssues($libID, $projectID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $pager = null)
    {
        $hasExistedIssues  = $this->dao->select('`from`')->from(TABLE_ISSUE)->where('lib')->eq($libID)->andWhere('deleted')->eq('0')->fetchPairs();
        $notDeletedProject = $this->loadModel('project')->getPairsByIdList();

        $query = '';
        if($browseType == 'bysearch')
        {
            $query = $this->getAssetBySearch('issue', $query, $queryID, $projectID);
        }

        $allIssues = $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('deleted')->eq(0)
            ->beginIF($browseType != 'bysearch' and $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
            ->andWhere('lib')->eq(0)
            ->beginIF($hasExistedIssues)->andWhere('id')->notIN($hasExistedIssues)->fi()
            ->andWhere('project')->in(array_keys($notDeletedProject))
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $allIssues;
    }

    /**
     * Get not import risks from project.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getNotImportedRisks($libID, $projectID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $pager = null)
    {
        $hasExistedRisks   = $this->dao->select('`from`')->from(TABLE_RISK)->where('lib')->eq($libID)->andWhere('deleted')->eq('0')->fetchPairs();
        $notDeletedProject = $this->loadModel('project')->getPairsByIdList();

        $query = '';
        if($browseType == 'bysearch')
        {
            $query = $this->getAssetBySearch('risk', $query, $queryID, $projectID);
        }

        $allRisks = $this->dao->select('*')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->beginIF($browseType != 'bysearch' and $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
            ->andWhere('lib')->eq(0)
            ->beginIF($hasExistedRisks)->andWhere('id')->notIN($hasExistedRisks)->fi()
            ->andWhere('project')->in(array_keys($notDeletedProject))
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $allRisks;
    }

    /**
     * Get not import opportunities from project.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getNotImportedOpportunities($libID, $projectID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $pager = null)
    {
        $hasExistedOpportunities = $this->dao->select('`from`')->from(TABLE_OPPORTUNITY)->where('lib')->eq($libID)->andWhere('deleted')->eq('0')->fetchPairs();
        $notDeletedProject       = $this->loadModel('project')->getPairsByIdList();

        $query = '';
        if($browseType == 'bysearch')
        {
            $query = $this->getAssetBySearch('opportunity', $query, $queryID, $projectID);
        }

        $allOpportunities = $this->dao->select('*')->from(TABLE_OPPORTUNITY)
            ->where('deleted')->eq(0)
            ->beginIF($browseType != 'bysearch' and $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
            ->andWhere('lib')->eq(0)
            ->beginIF($hasExistedOpportunities)->andWhere('id')->notIN($hasExistedOpportunities)->fi()
            ->andWhere('project')->in(array_keys($notDeletedProject))
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $allOpportunities;
    }

    /**
     * Get not import docs from project.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  int    $docLibID
     * @param  string $assetLibType
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getNotImportedDocs($libID, $projectID = 0, $docLibID = 0, $assetLibType = '', $orderBy = 'id_desc')
    {
        $hasExistedDocs = $this->dao->select('`from`,`fromVersion`')->from(TABLE_DOC)->where('assetlib')->eq($libID)->andWhere('deleted')->eq('0')->fetchAll();

        $allDocs = $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->andWhere('lib')->eq($docLibID)
            ->andWhere('assetlib')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll('id');

        foreach($allDocs as $docID => $doc)
        {
            foreach($hasExistedDocs as $existedDoc)
            {
                if($existedDoc->from == $docID and $existedDoc->fromVersion == $doc->version) unset($allDocs[$docID]);
            }
        }

        return $allDocs;
    }

    /**
     * Get object by search.
     *
     * @param  string $type story|issue|risk|object|practice|component
     * @param  int    $libID
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getObjectBySearch($type = '', $libID = 0, $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $queryID  = (int)$queryID;
        $table    = zget($this->config->objectTables, $type, '');
        $libField = 'lib';

        if($type == 'component' or $type == 'practice')
        {
            $table = $this->config->objectTables['doc'];
            $libField = 'assetLib';
        }

        if(!$table) return array();

        /* Get query. */
        $queryKey = "{$type}LibQuery";
        $formKey  = "{$type}LibForm";

        if($this->session->{$queryKey} == false) $this->session->set($queryKey, ' 1 = 1');
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryKey, $query->sql);
                $this->session->set($formKey,  $query->form);
            }
        }
        $query = $this->session->{$queryKey};

        /* Process query for lib field. */
        $allLibs = "`{$libField}` = 'all'";
        if(strpos($query, $allLibs) !== false)
        {
            $query = str_replace($allLibs, "`$libField` > '0'", $query);
            if($type == 'component') $query = $query . " AND `assetLibType` = 'component'";
            if($type == 'practice')  $query = $query . " AND `assetLibType` = 'practice'";
        }
        elseif(strpos($query, "`{$libField}` = ") === false)
        {
            $query = $query . " AND `$libField` = $libID";
        }

        $orderBy = str_replace('priOrder_', 'pri_', $orderBy);

        return $this->dao->select('*')->from($table)
            ->where('deleted')->eq(0)
            ->andWhere($query)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get object information list.
     *
     * @param  string $type story|issue|risk|opportunity|doc|component|practice
     * @param  int    $libID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getObjectList($type = '', $libID = 0, $browseType = 'all', $param = '', $orderBy = 'id_desc', $pager = null)
    {
        if(strtolower($browseType) == 'bysearch') return $this->getObjectBySearch($type, $libID, $param, $orderBy, $pager);

        if($type == 'component' or $type == 'practice') $type = 'doc';
        $table = zget($this->config->objectTables, $type, '');
        if(!$table) return array();

        $sqlCondition = '';
        if($type == 'story')
        {
            $showGrades = isset($this->config->assetlib->showGrades) ? $this->config->assetlib->showGrades : null;
            if($showGrades)
            {
                $items = explode(',', $showGrades);
                $conditions = array();
                foreach($items as $item)
                {
                    preg_match('/^([a-zA-Z]+)(\d+)$/', $item, $matches);
                    $type  = $matches[1];
                    $grade = $matches[2];
                    $conditions[$type][] = $grade;
                }

                $sqlCondition = '(';
                foreach($conditions as $type => $grades) $sqlCondition .= "(type = '$type' AND grade " . helper::dbIN($grades) . ") OR ";
                $sqlCondition  = rtrim($sqlCondition, 'OR ');
                $sqlCondition .= ')';
            }
        }

        $select = ($type == 'story' and strpos($orderBy, 'priOrder_') !== false) ? "*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder" : '*';
        return $this->dao->select($select)->from($table)
            ->where('deleted')->eq(0)
            ->beginIF($sqlCondition)->andWhere($sqlCondition)->fi()
            ->beginIF($libID and $type != 'doc')->andWhere('lib')->eq($libID)->fi()
            ->beginIF($libID and $type == 'doc')->andWhere('assetLib')->eq($libID)->fi()
            ->beginIF($browseType == 'draft')->andWhere('status')->eq('draft')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Approve an object.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function approve($objectID, $objectType)
    {
        $data = fixer::input('post')
            ->stripTags($this->config->assetlib->editor->approvestory['id'], $this->config->allowedTags)
            ->add('assignedTo', $this->app->user->account)
            ->add('status', 'active')
            ->setDefault('approvedDate', helper::today())
            ->remove('uid,comment,result')
            ->get();

        $table = $this->config->objectTables[$objectType];
        if($_POST['result'] == 'reject')
        {
            $this->dao->delete()->from($table)->where('id')->eq($objectID)->exec();
        }

        if($_POST['result'] == 'pass')
        {
            $this->dao->update($table)->data($data)->where('id')->eq($objectID)->exec();
        }
    }

    /**
     * Print assignedTo html.
     *
     * @param  object $object
     * @param  array  $users
     * @param  string $type  story|issue|risk|opportunity|practice|component
     * @access public
     * @return string
     */
    public function printAssignedHtml($object, $users, $type)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = zget($users, $object->assignedTo);

        if(empty($object->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->assetlib->noAssigned;
        }
        if($object->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($object->assignedTo) and $object->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $method       = $this->config->assetlib->assignToMethod[$type];
        $btnClass    .= $object->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('assetlib', $method, "objectID=$object->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $object->assignedTo) . "'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('assetlib', $method, $object) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  int    $object
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($object, $action)
    {
        $action = strtolower($action);

        $actionList = array('approvestory', 'approveissue', 'approverisk', 'approveopportunity', 'approvepractice', 'approvecomponent');
        if(in_array($action, $actionList)) return $object->status != 'active';

        return true;
    }

    /**
     * Get asset by search.
     *
     * @param  string    $assetType
     * @param  string    $query
     * @param  int       $queryID
     * @param  int       $projectID
     * @param  int       $productID
     * @access public
     * @return string
     */
    public function getAssetBySearch($assetType, $query, $queryID, $projectID, $productID = 0)
    {
        $queryCondition = 'asset' . ucfirst($assetType) . 'Query';

        if($queryID)
        {
            $this->session->set($queryCondition, ' 1 = 1');
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryCondition, $query->sql);
                $this->session->set('asset' . ucfirst($assetType) . 'Form', $query->form);
            }
        }
        else
        {
            if($this->session->$queryCondition == false) $this->session->set($queryCondition, ' 1 = 1');
        }

        $query          = $assetType == 'story' ? str_replace('`project`', 't1.`project`', $this->session->$queryCondition) : $this->session->$queryCondition;
        $allProject     = $assetType == 'story' ? "t1.`project` = 'all'" : "`project` = 'all'";
        $withAllProject = strpos($query, $allProject) !== false;

        if($withAllProject)  $query  = str_replace($allProject, 1, $query);
        if(!$withAllProject) $query .= $assetType == 'story' ? " AND t1.`project` = '$projectID'" : " AND `project` = '$projectID'";

        if($assetType == 'story')
        {
            $query          = str_replace('`product`', 't1.`product`', $query);
            $query          = str_replace('`version`', 't2.`version`', $query);
            $allProduct     = "t1.`product` = 'all'";
            $withAllProduct = strpos($query, $allProduct) !== false;

            if($withAllProduct)  $query  = str_replace($allProduct, 1, $query);
            if(!$withAllProduct) $query .= " AND t1.`product` = '$productID'";
        }

        return $query;
    }

    /**
     * Build search form for lib.
     *
     * @param  string $type story|issue|risk|object|practice|component
     * @param  int    $libID
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($type, $libID, $queryID, $actionURL)
    {
        $searchParams['module']    = $type . 'Lib';
        $searchParams['actionURL'] = $actionURL;
        $searchParams['queryID']   = $queryID;

        $lib = $this->getById($libID);

        $moduleName = $type;
        $libField   = 'lib';
        if($type == 'component' or $type == 'practice')
        {
            $moduleName = 'doc';
            $libField   = 'assetLib';
        }
        if($type == 'story') $moduleName = 'product';

        $this->app->loadConfig($moduleName);

        foreach(explode(',', $this->config->assetlib->searchFields[$type]) as $searchField)
        {
            if($searchField == $libField)
            {
                $libNameKey    = $type . 'Lib';
                $allLibNameKey = 'all' . ucfirst($type . 'Lib');
                $searchParams['fields'][$searchField] = $this->lang->assetlib->{$libNameKey};
                $searchParams['params'][$searchField] = array('operator' => '=', 'control' => 'select', 'values' => array(), 'nonull' => true);
                $searchParams['params'][$searchField]['values'] = array('' => '', $libID => $lib->name, 'all' => $this->lang->assetlib->{$allLibNameKey});
            }
            elseif(isset($this->config->assetlib->search['fields'][$searchField]))
            {
                $searchParams['fields'][$searchField] = $this->config->assetlib->search['fields'][$searchField];
                $searchParams['params'][$searchField] = $this->config->assetlib->search['params'][$searchField];
            }
            elseif(isset($this->config->{$moduleName}->search['fields'][$searchField]))
            {
                $searchParams['fields'][$searchField] = $this->config->{$moduleName}->search['fields'][$searchField];
                if(isset($this->config->{$moduleName}->search['params'][$searchField])) $searchParams['params'][$searchField] = $this->config->{$moduleName}->search['params'][$searchField];
            }
        }

        if(isset($searchParams['params']['status']))
        {
            $searchParams['fields']['status'] = $this->lang->assetlib->status;
            $searchParams['params']['status']['values'] = $this->lang->assetlib->statusList;
            $searchParams['params']['status']['nonull'] = true;
        }

        /* Fix field name. */
        if($type == 'component' or $type == 'practice')
        {
            $searchParams['fields']['addedBy']    = $this->lang->assetlib->importedBy;
            $searchParams['fields']['addedDate']  = $this->lang->assetlib->importedDate;
            $searchParams['fields']['editedDate'] = $this->lang->assetlib->lastEditedDate;
        }
        elseif($type == 'risk' or $type == 'opportunity' or $type == 'issue')
        {
            $searchParams['fields']['createdBy']   = $this->lang->assetlib->importedBy;
            $searchParams['fields']['createdDate'] = $this->lang->assetlib->importedDate;
            $searchParams['fields']['editedDate']  = $this->lang->assetlib->lastEditedDate;
        }
        elseif($type =='story')
        {
            $searchParams['fields']['openedBy']       = $this->lang->assetlib->importedBy;
            $searchParams['fields']['openedDate']     = $this->lang->assetlib->importedDate;
            $searchParams['fields']['lastEditedDate'] = $this->lang->assetlib->lastEditedDate;

            $searchParams['params']['openedDate']     = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
            $searchParams['params']['lastEditedDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
        }

        $this->loadModel('search')->setSearchParams($searchParams);
    }

    /**
     * Get review tip.
     *
     * @param  array  $objects
     * @param  string $objectType
     * @param  bool   $result
     * @access public
     * @return string
     */
    public function getReviewTip($objects, $objectType, $result)
    {
        $reviewList    = array();
        $notReviewList = array();

        foreach($objects as $object)
        {
            if($object->status == 'active')
            {
                $notReviewList[$object->id] = $object->id;
                continue;
            }

            $reviewList[$object->id] = $object->id;
        }

        $tip = '';
        if(!empty($reviewList) or !empty($notReviewList))  $tip .= sprintf($this->lang->assetlib->objectTip, $this->lang->assetlib->objectList[$objectType]);
        if(!empty($reviewList))                            $tip .= sprintf($this->lang->assetlib->reviewFinishTip, implode(',', $reviewList), $this->lang->assetlib->resultList[$result]);
        if(!empty($reviewList) and !empty($notReviewList)) $tip .= $this->lang->comma;
        if(!empty($notReviewList))                         $tip .= sprintf($this->lang->assetlib->noNeedReviewTip, implode(',', $notReviewList));

        return $tip;
    }
}

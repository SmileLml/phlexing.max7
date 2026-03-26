<?php
/**
 * The control file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: control.php 5107 2021-06-23 10:33:12Z tsj $
 * @link        https://www.zentao.net
 */
class assetlib extends control
{
    /**
     * Story library list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function storyLib($orderBy = 'order_desc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('storyLibList', $uri, 'assetlib');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $this->view->title        = $this->lang->assetlib->storyLib . $this->lang->hyphen . $this->lang->assetlib->browse;
        $this->view->libs         = $this->assetlib->getList('story', $orderBy, $pager);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->objectType   = 'story';
        $this->view->browseLink   = 'storyLib';
        $this->view->createMethod = 'createStoryLib';
        $this->view->editMethod   = 'editStoryLib';
        $this->view->canSort      = common::hasPriv('assetlib', 'storylibSort');

        $this->display('assetlib', 'browse');
    }

    /**
     * Create story lib.
     *
     * @access public
     * @return void
     */
    public function createStoryLib()
    {
        if(!empty($_POST))
        {
            $storyLibID = $this->assetlib->create('story');
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('assetlib', $storyLibID, 'opened');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            /* Return lib id when call the API. */
            if($this->viewType == 'json')
            {
                $response['id'] = $storyLibID;
                return $this->send($response);
            }

            $response['locate'] = $this->createLink('assetlib', 'storyLib');
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->createStoryLib;

        $this->display('assetlib', 'create');
    }

    /**
     * Edit story lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editStoryLib($libID = 0)
    {
        if(!empty($_POST))
        {
            $changes = $this->assetlib->update($libID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('assetlib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('assetlib', 'storyLibView', "libID=$libID");
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->editStoryLib;
        $this->view->lib   = $this->assetlib->getById($libID);

        $this->display('assetlib', 'edit');
    }

    /**
     * Delete a story lib.
     *
     * @param  int    $libID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function deleteStoryLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->storyLibDelete, inlink('deleteStoryLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->assetlib->delete(TABLE_ASSETLIB, $libID);

            $this->executeHooks($libID);

            die(js::reload('parent'));
        }
    }

    /**
     * Story lib view.
     *
     * @param  int    $storyLibID
     * @access public
     * @return void
     */
    public function storyLibView($storyLibID)
    {
        $storyLibID = (int)$storyLibID;
        $storyLib   = $this->assetlib->getById($storyLibID);
        if(!isset($storyLib->id)) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->view->title = $storyLib->name . $this->lang->hyphen . $this->lang->assetlib->storyLibView;

        $this->view->lib        = $storyLib;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('assetlib', $storyLibID);
        $this->view->browseLink = $this->createLink('assetlib', 'story', "libID=$storyLibID");

        $this->display('assetlib', 'view');
    }

    /**
     * Story information list.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story($libID, $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('story');
        $this->loadModel('product');
        $browseType = strtolower($browseType);

        $uri = $this->app->getURI(true);
        $this->session->set('storyList', $uri, 'assetlib');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('assetlib', 'story', "libID=$libID&browseType=bySearch&queryID=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->assetlib->buildSearchForm('story', $libID, $queryID, $actionURL);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $stories = $this->assetlib->getObjectList('story', $libID, $browseType, $param, $sort, $pager);
        foreach($stories as $story)
        {
            $story->parent = array();
            foreach(explode(',', trim((string)$story->path, ',')) as $parentID)
            {
                if(!$parentID) continue;
                if($parentID == $story->id) continue;
                $story->parent[] = (int)$parentID;
            }
        }

        $this->view->title      = $this->lang->assetlib->storyLib . $this->lang->hyphen . $this->lang->assetlib->story;
        $this->view->libID      = $libID;
        $this->view->libs       = $this->assetlib->getPairs('story');
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->stories    = $stories;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvers  = $this->assetlib->getApproveUsers('story');
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->gradeGroup = $this->loadModel('story')->getGradeGroup();
        $this->view->gradeMenu  = $this->loadModel('story')->getGradeMenu('all');
        $this->view->showGrades = isset($this->config->assetlib->showGrades) ? $this->config->assetlib->showGrades : $this->story->getDefaultShowGrades($this->view->gradeMenu);
        $this->view->showGrade  = $this->config->edition == 'ipd';

        $this->display();
    }

    /**
     * Edit story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function editStory($storyID)
    {
        $this->loadModel('story');
        $story = $this->story->getByID($storyID);

        if($_POST)
        {
            $changes = $this->assetlib->updateAsset($story, 'story');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('story', $storyID, 'edited');
            $this->action->logHistory($actionID, $changes);

            $locate = isonlybody() ? 'parent' : inLink('story', "libID=$story->lib");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->view->title = $this->lang->assetlib->storyLib . $this->lang->hyphen . $this->lang->assetlib->editStory;
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->story = $story;

        $this->display();
    }

    /**
     * View a story.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function storyView($storyID, $version = 0)
    {
        $storyID    = (int)$storyID;
        $story      = $this->loadModel('story')->getById($storyID, $version, true);
        $browseLink = $this->app->session->storyList ? $this->app->session->storyList : $this->createLink('assetlib', 'story', "libID=$story->lib");

        if(!$story) die(js::locate($browseLink));

        $story->files = $this->loadModel('file')->getByObject('story', $storyID);

        $this->view->title      = $this->lang->assetlib->storyLib . $this->lang->hyphen . $this->lang->assetlib->storyView;
        $this->view->story      = $story;
        $this->view->version    = $version == 0 ? $story->version : $version;
        $this->view->actions    = $this->loadModel('action')->getList('story', $storyID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseLink = $browseLink;

        $this->display();
    }

    /**
     * Import story from project to story lib.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importStory($libID, $projectID = 0, $productID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project');
        $this->loadModel('product');

        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;

        if($_POST)
        {
            $this->loadModel('story')->importToLib($this->post->storyIdList);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $allProject = $this->project->getPairsByModel();
        $allProject = $this->assetlib->filterNoProductProject($allProject);
        if(empty($allProject))
        {
            echo js::alert($this->lang->assetlib->noProject);
            die(js::locate($this->session->storyList));
        }
        if(empty($projectID) or !isset($allProject[$projectID])) $projectID = key($allProject);

        $products = $this->product->getProductPairsByProject($projectID);
        if(empty($productID) or !isset($products[$productID])) $productID = key($products);

        /* Build the search form. */
        $actionURL = $this->createLink('assetlib', 'importStory', "libID=$libID&projectID=$projectID&productID=$productID&orderBy=$orderBy&browseType=bySearch&queryID=myQueryID");
        $this->config->product->search['module']    = 'assetStory';
        $this->config->product->search['actionURL'] = $actionURL;
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['fields']['project'] = $this->lang->assetlib->project;
        $this->config->product->search['params']['project'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $projectID => $allProject[$projectID], 'all' => $this->lang->assetlib->allProject));
        $this->product->buildSearchForm($productID, $products, $queryID, $actionURL);

        $stories = $this->assetlib->getNotImportedStories($libID, $projectID, $productID, $orderBy, $browseType, $queryID);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'assetStory', $browseType != 'bysearch');

        $stories = $this->loadModel('story')->mergePlanTitleAndChildren($productID, $stories);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal = count($stories);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $stories  = array_chunk($stories, $pager->recPerPage);

        $this->view->title = $this->lang->assetlib->common . $this->lang->hyphen . $this->lang->assetlib->importStory;

        $this->view->allProject = $allProject;
        $this->view->libID      = $libID;
        $this->view->project    = $this->project->getById($projectID);
        $this->view->projectID  = $projectID;
        $this->view->products   = $products;
        $this->view->productID  = $productID;
        $this->view->stories    = empty($stories) ? $stories : $stories[$pageID - 1];
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->queryID    = $queryID;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->gradeGroup = $this->loadModel('story')->getGradeGroup();
        $this->view->showGrade  = $this->config->edition == 'ipd';

        $this->display();
    }

    /**
     * Update assign of story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function assignToStory($storyID)
    {
        $this->loadModel('story');
        $this->app->loadConfig('action');

        if($_POST)
        {
            $changes = $this->story->assign($storyID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('story', $storyID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title  = $this->lang->assetlib->storyLib . $this->lang->hyphen . $this->lang->assetlib->assignToStory;
        $this->view->object = $this->story->getByID($storyID);
        $this->view->users  = $this->assetlib->getApproveUsers('story');
        $this->view->type   = 'story';

        $this->display('assetlib', 'assignto');
    }

    /**
     * Batch assign to stories.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function batchAssignToStory($libID)
    {
        $this->loadModel('story');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $storyIDList = $this->post->storyIDList;
            $storyIDList = array_unique($storyIDList);

            unset($_POST['storyIDList']);

            if(!is_array($storyIDList)) die(js::locate($this->createLink('assetlib', 'story', "libID=$libID"), 'parent'));

            $stories = $this->story->getByList($storyIDList);
            foreach($stories as $storyID => $story)
            {
                $changes = $this->story->assign($storyID);
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->action->create('story', $storyID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent'));
        }
    }

    /**
     * Approve a story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function approveStory($storyID)
    {
        $this->loadModel('action');
        $story = $this->loadModel('story')->getById($storyID);
        if(empty($story) or $story->status == 'active') die(js::reload('parent.parent'));

        if(!empty($_POST))
        {
            $this->assetlib->approve($storyID, 'story');
            if(dao::isError()) die(js::error(dao::getError()));

            $this->action->create('story', $storyID, 'approved', $this->post->comment, $_POST['result']);

            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->assetlib->storyLib . $this->lang->hyphen . $this->lang->assetlib->approveStory;
        $this->view->object     = $story;
        $this->view->objectType = 'story';

        $this->display('assetlib', 'approve');
    }

    /**
     * Batch approve stories.
     *
     * @param  int    $libID
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchApproveStory($libID, $result)
    {
        $this->loadModel('story');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $storyIDList = $this->post->storyIDList;
            $storyIDList = array_unique($storyIDList);
            $_POST['result'] = $result;

            unset($_POST['storyIDList']);

            if(!is_array($storyIDList)) die(js::locate($this->createLink('assetlib', 'story', "libID=$libID"), 'parent'));

            $stories = $this->story->getByList($storyIDList);
            foreach($stories as $storyID => $story)
            {
                if($story->status == 'active') continue;
                $this->assetlib->approve($storyID, 'story');
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $this->action->create('story', $storyID, 'approved', $this->post->comment, $_POST['result']);
            }

            return $this->send(array('load' => true, 'message' => $this->assetlib->getReviewTip($stories, 'story', $result)));
        }
    }

    /**
     * Remove story from story library.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function removeStory($storyID)
    {
        $this->dao->delete()->from(TABLE_STORY)->where('id')->eq($storyID)->exec();
        $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->exec();
        return $this->send(array('load' => true));
    }

    /**
     * Batch remove stories.
     *
     * @access public
     * @return void
     */
    public function batchRemoveStory()
    {
        $storyIDList = $this->post->storyIDList;
        $storyIDList = array_unique($storyIDList);

        if(!is_array($storyIDList)) die(js::reload('parent'));
        $this->loadModel('action');

        foreach($storyIDList as $storyID)
        {
            $this->dao->delete()->from(TABLE_STORY)->where('id')->eq($storyID)->exec();
            $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->exec();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('story', $storyID, 'removed');
        }

        return $this->send(array('load' => true));
    }

    /**
     * Case library list.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function caseLib($type = 'all', $orderBy = 'order_desc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('caselibList', $uri, 'assetlib');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title   = $this->lang->assetlib->caseLib . $this->lang->hyphen . $this->lang->assetlib->browse;
        $this->view->libs    = $this->loadModel('caselib')->getList($type, $orderBy, $pager);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->type    = $type;
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;
        $this->view->canSort = common::hasPriv('assetlib', 'caselibSort');

        $this->display();
    }

    /**
     * Issue library list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function issueLib($orderBy = 'order_desc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('issueLibList', $uri, 'assetlib');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->assetlib->issueLib . $this->lang->hyphen . $this->lang->assetlib->browse;
        $this->view->libs         = $this->assetlib->getList('issue', $orderBy, $pager);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->objectType   = 'issue';
        $this->view->browseLink   = 'issueLib';
        $this->view->createMethod = 'createIssueLib';
        $this->view->editMethod   = 'editIssueLib';
        $this->view->canSort      = common::hasPriv('assetlib', 'issuelibSort');

        $this->display('assetlib', 'browse');
    }

    /**
     * Create issue lib.
     *
     * @access public
     * @return void
     */
    public function createIssueLib()
    {
        if(!empty($_POST))
        {
            $issueLibID = $this->assetlib->create('issue');
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('assetlib', $issueLibID, 'opened');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            /* Return lib id when call the API. */
            if($this->viewType == 'json')
            {
                $response['id'] = $issueLibID;
                return $this->send($response);
            }

            $response['locate'] = $this->createLink('assetlib', 'issueLib');
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->createIssueLib;

        $this->display('assetlib', 'create');
    }

    /**
     * Edit issue lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editIssueLib($libID = 0)
    {
        if(!empty($_POST))
        {
            $changes = $this->assetlib->update($libID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('assetlib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('assetlib', 'issueLibView', "libID=$libID");
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->editIssueLib;
        $this->view->lib   = $this->assetlib->getById($libID);

        $this->display('assetlib', 'edit');
    }

    /**
     * Delete an issue lib.
     *
     * @param  int    $libID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function deleteIssueLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->issueLibDelete, inlink('deleteIssueLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->assetlib->delete(TABLE_ASSETLIB, $libID);

            $this->executeHooks($libID);

            die(js::reload('parent'));
        }
    }

    /**
     * Issue lib view.
     *
     * @param  int    $issueLibID
     * @access public
     * @return void
     */
    public function issueLibView($issueLibID)
    {
        $issueLibID = (int)$issueLibID;
        $issueLib   = $this->assetlib->getById($issueLibID);
        if(!isset($issueLib->id)) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->view->title = $issueLib->name . $this->lang->hyphen . $this->lang->assetlib->issueLibView;

        $this->view->lib        = $issueLib;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('assetlib', $issueLibID);
        $this->view->browseLink = $this->createLink('assetlib', 'issue', "libID=$issueLibID");

        $this->display('assetlib', 'view');
    }

    /**
     * Issue information list.
     *
     * @param  int    $libiD
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function issue($libID, $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $uri = $this->app->getURI(true);
        $this->session->set('issueList', $uri, 'assetlib');
        $this->app->loadLang('issue');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $queryID   = $browseType == 'bysearch' ? (int)$param : 0;
        $actionURL = $this->createLink('assetlib', 'issue', "libID=$libID&browseType=bySearch&param=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->assetlib->buildSearchForm('issue', $libID, $queryID, $actionURL);

        $this->view->title      = $this->lang->assetlib->issueLib . $this->lang->hyphen . $this->lang->assetlib->issue;
        $this->view->libID      = $libID;
        $this->view->libs       = $this->assetlib->getPairs('issue');
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->issues     = $this->assetlib->getObjectList('issue', $libID, $browseType, $param, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvers  = $this->assetlib->getApproveUsers('issue');
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;

        $this->display();
    }

    /**
     * Edit an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function editIssue($issueID)
    {
        $this->loadModel('issue');
        $issue = $this->issue->getByID($issueID);

        if($_POST)
        {
            $changes = $this->assetlib->updateAsset($issue, 'issue');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('issue', $issueID, 'edited');
            $this->action->logHistory($actionID, $changes);

            $locate = isonlybody() ? 'parent' : inLink('issue', "libID=$issue->lib");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->view->title = $this->lang->assetlib->issueLib . $this->lang->hyphen . $this->lang->assetlib->editIssue;
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->issue = $issue;

        $this->display();
    }

    /**
     * View an issue.
     *
     * @param  int    $issueID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function issueView($issueID)
    {
        $issueID    = (int)$issueID;
        $issue      = $this->loadModel('issue')->getById($issueID);
        $browseLink = $this->app->session->issueList ? $this->app->session->issueList : $this->createLink('assetlib', 'issue', "libID=$issue->lib");

        if(!$issue) die(js::locate($browseLink));

        $issue->files = $this->loadModel('file')->getByObject('issue', $issueID);

        $this->view->title      = $this->lang->assetlib->issueLib . $this->lang->hyphen . $this->lang->assetlib->issueView;
        $this->view->issue      = $issue;
        $this->view->actions    = $this->loadModel('action')->getList('issue', $issueID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseLink = $browseLink;

        $this->display();
    }

    /**
     * Import issue from project to issue lib.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importIssue($libID, $projectID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project');
        $this->loadModel('issue');

        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;

        if($_POST)
        {
            $this->issue->importToLib($this->post->issueIdList);
            die(js::reload('parent'));
        }

        $allProject = $this->project->getPairsByModel();
        if(empty($allProject))
        {
            echo js::alert($this->lang->assetlib->noProject);
            die(js::locate($this->session->issueList));
        }
        if(empty($projectID) or !isset($allProject[$projectID])) $projectID = key($allProject);

        /* Build the search form. */
        $actionURL = $this->createLink('assetlib', 'importIssue', "libID=$libID&projectID=$projectID&orderBy=$orderBy&browseType=bySearch&queryID=myQueryID");
        $this->config->issue->search['module']    = 'assetIssue';
        $this->config->issue->search['actionURL'] = $actionURL;
        $this->config->issue->search['queryID']   = $queryID;
        $this->config->issue->search['fields']['project'] = $this->lang->assetlib->project;
        $this->config->issue->search['params']['project'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $projectID => $allProject[$projectID], 'all' => $this->lang->assetlib->allProject));
        $this->loadModel('search')->setSearchParams($this->config->issue->search);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager  = pager::init(0, $recPerPage, $pageID);
        $issues = $this->assetlib->getNotImportedIssues($libID, $projectID, $orderBy, $browseType, $queryID, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'assetIssue', $browseType != 'bysearch');

        $this->view->title      = $this->lang->assetlib->common . $this->lang->hyphen . $this->lang->assetlib->importIssue;
        $this->view->allProject = $allProject;
        $this->view->issues     = $issues;
        $this->view->libID      = $libID;
        $this->view->projectID  = $projectID;
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->queryID    = $queryID;

        $this->display();
    }

    /**
     * Update assign of issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function assignToIssue($issueID)
    {
        $this->loadModel('issue');
        $this->app->loadConfig('action');

        if($_POST)
        {
            $changes = $this->issue->assignTo($issueID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('issue', $issueID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title  = $this->lang->assetlib->issueLib . $this->lang->hyphen . $this->lang->assetlib->assignToIssue;
        $this->view->object = $this->issue->getByID($issueID);
        $this->view->users  = $this->assetlib->getApproveUsers('issue');
        $this->view->type   = 'issue';

        $this->display('assetlib', 'assignto');
    }

    /**
     * Batch assign to issues.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function batchAssignToIssue($libID)
    {
        $this->loadModel('issue');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $issueIDList = $this->post->issueIDList;
            $issueIDList = array_unique($issueIDList);

            unset($_POST['issueIDList']);

            if(!is_array($issueIDList)) die(js::locate($this->createLink('assetlib', 'issue', "libID=$libID"), 'parent'));

            $issues = $this->issue->getByList($issueIDList);
            foreach($issues as $issueID => $issue)
            {
                $changes = $this->issue->assignTo($issueID);
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->action->create('issue', $issueID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent'));
        }
    }

    /**
     * Approve an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function approveIssue($issueID)
    {
        $this->loadModel('action');
        $issue = $this->loadModel('issue')->getById($issueID);
        if(empty($issue) or $issue->status == 'active') die(js::reload('parent.parent'));

        if(!empty($_POST))
        {
            $this->assetlib->approve($issueID, 'issue');
            if(dao::isError()) die(js::error(dao::getError()));

            $this->action->create('issue', $issueID, 'approved', $this->post->comment, $_POST['result']);

            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->assetlib->issueLib . $this->lang->hyphen . $this->lang->assetlib->approveIssue;
        $this->view->object     = $issue;
        $this->view->objectType = 'issue';

        $this->display('assetlib', 'approve');
    }

    /**
     * Batch approve issues.
     *
     * @param  int    $libID
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchApproveIssue($libID, $result)
    {
        $this->loadModel('issue');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $issueIDList = $this->post->issueIDList;
            $issueIDList = array_unique($issueIDList);
            $_POST['result'] = $result;

            unset($_POST['issueIDList']);

            if(!is_array($issueIDList)) die(js::locate($this->createLink('assetlib', 'issue', "libID=$libID"), 'parent'));

            $issues = $this->issue->getByList($issueIDList);
            foreach($issues as $issueID => $issue)
            {
                if($issue->status == 'active') continue;
                $this->assetlib->approve($issueID, 'issue');
                if(dao::isError()) die(js::error(dao::getError()));
                $this->action->create('issue', $issueID, 'approved', $this->post->comment, $_POST['result']);
            }

            die(js::alert($this->assetlib->getReviewTip($issues, 'issue', $result)) . js::reload('parent'));
        }
    }

    /**
     * Remove issue from issue library.
     *
     * @param  int    $issueID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function removeIssue($issueID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->confirmDeleteIssue, $this->createLink('assetlib', 'removeIssue', "issueID=$issueID&confirm=yes")));
        }
        else
        {
            $this->dao->delete()->from(TABLE_ISSUE)->where('id')->eq($issueID)->exec();
            $this->loadModel('action')->create('issue', $issueID, 'removed');
            die(js::locate($this->session->issueList, 'parent'));
        }
    }

    /**
     * Batch remove issues.
     *
     * @access public
     * @return void
     */
    public function batchRemoveIssue()
    {
        $issueIDList = $this->post->issueIDList;
        $issueIDList = array_unique($issueIDList);

        if(!is_array($issueIDList)) die(js::reload('parent'));
        $this->loadModel('action');

        foreach($issueIDList as $issueID)
        {
            $this->dao->delete()->from(TABLE_ISSUE)->where('id')->eq($issueID)->exec();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('issue', $issueID, 'removed');
        }
        die(js::reload('parent'));
    }

    /**
     * Risk library list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function riskLib($orderBy = 'order_desc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('riskLibList', $uri, 'assetlib');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->assetlib->riskLib . $this->lang->hyphen . $this->lang->assetlib->browse;
        $this->view->libs         = $this->assetlib->getList('risk', $orderBy, $pager);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->objectType   = 'risk';
        $this->view->browseLink   = 'riskLib';
        $this->view->createMethod = 'createRiskLib';
        $this->view->editMethod   = 'editRiskLib';
        $this->view->canSort      = common::hasPriv('assetlib', 'risklibSort');

        $this->display('assetlib', 'browse');
    }

    /**
     * Create risk lib.
     *
     * @access public
     * @return void
     */
    public function createRiskLib()
    {
        if(!empty($_POST))
        {
            $riskLibID = $this->assetlib->create('risk');
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('assetlib', $riskLibID, 'opened');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            /* Return lib id when call the API. */
            if($this->viewType == 'json')
            {
                $response['id'] = $riskLibID;
                return $this->send($response);
            }

            $response['locate'] = $this->createLink('assetlib', 'riskLib');
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->createRiskLib;

        $this->display('assetlib', 'create');
    }

    /**
     * Edit risk lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editRiskLib($libID = 0)
    {
        if(!empty($_POST))
        {
            $changes = $this->assetlib->update($libID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('assetlib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('assetlib', 'riskLibView', "libID=$libID");
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->editRiskLib;
        $this->view->lib   = $this->assetlib->getById($libID);

        $this->display('assetlib', 'edit');
    }

    /**
     * Delete a risk lib.
     *
     * @param  int    $libID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function deleteRiskLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->riskLibDelete, inlink('deleteRiskLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->assetlib->delete(TABLE_ASSETLIB, $libID);

            $this->executeHooks($libID);

            die(js::reload('parent'));
        }
    }

    /**
     * Risk lib view.
     *
     * @param  int    $riskLibID
     * @access public
     * @return void
     */
    public function riskLibView($riskLibID)
    {
        $riskLibID = (int)$riskLibID;
        $riskLib   = $this->assetlib->getById($riskLibID);
        if(!isset($riskLib->id)) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->view->title = $riskLib->name . $this->lang->hyphen . $this->lang->assetlib->riskLibView;

        $this->view->lib        = $riskLib;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('assetlib', $riskLibID);
        $this->view->browseLink = $this->createLink('assetlib', 'risk', "libID=$riskLibID");

        $this->display('assetlib', 'view');
    }

    /**
     * Risk information list.
     *
     * @param  int    $libiD
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function risk($libID, $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $uri = $this->app->getURI(true);
        $this->session->set('riskList', $uri, 'assetlib');
        $this->app->loadLang('risk');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $queryID   = $browseType == 'bysearch' ? (int)$param : 0;
        $actionURL = $this->createLink('assetlib', 'risk', "libID=$libID&browseType=bySearch&param=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->assetlib->buildSearchForm('risk', $libID, $queryID, $actionURL);

        $this->view->title      = $this->lang->assetlib->riskLib . $this->lang->hyphen . $this->lang->assetlib->risk;
        $this->view->libID      = $libID;
        $this->view->browseType = $browseType;
        $this->view->libs       = $this->assetlib->getPairs('risk');
        $this->view->param      = $param;
        $this->view->risks      = $this->assetlib->getObjectList('risk', $libID, $browseType, $param, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvers  = $this->assetlib->getApproveUsers('risk');
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;

        $this->display();
    }

    /**
     * Edit risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function editRisk($riskID)
    {
        $this->loadModel('risk');
        $risk = $this->risk->getByID($riskID);

        if($_POST)
        {
            $changes = $this->assetlib->updateAsset($risk, 'risk');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('risk', $riskID, 'edited');
            $this->action->logHistory($actionID, $changes);

            $locate = isonlybody() ? 'parent' : inLink('risk', "libID=$risk->lib");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->view->title = $this->lang->assetlib->riskLib . $this->lang->hyphen . $this->lang->assetlib->editRisk;
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->risk  = $risk;

        $this->display();
    }

    /**
     * View a risk.
     *
     * @param  int    $riskID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function riskView($riskID)
    {
        $riskID     = (int)$riskID;
        $risk       = $this->loadModel('risk')->getById($riskID);
        $browseLink = $this->app->session->riskList ? $this->app->session->riskList : $this->createLink('assetlib', 'risk', "libID=$risk->lib");

        if(!$risk) die(js::locate($browseLink));

        $risk->files = $this->loadModel('file')->getByObject('risk', $riskID);

        $this->view->title      = $this->lang->assetlib->riskLib . $this->lang->hyphen . $this->lang->assetlib->riskView;
        $this->view->risk       = $risk;
        $this->view->actions    = $this->loadModel('action')->getList('risk', $riskID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseLink = $browseLink;

        $this->display();
    }

    /**
     * Import risk from project to risk lib.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importRisk($libID, $projectID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project');
        $this->loadModel('risk');

        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;

        if($_POST)
        {
            $this->risk->importToLib($this->post->riskIdList);
            die(js::reload('parent'));
        }

        $allProject = $this->project->getPairsByModel();
        if(empty($allProject))
        {
            echo js::alert($this->lang->assetlib->noProject);
            die(js::locate($this->session->riskList));
        }
        if(empty($projectID) or !isset($allProject[$projectID])) $projectID = key($allProject);

        /* Build the search form. */
        $actionURL = $this->createLink('assetlib', 'importRisk', "libID=$libID&projectID=$projectID&orderBy=$orderBy&browseType=bySearch&queryID=myQueryID");
        $this->config->risk->search['module']    = 'assetRisk';
        $this->config->risk->search['actionURL'] = $actionURL;
        $this->config->risk->search['queryID']   = $queryID;
        $this->config->risk->search['fields']['project'] = $this->lang->assetlib->project;
        $this->config->risk->search['params']['project'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $projectID => $allProject[$projectID], 'all' => $this->lang->assetlib->allProject));
        $this->loadModel('search')->setSearchParams($this->config->risk->search);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $recPerPage, $pageID);
        $risks = $this->assetlib->getNotImportedRisks($libID, $projectID, $orderBy, $browseType, $queryID, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'assetRisk', $browseType != 'bysearch');

        $this->view->title      = $this->lang->assetlib->common . $this->lang->hyphen . $this->lang->assetlib->importRisk;
        $this->view->allProject = $allProject;
        $this->view->libID      = $libID;
        $this->view->projectID  = $projectID;
        $this->view->risks      = $risks;
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->queryID    = $queryID;

        $this->display();
    }

    /**
     * Update assign of risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function assignToRisk($riskID)
    {
        $this->loadModel('risk');
        $this->app->loadConfig('action');

        if($_POST)
        {
            $changes = $this->risk->assign($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title  = $this->lang->assetlib->riskLib . $this->lang->hyphen . $this->lang->assetlib->assignToRisk;
        $this->view->object = $this->risk->getByID($riskID);
        $this->view->users  = $this->assetlib->getApproveUsers('risk');
        $this->view->type   = 'risk';

        $this->display('assetlib', 'assignto');
    }

    /**
     * Batch assign to risks.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function batchAssignToRisk($libID)
    {
        $this->loadModel('risk');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $riskIDList = $this->post->riskIDList;
            $riskIDList = array_unique($riskIDList);

            unset($_POST['riskIDList']);

            if(!is_array($riskIDList)) die(js::locate($this->createLink('assetlib', 'risk', "libID=$libID"), 'parent'));

            $risks = $this->risk->getByList($riskIDList);
            foreach($risks as $riskID => $risk)
            {
                $changes = $this->risk->assign($riskID);
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->action->create('risk', $riskID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent'));
        }
    }

    /**
     * Approve a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function approveRisk($riskID)
    {
        $this->loadModel('action');
        $risk = $this->loadModel('risk')->getById($riskID);
        if(empty($risk) or $risk->status == 'active') die(js::reload('parent.parent'));

        if(!empty($_POST))
        {
            $this->assetlib->approve($riskID, 'risk');
            if(dao::isError()) die(js::error(dao::getError()));

            $this->action->create('risk', $riskID, 'approved', $this->post->comment, $_POST['result']);

            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->assetlib->riskLib . $this->lang->hyphen . $this->lang->assetlib->approveRisk;
        $this->view->object     = $risk;
        $this->view->objectType = 'risk';

        $this->display('assetlib', 'approve');
    }

    /**
     * Batch approve risks.
     *
     * @param  int    $libID
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchApproveRisk($libID, $result)
    {
        $this->loadModel('risk');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $riskIDList = $this->post->riskIDList;
            $riskIDList = array_unique($riskIDList);
            $_POST['result'] = $result;

            unset($_POST['riskIDList']);

            if(!is_array($riskIDList)) die(js::locate($this->createLink('assetlib', 'risk', "libID=$libID"), 'parent'));

            $risks = $this->risk->getByList($riskIDList);
            foreach($risks as $riskID => $risk)
            {
                if($risk->status == 'active') continue;
                $this->assetlib->approve($riskID, 'risk');
                if(dao::isError()) die(js::error(dao::getError()));
                $this->action->create('risk', $riskID, 'approved', $this->post->comment, $_POST['result']);
            }

            die(js::alert($this->assetlib->getReviewTip($risks, 'risk', $result)) . js::reload('parent'));
        }
    }

    /**
     * Remove risk from risk library.
     *
     * @param  int    $riskID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function removeRisk($riskID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->confirmDeleteRisk, $this->createLink('assetlib', 'removeRisk', "riskID=$riskID&confirm=yes")));
        }
        else
        {
            $this->dao->delete()->from(TABLE_RISK)->where('id')->eq($riskID)->exec();
            $this->loadModel('action')->create('risk', $riskID, 'removed');
            die(js::locate($this->session->riskList, 'parent'));
        }
    }

    /**
     * Batch remove risks.
     *
     * @access public
     * @return void
     */
    public function batchRemoveRisk()
    {
        $riskIDList = $this->post->riskIDList;
        $riskIDList = array_unique($riskIDList);

        if(!is_array($riskIDList)) die(js::reload('parent'));
        $this->loadModel('action');

        foreach($riskIDList as $riskID)
        {
            $this->dao->delete()->from(TABLE_RISK)->where('id')->eq($riskID)->exec();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('risk', $riskID, 'removed');
        }
        die(js::reload('parent'));
    }

    /**
     * Opportunity library list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function opportunityLib($orderBy = 'order_desc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('opportunityLibList', $uri, 'assetlib');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title   = $this->lang->assetlib->opportunityLib . $this->lang->hyphen . $this->lang->assetlib->browse;
        $this->view->libs         = $this->assetlib->getList('opportunity', $orderBy, $pager);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->objectType   = 'opportunity';
        $this->view->browseLink   = 'opportunityLib';
        $this->view->createMethod = 'createOpportunityLib';
        $this->view->editMethod   = 'editOpportunityLib';
        $this->view->canSort      = common::hasPriv('assetlib', 'opportunitylibSort');

        $this->display('assetlib', 'browse');
    }

    /**
     * Create opportunity lib.
     *
     * @access public
     * @return void
     */
    public function createOpportunityLib()
    {
        if(!empty($_POST))
        {
            $opportunityLibID = $this->assetlib->create('opportunity');
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('assetlib', $opportunityLibID, 'opened');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            /* Return lib id when call the API. */
            if($this->viewType == 'json')
            {
                $response['id'] = $opportunityLibID;
                return $this->send($response);
            }

            $response['locate'] = $this->createLink('assetlib', 'opportunityLib');
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->createOpportunityLib;

        $this->display('assetlib', 'create');
    }

    /**
     * Edit opportunity lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editOpportunityLib($libID = 0)
    {
        if(!empty($_POST))
        {
            $changes = $this->assetlib->update($libID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('assetlib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('assetlib', 'opportunityLibView', "libID=$libID");
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->editOpportunityLib;
        $this->view->lib   = $this->assetlib->getById($libID);

        $this->display('assetlib', 'edit');
    }

    /**
     * Delete an opportunity lib.
     *
     * @param  int    $libID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function deleteOpportunityLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->opportunityLibDelete, inlink('deleteOpportunityLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->assetlib->delete(TABLE_ASSETLIB, $libID);

            $this->executeHooks($libID);

            die(js::reload('parent'));
        }
    }

    /**
     * Opportunity lib view.
     *
     * @param  int    $opportunityLibID
     * @access public
     * @return void
     */
    public function opportunityLibView($opportunityLibID)
    {
        $opportunityLibID = (int)$opportunityLibID;
        $opportunityLib   = $this->assetlib->getById($opportunityLibID);
        if(!isset($opportunityLib->id)) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->view->title = $opportunityLib->name . $this->lang->hyphen . $this->lang->assetlib->opportunityLibView;

        $this->view->lib        = $opportunityLib;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('assetlib', $opportunityLibID);
        $this->view->browseLink = $this->createLink('assetlib', 'opportunity', "libID=$opportunityLibID");

        $this->display('assetlib', 'view');
    }

    /**
     * Opportunity information list.
     *
     * @param  int    $libiD
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function opportunity($libID, $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $uri = $this->app->getURI(true);
        $this->session->set('opportunityList', $uri, 'assetlib');
        $this->app->loadLang('opportunity');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('assetlib', 'opportunity', "libID=$libID&browseType=bySearch&param=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->assetlib->buildSearchForm('opportunity', $libID, $queryID, $actionURL);

        $this->view->title         = $this->lang->assetlib->opportunityLib . $this->lang->hyphen . $this->lang->assetlib->opportunity;
        $this->view->libID         = $libID;
        $this->view->libs          = $this->assetlib->getPairs('opportunity');
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;
        $this->view->opportunities = $this->assetlib->getObjectList('opportunity', $libID, $browseType, $param, $orderBy, $pager);
        $this->view->users         = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvers     = $this->assetlib->getApproveUsers('opportunity');
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;

        $this->display();
    }

    /**
     * Edit opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function editOpportunity($opportunityID)
    {
        $this->loadModel('opportunity');
        $opportunity = $this->opportunity->getByID($opportunityID);

        if($_POST)
        {
            $changes = $this->assetlib->updateAsset($opportunity, 'opportunity');
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('opportunity', $opportunityID, 'edited');
            $this->action->logHistory($actionID, $changes);

            $locate = isonlybody() ? 'parent' : inLink('opportunity', "libID=$opportunity->lib");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->view->title       = $this->lang->assetlib->opportunityLib . $this->lang->hyphen . $this->lang->assetlib->editOpportunity;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->opportunity = $opportunity;

        $this->display();
    }

    /**
     * View an opportunity.
     *
     * @param  int    $opportunityID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function opportunityView($opportunityID)
    {
        $opportunityID    = (int)$opportunityID;
        $opportunity      = $this->loadModel('opportunity')->getById($opportunityID);
        $browseLink = $this->app->session->opportunityList ? $this->app->session->opportunityList : $this->createLink('assetlib', 'opportunity', "libID=$opportunity->lib");

        if(!$opportunity) die(js::locate($browseLink));

        $opportunity->files = $this->loadModel('file')->getByObject('opportunity', $opportunityID);

        $this->view->title       = $this->lang->assetlib->opportunityLib . $this->lang->hyphen . $this->lang->assetlib->opportunityView;
        $this->view->opportunity = $opportunity;
        $this->view->actions     = $this->loadModel('action')->getList('opportunity', $opportunityID);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseLink  = $browseLink;

        $this->display();
    }

    /**
     * Import opportunity from project to story lib.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importOpportunity($libID, $projectID = 0, $orderBy = 'id_desc', $browseType = '', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project');
        $this->loadModel('opportunity');

        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;

        if($_POST)
        {
            $this->opportunity->importToLib($this->post->opportunityIdList);
            die(js::reload('parent'));
        }

        $allProject = $this->project->getPairsByModel(array('waterfall', 'waterfallplus', 'ipd'));
        if(empty($allProject))
        {
            echo js::alert($this->lang->assetlib->noProject);
            die(js::locate($this->session->opportunityList));
        }
        if(empty($projectID) or !isset($allProject[$projectID])) $projectID = key($allProject);

        /* Build the search form. */
        $actionURL = $this->createLink('assetlib', 'importOpportunity', "libID=$libID&projectID=$projectID&orderBy=$orderBy&browseType=bySearch&queryID=myQueryID");
        $this->config->opportunity->search['module']    = 'assetOpportunity';
        $this->config->opportunity->search['actionURL'] = $actionURL;
        $this->config->opportunity->search['queryID']   = $queryID;
        $this->config->opportunity->search['fields']['project'] = $this->lang->assetlib->project;
        $this->config->opportunity->search['params']['project'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $projectID => $allProject[$projectID], 'all' => $this->lang->assetlib->allProject));
        $this->loadModel('search')->setSearchParams($this->config->opportunity->search);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager         = pager::init(0, $recPerPage, $pageID);
        $opportunities = $this->assetlib->getNotImportedOpportunities($libID, $projectID, $orderBy, $browseType, $queryID, $pager);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'assetOpportunity', $browseType != 'bysearch');

        $this->view->title         = $this->lang->assetlib->common . $this->lang->hyphen . $this->lang->assetlib->importOpportunity;
        $this->view->allProject    = $allProject;
        $this->view->libID         = $libID;
        $this->view->projectID     = $projectID;
        $this->view->opportunities = $opportunities;
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->queryID       = $queryID;

        $this->display();
    }

    /**
     * Update assign of opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function assignToOpportunity($opportunityID)
    {
        $this->loadModel('opportunity');
        $this->app->loadConfig('action');

        if($_POST)
        {
            $changes = $this->opportunity->assign($opportunityID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('opportunity', $opportunityID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title  = $this->lang->assetlib->opportunityLib . $this->lang->hyphen . $this->lang->assetlib->assignToOpportunity;
        $this->view->object = $this->opportunity->getByID($opportunityID);
        $this->view->users  = $this->assetlib->getApproveUsers('opportunity');
        $this->view->type   = 'opportunity';

        $this->display('assetlib', 'assignto');
    }

    /**
     * Batch assign to opportunities.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function batchAssignToOpportunity($libID)
    {
        $this->loadModel('opportunity');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $opportunityIDList = $this->post->opportunityIDList;
            $opportunityIDList = array_unique($opportunityIDList);

            unset($_POST['opportunityIDList']);

            if(!is_array($opportunityIDList)) die(js::locate($this->createLink('assetlib', 'opportunity', "libID=$libID"), 'parent'));

            $opportunities = $this->opportunity->getByList($opportunityIDList);
            foreach($opportunities as $opportunityID => $opportunity)
            {
                $changes = $this->opportunity->assign($opportunityID);
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->action->create('opportunity', $opportunityID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent'));
        }
    }

    /**
     * Approve an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return void
     */
    public function approveOpportunity($opportunityID)
    {
        $this->loadModel('action');
        $opportunity = $this->loadModel('opportunity')->getById($opportunityID);
        if(empty($opportunity) or $opportunity->status == 'active') die(js::reload('parent.parent'));

        if(!empty($_POST))
        {
            $this->assetlib->approve($opportunityID, 'opportunity');
            if(dao::isError()) die(js::error(dao::getError()));

            $this->action->create('opportunity', $opportunityID, 'approved', $this->post->comment, $_POST['result']);

            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->assetlib->opportunityLib . $this->lang->hyphen . $this->lang->assetlib->approveOpportunity;
        $this->view->object     = $opportunity;
        $this->view->objectType = 'opportunity';

        $this->display('assetlib', 'approve');
    }

    /**
     * Batch approve opportunities.
     *
     * @param  int    $libID
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchApproveOpportunity($libID, $result)
    {
        $this->loadModel('opportunity');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $opportunityIDList = $this->post->opportunityIDList;
            $opportunityIDList = array_unique($opportunityIDList);
            $_POST['result'] = $result;

            unset($_POST['opportunityIDList']);

            if(!is_array($opportunityIDList)) die(js::locate($this->createLink('assetlib', 'opportunity', "libID=$libID"), 'parent'));

            $opportunities = $this->opportunity->getByList($opportunityIDList);
            foreach($opportunities as $opportunityID => $opportunity)
            {
                if($opportunity->status == 'active') continue;
                $this->assetlib->approve($opportunityID, 'opportunity');
                if(dao::isError()) die(js::error(dao::getError()));
                $this->action->create('opportunity', $opportunityID, 'approved', $this->post->comment, $_POST['result']);
            }

            die(js::alert($this->assetlib->getReviewTip($opportunities, 'opportunity', $result)) . js::reload('parent'));
        }
    }

    /**
     * Remove opportunity from opportunity library.
     *
     * @param  int    $opportunityID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function removeOpportunity($opportunityID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->confirmDeleteOpportunity, $this->createLink('assetlib', 'removeOpportunity', "opportunityID=$opportunityID&confirm=yes")));
        }
        else
        {
            $this->dao->delete()->from(TABLE_OPPORTUNITY)->where('id')->eq($opportunityID)->exec();
            $this->loadModel('action')->create('opportunity', $opportunityID, 'removed');
            die(js::locate($this->session->opportunityList, 'parent'));
        }
    }

    /**
     * Batch remove opportunities.
     *
     * @access public
     * @return void
     */
    public function batchRemoveOpportunity()
    {
        $opportunityIDList = $this->post->opportunityIDList;
        $opportunityIDList = array_unique($opportunityIDList);

        if(!is_array($opportunityIDList)) die(js::reload('parent'));
        $this->loadModel('action');

        foreach($opportunityIDList as $opportunityID)
        {
            $this->dao->delete()->from(TABLE_OPPORTUNITY)->where('id')->eq($opportunityID)->exec();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('opportunity', $opportunityID, 'removed');
        }
        die(js::reload('parent'));
    }

    /**
     * Practice library list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function practiceLib($orderBy = 'order_desc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('practiceLibList', $uri, 'assetlib');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->assetlib->practiceLib . $this->lang->hyphen . $this->lang->assetlib->browse;
        $this->view->libs         = $this->assetlib->getList('practice', $orderBy, $pager);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->objectType   = 'practice';
        $this->view->browseLink   = 'practiceLib';
        $this->view->createMethod = 'createPracticeLib';
        $this->view->editMethod   = 'editPracticeLib';
        $this->view->canSort      = common::hasPriv('assetlib', 'practicelibSort');

        $this->display('assetlib', 'browse');
    }

    /**
     * Create practice lib.
     *
     * @access public
     * @return void
     */
    public function createPracticeLib()
    {
        if(!empty($_POST))
        {
            $practiceLibID = $this->assetlib->create('practice');
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('assetlib', $practiceLibID, 'opened');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            /* Return lib id when call the API. */
            if($this->viewType == 'json')
            {
                $response['id'] = $practiceLibID;
                return $this->send($response);
            }

            $response['locate'] = $this->createLink('assetlib', 'practiceLib');
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->createPracticeLib;

        $this->display('assetlib', 'create');
    }

    /**
     * Edit practice lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editPracticeLib($libID = 0)
    {
        if(!empty($_POST))
        {
            $changes = $this->assetlib->update($libID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('assetlib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('assetlib', 'practiceLibView', "libID=$libID");
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->editPracticeLib;
        $this->view->lib   = $this->assetlib->getById($libID);

        $this->display('assetlib', 'edit');
    }

    /**
     * Delete a practice lib.
     *
     * @param  int    $libID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function deletePracticeLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->practiceLibDelete, inlink('deletePracticeLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->assetlib->delete(TABLE_ASSETLIB, $libID);

            $this->executeHooks($libID);

            die(js::reload('parent'));
        }
    }

    /**
     * Practice lib view.
     *
     * @param  int    $practiceLibID
     * @access public
     * @return void
     */
    public function practiceLibView($practiceLibID)
    {
        $practiceLibID = (int)$practiceLibID;
        $practiceLib   = $this->assetlib->getById($practiceLibID);
        if(!isset($practiceLib->id)) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->view->title = $practiceLib->name . $this->lang->hyphen . $this->lang->assetlib->practiceLibView;

        $this->view->lib        = $practiceLib;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('assetlib', $practiceLibID);
        $this->view->browseLink = $this->createLink('assetlib', 'practice', "libID=$practiceLibID");

        $this->display('assetlib', 'view');
    }

    /**
     * Practice information list.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function practice($libID, $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $uri = $this->app->getURI(true);
        $this->session->set('practiceList', $uri, 'assetlib');
        $this->app->loadLang('doc');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('assetlib', 'practice', "libID=$libID&browseType=bySearch&param=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->assetlib->buildSearchForm('practice', $libID, $queryID, $actionURL);

        $this->view->title      = $this->lang->assetlib->practiceLib . $this->lang->hyphen . $this->lang->assetlib->practice;
        $this->view->libID      = $libID;
        $this->view->libs       = $this->assetlib->getPairs('practice');
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->objects    = $this->assetlib->getObjectList('practice', $libID, $browseType, $param, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvers  = $this->assetlib->getApproveUsers('practice');
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->objectType = 'practice';

        $this->display('assetlib' , 'doclist');
    }

    /**
     * Edit practice.
     *
     * @param  int    $practiceID
     * @access public
     * @return void
     */
    public function editPractice($practiceID)
    {
        $this->loadModel('doc');
        $practice        = $this->doc->getById($practiceID);
        $practiceContent = $this->doc->getContent($practice->id, $practice->version);

        if($_POST)
        {
            $this->lang->assetlib->title = $this->lang->doc->title;
            $this->config->doc->edit->requiredFields = str_replace('lib', '', $this->config->doc->edit->requiredFields);

            $docData = fixer::input('post')
                ->add('editedBy', $this->app->user->account)
                ->setIF(strpos(",$practice->editedList,", ",{$this->app->user->account},") === false, 'editedList', $practice->editedList . ",{$this->app->user->account}")
                ->remove('editedDate,contentType')
                ->stripTags('content')
                ->get();
            $docData->acl         = $practice->acl;
            $docData->type        = $practice->type;
            $docData->status      = $practice->status;
            $docData->rawContent  = $practiceContent->rawContent;
            $docData->contentType = $practice->contentType;
            $docData->parent      = $practice->parent;

            $result = $this->doc->update($practiceID, $docData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $changes = $result['changes'];
            $files   = $result['files'];

            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'edited' : 'commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->loadModel('action')->create('doc', $practiceID, $action, $fileAction . $this->post->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $locate = isonlybody() ? 'parent' : inLink('practice', "libID={$practice->assetLib}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->view->title      = $this->lang->assetlib->practiceLib . $this->lang->hyphen . $this->lang->assetlib->editPractice;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->doc        = $practice;
        $this->view->objectType = 'practice';

        $this->display('assetlib', 'editdoc');
    }

    /**
     * Practice View.
     *
     * @param  int    $practiceID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function practiceView($practiceID, $version = 0)
    {
        $practiceID = (int)$practiceID;
        $practice   = $this->loadModel('doc')->getById($practiceID, $version, true);
        $browseLink = $this->app->session->practiceList ? $this->app->session->practiceList : $this->createLink('assetlib', 'practice', "libID=$practice->assetLib");

        if(!$practice) die(js::locate($browseLink));

        $this->view->title      = $this->lang->assetlib->practiceLib . $this->lang->hyphen . $this->lang->assetlib->practiceView;
        $this->view->doc        = $practice;
        $this->view->actions    = $this->loadModel('action')->getList('doc', $practiceID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->version    = $version == 0 ? $practice->version : $version;
        $this->view->source     = $this->dao->select('title,project,lib')->from(TABLE_DOC)->where('id')->eq($practice->from)->fetch();
        $this->view->browseLink = $browseLink;
        $this->view->objectType = 'practice';

        $this->display('assetlib', 'docview');
    }

    /**
     * Import doc from project to practice lib.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  int    $docLibID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importPractice($libID, $projectID = 0, $docLibID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project');
        $this->loadModel('doc');

        $this->session->set('docList', $this->app->getURI(true), 'assetlib');

        if($_POST)
        {
            $this->doc->importToLib($this->post->docIdList, 'practice');
            die(js::reload('parent'));
        }

        $allProject = $this->project->getPairsByModel();
        $executions = $this->loadModel('execution')->getPairs();

        if(empty($allProject))
        {
            echo js::alert($this->lang->assetlib->noProject);
            die(js::locate($this->session->practiceList));
        }
        if(empty($projectID) or !isset($allProject[$projectID])) $projectID = key($allProject);

        $projectDocLib = $this->doc->getLibsByObject('project', $projectID);
        $docLibPairs   = array();
        foreach($projectDocLib as $id => $lib)
        {
            $docLibPairs[$id] = $lib->name;
            if($lib->type == 'execution' and isset($executions[$lib->execution])) $docLibPairs[$id] = $executions[$lib->execution] . '/' . $lib->name;
        }

        if(empty($docLibID) or !isset($docLibPairs[$docLibID])) $docLibID = key($docLibPairs);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $docs     = $this->assetlib->getNotImportedDocs($libID, $projectID, $docLibID, 'practice', $orderBy);
        $recTotal = count($docs);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $docs     = array_chunk($docs, $pager->recPerPage);

        $this->view->title = $this->lang->assetlib->common . $this->lang->hyphen . $this->lang->assetlib->importPractice;

        $this->view->allProject = $allProject;
        $this->view->docLibs    = $docLibPairs;
        $this->view->libID      = $libID;
        $this->view->projectID  = $projectID;
        $this->view->docLibID   = $docLibID;
        $this->view->docs       = empty($docs) ? $docs : $docs[$pageID - 1];
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->objectType = 'practice';

        $this->display('assetlib', 'importdoc');
    }

    /**
     * Update assign of practice.
     *
     * @param  int    $practiceID
     * @access public
     * @return void
     */
    public function assignToPractice($practiceID)
    {
        $this->loadModel('doc');
        $this->app->loadConfig('action');

        if($_POST)
        {
            $changes = $this->doc->assign($practiceID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('doc', $practiceID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title  = $this->lang->assetlib->practiceLib . $this->lang->hyphen . $this->lang->assetlib->assignToPractice;
        $this->view->object = $this->doc->getByID($practiceID);
        $this->view->users  = $this->assetlib->getApproveUsers('practice');
        $this->view->type   = 'doc';

        $this->display('assetlib', 'assignto');
    }

    /**
     * Batch assign to practices.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function batchAssignToPractice($libID)
    {
        $this->loadModel('doc');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $practiceIDList = $this->post->objectIDList;
            $practiceIDList = array_unique($practiceIDList);

            unset($_POST['objectIDList']);

            if(!is_array($practiceIDList)) die(js::locate($this->createLink('assetlib', 'practice', "libID=$libID"), 'parent'));

            $practices = $this->doc->getByList($practiceIDList);
            foreach($practices as $practiceID => $practice)
            {
                $changes = $this->doc->assign($practiceID);
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->action->create('doc', $practiceID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent'));
        }
    }

    /**
     * Approve a practice.
     *
     * @param  int    $practiceID
     * @access public
     * @return void
     */
    public function approvePractice($practiceID)
    {
        $this->loadModel('action');
        $practice = $this->loadModel('doc')->getById($practiceID);
        if(empty($practice) or $practice->status == 'active') die(js::reload('parent.parent'));

        if(!empty($_POST))
        {
            $this->assetlib->approve($practiceID, 'doc');
            if(dao::isError()) die(js::error(dao::getError()));

            $this->action->create('doc', $practiceID, 'approved', $this->post->comment, $_POST['result']);

            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->assetlib->practiceLib . $this->lang->hyphen . $this->lang->assetlib->approvePractice;
        $this->view->object     = $practice;
        $this->view->objectType = 'doc';

        $this->display('assetlib', 'approve');
    }

    /**
     * Batch approve practices.
     *
     * @param  int    $libID
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchApprovePractice($libID, $result)
    {
        $this->loadModel('doc');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $practiceIDList = $this->post->objectIDList;
            $practiceIDList = array_unique($practiceIDList);
            $_POST['result'] = $result;

            unset($_POST['objectIDList']);

            if(!is_array($practiceIDList)) die(js::locate($this->createLink('assetlib', 'practice', "libID=$libID"), 'parent'));

            $practices = $this->doc->getByList($practiceIDList);
            foreach($practices as $practiceID => $practice)
            {
                if($practice->status == 'active') continue;
                $this->assetlib->approve($practiceID, 'doc');
                if(dao::isError()) die(js::error(dao::getError()));
                $this->action->create('doc', $practiceID, 'approved', $this->post->comment, $_POST['result']);
            }

            die(js::alert($this->assetlib->getReviewTip($practices, 'practice', $result)) . js::reload('parent'));
        }
    }

    /**
     * Remove practice from practice library.
     *
     * @param  int    $practiceID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function removePractice($practiceID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->confirmDeletePractice, $this->createLink('assetlib', 'removePractice', "practiceID=$practiceID&confirm=yes")));
        }
        else
        {
            $this->dao->delete()->from(TABLE_DOC)->where('id')->eq($practiceID)->exec();
            $this->dao->delete()->from(TABLE_DOCCONTENT)->where('doc')->eq($practiceID)->exec();
            $this->loadModel('action')->create('doc', $practiceID, 'removed');
            die(js::locate($this->session->practiceList, 'parent'));
        }
    }

    /**
     * Batch remove practices.
     *
     * @access public
     * @return void
     */
    public function batchRemovePractice()
    {
        $practiceIDList = $this->post->objectIDList;
        $practiceIDList = array_unique($practiceIDList);

        if(!is_array($practiceIDList)) die(js::reload('parent'));
        $this->loadModel('action');

        foreach($practiceIDList as $practiceID)
        {
            $this->dao->delete()->from(TABLE_DOC)->where('id')->eq($practiceID)->exec();
            $this->dao->delete()->from(TABLE_DOCCONTENT)->where('doc')->eq($practiceID)->exec();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('doc', $practiceID, 'removed');
        }
        die(js::reload('parent'));
    }

    /**
     * Component library list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function componentLib($orderBy = 'order_desc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('componentLibList', $uri, 'assetlib');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->assetlib->componentLib . $this->lang->hyphen . $this->lang->assetlib->browse;
        $this->view->libs         = $this->assetlib->getList('component', $orderBy, $pager);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->objectType   = 'component';
        $this->view->browseLink   = 'componentLib';
        $this->view->createMethod = 'createComponentLib';
        $this->view->editMethod   = 'editComponentLib';
        $this->view->canSort      = common::hasPriv('assetlib', 'componentlibSort');

        $this->display('assetlib', 'browse');
    }

    /**
     * Create component lib.
     *
     * @access public
     * @return void
     */
    public function createComponentLib()
    {
        if(!empty($_POST))
        {
            $componentLibID = $this->assetlib->create('component');
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('assetlib', $componentLibID, 'opened');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            /* Return lib id when call the API. */
            if($this->viewType == 'json')
            {
                $response['id'] = $componentLibID;
                return $this->send($response);
            }

            $response['locate'] = $this->createLink('assetlib', 'componentLib');
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->createComponentLib;

        $this->display('assetlib', 'create');
    }

    /**
     * Edit component lib.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function editComponentLib($libID = 0)
    {
        if(!empty($_POST))
        {
            $changes = $this->assetlib->update($libID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('assetlib', $libID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('assetlib', 'componentLibView', "libID=$libID");
            return $this->send($response);
        }

        $this->view->title = $this->lang->assetlib->editComponentLib;
        $this->view->lib   = $this->assetlib->getById($libID);

        $this->display('assetlib', 'edit');
    }

    /**
     * Delete a component lib.
     *
     * @param  int    $libID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function deleteComponentLib($libID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->componentLibDelete, inlink('deleteComponentLib', "libID=$libID&confirm=yes")));
        }
        else
        {
            $this->assetlib->delete(TABLE_ASSETLIB, $libID);

            $this->executeHooks($libID);

            die(js::reload('parent'));
        }
    }

    /**
     * Component lib view.
     *
     * @param  int    $componentLibID
     * @access public
     * @return void
     */
    public function componentLibView($componentLibID)
    {
        $componentLibID = (int)$componentLibID;
        $componentLib   = $this->assetlib->getById($componentLibID);
        if(!isset($componentLib->id)) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->view->title = $componentLib->name . $this->lang->hyphen . $this->lang->assetlib->componentLibView;

        $this->view->lib        = $componentLib;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions    = $this->loadModel('action')->getList('assetlib', $componentLibID);
        $this->view->browseLink = $this->createLink('assetlib', 'component', "libID=$componentLibID");

        $this->display('assetlib', 'view');
    }

    /**
     * Component information list.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function component($libID, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $uri = $this->app->getURI(true);
        $this->session->set('componentList', $uri, 'assetlib');
        $this->app->loadLang('doc');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $queryID   = $browseType == 'bysearch' ? (int)$param : 0;
        $actionURL = $this->createLink('assetlib', 'component', "libID=$libID&browseType=bySearch&param=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->assetlib->buildSearchForm('component', $libID, $queryID, $actionURL);

        $this->view->title      = $this->lang->assetlib->componentLib . $this->lang->hyphen . $this->lang->assetlib->component;
        $this->view->libID      = $libID;
        $this->view->libs       = $this->assetlib->getPairs('component');
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->objects    = $this->assetlib->getObjectList('component', $libID, $browseType, $param, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvers  = $this->assetlib->getApproveUsers('component');
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->objectType = 'component';

        $this->display('assetlib', 'doclist');
    }

    /**
     * Edit component.
     *
     * @param  int    $componentID
     * @access public
     * @return void
     */
    public function editComponent($componentID)
    {
        $this->loadModel('doc');
        $component = $this->doc->getById($componentID);

        if($_POST)
        {
            $this->lang->assetlib->title = $this->lang->doc->title;
            $this->config->doc->edit->requiredFields = str_replace('lib', '', $this->config->doc->edit->requiredFields);

            $docData = fixer::input('post')
                ->add('editedBy', $this->app->user->account)
                ->setIF(strpos(",$component->editedList,", ",{$this->app->user->account},") === false, 'editedList', $component->editedList . ",{$this->app->user->account}")
                ->remove('editedDate,contentType')
                ->get();
            $docData->acl    = $component->acl;
            $docData->type   = $component->type;
            $docData->status = $component->status;

            $result = $this->doc->update($componentID, $docData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $changes = $result['changes'];
            $files   = $result['files'];

            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'edited' : 'commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->loadModel('action')->create('doc', $componentID, $action, $fileAction . $this->post->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $locate = isonlybody() ? 'parent' : inLink('component', "libID={$component->assetLib}");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->view->title      = $this->lang->assetlib->componentLib . $this->lang->hyphen . $this->lang->assetlib->editComponent;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->doc        = $component;
        $this->view->objectType = 'component';

        $this->display('assetlib', 'editdoc');
    }

    /**
     * Component View.
     *
     * @param  int    $componentID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function componentView($componentID, $version = 0)
    {
        $componentID = (int)$componentID;
        $component   = $this->loadModel('doc')->getById($componentID, $version, true);
        $browseLink  = $this->app->session->componentList ? $this->app->session->componentList : $this->createLink('assetlib', 'component', "libID=$component->assetLib");

        if(!$component) die(js::locate($browseLink));


        $this->view->title      = $this->lang->assetlib->componentLib . $this->lang->hyphen . $this->lang->assetlib->componentView;
        $this->view->doc        = $component;
        $this->view->actions    = $this->loadModel('action')->getList('doc', $componentID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->version    = $version == 0 ? $component->version : $version;
        $this->view->source     = $this->dao->select('title,project,lib')->from(TABLE_DOC)->where('id')->eq($component->from)->fetch();
        $this->view->browseLink = $browseLink;
        $this->view->objectType = 'component';

        $this->display('assetlib', 'docview');
    }

    /**
     * Import doc from project to component lib.
     *
     * @param  int    $libID
     * @param  int    $projectID
     * @param  int    $docLibID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importComponent($libID, $projectID = 0, $docLibID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project');
        $this->loadModel('doc');

        if($_POST)
        {
            $this->doc->importToLib($this->post->docIdList, 'component');
            die(js::reload('parent'));
        }

        $allProject = $this->project->getPairsByModel();
        if(empty($allProject))
        {
            echo js::alert($this->lang->assetlib->noProject);
            die(js::locate($this->session->componentList));
        }
        if(empty($projectID) or !isset($allProject[$projectID])) $projectID = key($allProject);

        $projectDocLib = $this->doc->getLibsByObject('project', $projectID);
        $docLibPairs   = array();
        foreach($projectDocLib as $id => $lib) $docLibPairs[$id] = $lib->name;

        if(empty($docLibID) or !isset($docLibPairs[$docLibID])) $docLibID = key($docLibPairs);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $docs     = $this->assetlib->getNotImportedDocs($libID, $projectID, $docLibID, 'component', $orderBy);
        $recTotal = count($docs);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $docs     = array_chunk($docs, $pager->recPerPage);

        $this->view->title = $this->lang->assetlib->common . $this->lang->hyphen . $this->lang->assetlib->importComponent;

        $this->view->allProject = $allProject;
        $this->view->docLibs    = $docLibPairs;
        $this->view->libID      = $libID;
        $this->view->projectID  = $projectID;
        $this->view->docLibID   = $docLibID;
        $this->view->docs       = empty($docs) ? $docs : $docs[$pageID - 1];
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->objectType = 'component';

        $this->display('assetlib', 'importdoc');
    }

    /**
     * Approve a component.
     *
     * @param  int    $componentID.
     * @access public
     * @return void
     */
    public function approveComponent($componentID)
    {
        $this->loadModel('action');
        $component = $this->loadModel('doc')->getById($componentID);
        if(empty($component) or $component->status == 'active') die(js::reload('parent.parent'));

        if(!empty($_POST))
        {
            $this->assetlib->approve($componentID, 'doc');
            if(dao::isError()) die(js::error(dao::getError()));

            $this->action->create('doc', $componentID, 'approved', $this->post->comment, $_POST['result']);

            die(js::reload('parent.parent'));
        }

        $this->view->title      = $this->lang->assetlib->componentLib . $this->lang->hyphen . $this->lang->assetlib->approveComponent;
        $this->view->object     = $component;
        $this->view->objectType = 'doc';

        $this->display('assetlib', 'approve');
    }

    /**
     * Batch approve components.
     *
     * @param  int    $libID
     * @param  string $result
     * @access public
     * @return void
     */
    public function batchApproveComponent($libID, $result)
    {
        $this->loadModel('doc');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $componentIDList = $this->post->objectIDList;
            $componentIDList = array_unique($componentIDList);
            $_POST['result'] = $result;

            unset($_POST['objectIDList']);

            if(!is_array($componentIDList)) die(js::locate($this->createLink('assetlib', 'component', "libID=$libID"), 'parent'));

            $components = $this->doc->getByList($componentIDList);
            foreach($components as $componentID => $component)
            {
                if($component->status == 'active') continue;
                $this->assetlib->approve($componentID, 'doc');
                if(dao::isError()) die(js::error(dao::getError()));
                $this->action->create('doc', $componentID, 'approved', $this->post->comment, $_POST['result']);
            }

            die(js::alert($this->assetlib->getReviewTip($components, 'component', $result)) . js::reload('parent'));
        }
    }

    /**
     * Update assign of component.
     *
     * @param  int    $componentID
     * @access public
     * @return void
     */
    public function assignToComponent($componentID)
    {
        $this->loadModel('doc');
        $this->app->loadConfig('action');

        if($_POST)
        {
            $changes = $this->doc->assign($componentID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('doc', $componentID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title  = $this->lang->assetlib->componentLib . $this->lang->hyphen . $this->lang->assetlib->assignToComponent;
        $this->view->object = $this->doc->getByID($componentID);
        $this->view->users  = $this->assetlib->getApproveUsers('component');
        $this->view->type   = 'doc';

        $this->display('assetlib', 'assignto');
    }

    /**
     * Batch assign to components.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function batchAssignToComponent($libID)
    {
        $this->loadModel('doc');
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $componentIDList = $this->post->objectIDList;
            $componentIDList = array_unique($componentIDList);

            unset($_POST['objectIDList']);

            if(!is_array($componentIDList)) die(js::locate($this->createLink('assetlib', 'component', "libID=$libID"), 'parent'));

            $components = $this->doc->getByList($componentIDList);
            foreach($components as $componentID => $component)
            {
                $changes = $this->doc->assign($componentID);
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->action->create('doc', $componentID, 'assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::reload('parent'));
        }
    }

    /**
     * Remove component from component library.
     *
     * @param  int    $componentID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function removeComponent($componentID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->assetlib->confirmDeleteComponent, $this->createLink('assetlib', 'removeComponent', "componentID=$componentID&confirm=yes")));
        }
        else
        {
            $this->dao->delete()->from(TABLE_DOC)->where('id')->eq($componentID)->exec();
            $this->dao->delete()->from(TABLE_DOCCONTENT)->where('doc')->eq($componentID)->exec();
            $this->loadModel('action')->create('doc', $componentID, 'removed');
            die(js::locate($this->session->componentList, 'parent'));
        }
    }

    /**
     * Batch remove components.
     *
     * @access public
     * @return void
     */
    public function batchRemoveComponent()
    {
        $componentIDList = $this->post->objectIDList;
        $componentIDList = array_unique($componentIDList);

        if(!is_array($componentIDList)) die(js::reload('parent'));
        $this->loadModel('action');

        foreach($componentIDList as $componentID)
        {
            $this->dao->delete()->from(TABLE_DOC)->where('id')->eq($componentID)->exec();
            $this->dao->delete()->from(TABLE_DOCCONTENT)->where('id')->eq($componentID)->exec();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('doc', $componentID, 'removed');
        }
        die(js::reload('parent'));
    }

    /**
     * Library view.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function view($libID = 0)
    {
        $libID = (int)$libID;
        $lib   = $this->assetlib->getById($libID);
        if(empty($lib)) die(js::error($this->lang->notFound) . js::locate('back'));

        $libType = $lib->type;
        $url     = $this->createLink('assetlib', $libType . 'LibView', 'libID=' . $libID);
        $this->locate($url);
    }

    /**
     * For lib sort
     *
     * @param string $type risk|opportunity|issue|story|practice|component
     * @access public
     * @return void
     */
    public function libSort($type)
    {
        $idList = explode(',', trim($this->post->assetlib, ','));
        $order  = $this->dao->select('*')->from(TABLE_ASSETLIB)->where('id')->in($idList)->andWhere('type')->eq($type)->orderBy('order_asc')->fetch('order');
        $idList = array_reverse($idList);

        /* Init Order. */
        if(empty($order)) $order = 1;

        foreach($idList as $assetlibID)
        {
            $this->dao->update(TABLE_ASSETLIB)->set('`order`')->eq($order)->where('id')->eq($assetlibID)->andWhere('type')->eq($type)->exec();
            $order++;
        }
    }
}

<?php
/**
 * The control file of feedback of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class feedback extends control
{
    /**
     * 浏览页面的地址。
     * The browse page URL.
     *
     * @var string
     * @access private
     */
    private $browseURL;

    /**
     * 构造函数，根据界面设置不同的浏览地址。
     * Construct function, set browseURL according to the vision.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->browseURL = inlink($this->config->vision == 'lite' ? 'browse' : 'admin');
    }

    /**
     * Index.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->browseURL);
    }

    /**
     * Common actions.
     *
     * @access public
     * @return void
     */
    public function commonActions()
    {
        $productID = $this->session->feedbackProduct ? $this->session->feedbackProduct : 0;
        $this->feedback->setMenu($productID);
    }

    /**
     * Create feedback.
     *
     * @param  string $extras
     * @param  int    $copyFeedbackID
     * @access public
     * @return void
     */
    public function create($extras = '', $copyFeedbackID = 0)
    {
        $this->extendRequireFields();
        if($_POST)
        {
            $feedbackID = $this->feedback->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('feedback', $feedbackID, 'Opened');

            $needReview = !$this->feedback->forceNotReview();
            if($this->feedback->getStatus('create', $needReview) == 'noreview') $this->action->create('feedback', $feedbackID, 'submitReview');

            if(!empty($_POST['fileList']))
            {
                $fileList = $this->post->fileList;
                if($fileList) $fileList = json_decode($fileList, true);
                $this->loadModel('file')->saveDefaultFiles($fileList, 'feedback', $feedbackID);
            }

            $this->executeHooks($feedbackID);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $feedbackID));

            $browseLink = $this->session->feedbackList ? $this->session->feedbackList : $this->browseURL;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $browseLink, 'closeModal' => true));
        }

        $products = $this->feedback->getGrantProducts();

        /* Get workflow relation by extras. */
        $relation = '';
        if($extras)
        {
            $extras = str_replace(array(',', ' '), array('&', ''), $extras);
            parse_str($extras, $params);

            if(isset($params['prevModule']) and isset($params['prevDataID']))
            {
                $relation = $this->loadModel('workflowrelation')->getByPrevAndNext($params['prevModule'], 'feedback');
                if($relation) $relation->prevDataID = $params['prevDataID'];
            }
        }
        $productID = !empty($params['productID']) ? $params['productID'] : (int)$this->session->feedbackProduct;
        $moduleID  = !empty($params['moduleID'])  ? $params['moduleID']  : 0;
        if($copyFeedbackID)
        {
            $feedback  = $this->feedback->getByID($copyFeedbackID);
            $productID = $feedback ? $feedback->product : $productID;
            foreach($feedback->files as $file)
            {
                $file->name = $file->title;
                $file->url  = $this->createLink('file', 'download', "fileID={$file->id}");
            }

            $this->view->feedback = $feedback;
        }
        $this->session->set('feedbackProduct', $productID, 'feedback');
        $this->commonActions();

        $this->view->title     = $this->lang->feedback->create;
        $this->view->modules   = $this->loadModel('tree')->getOptionMenu((int)$productID, $viewType = 'feedback', $startModuleID = 0);
        $this->view->moduleID  = $moduleID;
        $this->view->productID = $productID;
        $this->view->products  = arrayUnion(array('' => ''), $products);
        $this->view->relation  = $relation;
        $this->view->pri       = 3;
        $this->view->users     = $this->loadModel('user')->getPairs('devfirst|noclosed|nodeleted');

        $this->display();
    }

    /**
     * 批量创建反馈。
     * Batch create feedbacks.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchCreate($productID = 0, $moduleID = 0)
    {
        if($_POST)
        {
            $feedbacks = form::batchData()->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $feedbackIdList = $this->feedback->batchCreate($productID, $feedbacks);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Return feedback id when call the API. */
            if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $feedbackIdList));

            $browseLink = $this->session->feedbackList ? $this->session->feedbackList : $this->browseURL;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $browseLink, 'closeModal' => true));
        }

        $this->view->title     = $this->lang->feedback->batchCreate;
        $this->view->productID = $productID;
        $this->view->modules   = $this->loadModel('tree')->getOptionMenu((int)$productID, 'feedback', 0);
        $this->view->moduleID  = $moduleID;
        $this->view->users     = $this->loadModel('user')->getPairs('devfirst|noclosed|nodeleted');
        $this->view->feedbacks = $this->feedbackZen->getDataFromUploadImages($productID);

        $this->display();
    }

    /**
     * Edit feedback.
     *
     * @param  int    $id
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function edit($id, $productID = 0)
    {
        $feedback  = $this->feedback->getById($id);
        $productID = $productID ? $productID : $feedback->product;
        $feedback->product = $productID;

        $products = $this->feedback->getGrantProducts(true, false, 'all');
        if(!isset($products[$feedback->product])) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->feedback->accessDenied, 'locate' => $this->browseURL)));

        $this->session->set('feedbackProduct', $productID, 'feedback');

        $this->commonActions();

        $this->extendRequireFields($id);
        if($_POST)
        {
            $changes = $this->feedback->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('feedback', $id, 'Edited');
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($id);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $id));

            $browseLink = $this->session->feedbackList ? $this->session->feedbackList : $this->browseURL;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $browseLink, 'closeModal' => true));
        }

        $this->view->title     = $this->lang->feedback->edit;
        $this->view->feedback  = $feedback;
        $this->view->modules   = $this->loadModel('tree')->getOptionMenu($productID, 'feedback', 0, 'all');
        $this->view->products  = arrayUnion(array('' => ''), $products);
        $this->view->productID = $productID;
        $this->view->users     = $this->loadModel('user')->getPairs('devfirst|noclosed|nodeleted');

        $this->display();
    }

    /**
     * Batch edit feedback.
     *
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function batchEdit($browseType)
    {
        if(isset($_POST['title']))
        {
            $allChanges = $this->feedback->batchUpdate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            if(!empty($allChanges))
            {
                foreach($allChanges as $feedbackID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->action->create('feedback', $feedbackID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            $browseLink = $this->session->feedbackList ? $this->session->feedbackList : $this->browseURL;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $browseLink));
        }

        if($this->app->tab == 'my')
        {
            /* Set my menu. */
            $this->loadModel('my');

            if(isset($this->lang->my->featureBar['work']['feedback'][$browseType]))
            {
                $this->lang->my->menu->work['subModule'] = 'feedback';
                $this->lang->my->menu->work['subMenu']->feedback['subModule'] = 'feedback';
            }
            elseif(isset($this->lang->my->featureBar['contribute']['feedback'][$browseType]))
            {
                $this->lang->my->menu->contribute['subModule'] = 'feedback';
                $this->lang->my->menu->contribute['subMenu']->feedback['subModule'] = 'feedback';
            }
        }

        $type      = '';
        $hasPriv   = common::hasPriv('feedback', 'editOthers');
        $noChangeIDList = '';

        if(!$hasPriv) $type = 'openedbyme';

        $this->view->title      = $this->lang->feedback->edit;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->moduleList = $this->feedback->getModuleListGroupByProduct();
        $this->view->feedbacks  = !empty($_POST['feedbackIDList']) ? $this->feedback->getByList($_POST['feedbackIDList'], $type) : array();
        $this->view->products   = arrayUnion(array('' => ''), $this->feedback->getGrantProducts(true, false, 'all'));
        $this->view->browseType = $browseType;

        if(!$hasPriv) $noChangeIDList = array_diff($_POST['feedbackIDList'], array_keys($this->view->feedbacks));

        $this->view->noChangeIDList  = !empty($noChangeIDList) ? implode(',', $noChangeIDList) : '';
        $this->display();
    }

    /**
     * Browse feedback.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'wait', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $from = 'feedback', $blockID = 0)
    {
        $this->loadModel('datatable');
        $this->session->set('feedbackList', $this->app->getURI(true), 'feedback');

        /* Set menu.*/
        $param     = (int)$param;
        $productID = $param;
        $objectID  = $param == 'all' ? 0 : $param;

        /* Load tree model. */
        $this->loadModel('tree');
        $this->loadModel('custom');
        $this->app->loadLang('doc');

        if(!$this->session->feedbackProduct) $this->session->feedbackProduct = 'all';
        if($browseType == 'bysearch')                 $productID = $this->session->feedbackProduct = 'all';
        if($browseType == 'byModule'  && $param)      $productID = $this->tree->getByID($param)->root;
        if($browseType == 'byProduct' && !$param)     $productID = 'all';
        if($browseType != 'byProduct' && !$productID) $productID = $this->session->feedbackProduct;
        if(!$objectID) $objectID = $productID == 'all' ? 0 : $productID;

        $this->feedback->setMenu($productID);

        if(!in_array($browseType, array('byProduct', 'byModule', 'bysearch'))) $this->session->set('browseType', '', 'feedback');
        if(in_array($browseType, array('byProduct', 'byModule', 'bysearch')))
        {
            $this->session->set('browseType', $browseType, 'feedback');
            $this->session->set('objectID', $objectID, 'feedback');
        }
        elseif(!$this->session->objectID || $this->session->objectID != $objectID)
        {
            $this->session->set('objectID', $objectID, 'feedback');
            $this->session->set('browseType', 'byProduct', 'feedback');
        }

        $products = $this->feedback->getGrantProducts(true, false, 'all');
        if($from == 'doc' && empty($products)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->tips->noProduct));
        if($from == 'doc' && !$productID) $productID = key($products);

        if(!in_array($browseType, array('byModule', 'byProduct'))) $this->session->set('feedbackBrowseType', $browseType);
        if(!in_array($browseType, array('byModule', 'byProduct')) && $this->session->feedbackBrowseType == 'bysearch') $this->session->set('feedbackBrowseType', 'wait');

        $moduleName = $this->lang->feedback->allModule;
        $moduleID = $this->session->browseType == 'byModule' && $this->session->objectID ? $this->session->objectID : 0;

        if($this->session->browseType == 'byModule'  && $moduleID && $this->session->feedbackProduct != 'all') $moduleName = $this->tree->getById($moduleID)->name;
        if($this->session->browseType == 'byProduct' && $moduleID && $this->session->feedbackProduct != 'all') $moduleName = $this->loadModel('product')->getById($moduleID)->name;

        $queryID = $browseType == 'bysearch' ? $param : 0;
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        if($browseType != 'bysearch')
        {
            if($this->session->feedbackBrowseType) $browseType = $this->session->feedbackBrowseType;
            $feedbacks = $this->feedback->getList($browseType, $orderBy, $pager, $moduleID);
        }
        else
        {
            $feedbacks = $this->feedback->getBySearch($queryID, $orderBy, $pager);
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'feedback', false);

        $storyIdList = $bugIdList = $todoIdList = $taskIdList =  $ticketIdList = $demandIdList = array();
        $feedbackRelatedObjectList = $this->custom->getRelatedObjectList(array_keys($feedbacks), 'feedback', 'byRelation', true);
        foreach($feedbacks as $feedback)
        {
            if($feedback->solution == 'tobug')       $bugIdList[]    = $feedback->result;
            if($feedback->solution == 'tostory')     $storyIdList[]  = $feedback->result;
            if($feedback->solution == 'touserstory') $storyIdList[]  = $feedback->result;
            if($feedback->solution == 'totodo')      $todoIdList[]   = $feedback->result;
            if($feedback->solution == 'totask')      $taskIdList[]   = $feedback->result;
            if($feedback->solution == 'toticket')    $ticketIdList[] = $feedback->result;
            if($feedback->solution == 'todemand')    $demandIdList[] = $feedback->result;
            $feedback->relatedObject = zget($feedbackRelatedObjectList, $feedback->id, 0);
        }
        $bugs    = $bugIdList    ? $this->loadModel('bug')->getByIdList($bugIdList) : array();
        $stories = $storyIdList  ? $this->loadModel('story')->getByList($storyIdList) : array();
        $todos   = $todoIdList   ? $this->loadModel('todo')->getByList($todoIdList) : array();
        $tasks   = $taskIdList   ? $this->loadModel('task')->getByIdList($taskIdList) : array();
        $tickets = $ticketIdList ? $this->loadModel('ticket')->getByList($ticketIdList) : array();
        $demands = ($demandIdList and $this->config->vision == 'or') ? $this->loadModel('demand')->getByList($demandIdList) : array();

        $this->config->feedback->search['onMenuBar'] = 'no';
        $actionURL = inlink($this->app->getMethodName(), "browseType=bysearch&param=myQueryID&orderBy=$orderBy&recTotal=0&recPerPage=$recPerPage&pageID=$pageID&from=$from&blockID=$blockID");
        $this->feedback->buildSearchForm($actionURL, $queryID, $productID);

        $this->loadModel('user');
        $userPairs     = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $productViewID = $productID != 'all' ? $productID : 0;
        $productAcl    = $this->dao->select('acl')->from(TABLE_PRODUCT)->where('id')->eq($productViewID)->fetch();
        if(isset($productAcl->acl) and $productAcl->acl != 'open' and $productViewID)
        {
            $users = $this->user->getProductViewUsers($productViewID);

            $userPairs = array('' => '');
            $users = $this->loadModel('user')->getListByAccounts($users, 'account');
            foreach($users as $account => $user) $userPairs[$account] = $user->realname ? $user->realname : $user->account;
        }

        $showModule  = !empty($this->config->feedback->admin->showModule) ? $this->config->feedback->admin->showModule : '';
        $modulePairs =  $showModule ? $this->tree->getModulePairs(0, 'feedback', $showModule) : array();

        $modules = array(0 => '/');
        foreach($modulePairs as $moduleID => $moduleName) $modules[$moduleID] = '/' . $moduleName;

        $this->view->from    = $from;
        $this->view->blockID = $blockID;
        $this->view->idList  = '';
        if($from == 'doc')
        {
            $docBlock = $this->loadModel('doc')->getDocBlock($blockID);
            $this->view->docBlock = $docBlock;
            if($docBlock)
            {
                $content = json_decode($docBlock->content, true);
                if(isset($content['idList'])) $this->view->idList = $content['idList'];
            }
        }

        $this->view->title       = $this->lang->feedback->browse;
        $this->view->browseType  = $browseType;
        $this->view->feedbacks   = $feedbacks;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->moduleID    = $moduleID;
        $this->view->productID   = $productID;
        $this->view->product     = $this->loadModel('product')->getByID((int)$productID);
        $this->view->products    = $products;
        $this->view->bugs        = $bugs;
        $this->view->todos       = $todos;
        $this->view->stories     = $stories;
        $this->view->tasks       = $tasks;
        $this->view->tickets     = $tickets;
        $this->view->demands     = $demands;
        $this->view->setModule   = true;
        $this->view->showBranch  = false;
        $this->view->modulePairs = $modulePairs;
        $this->view->modules     = $modules;
        $this->view->moduleTree  = $this->tree->getFeedbackTreeMenu(array('treeModel', 'createFeedbackLink'));
        $this->view->moduleName  = $moduleName;
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->users       = $userPairs;
        $this->view->projects    = $this->loadModel('project')->getPairsByProgram(0, 'noclosed');
        $this->view->allProducts = $products;

        $this->display();
    }

    /**
     * View feedback.
     *
     * @param  int    $feedbackID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function view($feedbackID, $browseType = '')
    {
        $this->commonActions();

        $feedbackID = (int)$feedbackID;
        $feedback   = $this->feedback->getById($feedbackID);
        if(empty($feedback)) return $this->feedbackZen->responseNotFound($this->browseURL);

        $products = $this->feedback->getGrantProducts(true, false, 'all');
        if(!isset($products[$feedback->product])) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->feedback->accessDenied, 'locate' => $this->browseURL)));

        $uri = $this->app->getURI(true);
        $this->session->set('bugList',   $uri, 'qa');
        $this->session->set('storyList', $uri, 'product');
        $this->session->set('taskList',  $uri, 'execution');
        if((!empty($this->app->user->feedback) or $this->cookie->feedbackView) and $feedback->public == 0 and $this->app->user->account != $feedback->openedBy) return;

        $actions = $this->loadModel('action')->getList('feedback', $feedbackID);
        $actions = $this->feedback->processActions($feedbackID, $actions);

        $this->executeHooks($feedbackID);

        $this->view->title       = $this->lang->feedback->view;
        $this->view->feedbackID  = $feedbackID;
        $this->view->feedback    = $feedback;
        $this->view->productID   = $feedback->product;
        $this->view->modulePath  = $this->loadModel('tree')->getParents($feedback->module);
        $this->view->product     = $this->loadModel('product')->getById($feedback->product);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->preAndNext  = $this->loadModel('common')->getPreAndNextObject('feedback', $feedbackID);
        $this->view->actions     = $actions;
        $this->view->contacts    = $this->dao->select('*')->from(TABLE_USER)->where('account')->in("{$feedback->openedBy},{$feedback->assignedTo},{$feedback->processedBy}")->fetchAll('account', false);
        $this->view->browseType  = $browseType;

        if($this->config->vision != 'lite') $this->view->relations = $this->feedback->getRelations($feedbackID, $feedback);
        if($this->config->vision == 'or')
        {
            $this->view->products = $this->product->getPairs();
            $this->view->roadmaps = $this->loadModel('roadmap')->getPairs();
        }

        $this->lang->action->desc->commented = $this->lang->feedback->commented;

        $this->view->products       = $products;
        $this->view->projects       = $this->loadModel('project')->getPairsByProgram(0, 'noclosed');
        $this->view->relatedObjects = $this->loadModel('custom')->getRelatedObjectList($feedback->id, 'feedback', 'byObject');
        $this->display();
    }

    /**
     * Feedback to todo.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function toTodo($feedbackID)
    {
        $this->commonActions();
        $this->app->loadLang('todo');
        $this->lang->todo->idvalue = $this->lang->todo->name;
        if($this->config->vision == 'or')
        {
            $typeList['feedback'] = $this->lang->todo->typeList['feedback'];
            $this->lang->todo->typeList = $typeList;
        }
        echo $this->fetch('todo', 'create', "date=today&account=&from=feedback&feedbackID=$feedbackID");
    }

    /**
     * Feedback to story.
     *
     * @param  int    $product
     * @param  string $extra
     * @access public
     * @return void
     */
    public function toStory($product, $extra)
    {
        $this->commonActions();
        echo $this->fetch('story', 'create', "product=$product&branch=0&moduleID=0&storyID=0&executionID=0&bugID=0&planID=0&todoID=0&extra=$extra&type=story");
    }

    /**
     * Feedback to user story.
     *
     * @param  int    $product
     * @param  string $extra
     * @access public
     * @return void
     */
    public function toUserStory($product, $extra)
    {
        $this->commonActions();
        echo $this->fetch('story', 'create', "product=$product&branch=0&moduleID=0&storyID=0&executionID=0&bugID=0&planID=0&todoID=0&extra=$extra&type=requirement");
    }

    /**
     * Feedback to epic.
     *
     * @param  int    $product
     * @param  string $extra
     * @access public
     * @return void
     */
    public function toEpic($product, $extra)
    {
        $this->commonActions();
        echo $this->fetch('story', 'create', "product=$product&branch=0&moduleID=0&storyID=0&executionID=0&bugID=0&planID=0&todoID=0&extra=$extra&type=epic");
    }

    /**
     * Feedback to ticket.
     *
     * @param  string    $extra
     * @access public
     * @return void
     */
    public function toTicket($extra)
    {
        $this->commonActions();
        echo $this->fetch('ticket', 'create', "productID=&extras=$extra");
    }

    /**
     * Feedback to demand.
     *
     * @param  int    $poolID
     * @param  int    $demandID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function toDemand($poolID = 0, $demandID = 0, $extra = '')
    {
        $this->commonActions();
        echo $this->fetch('demand', 'create', "poolID=$poolID&demandID=$demandID&extra=$extra");
    }

    /**
     * Review feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function review($feedbackID)
    {
        if($_POST)
        {
            $changes = $this->feedback->review($feedbackID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $result   = $this->post->result;
                $actionID = $this->loadModel('action')->create('feedback', $feedbackID, 'reviewed', $this->post->comment, ucfirst($result));
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($feedbackID);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $feedback = $this->feedback->getById($feedbackID);
        if($feedback->status != 'noreview')
        {
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert({icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x', message: '{$this->lang->hasReviewed}'}).then((res) => {loadCurrentPage()});"));
        }


        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->view->feedback   = $feedback;
        $this->view->actions    = $this->loadModel('action')->getList('feedback', $feedbackID);
        $this->view->assignedTo = $this->dao->findByID($feedback->product)->from(TABLE_PRODUCT)->fetch('feedback');
        $this->display();
    }

    /**
     * Batch review.
     *
     * @param  int    $result
     * @access public
     * @return void
     */
    public function batchReview($result)
    {
        $feedbackIdList = $this->post->feedbackIDList;
        if(!empty($feedbackIdList))
        {
            $feedbackIdList = array_unique($feedbackIdList);
            $actions        = $this->feedback->batchReview($feedbackIdList, $result);
        }

        if(dao::isError()) $this->send(array('result' => 'success', 'message' => dao::getError(), 'load' => true));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Ajax set like.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function ajaxLike($feedbackID)
    {
        $likes = $this->feedback->like($feedbackID);
        $users = $this->loadModel('user')->getPairs('noletter');
        $count = 0;
        $title = '';

        if($likes)
        {
            $likes = explode(',', $likes);
            $count = count($likes);
            foreach($likes as $account) $title .= zget($users, $account, $account) . ',';
            $title .= $this->lang->feedback->feelLike;
        }

        $likeIcon = (!empty($likes) and in_array($this->app->user->account, $likes)) ? 'thumbs-up-solid' : 'thumbs-up';
        $output   = html::a("javascript:like($feedbackID)", "<i class='icon icon-{$likeIcon}''></i>({$count})", '', "class='toolbar-item btn ghost' key='like' title='$title'");

        if($this->app->viewType == 'mhtml') $output = html::a("javascript:like($feedbackID)", "<i class='icon icon-thumbs-up'></i> ({$count})", '', "id='likeLink'");

        return print($output);
    }

    /**
     * AJAX: return feedbacks of a user in html select.
     *
     * @param  int    $userID
     * @param  int    $id
     * @param  int    $appendID
     * @access public
     * @return void
     */
    public function ajaxGetUserFeedback($userID = '', $id = '', $appendID = '')
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $feedbacks = $this->feedback->getUserFeedbackPairs($account, 0, $appendID);

        $items = array();
        foreach($feedbacks as $feedbackID => $feedbackTitle) $items[] = array('text' => $feedbackTitle, 'value' => $feedbackID);

        $fieldName = $id ? "feedbacks[$id]" : 'feedback';
        return print(json_encode(array('name' => $fieldName, 'items' => $items)));
    }

    /**
     * Ask or reply feedback.
     *
     * @param  int    $feedbackID
     * @param  string $type
     * @access public
     * @return void
     */
    public function comment($feedbackID, $type = 'commented')
    {
        $this->extendRequireFields($feedbackID);
        if($_POST)
        {
            if(!$this->post->comment)
            {
                dao::$errors['comment'] = $this->lang->feedback->mustInputComment[$type];
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $oldFeedback = $this->dao->findById($feedbackID)->from(TABLE_FEEDBACK)->fetch();

            $now  = helper::now();
            $data = fixer::input('post')->stripTags($this->config->feedback->editor->comment['id'])->remove('comment,faq,labels')->get();
            $data->status = $type;

            if($type == 'asked')
            {
                $this->app->methodName = 'ask';

                $data->editedBy   = $this->app->user->account;
                $data->editedDate = $now;
                if(empty($oldFeedback->prevStatus)) $data->prevStatus = $oldFeedback->status;
            }

            if($type == 'replied')
            {
                $this->app->methodName = 'reply';

                $data->processedBy   = $this->app->user->account;
                $data->processedDate = $now;
            }

            /* Comment do not change status. */
            if($type == 'commented') $data->status = $oldFeedback->status;

            $data = $this->loadModel('file')->processImgURL($data, $this->config->feedback->editor->comment['id'], $this->post->uid);
            $this->dao->update(TABLE_FEEDBACK)->data($data)->autoCheck()->checkFlow()->where('id')->eq($feedbackID)->exec();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $files      = $this->loadModel('file')->saveUpload('feedback', $feedbackID);
            $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "<br />" : '';

            $changes = common::createChanges($oldFeedback, $data);
            if($changes or $this->post->comment)
            {
                $actionID = $this->loadModel('action')->create('feedback', $feedbackID, htmlspecialchars($type), $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($feedbackID);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $feedback = $this->feedback->getByID($feedbackID);
        $title    = $this->lang->feedback->reply;
        if($type == 'asked')     $title = $this->lang->feedback->ask;
        if($type == 'commented') $title = $this->lang->feedback->comment;

        $this->view->title      = $title;
        $this->view->feedbackID = $feedbackID;
        $this->view->feedback   = $feedback;
        $this->view->type       = $type;
        $this->view->faqs       = arrayUnion(array('' => ''), $this->loadModel('faq')->getPairs($feedback->product));
        $this->display();
    }

    /**
     * Reply feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function reply($feedbackID)
    {
        echo $this->fetch('feedback', 'comment', "feedbackID=$feedbackID&type=replied");
    }

    /**
     * Ask feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function ask($feedbackID)
    {
        echo $this->fetch('feedback', 'comment', "feedbackID=$feedbackID&type=asked");
    }

    /**
     * Update assign of feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function assignTo($feedbackID)
    {
        $feedback = $this->feedback->getById($feedbackID);

        $this->extendRequireFields($feedbackID);
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->feedback->assign($feedbackID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->action->create('feedback', $feedbackID, 'Assigned', $this->post->comment, $this->post->assignedTo);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($feedbackID);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $product = $this->loadModel('product')->getById($feedback->product);
        $this->loadModel('user');
        if($product->acl != 'open')
        {
            $users = $this->user->getProductViewUsers($product->id);
            if($feedback->assignedTo) $users[$feedback->assignedTo] = $feedback->assignedTo;

            $userPairs = array('' => '');
            $users = $this->loadModel('user')->getListByAccounts($users, 'account');
            foreach($users as $account => $user) $userPairs[$account] = $user->realname ? $user->realname : $user->account;
        }
        else
        {
            $userPairs = $this->loadModel('user')->getPairs('noclosed|nodeleted', $feedback->assignedTo);
        }

        $this->view->users      = $userPairs;
        $this->view->feedback   = $feedback;
        $this->view->feedbackID = $feedbackID;
        $this->view->actions    = $this->loadModel('action')->getList('feedback', $feedbackID);
        $this->display();
    }

    /**
     * Batch assignTo.
     *
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function batchAssignTo($assignedTo)
    {
        return $this->send(array('result' => 'success', 'message' => $this->feedback->batchAssign($assignedTo), 'load' => true));
    }

    /**
     * Delete feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function delete($feedbackID, $type = 'oldPage', $confirm = 'no')
    {
        if($type == 'oldPage' && $confirm != 'yes') return print(js::confirm($this->lang->feedback->confirmDelete, inlink('delete', "feedbackID=$feedbackID&type=$type&confirm=yes")));

        $this->feedback->delete(TABLE_FEEDBACK, $feedbackID);
        $this->executeHooks($feedbackID);

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'message' => $this->lang->saveSuccess));
        if($type == 'oldPage') return print(js::reload('parent'));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "feedbackID=$feedbackID"), 'load' => true, 'closeModal' => true));
    }

    /**
     * Close feedback.
     *
     * @param  int    $feedbackID
     * @param  string $confirmClose
     * @access public
     * @return void
     */
    public function close($feedbackID, $confirmClose = 'no')
    {
        $relations = $this->feedback->getRelations($feedbackID);
        $canClose  = true;
        foreach($relations as $type => $typeRelation)
        {
            foreach($typeRelation as $relation)
            {
                if($relation->status != 'closed')
                {
                    $canClose = false;
                    break 2;
                }
            }
        }
        if(!$canClose && $confirmClose != 'yes')
        {
            $confirmedURL = $this->createLink('feedback', 'close', 'feedbackID=' . $feedbackID . '&confirmClose=yes');
            $canceledURL  = true;
            return $this->send(array('result' => 'fail', 'message' => '', 'load' => array('confirm' => $this->lang->feedback->uncloedObjectsNotice, 'confirmed' => array('url' => $confirmedURL, 'load' => 'modal'), 'canceled' => $canceledURL)));
        }

        $this->extendRequireFields($feedbackID);
        if($_POST)
        {
            $this->feedback->close($feedbackID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($feedbackID);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $feedbackList = array('' => '');
        $feedbacks = $this->feedback->getList('all');

        if($feedbacks)
        {
            foreach($feedbacks as $key => $feedback) $feedbackList[$feedback->id] = "#$feedback->id " . $feedback->title;
            if(!empty($feedbackList[$feedbackID])) unset($feedbackList[$feedbackID]);
        }

        $this->view->feedbackID   = $feedbackID;
        $this->view->feedback     = $this->feedback->getById($feedbackID);
        $this->view->feedbacks    = $feedbackList;
        $this->view->closedReason = (!empty($this->app->user->feedback) or $this->cookie->feedbackView) ? 'commented' : '';
        $this->display();
    }

    /**
     * Activate feedback.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function activate($feedbackID)
    {
        $feedback = $this->feedback->getById((int)$feedbackID);
        $actions  = $this->loadModel('action')->getList('feedback', $feedbackID);
        $actions  = $this->feedback->processActions($feedbackID, $actions);
        $product  = $this->loadModel('product')->getById($feedback->product);

        $this->extendRequireFields($feedbackID);
        if(!empty($_POST))
        {
            $changes = $this->feedback->activate($feedbackID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('feedback', $feedbackID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }
        $this->commonActions();

        $this->view->assignedTo = $product->feedback ? $product->feedback : '';
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->view->actions    = $actions;
        $this->view->feedback   = $feedback;

        $this->display();
    }

    /**
     * Batch close feedback.
     *
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchClose($from = '')
    {
        /* Get edited feedbacks. */
        if(!$this->post->feedbackIdList && !$this->post->feedbackIDList) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->feedbackList));
        $feedbackIdList = $this->post->feedbackIdList ? $this->post->feedbackIdList : $this->post->feedbackIDList;
        $feedbackIdList = array_unique($feedbackIdList);
        $feedbackPairs  = $this->feedback->getList('all');
        $feedbacks      = $this->feedback->getByList($feedbackIdList);
        $feedbackCount  = count($feedbacks);

        $closedFeedbacks    = array();
        $hasUnclosedObjects = $this->feedback->checkUnclosedObjectsForFeedbacks($feedbackIdList);
        foreach($feedbacks as $feedback)
        {
            $feedback->feedbackIdList = $feedback->id;
            if($feedback->status == 'closed')
            {
                $closedFeedbacks[] = $feedback->id;
                unset($feedbacks[$feedback->id]);
            }
            elseif(in_array($feedback->id, $hasUnclosedObjects))
            {
                unset($feedbacks[$feedback->id]);
            }
        }

        $errorTips          = '';
        $hasUnclosedObjects = array_diff($hasUnclosedObjects, $closedFeedbacks);
        if(count($closedFeedbacks) == $feedbackCount)
        {
            return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->feedback->allClosedFeedback, 'load' => $this->session->feedbackList)));
        }
        elseif(count($hasUnclosedObjects) == $feedbackCount)
        {
            return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->feedback->allUnclosedObjects, 'load' => $this->session->feedbackList)));
        }
        elseif(count($closedFeedbacks) + count($hasUnclosedObjects) == $feedbackCount)
        {
            return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->feedback->bothClosedAndUnclosedObjects, 'load' => $this->session->feedbackList)));
        }

        if(count($closedFeedbacks) > 0) $errorTips .= sprintf($this->lang->feedback->closedFeedback, implode(',', $closedFeedbacks)) . '<br/>';
        if(count($hasUnclosedObjects) > 0) $errorTips .= sprintf($this->lang->feedback->hasUnclosedObjects, implode(',', $hasUnclosedObjects));

        $this->extendRequireFields();
        if(isset($_POST['comments']))
        {
            $allChanges = $this->feedback->batchClose();

            if($allChanges)
            {
                foreach($allChanges as $feedbackID => $changes)
                {
                    if(empty($changes)) continue;
                    $actionID = $this->loadModel('action')->create('feedback', $feedbackID, 'closed', $this->post->comments[$feedbackID], $this->post->closedReasons[$feedbackID] . ($this->post->repeatFeedbackIDList[$feedbackID] ? ':' . (int)$this->post->repeatFeedbackIDList[$feedbackID] : ''));
                    $this->action->logHistory($actionID, $changes);
                }
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchOther');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->feedbackList));
        }

        $this->app->loadLang('bug');

        $feedbackList = array('' => '');
        if($feedbackPairs)
        {
            foreach($feedbackPairs as $value) $feedbackList[$value->id] = "#$value->id " . $value->title;
            foreach(array_keys($feedbacks) as $id)
            {
                if(!empty($feedbackList[$id])) unset($feedbackList[$id]);
            }
        }

        if($this->app->tab == 'my')
        {
            $this->lang->feedback->menu      = $this->lang->my->menu;
            $this->lang->feedback->menuOrder = $this->lang->my->menuOrder;

            $this->loadModel('my');
            if($from == 'work')
            {
                /* Set my menu. */
                $this->lang->my->menu->work['subModule'] = 'feedback';
                $this->lang->my->menu->work['subMenu']->feedback['subModule'] = 'feedback';
            }
            elseif($from == 'contribute')
            {
                $this->lang->my->menu->contribute['subModule'] = 'feedback';
                $this->lang->my->menu->contribute['subMenu']->feedback['subModule'] = 'feedback';
            }
        }

        $this->view->title          = $this->lang->feedback->batchClose;
        $this->view->feedbacks      = $feedbacks;
        $this->view->feedbackIdList = $feedbackIdList;
        $this->view->reasonList     = $this->lang->feedback->closedReasonList;
        $this->view->feedbackList   = $feedbackList;
        $this->view->errorTips      = $errorTips;

        $this->display();
    }

    /**
     * Browse feedback in admin.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function admin($browseType = 'wait', $param = 0, $orderBy = 'editedDate_desc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $from = 'feedback', $blockID = 0)
    {
        $this->session->set('todoList', $this->app->getURI(true), 'feedback');

        $deptPairs     = $this->loadModel('dept')->getOptionMenu();
        $userDeptPairs = $this->dao->select('account,dept')->from(TABLE_USER)->where('deleted')->eq(0)->fetchPairs('account', 'dept');

        if(!$this->config->URAndSR or $this->config->vision == 'lite') unset($this->lang->feedback->moreSelects['admin']['more']['touserstory']);

        /* Build the search form. */
        $actionURL = $this->createLink('feedback', 'admin', "browseType=$browseType&param=$param&orderBy=$orderBy");
        $this->feedback->buildSearchForm($actionURL);

        $this->view->userDeptPairs = $userDeptPairs;
        foreach($userDeptPairs as $user => $dept) $userDeptPairs[$user] = isset($deptPairs[$dept]) ? $deptPairs[$dept] : '';
        return $this->browse($browseType, $param, $orderBy, $recTotal, $recPerPage, $pageID, $from, $blockID);
    }

    /**
     * View feedback in admin.
     *
     * @param  int    $feedbackID
     * @access public
     * @return void
     */
    public function adminView($feedbackID, $browseType = '')
    {
        $feedback = $this->feedback->getById($feedbackID);
        if(empty($feedback)) return $this->feedbackZen->responseNotFound($this->browseURL);

        $products = $this->feedback->getGrantProducts(true, false, 'all');
        if(!isset($products[$feedback->product])) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->feedback->accessDenied, 'locate' => $this->browseURL)));

        $this->session->set('todoList', $this->app->getURI(true), 'feedback');

        $product = $feedback->product;
        if(!$this->app->user->admin && !in_array($product, $products)) $product = 'all';
        $this->session->set('feedbackProduct', $product, 'feedback');

        if(empty($browseType)) $browseType = $feedback->solution == 'touserstory' ? 'tostory' : $feedback->solution;

        return $this->view($feedbackID, $browseType);
    }

    /**
     * Products.
     *
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function products($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('product');

        $productSettingList = isset($this->config->global->productSettingList) ? json_decode($this->config->global->productSettingList, true) : array();
        if(empty($productSettingList))
        {
            $allProducts = $this->product->getPairs('noclosed', 0, '', 'all');
            $productSettingList = array_keys($allProducts);
        }

        $orderBy = 'program_asc';
        if($this->config->systemMode == 'light' and $orderBy == 'program_asc') $orderBy = 'line_desc,order_asc';

        $productStats = $this->product->getStats($productSettingList, $orderBy, null, 'story', 0);
        $feedbackView = $this->feedback->getFeedbackView(array_keys($productStats));

        if($productSettingList and !$this->app->user->admin)
        {
            foreach($productStats as $productID => $product)
            {
                if(!in_array($productID, $productSettingList)) unset($productStats[$productID]);
            }
        }

        $this->app->loadClass('pager', true);
        $recTotal     = count($productStats);
        $pager        = pager::init($recTotal, $recPerPage, $pageID);
        $productStats = array_slice($productStats, ($pageID - 1) * $pager->recPerPage, $pager->recPerPage, true);

        $productStructure = $this->product->statisticProgram($productStats);
        $productLines     = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('deleted')->eq(0)->orderBy('`order` asc')->fetchAll('id', false);
        $programLines     = array();

        foreach($productLines as $index => $productLine)
        {
            if(!isset($programLines[$productLine->root])) $programLines[$productLine->root] = array();
            $programLines[$productLine->root][$productLine->id] = $productLine->name;
        }

        $this->view->title = $this->lang->feedback->products;
        $this->view->users = $this->loadModel('user')->getHasFeedbackPriv(null, 'all');
        $this->view->pager = $pager;

        $this->view->productStats     = $productStats;
        $this->view->productStructure = $productStructure;
        $this->view->productLines     = $productLines;
        $this->view->programLines     = $programLines;
        $this->view->feedbackView     = $feedbackView;

        $this->display();
    }

    /**
     * Manage product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function manageProduct($productID)
    {
        $product = $this->loadModel('product')->getById($productID);
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $this->feedback->manageProduct($productID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if(isonlybody()) return print(js::closeModal('parent.parent', 'this'));
            return print(js::locate($this->createLink('feedback', 'products'), 'parent'));
        }

        $users = $this->loadModel('user')->getHasFeedbackPriv(null, 'all');
        $view  = $this->feedback->getFeedbackView($productID);
        if(isset($view[$productID])) $view = array_keys($view[$productID]);

        $this->view->users   = $users;
        $this->view->product = $product;
        $this->view->view    = $view;
        $this->display();
    }

    /**
     * Sync product module.
     *
     * @param  int    $productID
     * @param  string $module
     * @param  string $parent
     * @access public
     * @return void
     */
    public function syncProduct($productID = 0, $module = 'feedback', $parent = '')
    {
        $syncConfig      = json_decode($this->config->global->syncProduct, true);
        $feedbacks       = $this->dao->select('id')->from(TABLE_FEEDBACK)->where('product')->eq($productID)->fetchAll();
        $feedbackModules = $this->loadModel('tree')->getOptionMenu($productID, $module, 0, 0, 'nodeleted|noproduct');

        if($_POST)
        {
            $syncLevel = fixer::input('post')->get('syncLevel');
            $needMerge = fixer::input('post')->get('needMerge');
            $syncConfig[$module][$productID] = $syncLevel;

            $this->loadModel('setting')->setItem('system.common.global.syncProduct', json_encode($syncConfig));

            if($parent == 'onlybody') $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => 1, 'callback' => array('name' => 'closeParentModal')));

            if(($syncLevel and $needMerge == 'no') or (count($feedbackModules) == 1 and !$feedbacks)) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => 1, 'callback' => array('name' => 'jumpBrowse')));

            return $this->send(array('result' => 'success', 'locate' => inlink('mergeProductModule', "productID=$productID&syncLevel={$syncLevel}&module=$module")));
        }

        $browseLink = $this->createLink($module, 'browse', '', '', false);
        if($module == 'feedback') $browseLink = $this->createLink('feedback', 'admin', '', '', false);
        $browseLink = str_replace('onlybody=yes', '', str_replace('onlybody=yes&', '', $browseLink));

        $this->view->browseLink          = $browseLink;
        $this->view->feedbackCount       = count($feedbacks);
        $this->view->feedbackModuleCount = count($feedbackModules);
        $this->display();
    }

    /**
     * Merge product module.
     *
     * @param  int    $productID
     * @param  int    $syncLevel
     * @param  string $module
     * @access public
     * @return void
     */
    public function mergeProductModule($productID = 0, $syncLevel = 0, $module = 'feedback')
    {
        if(!common::hasPriv($module, 'syncProduct')) $this->loadModel('common')->deny($module, 'syncProduct');

        $this->app->loadLang('upgrade');
        $this->app->loadLang('tree');
        $table           = $this->config->objectTables[$module];
        $productModules  = $this->loadModel('tree')->getOptionMenu($productID, 'story', 0, 'all', 'nodeleted', $syncLevel);
        $feedbackModules = $this->loadModel('tree')->getOptionMenu($productID, $module, 0, 0, 'nodeleted|noproduct');

        if(empty($this->session->mergeList))
        {
            $this->session->set('mergeList', $feedbackModules);
            $this->session->set('mergeCount', count($this->session->mergeList));
        }
        $mergeList = $this->session->mergeList;

        if($_POST)
        {
            $mergeFrom = fixer::input('post')->get('mergeFrom');
            $mergeTo   = fixer::input('post')->get('mergeTo');

            $objects = $this->dao->select('*')->from($table)->where('module')->in($mergeFrom)->fetchAll('id', false);

            foreach($mergeFrom as $k => $from)
            {
                $to = $mergeTo[$k];

                /* Deleted old feedback module.*/
                $this->dao->update(TABLE_MODULE)->set('deleted')->eq('1')->where('type')->eq($module)->andWhere('id')->eq($from)->exec();

                /* Move feedbacks to new module.*/
                $this->dao->update($table)->set('module')->eq($to)->where('module')->eq($from)->exec();
                unset($mergeList[$from]);

                /* Add action for feedback.*/
                foreach($objects as $id => $oldObject)
                {
                    if($from != $oldObject->module) continue;
                    $actionID = $this->loadModel('action')->create($module, $id, 'SyncModule', '', $productModules[$to]);
                    $changes  = common::createChanges($oldObject, array('module' => $to));
                    if(!empty($changes)) $this->action->logHistory($actionID, $changes);
                }
            }

            $this->session->set('mergeList', $mergeList);
            if(empty($mergeList))
            {
                $browseLink = $this->createLink('feedback', 'admin', '', '', false);
                if($module == 'ticket') $browseLink = $this->createLink('ticket', 'browse', '', '', false);
                $browseLink = str_replace('onlybody=yes', '', str_replace('onlybody=yes&', '', $browseLink));
                return print(js::locate($browseLink, 'parent'));
            }
        }

        $this->view->product        = $this->loadModel('product')->getByID($productID);
        $this->view->mergeCount     = $this->session->mergeCount;
        $this->view->mergeList      = $this->session->mergeList;
        $this->view->recPerPage     = 50;
        $this->view->productModules = $productModules;
        $this->display();
    }

    /**
     * AJAX: Get product FM.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetProductFM($productID)
    {
        $product = $this->loadModel('product')->getByID($productID);
        $FM      = $product->feedback;
        return print($FM);
    }

    /**
     * Get status by ajax.
     *
     * @param  string $methodName
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetStatus($methodName, $status = '')
    {
        $needReview = !$this->feedback->forceNotReview();

        $status = $this->feedback->getStatus($methodName, $needReview, $status);

        return print($status);
    }

    /**
     * Get projects by productId.
     *
     * @param  int    $productID
     * @param  string $field
     * @param  string $onchange
     * @param  int    $getHTML
     * @access public
     * @return void
     */
    public function ajaxGetProjects($productID = 0, $field = 'bugProjects', $onchange = '', $getHTML = 0)
    {
        $branches = array();
        $branches = array_keys($this->loadModel('branch')->getPairs($productID, 'active'));
        if($onchange) $onchange .= '()';
        $projects = $this->loadModel('product')->getProjectPairsByProduct($productID, implode(',', $branches));
        if($field == 'taskProjects') $onchange = 'getExecutions(this.value)';
        if($getHTML) return print(html::select($field, arrayUnion(array('' => ''), $projects), '', "class='form-control chosen' onchange='$onchange'"));

        $items = array();
        if(!empty($projects))
        {
            foreach($projects as $projectID => $projectName) $items[] = array('text' => $projectName, 'value' => $projectID);
        }

        return print(json_encode(array('multiple' => false, 'defaultValue' => '', 'name' => "taskProjects", 'items' => $items)));
    }

    /**
     * Get executions by executions.
     *
     * @param  int    $projectID
     * @param  int    $getHTML
     * @access public
     * @return void
     */
    public function ajaxGetExecutions($projectID = 0, $getHTML = 0)
    {
        $executions = empty($projectID) ? array() : $this->loadModel('execution')->getPairs($projectID, 'all', 'leaf|order_asc|noclosed');

        if($getHTML) return print(html::select('executions', empty($projectID) ? array(0 => '') : $executions, '', "class='form-control chosen' onchange=changeTaskButton()"));

        $items        = array();
        $defaultValue = '';
        if(!empty($executions))
        {
            foreach($executions as $executionID => $executionName) $items[] = array('text' => $executionName, 'value' => $executionID);
            $defaultValue = key($executions);
        }

        return print(json_encode(array('multiple' => false, 'defaultValue' => $defaultValue, 'name' => "executions", 'items' => $items)));
    }

	/**
	 * Ajax get execution lang.
	 *
	 * @param  int    $projectID
	 * @access public
	 * @return string
	 */
	public function ajaxGetExecutionLang($projectID = 0)
	{
        $this->app->loadLang('execution');
        $project = $this->loadModel('project')->getByID($projectID);

        if($project->model == 'kanban')
        {
            $this->lang->feedback->execution = str_replace($this->lang->execution->common, $this->lang->project->kanban, $this->lang->feedback->execution);
        }

        return print($this->lang->feedback->execution);
	}

    /**
     * Export feedback.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($browseType, $orderBy)
    {
        if($_POST)
        {
            $this->loadModel('file');
            $feedbackLang = $this->lang->feedback;

            /* Create field lists. */
            $sort   = common::appendOrder($orderBy);
            $fields = explode(',', $this->config->feedback->exportFields);
            if(!empty($this->app->user->feedback) or !empty($_COOKIE['feedbackView'])) $fields = explode(',', $this->config->feedback->frontFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($feedbackLang->$fieldName) ? $feedbackLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get feedbacks. */
            $feedbackQueryCondition = preg_replace('/SELECT.*WHERE/i', '', $this->session->feedbackQueryCondition);
            $feedbackQueryCondition = str_replace('`closedDate`', 't1.`closedDate`', $feedbackQueryCondition);
            $sort = preg_replace('/id/i', 't1.id', $sort);
            $feedbacks = $this->dao->select('t1.*')
                ->from(TABLE_FEEDBACK)->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
                ->where($feedbackQueryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($this->cookie->checkedItem)->fi()
                ->orderBy($sort)->fetchAll('id', false);

            $sql = $this->dao->get();
            $this->session->set('feedbackTransferCondition', $sql);

            $productIdList = $bugIdList = $storyIdList = $todoIdList = $taskIdList = $ticketIdList = array();
            foreach($feedbacks as $feedback)
            {
                $productIdList[$feedback->product] = $feedback->product;
                if($feedback->solution == 'tobug')       $bugIdList[$feedback->result]    = $feedback->result;
                if($feedback->solution == 'tostory')     $storyIdList[$feedback->result]  = $feedback->result;
                if($feedback->solution == 'touserstory') $storyIdList[$feedback->result]  = $feedback->result;
                if($feedback->solution == 'totodo')      $todoIdList[$feedback->result]   = $feedback->result;
                if($feedback->solution == 'totask')      $taskIdList[$feedback->result]   = $feedback->result;
                if($feedback->solution == 'toticket')    $ticketIdList[$feedback->result] = $feedback->result;
            }

            /* Get users and projects. */
            $users    = $this->loadModel('user')->getPairs('noletter');
            $modules  = $this->dao->select('id,name')->from(TABLE_MODULE)->where('root')->in($productIdList)->andWhere('type')->in('feedback,story')->fetchPairs('id', 'name');
            $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchPairs('id', 'name');
            $bugs     = $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->in($bugIdList)->fetchPairs('id', 'title');
            $todos    = $this->dao->select('id,name')->from(TABLE_TODO)->where('id')->in($todoIdList)->fetchPairs('id', 'name');
            $tasks    = $this->dao->select('id,name')->from(TABLE_TASK)->where('id')->in($taskIdList)->fetchPairs('id', 'name');
            $stories  = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchPairs('id', 'title');
            $tickets  = $this->dao->select('id,title')->from(TABLE_TICKET)->where('id')->in($ticketIdList)->fetchPairs('id', 'title');

            foreach($feedbacks as $feedback)
            {
                $title = '';
                if($feedback->solution == 'tobug')       $title = $bugs[$feedback->result];
                if($feedback->solution == 'tostory')     $title = $stories[$feedback->result];
                if($feedback->solution == 'touserstory') $title = $stories[$feedback->result];
                if($feedback->solution == 'totodo')      $title = $todos[$feedback->result];
                if($feedback->solution == 'totask')      $title = $tasks[$feedback->result];
                if($feedback->solution == 'toticket')    $title = $tickets[$feedback->result];

                if($feedback->mailto)
                {
                    $mailtos   = explode(',', $feedback->mailto);
                    $realnames = array();
                    foreach($mailtos as $mailto) $realnames[] = zget($users, $mailto);
                    $feedback->mailto = trim(join(',', $realnames), ',');
                }

                $feedback->product       = zget($products, $feedback->product, '') . "(#$feedback->product)";
                $feedback->module        = zget($modules, $feedback->module, '') . "(#$feedback->module)";
                $feedback->status        = $this->processStatus('feedback', $feedback);
                $feedback->type          = zget($this->lang->feedback->typeList, $feedback->type, '');
                $feedback->solution      = zget($this->lang->feedback->solutionList, $feedback->solution, '');
                $feedback->openedBy      = zget($users, $feedback->openedBy);
                $feedback->assignedTo    = zget($users, $feedback->assignedTo);
                $feedback->processedBy   = zget($users, $feedback->processedBy);
                $feedback->closedBy      = zget($users, $feedback->closedBy);
                $feedback->editedBy      = zget($users, $feedback->editedBy);
                $feedback->openedDate    = helper::isZeroDate($feedback->openedDate) ? '' : $feedback->openedDate;
                $feedback->assignedDate  = helper::isZeroDate($feedback->assignedDate) ? '' : $feedback->assignedDate;
                $feedback->processedDate = helper::isZeroDate($feedback->processedDate) ? '' : $feedback->processedDate;
                $feedback->closedDate    = helper::isZeroDate($feedback->closedDate) ? '' : $feedback->closedDate;
                $feedback->closedReason  = zget($feedbackLang->closedReasonList, $feedback->closedReason);
                $feedback->editedDate    = helper::isZeroDate($feedback->editedDate) ? '' : $feedback->editedDate;
                $feedback->title         = "\t" . $feedback->title . "\t";
                $feedback->desc          = "\t" . $feedback->desc . "\t";
                $feedback->source        = "\t" . $feedback->source . "\t";

                if($title) $feedback->solution .= "#{$feedback->result} $title";
            }

            if($this->post->fileType == 'csv')
            {
                $feedback->desc = htmlspecialchars_decode($feedback->desc);
                $feedback->desc = str_replace("<br />", "\n", $feedback->desc);
                $feedback->desc = str_replace('"', '""', $feedback->desc);
            }
            if($this->config->edition != 'open') list($fields, $feedbacks) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $feedbacks);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $feedbacks);
            $this->post->set('kind', 'feedback');

            $width['openedDate']    = 20;
            $width['assignedDate']  = 20;
            $width['processedDate'] = 20;
            $width['closedDate']    = 20;
            $width['editedDate']    = 20;
            $this->post->set('width', $width);

            $this->post->set('exportFields', explode(',', $this->config->feedback->exportFields));

            $this->feedback->setListValue();
            $this->loadModel('transfer')->export('feedback');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName = zget($this->lang->feedback->featureBar['admin'], $browseType, '');
        if(empty($fileName)) $fileName = zget($this->lang->feedback->statusList, $browseType, '');
        if(empty($fileName) and isset($this->lang->feedback->$browseType)) $fileName = $this->lang->feedback->$browseType;
        if($fileName) $fileName = $this->lang->feedback->common . $this->lang->dash . $fileName;

        $this->view->fileName = $fileName;
        $this->display();
    }

    /**
     * Get feedback module
     *
     * @param  int    $projectID
     * @param  bool   $isChosen
     * @param  int    $number
     * @param  int    $moduleID
     * @param  int    $returnItems
     * @access public
     * @return string
     */
    public function ajaxGetModule($productID, $isChosen = true, $number = 0, $moduleID = 0, $returnItems = 0)
    {
        $module = $this->loadModel('tree')->getOptionMenu($productID, 'feedback', 0, 'all');
        if(!empty($returnItems))
        {
            $moduleItems = array();
            foreach($module as $moduleID => $moduleName) $moduleItems[] = array('text' => $moduleName, 'value' => $moduleID);
            return print(json_encode($moduleItems));
        }
        $chosen = $isChosen ? 'chosen' : '';
        $number = !empty($number) ? $number : '';
        $name   = $number ? "module[$number]" : 'module';
        $select =  html::select($name, empty($module) ? array('' => '') : $module, $moduleID, "class='form-control {$chosen}'");
        die($select);
    }

    /**
     * Drop menu page.
     *
     * @param  int    $productID
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @param  string $from
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($productID, $module, $method, $extra = '')
    {
        $this->loadModel('product');
        $programProducts = array();

        $products = $this->loadModel('feedback')->getGrantProducts(false, false, 'all');

        $programProducts = array();
        foreach($products as $product) $programProducts[$product->program][] = $product;

        $this->view->link      = $this->product->getProductLink($module, $method, $extra);
        $this->view->productID = $productID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;
        $this->view->products  = $programProducts;
        $this->view->projectID = 0;
        $this->view->programs  = $this->loadModel('program')->getPairs(true);
        $this->view->lines     = $this->product->getLinePairs();

        $this->display();
    }

    /**
     * Product setting
     *
     * @access public
     * @return void
     */
    public function productSetting()
    {
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $data = fixer::input('post')->get();

            $productList = !empty($data->products) ? array_values($data->products) : array();

            if(empty($productList[0])) return $this->sendError($this->lang->feedback->productSettingSaveError);

            $this->loadModel('setting')->setItem('system.common.global.productSettingList', json_encode($productList));

            $this->feedback->productSetting();

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $feedbackProducts   = $this->feedback->getFeedbackProducts(NULL, false);
        $productSettingList = isset($this->config->global->productSettingList) ? json_decode($this->config->global->productSettingList, true) : array();

        $productPairs = $productHeadMap = array();
        foreach($feedbackProducts as $productID => $product)
        {
            if(!empty($productSettingList) and !in_array($productID, $productSettingList)) continue;
            $productPairs[$productID]   = $product->name;
            $productHeadMap[$productID] = array('feedback' => $product->feedback, 'ticket' => $product->ticket);
        }

        /* 使用键名比较计算数组的交集 */
        $intersectKey = array_intersect_key(array_flip($productSettingList), $productPairs);
        /* 使用后面数组的值替换第一个数组的值 */
        $productPairs = array_replace($intersectKey, $productPairs);

        $this->view->productPairs   = $productPairs;
        $this->view->productHeadMap = $productHeadMap;
        $this->view->products       = arrayUnion(array('' => ''), $this->loadModel('product')->getPairs('noclosed', 0, '', 'all'));
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter');

        $this->display();
    }
}

<?php
/**
 * The control file of ticket of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yue Ma, Zongjun Lan, Xin Zhou
 * @package     ticket
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class ticket extends control
{

    /**
     * Construct function, load model
     *
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('action');
    }

    /**
     * Common actions of ticket module.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function commonAction($ticketID)
    {
        $this->view->ticket  = $this->ticket->getByID($ticketID);
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions = $this->action->getList('ticket', $ticketID);
    }

    /**
     * Browse ticket.
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
    public function browse($browseType = 'wait', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1, $from = 'ticket', $blockID = 0)
    {
        $this->loadModel('feedback');
        $this->loadModel('tree');
        $this->app->loadLang('doc');

        $param     = (int)$param;
        $productID = $param;
        $objectID  = $param == 'all' ? 0 : $param;

        $products = $this->feedback->getGrantProducts(true, false, 'all');
        if($from == 'doc' && empty($products)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->tips->noProduct));

        if(!$this->session->ticketProduct)                                               $this->session->set('ticketProduct', 'all');
        if($browseType == 'bysearch')                                                    $productID = $this->session->ticketProduct;
        if($browseType == 'byModule' and $param)                                         $productID = $this->loadModel('tree')->getByID($param)->root;
        if($browseType == 'byProduct' && !$param)                                        $productID = 'all';
        if($this->session->ticketProduct and !$productID and $browseType != 'byProduct') $productID = $this->session->ticketProduct;
        if(!$objectID) $objectID = $productID == 'all' ? 0 : $productID;
        if($from == 'doc' && !$productID) $productID = key($products);

        $this->feedback->setMenu($productID, 'ticket');
        if(!in_array($browseType, array('byProduct', 'byModule', 'bysearch'))) $this->session->set('browseType', '', 'feedback');
        if(in_array($browseType, array('byProduct', 'byModule', 'bysearch')))
        {
            $this->session->set('browseType', $browseType, 'feedback');
            $this->session->set('ticketObjectID', $objectID, 'feedback');
        }
        elseif(!$this->session->objectID || $this->session->objectID != $objectID)
        {
            $this->session->set('browseType', 'byProduct', 'feedback');
            $this->session->set('ticketObjectID', $objectID, 'feedback');
        }

        if(!in_array($browseType, array('byModule', 'byProduct'))) $this->session->set('ticketBrowseType', $browseType, 'feedback');
        if(!in_array($browseType, array('byModule', 'byProduct')) && $this->session->ticketBrowseType == 'bysearch') $this->session->set('ticketBrowseType', 'wait', 'feedback');

        $queryID = $browseType == 'bysearch' ? $param : 0;
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $moduleName = $this->lang->feedback->allModule;
        $moduleID   = $this->session->browseType == 'byModule' && $this->session->ticketObjectID ? $this->session->ticketObjectID : 0;

        if($this->session->browseType == 'byModule'  and $moduleID and $this->session->ticketProduct != 'all') $moduleName = $this->loadModel('tree')->getById($moduleID)->name;
        if($this->session->browseType == 'byProduct' and $moduleID and $this->session->ticketProduct != 'all') $moduleName = $this->loadModel('product')->getById($moduleID)->name;

        if($browseType != 'bysearch')
        {
            if($this->session->ticketBrowseType) $browseType = $this->session->ticketBrowseType;
            $tickets = $this->ticket->getList($browseType, $orderBy, $pager, $moduleID);
        }
        else
        {
            $tickets = $this->ticket->getBySearch($queryID, $orderBy, $pager, $productID);
        }
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'ticket', false);

        /* Processing tickets consumed hours. */
        $allConsumed = $this->ticket->getConsumedByTicket(array_keys($tickets));
        $ticketRelatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($tickets), 'ticket', 'byRelation', true);
        foreach($tickets as $ticket)
        {
            $ticket->consumed      = isset($allConsumed[$ticket->id]) ? round($allConsumed[$ticket->id], 2) : 0;
            $ticket->feedbackTip   = $ticket->feedback != 0 ? '#' . $ticket->feedback : '';
            $ticket->relatedObject = zget($ticketRelatedObjectList, $ticket->id, 0);
        }

        $actionURL = $this->createLink('ticket', 'browse', "browseType=bysearch&param=myQueryID&orderBy=$orderBy&recTotal=0&recPerPage=$recPerPage&pageID=$pageID&from=$from&blockID=$blockID");
        $this->ticket->buildSearchForm($queryID, $actionURL, $productID);

        $this->session->set('ticketList', $this->app->getURI(true), 'feedback');

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

        $showModule = !empty($this->config->ticket->browse->showModule) ? $this->config->ticket->browse->showModule : '';

        $this->view->title       = $this->lang->ticket->browse;
        $this->view->products    = $products;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->tickets     = $tickets;
        $this->view->feedbacks   = arrayUnion(array(0 => ''), $this->feedback->getPairs());
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->browseType  = $browseType;
        $this->view->moduleName  = $moduleName;
        $this->view->moduleTree  = $this->tree->getTicketTreeMenu(array('treeModel', 'createTicketLink'));
        $this->view->moduleID    = $moduleID;
        $this->view->productID   = $productID;
        $this->view->product     = $this->loadModel('product')->getByID((int)$productID);
        $this->view->param       = $param;
        $this->view->builds      = $this->loadModel('build')->getBuildPairs((int)$productID);
        $this->view->modulePairs = $showModule ? $this->tree->getModulePairs(0, 'ticket', $showModule) : array();

        $this->display();
    }

    /**
     * Create a ticket.
     *
     * @param  string $extras
     * @access public
     * @return void
     */
    public function create($productID = 0, $extras = '')
    {
        $this->loadModel('feedback');
        $modules = $this->loadModel('tree')->getOptionMenu((int)$productID, 'ticket', 0, 'all');

        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);
        $fromType = isset($output['fromType']) ? $output['fromType'] : '';
        $fromID   = isset($output['fromID']) ? $output['fromID'] : '';

        if($_POST)
        {
            $ticketID = $this->ticket->create($extras);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $files = $this->loadModel('file')->saveUpload('ticket', $ticketID, 'create');

            if(empty($fromType)) $actionID = $this->action->create('ticket', $ticketID, 'Opened');
            $this->executeHooks($ticketID);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $ticketID));

            if($fromType == 'feedback' && isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            $browseLink = $fromType == 'feedback' ? $this->createLink('feedback', 'adminView', "feedbackID=$fromID") : $this->createLink('ticket', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $browseLink));
        }

        $products = $this->feedback->getGrantProducts(false);
        $productPairs = $productTicket = array();
        foreach($products as $product)
        {
            if(empty($product)) continue;
            $productPairs[$product->id]  = $product->name;
            $productTicket[$product->id] = $product->ticket;
        }

        $ticketTitle = '';
        $desc        = '';
        $customer    = '';
        $contact     = '';
        $email       = '';
        $moduleID    = '';
        $pri         = 3;
        if($fromType == 'feedback')
        {
            $feedback    = $this->feedback->getByID($fromID);
            $productID   = $feedback->product;
            $ticketTitle = $feedback->title;
            $desc        = $feedback->desc;
            $modules     = $this->loadModel('tree')->getOptionMenu($productID, 'ticket', 0, 'all');
            $customer    = $feedback->source;
            $contact     = $feedback->feedbackBy;
            $email       = $feedback->notifyEmail;
            $moduleID    = $feedback->module;
            $pri         = $feedback->pri;
            $this->view->sourceFiles = $feedback->files;
        }

        $this->feedback->setMenu($productID, 'ticket');

        $this->view->title               = $this->lang->ticket->create;
        $this->view->defaultType         = 'code';
        $this->view->products            = $productPairs;
        $this->view->productTicket       = $productTicket;
        $this->view->users               = $this->loadModel('user')->getPairs('noclosed');
        $this->view->pri                 = $pri;
        $this->view->productID           = $productID;
        $this->view->product             = $this->loadModel('product')->getByID((int)$productID);
        $this->view->ticketTitle         = $ticketTitle;
        $this->view->desc                = $desc;
        $this->view->modules             = $modules ? $modules : array('' => '/');
        $this->view->customer            = $customer;
        $this->view->contact             = $contact;
        $this->view->email               = $email;
        $this->view->fromType            = $fromType;
        $this->view->fromID              = $fromID;
        $this->view->builds              = $this->loadModel('build')->getBuildPairs((int)$productID, 'all', 'noreleased');
        $this->view->moduleID            = $moduleID;
        $this->view->notifyEmailRequired = strpos($this->config->ticket->create->requiredFields, 'notifyEmail') !== false ? true : false;

        $this->display();
    }

    /**
     * Edit a ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function edit($ticketID)
    {
        $ticket = $this->ticket->getByID($ticketID);

        $products = $this->loadModel('feedback')->getGrantProducts(true, false, 'all');
        if(!isset($products[$ticket->product])) return print(js::error($this->lang->ticket->accessDenied) . js::locate('back'));

        $ticketSources = $this->ticket->getSourceByTicket($ticketID);
        if(empty($ticketSources))
        {
            $source = new stdclass();
            $source->customer    = '';
            $source->contact     = '';
            $source->notifyEmail = '';
            $ticketSources = array(0 => $source);
        }

        $this->loadModel('feedback')->setMenu($ticket->product, 'ticket');


        if($ticket->status == 'done') $this->config->ticket->edit->requiredFields = 'product,title,resolvedBy,resolution';
        if($_POST)
        {
            $changes = $this->ticket->update($ticketID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($ticketID);

            $createFiles = $this->loadModel('file')->saveUpload('ticket', $ticketID, 'create', 'createFiles', 'createLabels');
            $finishFiles = $this->loadModel('file')->saveUpload('ticket', $ticketID, 'finished', 'finishFiles', 'finishLabels');
            $fileAction  = !empty($createFiles) ? $this->lang->addFiles . join(',', $createFiles) . "\n" : '';
            $fileAction .= !empty($finishFiles) ? $this->lang->addFiles . join(',', $finishFiles) . "\n" : '';
            $actionID = $this->loadModel('action')->create('ticket', $ticketID, 'Edited', $fileAction);
            $this->action->logHistory($actionID, $changes);

            $browseLink = $this->session->ticketList ? $this->session->ticketList : $this->createLink('ticket', 'browse');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $browseLink));
        }

        if(!empty($ticket->feedback)) $feedback = $this->feedback->getById($ticket->feedback);
        $this->extendRequireFields($ticketID);

        $this->view->title         = $this->lang->ticket->edit;
        $this->view->products      = $products;
        $this->view->ticketSources = array_values($ticketSources);
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->modules       = $this->loadModel('tree')->getOptionMenu($ticket->product, 'ticket', 0, 'all');
        $this->view->actions       = $this->loadModel('action')->getList('ticket', $ticketID);
        $this->view->builds        = $this->loadModel('build')->getBuildPairs($ticket->product, 'all', 'noreleased', 0, 'execution', $ticket->openedBuild);
        $this->view->ticket        = $ticket;
        $this->view->feedback      = empty($feedback) ? '' : $feedback;

        $this->view->notifyEmailRequired = strpos($this->config->ticket->edit->requiredFields, 'notifyEmail') !== false ? true : false;
        $this->display();
    }

    /**
     * View a ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function view($ticketID)
    {
        $ticket    = $this->ticket->getByID($ticketID);
        $browseURL = $this->session->ticketList ? $this->session->ticketList : $this->createLink('ticket', 'browse');
        if(empty($ticket)) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => inlink('browse'))));

        $products = $this->loadModel('feedback')->getGrantProducts(true, false, 'all');
        if(!isset($products[$ticket->product])) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->ticket->accessDenied, 'locate' => $browseURL)));

        $this->loadModel('feedback')->setMenu($ticket->product, 'ticket');

        if(!empty($ticket->feedback)) $feedback = $this->loadModel('feedback')->getById($ticket->feedback);

        $this->view->title          = $this->lang->ticket->view;
        $this->view->products       = $products;
        $this->view->ticketSources  = $this->ticket->getSourceByTicket($ticketID);
        $this->view->actions        = $this->loadModel('action')->getList('ticket', $ticketID);
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->builds         = $this->loadModel('build')->getBuildPairs($ticket->product);
        $this->view->ticket         = $ticket;
        $this->view->preAndNext     = $this->loadModel('common')->getPreAndNextObject('ticket', $ticketID);
        $this->view->feedback       = empty($feedback) ? '' : $feedback;
        $this->view->ticket         = $ticket;
        $this->view->product        = $this->loadModel('product')->getByID($ticket->product);
        $this->view->modulePath     = $this->loadModel('tree')->getParents($ticket->module);
        $this->view->relatedObjects = $this->loadModel('custom')->getRelatedObjectList($ticket->id, 'ticket', 'byObject');
        $this->display();
    }

    /**
     * Start a ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function start($ticketID)
    {
        $this->extendRequireFields($ticketID);
        if(!empty($_POST))
        {
            $changes = $this->ticket->start($ticketID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($changes)
            {
                $actionID = $this->action->create('ticket', $ticketID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($ticketID);

            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate($this->createLink('ticket', 'view', "ticketID=$ticketID"), 'parent'));
        }

        $this->commonAction($ticketID);

        $this->view->assignedTo = $this->app->user->account;
        $this->view->actions    = $this->loadModel('action')->getList('ticket', $ticketID);
        $this->display();
    }

    /**
     * Update assign of ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function assignTo($ticketID)
    {
        $this->extendRequireFields($ticketID);
        if(!empty($_POST))
        {
            $changes = $this->ticket->assign($ticketID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($changes)
            {
                $actionID = $this->action->create('ticket', $ticketID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($ticketID);

            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate($this->createLink('ticket', 'view', "ticketID=$ticketID"), 'parent'));
        }

        $this->commonAction($ticketID);

        $this->display();
    }

    /**
     * Finish a ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function finish($ticketID)
    {
        $this->extendRequireFields($ticketID);
        if($_POST)
        {
            $this->ticket->finish($ticketID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->executeHooks($ticketID);

            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate($this->createLink('ticket', 'view', "ticketID=$ticketID"), 'parent'));
        }

        $this->commonAction($ticketID);

        $this->view->finishedBy = $this->app->user->account;
        $this->display();
    }

    /**
     * Delete ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function delete($ticketID)
    {
        $this->ticket->delete(TABLE_TICKET, $ticketID);
        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * Close ticket.
     *
     * @param  int    $ticketID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function close($ticketID, $confirm = 'no')
    {
        $this->extendRequireFields($ticketID);
        if($_POST or $confirm == 'yes')
        {
            $this->ticket->close($ticketID, $confirm);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->executeHooks($ticketID);

            if(isInModal()) return $this->sendSuccess(array('load' => true, 'closeModal' => true));
            if($confirm == 'yes') return $this->sendSuccess(array('load' => true));
            return $this->sendSuccess(array('load' => $this->createLink('ticket', 'view', "ticketID=$ticketID"), 'closeModal' => true));
        }

        $ticket = $this->ticket->getByID($ticketID);
        if($ticket->status == 'done') return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->ticket->confirmClose, 'confirmed' => inlink('close', "ticketID=$ticketID&confirm=yes"))));

        $ticketList = array('' => '');
        $tickets = $this->ticket->getList('all');

        if($tickets)
        {
            foreach($tickets as $key => $ticket) $ticketList[$ticket->id] = "#$ticket->id " . $ticket->title;
            if(!empty($ticketList[$ticketID])) unset($ticketList[$ticketID]);
        }

        $this->view->ticket  = $this->ticket->getByID($ticketID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->actions = $this->loadModel('action')->getList('ticket', $ticketID);
        $this->view->tickets = $ticketList;
        $this->display();
    }

    /**
     * Activate ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function activate($ticketID)
    {
        $this->extendRequireFields($ticketID);
        if(!empty($_POST))
        {
            $this->ticket->activate($ticketID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->executeHooks($ticketID);

            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate($this->createLink('ticket', 'view', "ticketID=$ticketID"), 'parent'));
        }

        $this->commonAction($ticketID);
        /* 默认指派给最后解决人，最后解决者被删除置空 */
        $finishedAccount = !empty($this->view->ticket->resolvedBy) ? $this->loadModel('user')->getById($this->view->ticket->resolvedBy) : '';
        $finishedBy = (!empty($finishedAccount) and empty($finishedAccount->deleted)) ? $finishedAccount->account : '';
        $this->view->assignedTo = $finishedBy;

        $this->display();
    }

    /**
     * Create story by ticket;
     *
     * @param  int    $productID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function createStory($productID, $extra)
    {
        $objectID = 0;
        $product = $this->loadModel('product')->fetchByID($productID);
        if(!empty($product->shadow))
        {
            $project  = $this->loadModel('project')->getByShadowProduct($productID);
            $objectID = $project->id;
            if(empty($project->multiple)) $objectID = $this->loadModel('execution')->getNoMultipleID($project->id);
        }
        echo $this->fetch('story', 'create', "product=$productID&branch=0&moduleID=0&storyID=0&executionID=$objectID&bugID=0&planID=0&todoID=0&extra=$extra&type=story");
    }

    /**
     * Create bug by ticket;
     *
     * @param  int    $product
     * @param  string $extra
     * @access public
     * @return void
     */
    public function createBug($product, $extra)
    {
        $extras = str_replace(array(',', ' ', '*'), array('&', '', '-'), $extra);
        parse_str($extras, $params);

        $branch = isset($params['branch']) ? $params['branch'] : 0;

        echo $this->fetch('bug', 'create', "product=$product&branch=$branch&extras=$extra");
    }

    /**
     * Get ticket module
     *
     * @param  int    $projectID
     * @param  int    $isChosen
     * @param  int    $number
     * @param  int    $moduleID
     * @param  string $field
     * @access public
     * @return string
     */
    public function ajaxGetModule($productID, $isChosen = 1, $number = 0, $moduleID = 0, $field = '')
    {
        $module = $this->loadModel('tree')->getOptionMenu($productID, 'ticket', 0, 'all');
        $chosen = $isChosen ? 'chosen' : '';
        $number = !empty($number) ? $number : '';
        if(!empty($field))
        {
            $name = $field . "[" . $number . "]";
        }
        else
        {
            $name = $number ? "modules[$number]" : 'module';
        }
        $select =  html::select($name, empty($module) ? array('' => '') : $module, $moduleID, "class='form-control {$chosen}'");
        die($select);
    }

    /**
     * 批量创建工单。
     * Batch create tickets.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchCreate($productID, $moduleID = 0)
    {
        $this->extendRequireFields();
        if($_POST)
        {
            $tickets = form::batchData($this->config->ticket->form->batchCreate)->get();
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->ticket->batchCreate($tickets, $productID);
            if(dao::isError()) return $this->sendError(dao::getError());

            /* Return feedback id when call the API. */
            if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $feedbackIdList));

            $browseLink = $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
            return $this->sendSuccess(array('load' => $browseLink));
        }
        foreach(explode(',', $this->config->ticket->list->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->ticket->$field;
        $showFields = trim($this->config->ticket->custom->batchCreateFields, ',');

        $product = $this->loadModel('product')->getById($productID);

        $this->view->title        = $this->lang->ticket->batchCreate;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->modules      = $this->loadModel('feedback')->getModuleList('ticket', false, 'yes', array($productID => $product->name));
        $this->view->productID    = $productID;
        $this->view->moduleID     = $moduleID;
        $this->view->builds       = $this->loadModel('build')->getBuildPairs((int)$productID, 'all', 'noreleased');
        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;
        $this->view->tickets      = $this->ticketZen->getDataFromUploadImages($productID);

        $this->display();
    }

    /**
     * Batch edit ticket.
     *
     * @access public
     * @return void
     */
    public function batchEdit()
    {
        $this->extendRequireFields();
        if(isset($_POST['ticketIDList']))
        {
            $tickets = $this->ticket->getByList($_POST['ticketIDList']);
            $closedTickets = array();
            foreach($tickets as $ticketID => $ticket)
            {
                if($ticket->status == 'closed')
                {
                    $closedTickets[] = $ticket->id;
                    unset($tickets[$ticketID]);
                }

            }
            if($tickets == array())
            {
                $browseLink = $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
                $message    = sprintf($this->lang->ticket->batchEditTip, implode(',', $closedTickets));
                return $this->send(array('result' => 'fail', 'load' => array('alert' => $message, 'locate' => $browseLink)));
            }

            $this->view->title        = $this->lang->ticket->edit;
            $this->view->users        = $this->loadModel('user')->getPairs('noletter|noclosed');
            $this->view->modules      = $this->loadModel('feedback')->getModuleList('ticket');
            $this->view->tickets      = $tickets;
            $this->view->batchEditTip = !empty($closedTickets) ? sprintf($this->lang->ticket->batchEditTip, implode(',', $closedTickets)) : '';
            $this->view->products     = $this->feedback->getGrantProducts(true, true, 'all');

            $this->display();
        }
        elseif($_POST)
        {
            $allChanges = $this->ticket->batchUpdate();
            if(dao::isError()) return $this->sendError(dao::getError());
            $this->loadModel('action');
            if(!empty($allChanges))
            {
                foreach($allChanges as $ticketID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->action->create('ticket', $ticketID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }

            }

            $this->executeHooks(key($this->post->id));

            $browseLink = $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
            return $this->sendSuccess(array('load' => $browseLink));
        }
        else
        {
            $browseLink = $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
            $this->locate($browseLink);
        }
    }

    /**
     * 批量指派。
     * Batch assignTo.
     *
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function batchAssignTo($assignedTo = '')
    {
        $allChanges = $this->ticket->batchAssign($assignedTo);

        $this->loadModel('action');
        if(!empty($allChanges))
        {
            foreach($allChanges as $ticketID => $changes)
            {
                if(empty($changes)) continue;
                $actionID = $this->action->create('ticket', $ticketID, 'Assigned', '', $assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
        }
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Batch finish ticket.
     *
     * @access public
     * @return void
     */
    public function batchFinish()
    {
        if(isset($_POST['ticketIDList']))
        {
            $ticketIDList = $this->ticket->getByList($_POST['ticketIDList']);
            $notFinishTicket = array();
            foreach($ticketIDList as $ticketID => $ticket)
            {
                if($ticket->status == 'done' or $ticket->status == 'closed')
                {
                    $notFinishTicket[] = $ticket->id;
                    unset($ticketIDList[$ticketID]);
                }
            }

            if($ticketIDList == array())
            {
                $browseLink = $this->session->ticketList ? $this->session->ticketList : $this->createlink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
                $message    = sprintf($this->lang->ticket->batchFinishTip, implode(',', $notFinishTicket));
                return $this->send(array('result' => 'fail', 'load' => array('alert' => $message, 'locate' => $browseLink)));
            }

            $this->view->tickets         = $ticketIDList;
            $this->view->batchFinishTip  = !empty($notFinishTicket) ? sprintf($this->lang->ticket->batchFinishTip, implode(',', $notFinishTicket)) : '';
            $this->view->title           = $this->lang->ticket->batchFinish;

            $this->display();
        }
        elseif($_POST)
        {
            $allChanges = $this->ticket->batchFinish();
            if(dao::isError()) return $this->sendError(dao::getError());
            if(!empty($allChanges))
            {
                foreach($allChanges as $ticketID => $changes)
                {
                    $actionID = $this->loadModel('action')->create('ticket', $ticketID, 'Finished', zget($this->post->comment, $ticketID, ''));
                    if(!empty($changes)) $this->action->logHistory($actionID, $changes);
                }
            }
            $browseLink = $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
            return $this->sendSuccess(array('load' => $browseLink));
        }
        else
        {
            $browseLink = $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
            $this->locate($browseLink);
        }
    }

    /**
     * Batch close ticket.
     *
     * @access public
     * @return void
     */
    public function batchClose()
    {
        if(isset($_POST['ticketIDList']))
        {
            $doneTickets   = array();
            $closedTickets = '';
            $tickets       = $this->ticket->getByList($_POST['ticketIDList']);
            foreach($tickets as $ticketID => $ticket)
            {
                if($ticket->status == 'closed')
                {
                    $closedTickets .= "#{$ticket->id}, ";
                    unset($tickets[$ticketID]);
                    continue;
                }

                if($ticket->status == 'done')
                {
                    $doneTickets[] = $ticket->id;
                    $ticket->closedReason = 'commented';
                }
                $ticket->statusText = zget($this->lang->ticket->statusList, $ticket->status);
                $ticket->resolution = '';
            }

            if(empty($tickets))
            {
                $browseLink = $this->session->ticketList ? $this->session->ticketList : $this->createlink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
                $message    = sprintf($this->lang->ticket->batchClosedTip, trim($closedTickets, ', '));
                return $this->send(array('result' => 'fail', 'load' => array('alert' => $message, 'locate' => $browseLink)));
            }

            $this->view->title          = $this->lang->ticket->batchClose;
            $this->view->tickets        = $tickets;
            $this->view->batchCloseTip  = !empty($closedTickets) ? sprintf($this->lang->ticket->batchClosedTip, trim($closedTickets, ', ')) : '';
            $this->view->showResolution = count($doneTickets) != count($tickets);

            $this->display();
        }
        elseif($_POST)
        {
            $ticketData = form::batchData($this->config->ticket->form->batchClose)->get();
            $oldTickets = $this->ticket->getByList(array_keys($ticketData));
            $ticketData = $this->ticketZen->buildBatchCloseData($ticketData, $oldTickets);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->ticket->batchClose($ticketData, $oldTickets);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('load' => $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}")));
        }
        else
        {
            $this->locate($this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}"));
        }
    }

    /**
     * Batch activated ticket.
     *
     * @access public
     * @return void
     */
    public function batchActivate()
    {
        if(isset($_POST['ticketIDList']))
        {
            $ticketIDList = $this->ticket->getByList($_POST['ticketIDList']);
            $notActivateTicket = array();
            foreach($ticketIDList as $ticketID => $ticket)
            {
                if($ticket->status == 'wait' or $ticket->status == 'doing')
                {
                    $notActivateTicket[] = $ticket->id;
                    unset($ticketIDList[$ticketID]);
                }

            }

            if($ticketIDList == array())
            {
                $browseLink = $this->session->ticketList ? $this->session->ticketList : $this->createlink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
                $message    = sprintf($this->lang->ticket->batchActivateTip, implode(',', $notActivateTicket));
                return $this->send(array('result' => 'fail', 'load' => array('alert' => $message, 'locate' => $browseLink)));
            }

            $this->view->tickets          = $ticketIDList;
            $this->view->batchActivateTip = !empty($notActivateTicket) ? sprintf($this->lang->ticket->batchActivateTip, implode(',', $notActivateTicket)) : '';
            $this->view->users            = $this->loadModel('user')->getPairs('noclosed|noletter');
            $this->view->title            = $this->lang->ticket->batchActivate;

            $this->display();
        }
        elseif($_POST)
        {
            $allChanges = $this->ticket->batchActivate();
            if(dao::isError()) return $this->sendError(dao::getError());
            if(!empty($allChanges))
            {
                foreach($allChanges as $ticketID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->loadModel('action')->create('ticket', $ticketID, 'Activated', zget($this->post->comment, $ticketID, ''));
                    $this->action->logHistory($actionID, $changes);
                }
            }
            $browseLink = $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
            return $this->sendSuccess(array('load' => $browseLink));
        }
        else
        {
            $browseLink = $this->createLink('ticket', 'browse', "browseType={$this->session->ticketBrowseType}");
            $this->locate($browseLink);
        }
    }

    /**
     * Sync product module.
     *
     * @param  int    $productID
     * @param  string $parent
     * @access public
     * @return void
     */
    public function syncProduct($productID = 0, $parent = '')
    {
        echo $this->fetch('feedback', 'syncProduct', "productID=$productID&module=ticket&parent=$parent");
    }

    /**
     * Export ticket.
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
            $this->config->ticket->dtable = $this->config->ticket->datatable;

            $this->loadModel('transfer');
            $sort = common::appendOrder($orderBy);
            $sort = str_replace('id', 't1.id', $sort);

            /* Define the common fields. */
            $commonFields = array(
                'group_concat(t2.customer) as customer',
                'group_concat(t2.contact) as contact',
                'group_concat(t2.notifyEmail) as notifyEmail',
            );

            /* Replace id and desc with their corresponding aliases. */
            $selectFields = array_merge($_POST['exportFields'], $commonFields);
            $selectFields = str_replace(array('id', 'desc'), array('t1.id', '`desc`'), $selectFields);
            $selectFields = implode(',', $selectFields);

            /* Get tickets. */
            $ticketQueryCondition = preg_replace('/SELECT.*WHERE/i', '', $this->session->ticketQueryCondition);
            $sql = $this->dao->select("t1.*,group_concat(t4.customer) as customer,group_concat(t4.contact) as contact,group_concat(t4.notifyEmail) as notifyEmail")
                ->from(TABLE_TICKET)->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
                ->leftJoin(TABLE_TICKETSOURCE)->alias('t4')->on('t1.id=t4.ticketId')
                ->where($ticketQueryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($this->cookie->checkedItem)->fi()
                ->beginIF(strpos($ticketQueryCondition, ' GROUP BY ') === false)->groupBy('t1.id')->fi()
                ->orderBy($sort)
                ->get();

            $this->loadModel('common')->saveQueryCondition($sql, 'ticket', false);

            $this->ticket->setListValue();
            $this->transfer->export('ticket');

            $this->fetch('file', 'export2' . $_POST['fileType'], $_POST);
        }

        $fileName = zget($this->lang->ticket->featureBar['browse'], $browseType, '');
        if(empty($fileName)) $fileName = zget($this->lang->ticket->statusList, $browseType, '');
        if(empty($fileName)) $fileName = zget($this->lang->ticket, $browseType, '');
        if($fileName && is_string($fileName)) $this->view->fileName = $this->lang->ticket->common . $this->lang->dash . $fileName;

        $this->view->allExportFields = $this->config->ticket->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }

    /**
     * 获取重复的工单列表。
     * Get repeat tickets.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function ajaxGetRepeatTickets($ticketID = 0)
    {
        $tickets = $this->ticket->getList('all');
        if(!empty($tickets[$ticketID])) unset($tickets[$ticketID]);

        $ticketItems = array();
        foreach($tickets as $key => $ticket) $ticketItems[] = array('value' => $ticket->id, 'text' => ("#$ticket->id " . $ticket->title));
        return print(json_encode($ticketItems));
    }
}

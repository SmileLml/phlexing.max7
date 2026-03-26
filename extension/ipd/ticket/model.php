<?php
/**
 * The model file of ticket module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yue Ma, Zongjun Lan, Xin Zhou
 * @package     ticket
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class ticketModel extends model
{
    /**
     * Get info of a ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return object
     */
    public function getByID($ticketID)
    {
        $ticket = $this->dao->select('*')->from(TABLE_TICKET)
            ->where('id')->eq($ticketID)
            ->fetch();
        if(empty($ticket)) return false;

        foreach($ticket as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $ticket->$key = '';

        $ticket->deadline    = helper::isZeroDate($ticket->deadline) ? '' : $ticket->deadline;
        $ticket->createFiles = $this->loadModel('file')->getByObject('ticket', $ticketID, 'create');
        $ticket->finishFiles = $this->loadModel('file')->getByObject('ticket', $ticketID, 'finished');
        $consumed            = (float)$this->dao->select('sum(consumed) as consumed')->from(TABLE_EFFORT)->where('objectType')->eq('ticket')->andWhere('objectID')->eq($ticketID)->andWhere('deleted')->eq(0)->fetch('consumed');
        $ticket->consumed    = round($consumed, 2);
        $ticket              = $this->file->replaceImgURL($ticket, 'desc,resolution');
        $ticket->desc        = $this->file->setImgSize($ticket->desc);
        $ticket->resolution  = $this->file->setImgSize($ticket->resolution);
        $ticket->files       = $this->loadModel('file')->getByObject('ticket', $ticketID);

        return $ticket;
    }

    /**
     * Get ticket list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByList($idList)
    {
        return $this->dao->select('*')->from(TABLE_TICKET)
            ->where('id')->in($idList)
            ->fetchAll('id', false);
    }

    /**
     * Get ticket source by ticketID.
     *
     * @param  int    $ticketID
     * @access public
     * @return array
     */
    public function getSourceByTicket($ticketID)
    {
        $sources = $this->dao->select('*')->from(TABLE_TICKETSOURCE)
            ->where('ticketId')->eq($ticketID)
            ->fetchAll('id', false);

        return $sources;
    }

    /**
     * Get ticket by product.
     *
     * @param  int|string $product
     * @param  string     $params  noclosed|nodone
     * @param  string     $orderBy
     * @param  object     $pager
     *
     * @access public
     * @return array
     */
    public function getTicketByProduct($product = 'all', $params = '', $orderBy = 'id_desc', $pager = null)
    {
        $productIdList = '';
        if($product == 'all')
        {
            $products      = $this->loadModel('feedback')->getGrantProducts();
            $productIdList = array_keys($products);
        }

        return $this->dao->select('*')->from(TABLE_TICKET)
            ->where('deleted')->eq(0)
            ->beginIF($product and $product != 'all')->andWhere('product')->eq($product)->fi()
            ->beginIF($product == 'all')->andWhere('product')->in($productIdList)->fi()
            ->beginIF(strpos($params, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF(strpos($params, 'nodone') !== false)->andWhere('status')->ne('done')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get stories by ticket.
     *
     * @param int $ticketID
     *
     * @access public
     * @return array
     */
    public function getStoriesByTicket($ticketID)
    {
        return $this->dao->select('t2.*')->from(TABLE_TICKETRELATION)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.ticketID')->eq($ticketID)
            ->andWhere('t1.objectType')->eq('story')
            ->fetchAll('id', false);
    }

    /**
     * Get bugs by ticket.
     *
     * @param int $ticketID
     *
     * @access public
     * @return array
     */
    public function getBugsByTicket($ticketID)
    {
        return $this->dao->select('t2.*')->from(TABLE_TICKETRELATION)->alias('t1')
            ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.ticketID')->eq($ticketID)
            ->andWhere('t1.objectType')->eq('bug')
            ->fetchAll('id', false);
    }

    /**
     * Create a ticket.
     *
     * @param  string  $extras
     * @access public
     * @return mixed
     */
    public function create($extras)
    {
        $this->loadModel('action');

        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);
        $fromType = isset($output['fromType']) ? $output['fromType'] : '';
        $fromID   = isset($output['fromID']) ? $output['fromID'] : '';
        if($fromType == 'feedback' and $fromID) $fileIDPairs = $this->loadModel('file')->copyObjectFiles('ticket');

        $now = helper::now();
        $ticket = fixer::input('post')
            ->setDefault('product', 0)
            ->setDefault('module', 0)
            ->setDefault('estimate', 0)
            ->setDefault('left', 0)
            ->setDefault('status', 'wait')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setDefault('openedBuild', '')
            ->setDefault('deadline', null)
            ->stripTags($this->config->ticket->editor->create['id'], $this->config->allowedTags)
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($fromType == 'feedback', 'feedback', $fromID)
            ->trim('title')
            ->join('mailto', ',')
            ->join('openedBuild', ',')
            ->remove('files,uid,customer,contact,notifyEmail,labels,color,deleteFiles,contactList')
            ->get();

        $this->checkNotifyEmail();
        if(dao::isError()) return false;

        $ticket = $this->loadModel('file')->processImgURL($ticket, $this->config->ticket->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TICKET)->data($ticket)
            ->autoCheck()
            ->batchCheck($this->config->ticket->create->requiredFields, 'notempty')
            ->checkflow()
            ->exec();

        if(!dao::isError())
        {
            $ticketID = $this->dao->lastInsertID();
            if(!empty($ticket->assignedTo)) $this->loadModel('action')->create('ticket', $ticketID, 'Assigned', '', $ticket->assignedTo);

            if($fromType == 'feedback' and $fromID)
            {
                $oldFeedback = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->eq($fromID)->fetch();

                $feedback = new stdclass();
                $feedback->status        = 'commenting';
                $feedback->result        = $ticketID;
                $feedback->processedBy   = $this->app->user->account;
                $feedback->processedDate = helper::now();
                $feedback->solution      = 'toticket';
                $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($fromID)->exec();
                $this->action->create('feedback', $fromID, 'ToTicket', '', $ticketID);
                $this->action->create('ticket', $ticketID, 'fromFeedback', '', $fromID);
                if($oldFeedback->status != 'commenting') $this->action->create('feedback', $fromID, 'syncDoingByTicket', '', $ticketID);

                $relation = new stdClass();
                $relation->AType    = 'feedback';
                $relation->AID      = $fromID;
                $relation->relation = 'transferredto';
                $relation->BType    = 'ticket';
                $relation->BID      = $ticketID;
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

                if(isset($fromID) && !empty($fileIDPairs))
                {
                    if(!empty($fileIDPairs)) $this->dao->update(TABLE_FILE)->set('objectID')->eq($ticketID)->where('id')->in($fileIDPairs)->exec();
                }
                $this->loadModel('feedback')->updateSubStatus($fromID, $feedback->status);
            }

            if(isset($_POST['customer']))
            {
                foreach($this->post->customer as $i => $customer)
                {
                    $customer    = trim($customer);
                    $contact     = trim($this->post->contact[$i]);
                    $notifyEmail = trim($this->post->notifyEmail[$i]);

                    if(empty($customer) and empty($contact) and empty($notifyEmail)) continue;

                    $ticketSource = new stdclass();
                    $ticketSource->ticketId    = $ticketID;
                    $ticketSource->customer    = $customer;
                    $ticketSource->contact     = $contact;
                    $ticketSource->notifyEmail = $notifyEmail;
                    $ticketSource->createdDate = $now;

                    $this->dao->insert(TABLE_TICKETSOURCE)->data($ticketSource)->exec();
                }
            }
            return $ticketID;
        }
        if(dao::isError()) return false;
    }

    /**
     * Update a ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return void
     */
    public function update($ticketID)
    {
        $oldTicket = $this->getByID($ticketID);

        $now    = helper::now();
        $ticket = form::data($this->config->ticket->form->edit)
            ->add('id', $ticketID)
            ->stripTags($this->config->ticket->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('status', $oldTicket->status)
            ->setDefault('startedBy', $oldTicket->startedBy)
            ->setDefault('startedDate', helper::isZeroDate($oldTicket->startedDate) ? null : $oldTicket->startedDate)
            ->setDefault('resolvedBy', $oldTicket->resolvedBy)
            ->setDefault('resolvedDate', helper::isZeroDate($oldTicket->resolvedDate) ? null : $oldTicket->resolvedDate)
            ->setDefault('closedBy', $oldTicket->closedBy)
            ->setDefault('closedDate', helper::isZeroDate($oldTicket->closedDate) ? null : $oldTicket->closedDate)
            ->setDefault('deadline', helper::isZeroDate($oldTicket->deadline) ? null : $oldTicket->deadline)
            ->setDefault('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->get();
        if($ticket->assignedTo != $oldTicket->assignedTo) $ticket->assignedDate = $now;

        $this->checkNotifyEmail('edit');
        if(dao::isError()) return false;

        $this->dao->update(TABLE_TICKET)->data($ticket, 'deleteFiles')
            ->autoCheck()
            ->batchCheck($this->config->ticket->edit->requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq($ticketID)
            ->exec();

        if(!dao::isError())
        {
            $this->dao->delete()->from(TABLE_TICKETSOURCE)->where('ticketId')->eq($ticketID)->exec();
            if(isset($_POST['customer']))
            {
                foreach($this->post->customer as $i => $customer)
                {
                    $customer    = trim($customer);
                    $contact     = trim($this->post->contact[$i]);
                    $notifyEmail = trim($this->post->notifyEmail[$i]);

                    if(empty($customer) and empty($contact) and empty($notifyEmail)) continue;

                    $ticketSource = new stdclass();
                    $ticketSource->ticketId    = $ticketID;
                    $ticketSource->customer    = $customer;
                    $ticketSource->contact     = $contact;
                    $ticketSource->notifyEmail = $notifyEmail;
                    $ticketSource->createdDate = $now;

                    $this->dao->insert(TABLE_TICKETSOURCE)->data($ticketSource)->exec();
                }
            }

            $this->file->processFile4Object('ticket', $oldTicket, $ticket);
            return common::createChanges($oldTicket, $ticket);
        }
        if(dao::isError()) return false;

    }

    /**
     * Start a ticket.
     *
     * @param  int    $ticketID
     * @access public
     * @return array
     */
    public function start($ticketID)
    {
        $oldTicket = $this->getByID($ticketID);
        if($oldTicket->status != 'wait') return false;

        $now  = helper::now();
        $ticket = fixer::input('post')
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', $now)
            ->setDefault('startedBy', $this->app->user->account)
            ->setDefault('startedDate', $now)
            ->setDefault('status', 'doing')
            ->setDefault('realStarted', $now)
            ->setIF($oldTicket->assignedTo != $this->app->user->account, 'assignedDate', $now)
            ->stripTags($this->config->ticket->editor->start['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $requiredFields = explode(',', $this->config->ticket->start->requiredFields);
        foreach($requiredFields as $requiredField)
        {
            $requiredField = trim($requiredField);
            if(!$this->post->$requiredField) dao::$errors[] = sprintf($this->lang->ticket->noRequire, '', $this->lang->ticket->$requiredField);
        }

        if(dao::isError()) return false;

        /* Record consumed and left. */
        if($this->post->consumed)
        {
            $estimate = new stdclass();
            $estimate->date     = helper::today();
            $estimate->ticket   = $ticketID;
            $estimate->consumed = zget($ticket, 'consumed', 0);
            $estimate->left     = 0;
            $estimate->work     = $this->lang->ticket->started . $this->lang->ticket->common . " : " . $oldTicket->title;
            $estimate->account  = $this->app->user->account;
            $this->addTicketEstimate($estimate);
        }

        $ticket = $this->loadModel('file')->processImgURL($ticket, $this->config->ticket->editor->start['id'], $this->post->uid);

        $this->dao->update(TABLE_TICKET)->data($ticket)->autoCheck()->checkFlow()->where('id')->eq((int)$ticketID)->exec();

        if(!dao::isError()) return common::createChanges($oldTicket, $ticket);
    }

    /**
     * Assign a ticket to a user again.
     *
     * @param  int    $ticketID
     * @access public
     * @return string
     */
    public function assign($ticketID)
    {
        $now = helper::now();
        $oldTicket= $this->getByID($ticketID);
        $ticket = fixer::input('post')
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', $now)
            ->setDefault('assignedDate', $now)
            ->stripTags($this->config->ticket->editor->assignto['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $ticket = $this->loadModel('file')->processImgURL($ticket, $this->config->ticket->editor->assignto['id'], $this->post->uid);

        $this->dao->update(TABLE_TICKET)->data($ticket)->autoCheck()->checkFlow()->where('id')->eq((int)$ticketID)->exec();

        if(!dao::isError()) return common::createChanges($oldTicket, $ticket);
    }

    /**
     * Get ticket list.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function getList($browseType = 'wait', $orderBy = 'id_desc', $pager = null, $moduleID = 0)
    {
        if($this->session->ticketBrowseType == 'assignedbyme') return $this->loadModel('my')->getAssignedByMe($this->app->user->account, $pager, $orderBy, 'ticket');

        $modules  = ($moduleID and $this->session->browseType == 'byModule') ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $account  = $this->app->user->account;
        $products = $this->loadModel('feedback')->getGrantProducts(true, false, 'all');

        return $this->dao->select('t1.*,t2.dept')->from(TABLE_TICKET)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t1.product')->in(array_keys($products))
            ->beginIF($this->session->ticketBrowseType == 'unclosed')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($this->session->ticketBrowseType == 'wait')->andWhere('t1.status')->eq('wait')->fi()
            ->beginIF($this->session->ticketBrowseType == 'doing')->andWhere('t1.status')->eq('doing')->fi()
            ->beginIF($this->session->ticketBrowseType == 'done')->andWhere('t1.status')->eq('done')->fi()
            ->beginIF($this->session->ticketBrowseType == 'closed')->andWhere('t1.status')->eq('closed')->fi()
            ->beginIF($this->session->ticketBrowseType == 'openedbyme')->andWhere('t1.openedBy')->eq($account)->fi()
            ->beginIF($this->session->ticketBrowseType == 'finishedbyme')->andWhere('t1.resolvedBy')->eq($account)->fi()
            ->beginIF($this->session->browseType == 'byProduct' and $moduleID)->andWhere('t1.product')->eq($moduleID)->fi()
            ->beginIF($this->session->browseType == 'byProduct' and $this->session->ticketProduct != 'all')->andWhere('t1.product')->eq($this->session->ticketProduct)->fi()
            ->beginIF($this->session->ticketBrowseType == 'assignedtome')
            ->andWhere('t1.assignedTo')->eq($account)
            ->andWhere('t1.status')->in('wait,doing,done')
            ->fi()
            ->beginIF($this->session->ticketBrowseType == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->beginIF($this->session->ticketBrowseType == 'closedbyme')->andWhere('t1.closedBy')->eq($account)->fi()
            ->beginIF(!empty($modules))->andWhere('t1.module')->in($modules)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get ticket List by Search.
     *
     * @param  string    $queryID
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $productID
     * @access public
     * @return array
     */
    public function getBySearch($queryID, $orderBy, $pager, $productID = 0)
    {
        $moduleName = $this->app->moduleName;
        $rawMethod  = $this->app->rawMethod;
        $account    = $this->app->user->account;

        $ticketQuery = 'ticketQuery';
        $ticketForm  = 'ticketForm';
        if($moduleName == 'my') $ticketQuery = $rawMethod . 'TicketQuery';
        if($moduleName == 'my') $ticketForm  = $rawMethod . 'TicketForm';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($ticketQuery, $query->sql);
                $this->session->set($ticketForm, $query->form);
            }
            else
            {
                $this->session->set($ticketQuery, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->$ticketQuery == false) $this->session->set($ticketQuery, ' 1 = 1');
        }
        $ticketQuery = $this->session->$ticketQuery;
        $ticketQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $ticketQuery);
        $ticketQuery = preg_replace('/t1.`(customer|contact|notifyEmail)`/', 't3.`$1`', $ticketQuery);

        $grantProducts = $this->loadModel('feedback')->getGrantProducts(true, false, 'all');
        $assignedByMe  = $rawMethod == 'contribute' ? $this->loadModel('my')->getAssignedByMe($account, null, $orderBy, 'ticket') : array(0);

        $tickets =  $this->dao->select('DISTINCT t1.*,t2.dept')->from(TABLE_TICKET)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
            ->leftJoin(TABLE_TICKETSOURCE)->alias('t3')->on('t1.id = t3.ticketId')
            ->where('t1.deleted')->eq('0')
            ->beginIF($productID && $productID !== 'all')->andWhere('t1.product')->eq($productID)->fi()
            ->andWhere($ticketQuery)
            ->andWhere('t1.product')->in(array_keys($grantProducts))
            ->beginIF($moduleName == 'my' && $rawMethod == 'work')
            ->andWhere('t1.assignedTo')->eq($account)
            ->andWhere('t1.status')->in('wait,doing,done')
            ->fi()
            ->beginIF($moduleName == 'my' && $rawMethod == 'contribute')
            ->andWhere('t1.openedBy', true)->eq($account)
            ->orWhere('t1.resolvedBy')->eq($account)
            ->orWhere('t1.closedBy')->eq($account)
            ->orWhere('t1.id')->in(array_keys($assignedByMe))->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id', false);

        return $tickets;
    }

    /**
     * Get consumed by ticket.
     *
     * @param  array    $ticketIdList
     * @access public
     * @return array
     */
    public function getConsumedByTicket($ticketIdList)
    {
        return $this->dao->select('objectID,objectID,sum(consumed) as consumed')->from(TABLE_EFFORT)
           ->where('objectType')->eq('ticket')
           ->andWhere('objectID')->in($ticketIdList)
           ->andWhere('deleted')->eq(0)
           ->groupBy('objectID')
           ->fetchPairs();
    }

    /**
     * Close a ticket.
     *
     * @param  int    $ticketID
     * @param  string $confirm
     * @access public
     * @return array
     */
    public function close($ticketID, $confirm)
    {
        $oldTicket = $this->getByID($ticketID);
        if($oldTicket->status == 'closed') return false;

        $now  = helper::now();
        $ticket = form::data(null, (int)$ticketID)
            ->setDefault('status', 'closed')
            ->setDefault('assignedDate', $now)
            ->add('id', $ticketID)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('closedBy', $this->app->user->account)
            ->add('closedDate', $now)
            ->add('assignedTo', 'closed')
            ->cleanINT('repeatTicket')
            ->stripTags($this->config->ticket->editor->close['id'], $this->config->allowedTags)
            ->remove('comment,files,labels')
            ->get();

        if(empty($ticket->closedReason) and ($oldTicket->status == 'wait' or $oldTicket->status == 'doing'))
        {
            dao::$errors['closedReason'][] = sprintf($this->lang->ticket->noRequire, '', $this->lang->ticket->closedReason);
        }

        if($ticket->closedReason == 'commented')
        {
            if($confirm == 'no')
            {
                $requiredFields = explode(',', 'resolvedBy,resolvedDate,resolution');
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if(!$this->post->$requiredField) dao::$errors[$requiredField][] = sprintf($this->lang->ticket->noRequire, '', $this->lang->ticket->$requiredField);
                }
            }
            unset($ticket->repeatTicket);
        }

        if($ticket->closedReason == 'repeat')
        {
            if(!$this->post->repeatTicket) dao::$errors['repeatTicket'][] = sprintf($this->lang->ticket->noRequire, '', $this->lang->ticket->repeatTicket);
            unset($ticket->resolvedBy, $ticket->resolvedDate, $ticket->resolution);
        }

        if($ticket->closedReason == 'refuse') unset($ticket->resolvedBy, $ticket->resolvedDate, $ticket->resolution, $ticket->repeatTicket);
        if($confirm == 'yes') unset($ticket->closedReason, $ticket->resolvedBy, $ticket->resolvedDate, $ticket->resolution, $ticket->repeatTicket);

        if(dao::isError()) return false;

        $ticket = $this->loadModel('file')->processImgURL($ticket, $this->config->ticket->editor->close['id'], $this->post->uid);

        $this->dao->update(TABLE_TICKET)->data($ticket)->batchCheck($this->config->ticket->close->requiredFields, 'notempty')->where('id')->eq($ticketID)->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldTicket, $ticket);
        if($changes)
        {
            $this->loadModel('action');

            if($confirm == 'no')
            {
                $files = $this->loadModel('file')->saveUpload('ticket', $ticketID, 'finished');
                $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
                $actionID = $this->action->create('ticket', $ticketID, 'Closed', $fileAction . $this->post->comment, $this->post->closedReason);
            }
            else
            {
                $actionID = $this->action->create('ticket', $ticketID, 'Closed', '', 'commented');
            }
            $this->action->logHistory($actionID, $changes);
        }

        if($oldTicket->feedback) $this->loadModel('feedback')->updateStatus('ticket', $oldTicket->feedback, $ticket->status, $oldTicket->status, $ticketID);
        return $changes;
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
        $oldTicket = $this->getByID($ticketID);
        if($oldTicket->status != 'wait' and  $oldTicket->status != 'doing') return false;

        $now  = helper::now();
        $ticket = fixer::input('post')
            ->setDefault('status', 'done')
            ->setDefault('left', 0)
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', $now)
            ->setDefault('finishedBy', $this->app->user->account)
            ->setDefault('finishedDate', $now)
            ->setIF(empty($oldTicket->startedBy), 'startedBy', $this->app->user->account)
            ->stripTags($this->config->ticket->editor->finish['id'], $this->config->allowedTags)
            ->remove('comment,files,labels')
            ->get();

        if($ticket->currentConsumed < 0)
        {
            dao::$errors[] = $this->lang->ticket->errorRecordMinus;
            return false;
        }

        if(!is_numeric($ticket->currentConsumed))
        {
            dao::$errors[] = $this->lang->ticket->errorCustomedNumber;
            return false;
        }

        $requiredFields = explode(',', $this->config->ticket->finish->requiredFields);
        foreach($requiredFields as $requiredField)
        {
            $requiredField = trim($requiredField);
            if(!$this->post->$requiredField) dao::$errors[] = sprintf($this->lang->ticket->noRequire, '', $this->lang->ticket->$requiredField);
        }

        if(dao::isError()) return false;

        /* Record consumed and left. */
        if($ticket->currentConsumed)
        {
            $estimate = new stdclass();
            $estimate->date     = substr($ticket->finishedDate, 0, 10);
            $estimate->ticket   = $ticketID;
            $estimate->left     = 0;
            $estimate->work     = $this->lang->ticket->finished . $this->lang->ticket->common . " : " . $oldTicket->title;
            $estimate->account  = $this->app->user->account;
            $estimate->consumed = $ticket->currentConsumed;
            $estimateID = $this->addTicketEstimate($estimate);
        }

        $ticket = $this->loadModel('file')->processImgURL($ticket, $this->config->ticket->editor->finish['id'], $this->post->uid);

        $this->dao->update(TABLE_TICKET)->data($ticket, 'currentConsumed,consumed')->where('id')->eq((int)$ticketID)->exec();

        if(dao::isError()) return false;

        $oldTicket->consumed = round($ticket->consumed - $ticket->currentConsumed, 2);

        $changes = common::createChanges($oldTicket, $ticket);
        if($changes or ($this->post->comment))
        {
            $this->loadModel('action');

            $files      = $this->loadModel('file')->saveUpload('ticket', $ticketID, 'finished');
            $fileAction = !empty($files) ? $this->lang->addFiles . join(',', $files) . "\n" : '';
            $actionID   = $this->action->create('ticket', $ticketID, 'Finished', $fileAction . $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        if($oldTicket->feedback) $this->loadModel('feedback')->updateStatus('ticket', $oldTicket->feedback, $ticket->status, $oldTicket->status, $ticketID);

        return $changes;
    }

    /**
     * Add ticket estimate.
     *
     * @param  object    $data
     * @access public
     * @return int
     */
    public function addTicketEstimate($data)
    {
        $relation = $this->loadModel('action')->getRelatedFields('ticket', $data->ticket);

        $effort = new stdclass();
        $effort->objectType = 'ticket';
        $effort->objectID   = $data->ticket;
        $effort->product    = $relation['product'];
        $effort->project    = (int)$relation['project'];
        $effort->account    = $data->account;
        $effort->date       = $data->date;
        $effort->consumed   = $data->consumed;
        $effort->left       = $data->left;
        $effort->work       = isset($data->work) ? $data->work : '';
        $effort->vision     = $this->config->vision;
        $effort->order      = isset($data->order) ? $data->order : 0;
        $this->dao->insert(TABLE_EFFORT)->data($effort)->autoCheck()->exec();

        $effortID = $this->dao->lastInsertID();
        $this->action->create('effort', $effortID, 'created', '', '', '', false);

        return $effortID;
    }

    /**
     * Activate ticket estimate.
     *
     * @param  int    $ticketID
     * @access public
     * @return bool
     */
    public function activate($ticketID)
    {
        $oldTicket = $this->getByID($ticketID);
        if($oldTicket->status != 'done' and $oldTicket->status != 'closed') return false;

        $now  = helper::now();
        $ticket = fixer::input('post')
            ->setDefault('status', 'doing')
            ->setDefault('assignedDate', $now)
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', $now)
            ->setDefault('activatedBy', $this->app->user->account)
            ->setDefault('activatedDate', $now)
            ->setDefault('finishedBy, closedBy, resolvedBy, closedReason, resolution', '')
            ->setDefault('repeatTicket', 0)
            ->setDefault('finishedDate, closedDate, resolvedDate', null)
            ->stripTags($this->config->ticket->editor->activate['id'], $this->config->allowedTags)
            ->remove('comment,files,labels')
            ->get();
        $ticket->activatedCount = $oldTicket->activatedCount + 1;

        $ticket = $this->loadModel('file')->processImgURL($ticket, $this->config->ticket->editor->activate['id'], $this->post->uid);

        $this->dao->update(TABLE_TICKET)->data($ticket)->where('id')->eq($ticketID)->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldTicket, $ticket);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('ticket', $ticketID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }
        if($oldTicket->feedback) $this->loadModel('feedback')->updateStatus('ticket', $oldTicket->feedback, $ticket->status, $oldTicket->status, $ticketID);

        return !dao::isError();
    }

    /**
     * Adjust the action clickable.
     *
     * @param  object $feedback
     * @param  string $action
     * @param  string $module
     * @access public
     * @return bool
     */
    public static function isClickable($ticket, $action, $module = 'ticket')
    {
        if(!common::hasPriv($module, $action)) return false;

        if($action == 'edit' and strpos('wait|doing|done', $ticket->status) !== false) return true;
        if($action == 'start' and strpos('wait', $ticket->status) !== false) return true;
        if($action == 'finish' and strpos('wait|doing', $ticket->status) !== false) return true;
        if($action == 'close' and strpos('wait|doing|done', $ticket->status) !== false) return true;
        if($action == 'edit' and strpos('wait|doing|done', $ticket->status) !== false) return true;
        if($action == 'activate' and strpos('done|closed', $ticket->status) !== false) return true;
        if($action == 'assignTo' and strpos('wait|doing|done', $ticket->status) !== false) return true;
        if($action == 'createBug') return true;
        if($action == 'createStory') return true;
        if($action == 'delete') return true;

        return false;
    }

    /**
     * Build ticket view menu.
     *
     * @param  int    $ticketID
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($ticketID)
    {
        $ticket = $this->getByID($ticketID);
        if($ticket->deleted) return '';

        $menu   = '';
        $params = "ticket=$ticket->id";

        if($ticket->status != 'closed') $menu .= $this->loadModel('effort')->createAppendLink('ticket', $ticketID);

        $menu .= $this->buildMenu('ticket', 'assignTo', $params, $ticket, 'view', 'hand-right', '', "iframe", true, '', $this->lang->ticket->assignTo);
        $menu .= $this->buildMenu('ticket', 'start', $params, $ticket, 'view', 'play', '', "iframe", true, '', $this->lang->ticket->start);
        $menu .= $this->buildMenu('ticket', 'finish', $params, $ticket, 'view', 'checked', '', "iframe", true, '', $this->lang->ticket->finish);
        if($ticket->status == 'done')
        {
            $menu .= $this->buildMenu('ticket', 'close', $params, $ticket, 'view', 'off', 'hiddenwin', '', '', '', $this->lang->ticket->close);
        }
        else
        {
            $menu .= $this->buildMenu('ticket', 'close', $params, $ticket, 'view', 'off', '', 'iframe', true, '', $this->lang->ticket->close);
        }
        if(self::isClickable($ticket, 'createBug') or self::isClickable($ticket, 'createStory'))
        {
            $menu .= "<div class='btn-group dropup'>";
            $menu .= "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><i class='icon icon-arrow-right'></i> " . $this->lang->feedback->convert . " <span class='caret'></span></button>";
            $menu .= "<ul class='dropdown-menu' id='createCaseActionMenu'>";

            if(self::isClickable($ticket, 'createStory'))
            {
                $storyLink = helper::createLink('ticket', 'createStory', "product=$ticket->product&extra=fromType=ticket,fromID=$ticketID");
                $menu .= "<li>" . html::a($storyLink, $this->lang->SRCommon, '', "data-app='feedback'") . "</li>";
            }
            if(self::isClickable($ticket, 'createBug'))
            {
                $bugLink = helper::createLink('ticket', 'createBug', "product=$ticket->product&extra=projectID=0,fromType=ticket,fromID=$ticketID");
                $menu .= "<li>" . html::a($bugLink, $this->lang->bug->common, '', "data-app='feedback'") . "</li>";
            }
            $menu .= "</ul>";
            $menu .= "</div>";
        }
        $menu .= $this->buildMenu('ticket', 'activate', $params, $ticket, 'view', 'magic', '', "iframe", true, '', $this->lang->ticket->activate);
        $menu .= $this->buildMenu('ticket', 'edit', $params, $ticket, 'view', 'edit', '', '', '', '', $this->lang->ticket->edit);
        $menu .= $this->buildFlowMenu('ticket', $ticket, 'view', 'direct');
        $menu .= $this->buildMenu('ticket', 'delete', $params, $ticket, 'view', 'trash', 'hiddenwin');

        return $menu;
    }

    /**
     * Build ticket browse menu.
     *
     * @param  object $ticketID
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($ticketID)
    {
        $ticket = $this->getByID($ticketID);
        $menu   = '';
        $params = "ticket=$ticketID";

        $menu .= $this->buildMenu('ticket', 'start', $params, $ticket, 'browse', 'play', '', 'iframe', true, '', $this->lang->ticket->start);
        $menu .= $this->buildMenu('ticket', 'finish', $params, $ticket, 'browse', 'checked', '', 'iframe', true, '', $this->lang->ticket->finish);

        if($ticket->status == 'done')
        {
            $menu .= $this->buildMenu('ticket', 'close', $params, $ticket, 'browse', 'off', 'hiddenwin', '', '', '', $this->lang->ticket->close);
        }
        else
        {
            $menu .= $this->buildMenu('ticket', 'close', $params, $ticket, 'browse', 'off', '', 'iframe', true, '', $this->lang->ticket->close);
        }

        $menu .= $this->buildMenu('ticket', 'edit', $params, $ticket, 'browse', 'edit', '', $this->app->tab == 'my' ? 'iframe' : '', $this->app->tab == 'my' ? true : '', '', $this->lang->ticket->edit);
        $menu .= $this->buildFlowMenu('ticket', $ticket, 'browse', 'direct');

        return $menu;
    }

    /**
     * Build search form for browse page.
     *
     * @param  string    $queryID
     * @param  string    $actionURL
     * @param  string    $productID
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL, $productID)
    {
        $grantProducts = $this->loadModel('feedback')->getGrantProducts(true, false, 'all');
        $this->config->ticket->search['queryID']   = $queryID;
        $this->config->ticket->search['actionURL'] = $actionURL;
        $this->config->ticket->search['params']['product']['values']  = arrayUnion(array('' => ''), $grantProducts);
        $this->config->ticket->search['params']['module']['values']   = $productID == 'all' ? $this->feedback->getModuleList('ticket', true) : arrayUnion(array('' => ''), $this->loadModel('tree')->getOptionMenu(intval($productID), 'ticket', 0, 'all'));
        $this->config->ticket->search['params']['feedback']['values'] = $this->feedback->getPairs($productID !== 'all' ? $productID : 0);

        $productIDlist = array_keys($grantProducts);
        $this->config->ticket->search['params']['openedBuild']['values'] = $this->loadModel('build')->getBuildPairs($productIDlist, 'all', 'releasetag');
        $this->loadModel('search')->setSearchParams($this->config->ticket->search);
    }

    /**
     * Get feedback search config.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function buildSearchConfig($productID)
    {
        unset($this->config->ticket->search['fields']['product']);
        $this->config->ticket->search['params']['module']['values']   = $this->loadModel('tree')->getOptionMenu(intval($productID), 'ticket', 0, 'all');
        $this->config->ticket->search['params']['feedback']['values'] = $this->loadModel('feedback')->getPairs($productID);

        $productIDlist = array($productID);
        $this->config->ticket->search['params']['openedBuild']['values'] = $this->loadModel('build')->getBuildPairs($productIDlist, 'all', 'releasetag');

        $_SESSION['searchParams']['module'] = 'ticket';
        $searchConfig = $this->config->ticket->search;
        $searchConfig['params'] = $this->loadModel('search')->setDefaultParams($searchConfig['fields'], $searchConfig['params']);

        return $searchConfig;
    }

    /**
     * Check notifyEmail.
     *
     * @access public
     * @return void
     */
    public function checkNotifyEmail($mothod = 'create')
    {
        if(empty($_POST['notifyEmail']))
        {
            $indexKey = $this->app->methodName == 'create' ? 'notifyEmail[0]' : 0;
            if(strpos($this->config->ticket->$mothod->requiredFields, 'notifyEmail') !== false) dao::$errors[$indexKey] = $this->lang->ticket->notifyEmailEmptyTip;
            return false;
        }

        foreach($this->post->notifyEmail as $index => $notifyEmail)
        {
            $notifyEmail = trim($notifyEmail);
            $customer    = trim($this->post->customer[$index]);
            $contact     = trim($this->post->contact[$index]);
            $indexKey    = $this->app->methodName == 'create' ? "notifyEmail[{$index}]" : $index;;

            if(empty($notifyEmail))
            {
                if(strpos($this->config->ticket->$mothod->requiredFields, 'notifyEmail') !== false)
                {
                    dao::$errors[$indexKey] = $this->lang->ticket->notifyEmailEmptyTip;
                    return false;
                }
                continue;
            }

            if(validater::checkEmail($notifyEmail) === false)
            {
                dao::$errors[$indexKey] = sprintf($this->lang->ticket->notifyEmailError, $this->lang->ticket->notifyEmail);
                return false;
            }
            $this->checkEmail($notifyEmail);
            if(dao::isError()) return false;
        }
    }

    /**
     * Check email of notifyEmail field.
     *
     * @param  array    $data
     * @access public
     * @return void
     */
    public function checkEmail($data)
    {
        $this->dao->insert(TABLE_TICKETSOURCE)->data($data)->batchCheck('notifyEmail', 'email');
    }

    /**
     * Create ticket from import.
     *
     * @access public
     * @return bool
     */
    public function createFromImport()
    {
        $this->loadModel('action');
        $now  = helper::now();
        $data = fixer::input('post')->get();

        if(!empty($_POST['id'])) $oldTickets = $this->dao->select('*')->from(TABLE_TICKET)->where('id')->in($_POST['id'])->fetchAll('id', false);

        $line              = 1;
        $tickets           = array();
        $ticketsSourceData = array();

        foreach($data->title as $key => $value)
        {
            $ticketData       = new stdclass();
            $ticketSourceData = new stdclass();

            if(empty($value)) continue;
            $ticketData->title        = $data->title[$key];
            $ticketData->pri          = $data->pri[$key];
            $ticketData->product      = $data->product[$key];
            $ticketData->module       = $data->module[$key];
            $ticketData->type         = $data->type[$key];
            $ticketData->desc         = $data->desc[$key];
            $ticketData->openedBuild  = empty($data->openedBuild[$key]) ? '' : implode(',', $data->openedBuild[$key]);
            $ticketData->assignedTo   = $data->assignedTo[$key];
            $ticketData->assignedDate = $now;
            $ticketData->deadline     = $data->deadline[$key] ? $data->deadline[$key] : null;
            $ticketData->mailto       = !empty($data->mailto[$key]) ? implode(',', $data->mailto[$key]) : '';
            $ticketData->keywords     = $data->keywords[$key];

            $ticketSourceData->contact     = $data->contact[$key];
            $ticketSourceData->customer    = $data->customer[$key];
            $ticketSourceData->notifyEmail = $data->notifyEmail[$key];

            $index = isset($data->id[$key]) ? $data->id[$key] : $line;
            if(!empty($ticketSourceData->notifyEmail) and validater::checkEmail($ticketSourceData->notifyEmail) === false)
            {
                dao::$errors["notifyEmail[{$index}]"] = sprintf($this->lang->ticket->notifyEmailError, '');
            }

            if(isset($this->config->ticket->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->ticket->create->requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if(empty($ticketData->$requiredField)) dao::$errors["{$requiredField}[$key]"] = sprintf($this->lang->ticket->noRequireLine, $this->lang->ticket->$requiredField);
                }
            }

            $tickets[$key]           = $ticketData;
            $ticketsSourceData[$key] = $ticketSourceData;
            $line++;
        }

        if(dao::isError()) return false;

        foreach($tickets as $key => $ticketData)
        {
            $ticketID = 0;
            if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
            {
                $ticketID = $data->id[$key];
                if(!isset($oldTickets[$ticketID])) $ticketID = 0;
            }

            if($ticketID)
            {
                $oldTicket = (array)$oldTickets[$ticketID];
                $newTicket = (array)$ticketData;

                $changes = common::createChanges((object)$oldTicket, (object)$newTicket);
                if(empty($changes)) continue;

                $ticketData->editedBy   = $this->app->user->account;
                $ticketData->editedDate = $now;
                $this->dao->update(TABLE_TICKET)->data($ticketData)->where('id')->eq($ticketID)->autoCheck()->checkFlow()->exec();

                if(!dao::isError())
                {
                    $actionID = $this->action->create('ticket', $ticketID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            else
            {
                $ticketData->openedBy   = $this->app->user->account;
                $ticketData->openedDate = $now;
                $ticketData->status     = 'wait';

                $this->dao->insert(TABLE_TICKET)->data($ticketData)->exec();

                if(!dao::isError())
                {
                    $ticketSourceData = $ticketsSourceData[$key];
                    $ticketID = $this->dao->lastInsertID();
                    $ticketSourceData->ticketId = $ticketID;
                    $this->dao->insert(TABLE_TICKETSOURCE)->data($ticketSourceData)->exec();
                    $this->action->create('ticket', $ticketID, 'Opened');
                }
            }
        }

        if($this->post->isEndPage)
        {
            if(!empty($_SESSION['fileImport'])) unlink($this->session->fileImport);
            unset($_SESSION['fileImport']);
        }
    }

    /**
     * Print id row cell.
     *
     * @param  object  $ticket
     * @access public
     * @return bool
     */
    public function printIdCell($ticket)
    {
        $canView          = common::hasPriv('ticket', 'view');
        $canBatchEdit     = common::hasPriv('ticket', 'batchEdit');
        $canBatchActivate = common::hasPriv('ticket', 'batchActivate');
        $canBatchFinish   = common::hasPriv('ticket', 'batchFinish');
        $canBatchAssignTo = common::hasPriv('ticket', 'batchAssignTo');
        $canBatchAction   = ($canBatchEdit or $canBatchActivate or $canBatchFinish or $canBatchAssignTo);

        $ticketLink = helper::createLink('ticket', 'view', "ticketID=$ticket->id");
        if($canBatchAction)
        {
            echo html::checkbox('ticketIDList', array($ticket->id => '')) . html::a($ticketLink, sprintf('%03d', $ticket->id));
        }
        else
        {
            printf('%03d', $ticket->id);
        }
    }

    /**
     * 批量创建工单。
     * Batch create tickets.
     *
     * @param  array   $tickets
     * @param  int     $productID
     * @access public
     * @return array|bool
     */
    public function batchCreate($tickets = array(), $productID = 0)
    {
        foreach($tickets as $rowIndex => $ticket)
        {
            if($ticket->notifyEmail && !filter_var($ticket->notifyEmail, FILTER_VALIDATE_EMAIL)) dao::$errors["notifyEmail[{$rowIndex}]"] = sprintf($this->lang->error->email, $this->lang->ticket->notifyEmail);
        }
        if(dao::isError()) return false;

        $this->loadModel('action');
        $ticketIdList = array();
        $now          = helper::now();
        $account      = $this->app->user->account;
        foreach($tickets as $rowIndex => $ticket)
        {
            $ticket->status       = 'wait';
            $ticket->product      = $productID;
            $ticket->openedBy     = $account;
            $ticket->openedDate   = $now;
            $ticket->assignedDate = $now;

            $this->dao->insert(TABLE_TICKET)->data($ticket, 'customer,contact,notifyEmail')
                ->autoCheck()
                ->checkFlow()
                ->exec();
            if(dao::isError()) return false;

            $ticketID = $this->dao->lastInsertID();
            $this->action->create('ticket', $ticketID, 'Opened');
            if(!empty($ticket->assignedTo)) $this->action->create('ticket', $ticketID, 'Assigned', '', $ticket->assignedTo);

            if(!empty($ticket->customer) || !empty($ticket->contact) || !empty($ticket->notifyEmail))
            {
                $ticketSource = new stdclass();
                $ticketSource->ticketId    = $ticketID;
                $ticketSource->customer    = $ticket->customer;
                $ticketSource->contact     = $ticket->contact;
                $ticketSource->notifyEmail = $ticket->notifyEmail;
                $ticketSource->createdDate = $now;

                $this->dao->insert(TABLE_TICKETSOURCE)->data($ticketSource)->exec();
                if(dao::isError()) return false;
            }

            if($this->post->uploadImage && $this->post->uploadImage[$rowIndex]) $this->doSaveUploadImage($ticketID, $this->post->uploadImage[$rowIndex], $ticket->desc);
            if(dao::isError()) return false;

            $ticketIdList[$ticketID] = $ticketID;
        }

        if($this->session->ticketImagesFile)
        {
            $file     = current($_SESSION['ticketImagesFile']);
            $realPath = dirname($file['realpath']);
            if(is_dir($realPath)) $this->app->loadClass('zfile')->removeDir($realPath);
            unset($_SESSION['ticketImagesFile']);
        }
        return $ticketIdList;
    }

    /**
     * 保存上传图片作为工单内容。
     * Do save upload image.
     *
     * @param  int       $ticketID
     * @param  string    $fileName
     * @param string $desc
     * @access public
     * @return void
     */
    public function doSaveUploadImage($ticketID, $fileName, $desc)
    {
        $ticketImageFiles = $this->session->ticketImagesFile;
        if(empty($ticketImageFiles)) return;
        if(empty($ticketImageFiles[$fileName])) return;

        $file     = $ticketImageFiles[$fileName];
        $realPath = $file['realpath'];
        unset($file['realpath']);
        if(!file_exists($realPath)) return;

        $this->loadModel('file');
        if(!is_dir($this->file->savePath)) mkdir($this->file->savePath, 0777, true);
        if($realPath && rename($realPath, $this->file->savePath . $this->file->getSaveName($file['pathname'])))
        {
            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = helper::now();
            $file['objectType'] = 'ticket';
            $file['objectID']   = $ticketID;

            $isImage = in_array($file['extension'], $this->config->file->imageExtensions);
            if($isImage) $file['extra'] = 'editor';

            $this->dao->insert(TABLE_FILE)->data($file)->exec();
            $fileID = $this->dao->lastInsertID();

            if($isImage) $desc .= '<img src="{' . $fileID . '.' . $file['extension'] . '}" alt="" />';
            $this->dao->update(TABLE_TICKET)->set('desc')->eq($desc)->where('id')->eq($ticketID)->exec();
        }
    }

    /**
     * Batch update tickets.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $tickets = form::batchData($this->config->ticket->form->batchEdit)->get();
        if(dao::isError()) return false;

        /* Check data. */
        foreach(explode(',', $this->config->ticket->edit->requiredFields) as $field)
        {
            foreach($tickets as $ticketID => $ticket)
            {
                if(empty($ticket->$field)) dao::$errors["{$field}[{$ticketID}]"] = sprintf($this->lang->ticket->noRequire, $this->lang->ticket->$field);
            }
        }
        if(dao::isError()) return false;

        $now        = helper::now();
        $changes    = array();
        $oldTickets = $this->getByList(array_keys($tickets));
        foreach($tickets as $ticketID => $ticket)
        {
            $oldTicket = $oldTickets[$ticketID];
            $ticket->editedBy   = $this->app->user->account;
            $ticket->editedDate = $now;
            if($ticket->assignedTo != $oldTicket->assignedTo) $ticket->assignedDate = $now;

            $this->dao->update(TABLE_TICKET)->data($ticket, 'idIndex')
                ->batchCheck($this->config->ticket->edit->requiredFields, 'notempty')
                ->checkFlow()
                ->where('id')->eq($ticketID)
                ->exec();
            if(dao::isError()) return false;

            $changes[$ticketID] = common::createChanges($oldTicket, $ticket);
        }

        return $changes;
    }

    /**
     * 批量指派工单。
     * Batch assign tickets.
     *
     * @param  string $assignedTo
     * @access public
     * @return array
     */
    public function batchAssign($assignedTo = '')
    {
        $oldTickets = $this->getByList($this->post->ticketIDList);

        $now     = helper::now();
        $changes = array();
        foreach($this->post->ticketIDList as $ticketID)
        {
            $oldTicket = $oldTickets[$ticketID];

            /* Filter data. */
            if($oldTicket->status == 'closed') continue;
            if($oldTicket->assignedTo == $assignedTo) continue;

            $ticket = new stdclass();
            $ticket->assignedTo   = $assignedTo;
            if($assignedTo != $oldTicket->assignedTo) $ticket->assignedDate = $now;
            $ticket->editedBy     = $this->app->user->account;
            $ticket->editedDate   = $now;

            $this->dao->update(TABLE_TICKET)->data($ticket)->where('id')->eq($ticketID)->exec();
            $changes[$ticketID] = common::createChanges($oldTicket, $ticket);
        }
        return $changes;
    }

    /**
     * Batch finish tickets.
     *
     * @access public
     * @return array
     */
    public function batchFinish()
    {
        $tickets = form::batchData($this->config->ticket->form->batchFinish)->get();
        if(dao::isError()) return false;

        /* Check data. */
        foreach(explode(',', $this->config->ticket->finish->requiredFields) as $field)
        {
            foreach($tickets as $ticketID => $ticket)
            {
                if(empty($ticket->$field)) dao::$errors["{$field}[{$ticketID}]"] = sprintf($this->lang->ticket->noRequire, $this->lang->ticket->$field);
            }
        }
        if(dao::isError()) return false;

        $oldTickets = $this->getByList(array_keys($tickets));

        $changes = array();
        $now     = helper::now();
        $account = $this->app->user->account;
        foreach($tickets as $ticketID => $ticket)
        {
            $oldTicket = $oldTickets[$ticketID];
            $ticket->left         = 0;
            $ticket->status       = 'done';
            $ticket->editedBy     = $account;
            $ticket->editedDate   = $now;
            $ticket->resolvedBy   = $account;
            $ticket->finishedBy   = $account;
            $ticket->finishedDate = $now;
            if(!$oldTicket->startedBy) $ticket->startedBy = $account;

            /* Record consumed and left. */
            if(!empty($ticket->consumed))
            {
                $estimate = new stdclass();
                $estimate->date     = substr((string)$now, 0, 10);
                $estimate->ticket   = $ticketID;
                $estimate->left     = 0;
                $estimate->work     = $this->lang->ticket->finished . $this->lang->ticket->common . " : " . $oldTicket->title;;
                $estimate->account  = $account;
                $estimate->consumed = $ticket->consumed;
                $estimateID = $this->addTicketEstimate($estimate);
            }
            if(dao::isError()) return false;

            $this->dao->update(TABLE_TICKET)->data($ticket, 'comment,consumed,idIndex')->where('id')->eq((int)$ticketID)->exec();
            if(dao::isError()) return false;

            if($oldTicket->feedback) $this->loadModel('feedback')->updateStatus('ticket', $oldTicket->feedback, $ticket->status, $oldTicket->status, $ticketID);
            $changes[$ticketID] = common::createChanges($oldTicket, $ticket);
        }

        return $changes;
    }

    /**
     * 批量关闭工单。
     * Batch close tickets.
     *
     * @param  array  $ticketData
     * @param  array  $oldTickets
     * @access public
     * @return bool
     */
    public function batchClose($ticketData = array(), $oldTickets = array())
    {
        $this->loadModel('feedback');
        $this->loadModel('action');

        foreach($ticketData as $ticketID => $ticket)
        {
            if(empty($ticket->resolution)) unset($ticket->resolution);

            $changes   = array();
            $oldTicket = $oldTickets[$ticketID];
            $this->dao->update(TABLE_TICKET)->data($ticket)->where('id')->eq((int)$ticketID)->exec();
            if(dao::isError()) return false;

            $changes = common::createChanges($oldTicket, $ticket);
            $comment = zget($this->post->comment, $ticketID, '');
            if(!empty($changes) || $comment)
            {
                $actionID = $this->action->create('ticket', $ticketID, 'Closed', $comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }
            if($oldTicket->feedback) $this->feedback->updateStatus('ticket', $oldTicket->feedback, $ticket->status, $oldTicket->status, $ticketID);
        }

        return !dao::isError();
    }

    /**
     * Batch activate tickets.
     *
     * @access public
     * @return array
     */
    public function batchActivate()
    {
        $this->loadModel('feedback');

        $tickets = form::batchData($this->config->ticket->form->batchActivate)->get();
        if(dao::isError()) return false;

        /* Check data. */
        foreach(explode(',', $this->config->ticket->batchActivate->requiredFields) as $field)
        {
            foreach($tickets as $ticketID => $ticket)
            {
                if(empty($ticket->$field)) dao::$errors["{$field}[{$ticketID}]"] = sprintf($this->lang->ticket->noRequire, $this->lang->ticket->$field);
            }
        }
        if(dao::isError()) return false;

        $oldTickets = $this->getByList(array_keys($tickets));

        $now     = helper::now();
        $account = $this->app->user->account;
        $changes = array();
        foreach($tickets as $ticketID => $ticket)
        {
            $oldTicket = $oldTickets[$ticketID];
            $ticket->editedBy       = $account;
            $ticket->editedDate     = $now;
            $ticket->assignedDate   = $now;
            $ticket->activatedBy    = $account;
            $ticket->activatedDate  = $now;
            $ticket->status         = 'doing';
            $ticket->finishedBy     = '';
            $ticket->closedBy       = '';
            $ticket->resolvedBy     = '';
            $ticket->resolution     = '';
            $ticket->closedReason   = '';
            $ticket->repeatTicket   = 0;
            $ticket->finishedDate   = null;
            $ticket->closedDate     = null;
            $ticket->resolvedDate   = null;
            $ticket->activatedCount = $oldTicket->activatedCount + 1;

            $this->dao->update(TABLE_TICKET)->data($ticket, 'idIndex,comment')->where('id')->eq((int)$ticketID)->exec();

            if(dao::isError()) return false;
            $changes[$ticketID] = common::createChanges($oldTicket, $ticket);

            if($oldTicket->feedback) $this->feedback->updateStatus('ticket', $oldTicket->feedback, $ticket->status, $oldTicket->status, $ticketID);
            if(dao::isError()) return false;
        }

        return $changes;

    }

    /**
     * Print assinged to html.
     *
     * @param  object   $ticket
     * @param  array    $users
     * @access public
     * @return mixed
     */
    public function printAssignedHtml($ticket, $users)
    {
        $assignedToText = !empty($ticket->assignedTo) ? zget($users, $ticket->assignedTo) : $this->lang->ticket->noAssigned;

        $btnTextClass   = '';
        $btnClass       = '';
        if(empty($ticket->assignedTo))                                                       $btnClass = $btnTextClass = 'assigned-none';
        if($ticket->assignedTo == $this->app->user->account)                                 $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($ticket->assignedTo) and $ticket->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $ticket->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';

        $assignToLink = helper::createLink('ticket', 'assignTo', "ticketID=$ticket->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $ticket->assignedTo) . "'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('ticket', 'assignTo', $ticket) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    /**
     * Sendmail.
     *
     * @param  int    $ticketID
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function sendmail($ticketID, $actionID)
    {
        $object = $this->getByID($ticketID);
        if(empty($object)) return false;
        $users = $this->loadModel('user')->getPairs('noletter');

        $this->loadModel('mail');
        $productName = $this->loadModel('product')->getById($object->product)->name;

        $domain = zget($this->config->mail, 'domain', common::getSysURL());
        $domain = rtrim($domain, '/');

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Get mail content. */
        $modulePath = $this->app->getModulePath($appName = '', 'ticket');
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        /* Set toList and ccList. */
        $sendUsers = $this->getToAndCcList($object, $action->action);
        if(!$sendUsers) return;
        list($toList, $ccList) = $sendUsers;
        $emails = $this->getContactEmails($ticketID, $toList, $ccList, $action->action == 'closed');

        /* Send it. */
        $this->mail->send($toList, $this->lang->ticket->common . ' #'. $object->id . ' ' . $object->title . ' - ' . $productName, $mailContent, $ccList, false, $emails, true, false);

        if($this->mail->isError()) error_log(join("\n", $this->mail->getError()));
    }

    /**
     * Get to and ccList.
     *
     * @param  object $ticket
     * @param  string $action
     * @access public
     * @return array
     */
    public function getToAndCcList($ticket, $action)
    {
        $toList = '';
        /* Set toList and ccList. */
        if(in_array($action, array('opened', 'edited', 'assigned', 'activated'))) $toList .= $ticket->assignedTo;

        if(in_array($action, array('started', 'finished', 'closed', 'activated'))) $toList .= ',' . $ticket->openedBy;

        if(empty($toList)) return false;

        $toList = trim($toList, ',');

        $ccList = '';
        if(!empty($ticket->mailto))
        {
            foreach(explode(',', $toList) as $toUser)
            {
                foreach(explode(',', $ticket->mailto) as $ccUser)
                {
                    if($toUser != $ccUser) $ccList .= "$ccUser,";
                }
            }
        }

        $ccList = trim($ccList, ',');

        list($toList, $ccList) = $this->loadModel('mail')->processToAndCC($toList, $ccList, false);

        if($action == 'closed')
        {
            $contacts = $this->dao->select('customer')->from(TABLE_TICKETSOURCE)->where('ticketId')->eq($ticket->id)->fetchPairs('customer');
            $contacts = !empty($contacts) ? implode(',', $contacts) : '';
            $ccList  .= ",$contacts";
        }

        $ccList = trim($ccList, ',');

        return array($toList, $ccList);
    }

    /**
     * Get content emails
     *
     * @param  int    $ticked
     * @param  string $toList
     * @param  string $ccList
     * @param  bool   $addContactEmails
     * @access public
     * @return array
     */
    public function getContactEmails($ticketID, $toList, $ccList, $addContactEmails = false)
    {
        $toList  = $toList ? explode(',', str_replace(' ', '', $toList)) : array();
        $ccList  = $ccList ? explode(',', str_replace(' ', '', $ccList)) : array();

        /* Process toList and ccList, remove current user from them. If toList is empty, use the first cc as to. */
        $account = isset($this->app->user->account) ? $this->app->user->account : '';

        foreach($toList as $key => $to) if(trim($to) == $account or !trim($to)) unset($toList[$key]);
        foreach($ccList as $key => $cc) if(trim($cc) == $account or !trim($cc)) unset($ccList[$key]);

        /* Remove deleted users. */
        $this->app->loadConfig('message');
        $users      = $this->loadModel('user')->getPairs('nodeleted|all');
        $blockUsers = isset($this->config->message->blockUser) ? explode(',', $this->config->message->blockUser) : array();
        foreach($toList as $key => $to) if(!isset($users[trim($to)]) or in_array(trim($to), $blockUsers)) unset($toList[$key]);
        foreach($ccList as $key => $cc) if(!isset($users[trim($cc)]) or in_array(trim($cc), $blockUsers)) unset($ccList[$key]);

        $contacts = array();
        if($addContactEmails)
        {
            /* Get ticket contact email. */
            $contacts = $this->dao->select('customer as account,customer as realname,notifyEmail as email')->from(TABLE_TICKETSOURCE)->where('ticketId')->eq($ticketID)->fetchAll('account');
        }

        if(!$toList and !$ccList and !$contacts) return array();
        if(!$toList and $ccList) $toList = array(array_shift($ccList));
        $toList = join(',', $toList);
        $ccList = join(',', $ccList);

        /* Get realname and email of users. */
        $this->loadModel('user');
        $emails = $this->user->getRealNameAndEmails(str_replace(' ', '', $toList . ',' . $ccList));

        return array_merge($emails, $contacts);
    }

    /**
     * Print cell.
     *
     * @param  array  $value
     * @param  object $ticket
     * @param  array  $users
     * @param  array  $products
     * @access public
     * @return void
     */
    public function printCell($value, $ticket, $users, $products)
    {
        $canBatchEdit     = common::hasPriv('ticket', 'batchEdit');
        $canBatchActivate = common::hasPriv('ticket', 'batchActivate');
        $canBatchFinish   = common::hasPriv('ticket', 'batchFinish');
        $canBatchAssignTo = common::hasPriv('ticket', 'batchAssignTo');
        $canView          = common::hasPriv('ticket', 'view');
        $canBatchAction   = ($canBatchEdit or $canBatchActivate or $canBatchFinish or $canBatchAssignTo);

        $id      = $value->id;
        $title   = '';
        $style   = '';
        $class   = "c-$id";
        if($id == 'activatedCount') $class .= ' text-center';
        if($id == 'assignedTo')     $class .= ' no-wrap';
        if($id == 'openedBuild')
        {
            $ticketOpenedBuild = '';
            if(!empty($ticket->openedBuild))
            {
                $builds = $this->loadModel('build')->getBuildPairs($ticket->product);
                foreach(explode(',', str_replace(' ', '', $ticket->openedBuild)) as $openedBuild) $ticketOpenedBuild .= ' ' . zget($builds, $openedBuild);
            }
            $title  = "title='$ticketOpenedBuild'";
            $class .= ' no-wrap';
        }
        if($id == 'keywords')
        {
            $title  = "title='$ticket->keywords'";
            $class .= ' no-wrap';
        }
        if($id == 'mailto')
        {
            $ticketMailto = '';
            if(!empty($ticket->mailto))
            {
                foreach(explode(',', str_replace(' ', '', $ticket->mailto)) as $account) $ticketMailto .= ' ' . zget($users, $account);
            }
            $title  = "title='$ticketMailto'";
            $class .= ' no-wrap';
        }
        if($id == 'type')
        {
            $title  = "title='" . zget($this->lang->ticket->typeList, $ticket->type) . "'";
            $class .= ' no-wrap';
        }
        if($id == 'consumed')
        {
            $title  = "title='" . $ticket->consumed . $this->lang->workingHour . "'";
            $class .= ' text-center';
        }
        if($id == 'estimate')
        {
            $title  = "title='" . $ticket->estimate . $this->lang->workingHour . "'";
            $class .= ' text-center';
        }
        if($id == 'title')
        {
            $title  = "title='{$ticket->title}'";
        }
        if($id == 'product')
        {
            $title  = "title='" . zget($products, $ticket->product) . "'";
        }
        if($id == 'openedBy')
        {
            $title  = "title='" . zget($users, $ticket->openedBy) . "'";
        }
        if($id == 'closedReason')
        {
            $title  = "title='" . zget($this->lang->ticket->closedReasonList, $ticket->closedReason) . "'";
        }

        echo "<td class='" . $class . "' $title $style>";
        switch($id)
        {
        case 'id':
            echo $canView ? $this->printIdCell($ticket) : $ticket->id;
            break;
        case 'title':
            echo $canView ? html::a(helper::createLink('ticket', 'view', "id={$ticket->id}"), $ticket->title) : $ticket->title;
            break;
        case 'product':
            echo zget($products, $ticket->product);
            break;
        case 'pri':
            echo "<span class='label-pri label-pri-" . $ticket->pri . "' title='" . zget($this->lang->ticket->priList, $ticket->pri) . "'>";
            echo zget($this->lang->ticket->priList, $ticket->pri, $ticket->pri);
            echo "</span>";
            break;
        case 'feedback':
            if(!empty($ticket->feedback))
            {
                echo common::hasPriv('feedback', 'adminView') ? html::a(helper::createLink('feedback', 'adminView', "feedbackID=$ticket->feedback"), "#$ticket->feedback") : "#$ticket->feedback";
                echo "<span class='label label-info'>" . $this->lang->feedback->common . "</span>";
            }
            break;
        case 'status':
            echo "<span class='status-task status-" . $ticket->status . "' title='" . zget($this->lang->ticket->statusList, $ticket->status) . "'>";
            echo zget($this->lang->ticket->statusList, $ticket->status);
            echo "</span>";
            break;
        case 'type':
            echo zget($this->lang->ticket->typeList, $ticket->type);
            break;
        case 'assignedTo':
            echo $this->printAssignedHtml($ticket, $users);
            break;
        case 'estimate':
            echo "$ticket->estimate h";
            break;
        case 'openedBy':
            echo zget($users, $ticket->openedBy);
            break;
        case 'openedDate':
            echo helper::isZeroDate($ticket->openedDate) ? '' : substr($ticket->openedDate, 5, 11);
            break;
        case 'deadline':
            echo helper::isZeroDate($ticket->deadline) ? '' : substr($ticket->deadline, 5, 11);
            break;
        case 'consumed':
            echo "$ticket->consumed h";
            break;
        case 'openedBuild':
            echo $ticketOpenedBuild;
            break;
        case 'keywords':
            echo $ticket->keywords;
            break;
        case 'mailto':
            echo $ticketMailto;
            break;
        case 'startedBy':
            echo zget($users, $ticket->startedBy);
            break;
        case 'startedDate':
            echo helper::isZeroDate($ticket->startedDate) ? '' : substr($ticket->startedDate, 5, 11);
            break;
        case 'finishedBy':
            echo zget($users, $ticket->finishedBy);
            break;
        case 'finishedDate':
            echo helper::isZeroDate($ticket->finishedDate) ? '' : substr($ticket->finishedDate, 5, 11);
            break;
        case 'closedBy':
            echo zget($users, $ticket->closedBy);
            break;
        case 'closedDate':
            echo helper::isZeroDate($ticket->closedDate) ? '' : substr($ticket->closedDate, 5, 11);
            break;
        case 'closedReason':
            echo zget($this->lang->ticket->closedReasonList, $ticket->closedReason);
            break;
        case 'activatedBy':
            echo zget($users, $ticket->activatedBy);
            break;
        case 'activatedDate':
            echo helper::isZeroDate($ticket->activatedDate) ? '' : substr($ticket->activatedDate, 5, 11);
            break;
        case 'activatedCount':
            echo $ticket->activatedCount;
            break;
        case 'editedBy':
            echo zget($users, $ticket->editedBy);
            break;
        case 'editedDate':
            echo helper::isZeroDate($ticket->editedDate) ? '' : substr($ticket->editedDate, 5, 11);
            break;
        case 'legendMisc':
            $this->app->loadLang('story');
            $this->app->loadLang('bug');
            $stories = $this->getStoriesByTicket($ticket->id);
            $bugs    = $this->getBugsByTicket($ticket->id);
            if(!empty($stories) and !empty($bugs))
            {
                $endStory = end($stories);
                $endBug   = end($bugs);
                if($endStory->openedDate > $endBug->openedDate) echo $this->lang->ticket->toStory . " #$endStory->id" . " <span class='label label-info'>" . zget($this->lang->story->statusList, $endStory->status) . "</span>";
                if($endStory->openedDate < $endBug->openedDate) echo $this->lang->ticket->toBug   . " #$endBug->id"   . " <span class='label label-info'>" . zget($this->lang->bug->statusList, $endBug->status)     . "</span>";
            }
            if(empty($stories) and !empty($bugs)) echo $this->lang->ticket->toBug . " #" . end($bugs)->id . " <span class='label label-info'>" . zget($this->lang->bug->statusList, end($bugs)->status) . "</span>";
            if(empty($bugs) and !empty($stories)) echo $this->lang->ticket->toStory . " #" . end($stories)->id . " <span class='label label-info'>" . zget($this->lang->story->statusList, end($stories)->status) . "</span>";
            break;
        case 'actions':
            echo $this->buildOperateBrowseMenu($ticket->id);
            break;
        default:
            $this->loadModel('flow')->printFlowCell('ticket', $ticket, $id);
        }
        echo '</td>';
    }

    /**
     * Get grant products.
     *
     * @param  bool   $isPairs
     * @param  bool   $isDefault
     * @param  bool   $queryAll
     * @access public
     * @return array
     */
    public function getGrantProducts($isPairs = true, $isDefault = false, $queryAll = false)
    {
        $products = $this->dao->select('*')->from(TABLE_FEEDBACKVIEW)->where('account')->eq($this->app->user->account)->fetchPairs('product', 'product');
        $productSettingList = isset($this->config->global->productSettingList) ? json_decode($this->config->global->productSettingList, true) : array();
        $allottedProducts = array();
        if(empty($products)) $allottedProducts = $this->dao->select('DISTINCT product')->from(TABLE_FEEDBACKVIEW)->fetchPairs('product', 'product');
        $vision = $this->config->vision == 'or' ? 'or' : 'rnd';
        $admin  = $this->app->user->admin;
        $stmt   = $this->dao->select('t1.*')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->like("%{$vision}%")
            ->beginIF(!empty($products) and !$admin)->andWhere('t1.id')->in($products)->fi()
            ->beginIF(!empty($productSettingList) and !$admin)->andWhere('t1.id')->in($productSettingList)->fi()
            ->beginIF(!empty($allottedProducts) and !$admin)->andWhere('t1.id')->notin($allottedProducts)->fi()
            ->beginIF(!$admin && !$queryAll)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->filterTpl('skip')
            ->orderBy('t2.order_asc,t1.line_desc,t1.order_asc');
        $pairs = $isPairs ? $stmt->fetchPairs('id', 'name') : $stmt->fetchAll('id', false);
        return $isDefault ? arrayUnion(array('' => ''), $pairs) : $pairs;
    }
}

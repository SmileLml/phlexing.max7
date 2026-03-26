<?php
/**
 * The model file of deploy module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     deploy
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class deployModel extends model
{
    /**
     * 根据ID获取上线申请。
     * Get deploy by ID.
     *
     * @param  int    $deployID
     * @access public
     * @return object|false
     */
    public function getByID($deployID)
    {
        $deploy = $this->dao->select('*')->from(TABLE_DEPLOY)->where('id')->eq($deployID)->fetch();
        if(!$deploy) return false;

        $deploy = $this->loadModel('file')->replaceImgURL($deploy, 'desc');
        $deploy->products = $this->dao->select('*')->from(TABLE_DEPLOYPRODUCT)->where('deploy')->eq($deployID)->fetchAll();

        return $deploy;
    }

    /**
     * 获取上线申请列表。
     * Get deploy list.
     *
     * @param  int    $productID
     * @param  string $status
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($productID, $status = '', $param = 0, $orderBy = 'id_desc', $pager = null)
    {
        $deployQuery = '';
        if($status == 'bySearch')
        {
            $deployQuery = $this->loadModel('search')->getQuery($param);
            if($deployQuery)
            {
                $this->session->set('deployQuery', $deployQuery->sql);
                $this->session->set('deployForm', $deployQuery->form);
            }
            elseif(!$this->session->deployQuery)
            {
                $this->session->set('deployQuery', ' 1 = 1');
            }

            $deployQuery = $this->session->deployQuery;
            $deployQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $deployQuery);
            $deployQuery = preg_replace('/t1.`(product)`/', 't2.`$1`', $deployQuery);
            $deployQuery = preg_replace('/t1.`(system)`/', 't3.`$1`', $deployQuery);
            if(strpos($deployQuery, "t3.`system` = ''") !== false) $deployQuery = str_replace("t3.`system` = ''", 't3.`system` IS NULL', $deployQuery);
            if(strpos($deployQuery, "t2.`product` = ''") !== false) $deployQuery = str_replace("t2.`product` = ''", 't2.`product` IS NULL', $deployQuery);

            $this->session->set('deployQueryCondition', $deployQuery, $this->app->tab);
            $this->session->set('deployOnlyCondition', true, $this->app->tab);
        }

        $deploys = $this->dao->select("DISTINCT t1.*")->from(TABLE_DEPLOY)->alias('t1')
            ->leftJoin(TABLE_DEPLOYPRODUCT)->alias('t2')->on('t1.id=t2.deploy')
            ->leftJoin(TABLE_RELEASE)->alias('t3')->on("t3.`id`=t2.`release` and t2.`release` > 0 and t3.`deleted` = '0'")
            ->where('t1.deleted')->eq(0)
            ->beginIF($productID)->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF($status != 'all' && $status != 'bySearch' && $status != 'createdbyme')->andWhere('t1.status')->eq($status)->fi()
            ->beginIF($status == 'createdbyme')->andWhere('t1.createdBy')->eq($this->app->user->account)->fi()
            ->beginIF($deployQuery)->andWhere($deployQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id', false);

        $relations = $this->dao->select('`deploy`,`product`,`release`')->from(TABLE_DEPLOYPRODUCT)->where('deploy')->in(array_keys($deploys))->fetchAll();
        $releases  = $this->loadModel('release')->getListByCondition(array_column($relations, 'release'));
        $products  = array();
        $systems   = array();
        foreach($relations as $relation)
        {
            $deployID  = $relation->deploy;
            $productID = $relation->product;
            $releaseID = $relation->release;
            if(!isset($products[$deployID])) $products[$deployID] = array();
            if(!isset($deploys[$deployID]->product)) $deploys[$deployID]->product = '';
            if(!isset($deploys[$deployID]->system))  $deploys[$deployID]->system  = '';
            if($productID && !in_array($productID, $products[$deployID]))
            {
                $deploys[$deployID]->product .= ',' . $productID;
                $products[$deployID][] = $productID;
            }

            if(!isset($systems[$deployID])) $systems[$deployID] = array();
            if(!$releaseID || !isset($releases[$releaseID]) || in_array($releases[$releaseID]->system, $systems[$deployID])) continue;
            $deploys[$deployID]->system .= ',' . $releases[$releaseID]->system;
            $systems[$deployID][] = $releases[$releaseID]->system;
        }
        return $deploys;
    }

    /**
     * 根据步骤ID获取步骤。
     * Get step by ID.
     *
     * @param  int    $stepID
     * @access public
     * @return object|false
     */
    public function getStepByID($stepID)
    {
        return $this->dao->select('*')->from(TABLE_DEPLOYSTEP)->where('id')->eq($stepID)->fetch();
    }

    /**
     * 获取步骤列表。
     * Get step list.
     *
     * @param  int    $deployID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getStepList($deployID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_DEPLOYSTEP)
            ->where('deploy')->eq($deployID)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取上线申请的成员。
     * Get deploy members.
     *
     * @param  int    $deployID
     * @access public
     * @return array
     */
    public function getMembers($deployID)
    {
        $deploy  = $this->dao->select('*')->from(TABLE_DEPLOY)->where('id')->eq($deployID)->fetch();
        $members = $deploy->owner . ',' . $deploy->createdBy . ',' . $deploy->members;
        $users   = $this->dao->select('*')->from(TABLE_USER)->where('account')->in($members)->fetchAll('account');

        $userPairs = array('' => '');
        foreach($users as $account => $user) $userPairs[$account] = empty($user->realname) ? $account : $user->realname;
        return $userPairs;
    }

    public function getLinkableCases($deploy, $productIdList, $type = 'all', $param = 0, $pager = null)
    {
        if($param)
        {
            $query = $this->loadModel('search')->getQuery($param);
            if($query)
            {
                $this->session->set('testcaseQuery', $query->sql);
                $this->session->set('testcaseForm', $query->form);
            }
        }
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $query = $this->session->testcaseQuery;

        $cases = array();
        $linkedCases = $deploy->cases;
        if($type == 'bySearch' || $type == 'all')     $cases = $this->getAllLinkableCases($productIdList, $query, $linkedCases, $pager);
        if($type == 'bysuite') $cases = $this->getLinkableCasesBySuite($productIdList, $query, $param, $linkedCases, $pager);

        return $cases;
    }

    public function getAllLinkableCases($productIdList, $query, $linkedCases, $pager)
    {
        return $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->andWhere('id')->notIN($linkedCases)
                ->andWhere('status')->ne('wait')
                ->andWhere('product')->in($productIdList)
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
    }

    public function getLinkableCasesBySuite($productIdList, $query, $suite, $linkedCases, $pager)
    {
        return $this->dao->select('t1.*,t2.version as version')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_SUITECASE)->alias('t2')->on('t1.id=t2.case')
                ->where($query)
                ->andWhere('t2.suite')->eq((int)$suite)
                ->andWhere('t1.status')->ne('wait')
                ->andWhere('t1.product')->in($productIdList)
                ->beginIF($linkedCases)->andWhere('t1.id')->notIN($linkedCases)->fi()
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
    }

    /**
     * create a deploy plan.
     *
     * @param  object    $formData
     * @access public
     * @return int|bool
     */
    public function create($formData)
    {
        $data = $this->loadModel('file')->processImgURL($formData, $this->config->deploy->editor->create['id'], $this->post->uid);

        $this->dao->insert(TABLE_DEPLOY)->data($data, 'products,uid,release')->autoCheck()->batchCheck($this->config->deploy->create->requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $deployID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $deployID, 'deploy');

            foreach($data->products as $i => $productID)
            {
                if(empty($productID)) continue;

                $deployProduct = new stdclass();
                $deployProduct->deploy  = $deployID;
                $deployProduct->product = (int)$productID;
                $deployProduct->release = zget($data->release, $i, 0);
                $this->dao->replace(TABLE_DEPLOYPRODUCT)->data($deployProduct)->exec();
            }

            return $deployID;
        }
        return false;
    }

    /**
     * update a deploy plan.
     *
     * @param  int $deployID
     * @param  object $formData
     * @access public
     * @return void
     */
    public function update($deployID, $formData)
    {
        $data = $this->loadModel('file')->processImgURL($formData, $this->config->deploy->editor->edit['id'], $this->post->uid);

        $oldDeploy = $this->dao->select('*')->from(TABLE_DEPLOY)->where('id')->eq($deployID)->fetch();
        $oldDeploy->begin   = substr($oldDeploy->begin, 0, 16);
        $oldDeploy->end     = substr($oldDeploy->end, 0, 16);
        $oldDeploy->product = array();
        $oldDeploy->release = array();

        if(isset($data->products))
        {
            $deployProducts = $this->dao->select('*')->from(TABLE_DEPLOYPRODUCT)->where('deploy')->eq($deployID)->fetchAll();
            foreach($deployProducts as $deployProduct)
            {
                $oldDeploy->product[] = $deployProduct->product;
                $oldDeploy->release[] = $deployProduct->release;
            }
            $oldDeploy->product = implode("\n", $oldDeploy->product);
            $oldDeploy->release = implode("\n", $oldDeploy->release);
        }

        $this->dao->update(TABLE_DEPLOY)
            ->data($data, 'products,uid,release,comment')
            ->autocheck()
            ->batchCheck($this->config->deploy->edit->requiredFields, 'notempty')
            ->where('id')
            ->eq($deployID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $deployID, 'deploy');

            if(isset($data->products))
            {
                $this->dao->delete()->from(TABLE_DEPLOYPRODUCT)->where('deploy')->eq($deployID)->exec();
                $products = $releases = array();
                foreach($data->products as $i => $productID)
                {
                    if(empty($productID)) continue;

                    $deployProduct = new stdclass();
                    $deployProduct->deploy  = $deployID;
                    $deployProduct->product = (int)$productID;
                    $deployProduct->release = (int)zget($data->release, $i, 0);

                    $this->dao->replace(TABLE_DEPLOYPRODUCT)->data($deployProduct)->exec();

                    $products[] = $deployProduct->product;
                    $releases[] = $deployProduct->release;
                }
                $data->products = implode("\n", $products);
                $data->release  = implode("\n", $releases);
            }
            return common::createChanges($oldDeploy, $data);
        }
        return false;
    }

    /**
     * 上线申请中关联用例。
     * Link cases on the deploy.
     *
     * @param  int $deployID
     * @access public
     * @return bool
     */
    public function linkCases($deployID)
    {
        $deploy = $this->getByID($deployID);
        $deploy->cases = explode(',', trim($deploy->cases, ','));

        $data   = fixer::input('post')->get();
        $cases  = array_merge($deploy->cases, $data->idList);
        $cases  = array_unique($cases);
        $this->dao->update(TABLE_DEPLOY)->set('cases')->eq(implode(',', $cases))->where('id')->eq($deployID)->exec();

        return !dao::isError();
    }

    /**
     * 管理上线步骤。
     * Manage steps.
     *
     * @param  int    $deployID
     * @param  object $formData
     * @param  array  $oldSteps
     * @access public
     * @return bool
     */
    public function manageStep($deployID, $formData, $oldSteps = array())
    {
        $editSteps = $this->constructSteps($deployID, $formData, $oldSteps);
        if(dao::isError()) return false;

        $deleteSteps = array_diff(array_keys($oldSteps), $editSteps);
        if($deleteSteps)
        {
            foreach($deleteSteps as $stepID) $this->delete(TABLE_DEPLOYSTEP, $stepID);
        }

        return !dao::isError();
    }

    /**
     * 改变一个上线申请的状态。
     * Change a deploy status.
     *
     * @param  int    $deployID
     * @param  string $action
     * @param  object $formData
     * @access public
     * @return array|bool
     */
    public function changeStatus($deployID, $action, $formData)
    {
        $oldDeploy = $this->fetchByID($deployID);
        $status = '';
        if($action == 'finish')   $status = $formData->result;
        if($action == 'activate') $status = 'wait';
        if($action == 'publish')  $status = 'doing';
        if(empty($status)) return false;

        $formData->status = $status;

        $this->dao->update(TABLE_DEPLOY)->data($formData)->where('id')->eq($deployID)->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldDeploy, $formData);
    }

    /**
     * 完成一个步骤。
     * Finish a step.
     *
     * @param  int    $stepID
     * @access public
     * @return array|false
     */
    public function finishStep($stepID)
    {
        $oldStep = $this->getStepByID($stepID);
        $data    = fixer::input('post')
            ->add('status', 'done')
            ->add('finishedBy', $this->app->user->account)
            ->add('finishedDate', helper::now())
            ->remove('comment')
            ->get();
        if($oldStep->assignedTo != $data->assignedTo) $data->assignedDate = helper::now();
        $this->dao->update(TABLE_DEPLOYSTEP)->data($data)->where('id')->eq($stepID)->exec();
        if(!dao::isError()) return common::createChanges($oldStep, $data);
        return false;
    }

    /**
     * 指派一个步骤。
     * Assign a step.
     *
     * @param  int    $stepID
     * @access public
     * @return array|false
     */
    public function assignTo($stepID)
    {
        $oldStep = $this->getStepByID($stepID);
        $data    = fixer::input('post')->remove('comment')->get();
        if($oldStep->assignedTo != $data->assignedTo) $data->assignedDate = helper::now();
        $this->dao->update(TABLE_DEPLOYSTEP)->data($data)->where('id')->eq($stepID)->exec();

        if(!dao::isError()) return common::createChanges($oldStep, $data);
        return false;
    }

    public function updateHostAdd($hosts, $adds)
    {
        $hosts = explode(',', trim($hosts, ','));
        $adds  = explode(',', trim($adds, ','));
        $hosts = array_unique(array_merge($hosts, $adds));
        return implode(',', $hosts);
    }

    public function updateHostRemove($hosts, $removes)
    {
        $hosts   = trim($hosts, ',');
        $hosts   = ",$hosts,";
        $removes = explode(',', trim($removes, ','));
        foreach($removes as $hostID) $hosts = str_replace(",$hostID,", ',', $hosts);
        return trim($hosts, ',');
    }

    /**
     * 修改一个步骤。
     * Update a step.
     *
     * @param  int $stepID
     * @param  object $formData
     * @access public
     * @return array
     */
    public function updateStep($stepID, $formData)
    {
        $oldStep = $this->getStepByID($stepID);

        $this->dao->update(TABLE_DEPLOYSTEP)->data($formData)->where('id')->eq($stepID)->exec();
        return common::createChanges($oldStep, $formData);
    }

    /**
     * 判断是否可以执行操作。
     * Check if the action is clickable.
     *
     * @param  object $deploy
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($deploy, $action)
    {
        $action = strtolower($action);

        if(in_array($action, array('finishstep', 'edit', 'delete'))) return $deploy->status == 'wait';

        if($action == 'activatestep') return $deploy->status != 'wait';
        if($action == 'publish')      return $deploy->status == 'wait' || $deploy->status == 'fail';

        if($action == 'finish')   return $deploy->status == 'doing';
        if($action == 'activate') return $deploy->status == 'success' || $deploy->status == 'fail';

        return true;
    }

    /**
     * 构造新增和更新的步骤。
     * Construct insert and update steps.
     *
     * @param  int    $deployID
     * @param  object $formData
     * @param  array  $oldSteps
     * @access public
     * @return array
     */
    protected function constructSteps($deployID, $formData, $oldSteps)
    {
        $preGrade      = 0;
        $parentStepID  = 0;
        $grandPaStepID = 0;
        $editSteps     = array();
        $now           = helper::now();
        foreach($formData->title as $stepKey => $title)
        {
            /* 跳过步骤描述为空的步骤。 */
            /* If step desc is empty, skip it. */
            if(empty($title)) continue;

            /* 计算步骤层级。 */
            /* Set step grade. */
            $grade  = substr_count((string)$stepKey, '.');
            $stepID = (int)$formData->id[$stepKey];

            /* 如果当前步骤层级为0，父ID和祖父ID清0。 */
            /* If step grade is zero, set parent step id and grand step id to zero. */
            if($grade == 0)
            {
                $parentStepID = $grandPaStepID = 0;
            }
            /* 如果前一个步骤的层级比当前步骤的层级大，将父ID设置为祖父ID，祖父ID清0。 */
            /* If previous step grade is greater than current step grade, set parent step id to grand step id, and set grand step id to zero. */
            elseif($preGrade > $grade)
            {
                $parentStepID  = $grandPaStepID;
                $grandPaStepID = 0;
            }

            if(isset($oldSteps[$stepID]) && !$parentStepID)
            {
                $parentStepID = $oldSteps[$stepID]->parent;
            }

            /* 构建步骤数据，插入步骤。 */
            /* Build step data, and insert it. */
            $step = new stdClass();
            $step->parent  = $parentStepID;
            $step->title   = htmlSpecialString($title);
            $step->content = nl2br(zget($formData->content, $stepKey, ''));
            $step->deploy  = $deployID;
            if(!isset($oldSteps[$stepID]))
            {
                $step->status      = 'wait';
                $step->createdBy   = $this->app->user->account;
                $step->createdDate = $now;

                $stepID = $this->deployTao->createStep($step);
            }
            else
            {
                $editSteps[] = $stepID;
                $this->deployTao->editStep($step, $oldSteps[$stepID]);
            }

            if($grade < 2)
            {
                $grandPaStepID = $parentStepID;
                $parentStepID  = $stepID;
            }

            $preGrade = $grade;
        }
        return $editSteps;
    }

    /**
     * 获取收件人和抄送人。
     * Get toList and ccList.
     *
     * @param  object    $deploy
     * @param  string    $actionType
     * @access public
     * @return array
     */
    public function getToAndCcList($deploy, $actionType)
    {
        $toList = '';
        $ccList = '';
        if($actionType == 'created')
        {
            if($deploy->createdBy != $deploy->owner) $toList = $deploy->owner;
        }
        else
        {
            if($deploy->createdBy != $this->app->user->account) $toList = $deploy->createdBy;
            if($deploy->owner     != $this->app->user->account) $ccList = $deploy->owner;
        }

        return array($toList, $ccList);
    }
}

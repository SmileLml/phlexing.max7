<?php
/**
 * The control file of marketreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hu Fangzhou <hufangzhou@easycorp.ltd>
 * @package     marketreport
 * @link        https://www.zentao.net
 */
class marketreport extends control
{
    /**
     * All market reports.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function all($browseType = 'published', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('marketreport', 'browse', "marketID=0&browseType=$browseType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Browse marketreport list.
     *
     * @param  int    $marketID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($marketID = 0, $browseType = 'published', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('datatable');
        $market = $this->loadModel('market')->getByID($marketID);

        $this->market->setMenu($marketID);

        if($this->app->rawMethod == 'browse')
        {
            $marketIndex = array_search('market', $this->config->marketreport->datatable->defaultField);
            unset($this->config->marketreport->datatable->defaultField[$marketIndex]);
            unset($this->config->marketreport->datatable->fieldList['market']);
        }

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $involved = $this->cookie->involvedReport ? $this->cookie->involvedReport : 0;
        $mode     = $this->app->rawMethod == 'all' ? 'all' : '';

        $this->app->session->set('marketreportList',   $this->app->getURI(true) . "#app={$this->app->tab}", 'market');
        $this->app->session->set('marketreportBrowse', $this->app->getURI(true) . "#app={$this->app->tab}", 'market');

        $this->view->title      = $marketID ? $market->name . '-' . $this->lang->marketreport->browse : $this->lang->marketreport->browse;
        $this->view->marketID   = $marketID;
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->reports    = $this->marketreport->getList($marketID, $mode, $browseType, $orderBy, $involved, $pager);
        $this->view->pager      = $pager;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->markets    = $this->market->getPairs();
        $this->view->researches = $this->loadModel('marketresearch')->getPairs();
        $this->display();
    }

    /**
     * Create report.
     *
     * @access public
     * @return void
     */
    public function create($marketID = 0)
    {
        $this->loadModel('market')->setMenu($marketID);
        if($_POST)
        {
            $reportID = $this->marketreport->create($marketID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('marketreport', $reportID, 'created');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->session->marketreportBrowse ? $this->session->marketreportBrowse : $this->inlink('browse', "marketID=$marketID");;

            $this->send($response);
        }

        $this->view->title        = $this->lang->marketreport->create;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->marketID     = $marketID;
        $this->view->marketList   = arrayUnion(array('' => ''), $this->loadModel('market')->getPairs());
        $this->view->researchList = arrayUnion(array('' => ''), $this->loadModel('marketresearch')->getPairs((int)$marketID));
        $this->display();
    }

    /**
     * Edit a report.
     *
     * @param  int    $reportID
     * @param  int    $fromMarket   currentMarketID, Used to distinguish whether it was accessed within a specific market or from the overall report list.
     * @access public
     * @return void
     */
    public function edit($reportID, $fromMarket = 0)
    {
        if($_POST)
        {
            $changes = $this->marketreport->update($reportID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('marketreport', $reportID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $report     = $this->marketreport->getByID($reportID);
            $locateLink = $this->session->marketreportList ? $this->session->marketreportList : $this->inlink('browse', "marketID={$report->market}");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locateLink));
        }

        $report = $this->marketreport->getByID($reportID);
        $report->participants = explode(',', $report->participants);
        $this->loadModel('market')->setMenu($fromMarket);

        $this->view->title        = $this->lang->marketreport->edit;
        $this->view->report       = $report;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->marketList   = arrayUnion(array('' => ''), $this->loadModel('market')->getPairs());
        $this->view->researchList = arrayUnion(array('' => ''), $this->loadModel('marketresearch')->getPairs($report->market));
        $this->display();
    }

    /**
     * View a report.
     *
     * @param  int    $reportID
     * @param  int    $fromMarket   currentMarketID, Used to distinguish whether it was accessed within a specific market or from the overall report list.
     * @access public
     * @return void
     */
    public function view($reportID, $fromMarket = 0)
    {
        $report = $this->marketreport->getByID($reportID);
        if(!$report) return print(js::error($this->lang->notFound) . js::locate('back'));

        $this->loadModel('market')->setMenu($fromMarket);

        $users        = $this->loadModel('user')->getPairs('noletter|noclosed');
        $participants = '';
        $report->participants = explode(',', $report->participants);
        foreach($report->participants as $participant) $participants .= zget($users, $participant) . ' ';
        $report->participants = trim($participants);

        $this->app->session->set('marketreportList', $this->app->getURI(true) . "#app={$this->app->tab}", 'market');
        $browseLink = $this->session->marketreportBrowse ? $this->session->marketreportBrowse : $this->inlink('browse', "marketID={$report->market}");

        $this->view->title        = $this->lang->marketreport->view;
        $this->view->report       = $report;
        $this->view->users        = $users;
        $this->view->browseLink   = $browseLink;
        $this->view->fromMarket   = $fromMarket;
        $this->view->actions      = $this->loadModel('action')->getList('marketreport', $reportID);
        $this->view->marketList   = $this->loadModel('market')->getPairs('all');
        $this->view->researchList = arrayUnion(array('' => ''), $this->loadModel('marketresearch')->getPairs());
        $this->display();
    }

    /**
     * Publish a report.
     *
     * @param  int    $reportID
     * @param  string $confirm
     * @access public
     * @return int
     */
    public function publish($reportID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->marketreport->confirmPublish, $this->createLink('marketreport', 'publish', "reportID=$reportID&confirm=yes"), ''));
        }

        $this->marketreport->publish($reportID);
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

        $locateLink = $this->session->marketreportList ? $this->session->marketreportList : $this->inlink('browse', "marketID={$report->market}");
        return print(js::locate($locateLink, 'parent'));
    }

    /**
     * Delete a report.
     *
     * @param  int    $reportID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($reportID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->marketreport->confirmDelete, $this->createLink('marketreport', 'delete', "reportID=$reportID&confirm=yes"), ''));
        }
        $this->marketreport->delete(TABLE_MARKETREPORT, $reportID);
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        $locateLink = $this->session->marketreportList ? $this->session->marketreportList : $this->inlink('browse', "marketID={$report->market}");
        return print(js::locate($locateLink, 'parent'));
    }

    /**
     * Print card files.
     *
     * @param  array  $files
     * @param  string $fieldset
     * @param  object $object
     * @param  string $method
     * @param  bool   $showDelete
     * @param  bool   $showEdit
     * @access public
     * @return void
     */
    public function printCardFiles($files, $fieldset, $object = null, $method = 'view', $showDelete = true, $showEdit = true)
    {
        $this->view->files      = $files;
        $this->view->fieldset   = $fieldset;
        $this->view->object     = $object;
        $this->view->method     = $method;
        $this->view->showDelete = $showDelete;
        $this->view->showEdit   = $showEdit;

        if(strpos('view,edit', $method) !== false and $this->app->clientDevice != 'mobile') return $this->display('marketreport', 'viewcardfiles');
        $this->display();
    }

    /**
     * ajax get researchList.
     *
     * @param  int    $marketID
     * @access public
     * @return string
     */
    public function ajaxGetResearchList($marketID)
    {
        $researchList = $this->dao->select('t1.id,t1.name')->from(TABLE_MARKETRESEARCH)->alias('t1')
            ->leftJoin(TABLE_MARKET)->alias('t2')
            ->on('t1.market=t2.id')
            ->where('t1.model')->eq('research')
            ->beginIF(!empty($marketID))->andWhere('t1.market')->eq($marketID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t1.id desc')
            ->fetchAll();

        $researchList = array_map(function($research){
            $option = new stdclass();
            $option->text  = $research->name;
            $option->value = $research->id;
            return $option;
        }, $researchList);

        $emptyResearch = new stdclass();
        $emptyResearch->value = '';
        $emptyResearch->text  = '';
        $researchList[] = $emptyResearch;

        return print(json_encode($researchList));
    }

    /**
     * ajax get market list.
     *
     * @param  int    $researchID
     * @access public
     * @return string
     */
    public function ajaxGetMarketList($researchID)
    {
        if(empty($researchID))
        {
            $marketList = $this->dao->select('id as value,name as text')->from(TABLE_MARKET)->where('deleted')->eq(0)->orderBy('id_desc')->fetchAll();
        }
        else
        {
            $marketList = $this->dao->select('t1.market as value,t2.name as text')->from(TABLE_MARKETRESEARCH)->alias('t1')
                ->leftJoin(TABLE_MARKET)->alias('t2')
                ->on('t1.market=t2.id')
                ->where('t1.id')->eq($researchID)
                ->orderBy('t2.id desc')
                ->fetchAll();
        }

        return print(json_encode($marketList));
    }
}

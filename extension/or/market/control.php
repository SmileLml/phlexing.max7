<?php
/**
 * The control file of market module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tang Hucheng <tanghucheng@easycorp.ltd>
 * @package     market
 * @link        https://www.zentao.net
 */
class market extends control
{
    /**
     * Browse market list.
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
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $this->session->set('marketList', $this->app->getURI(true));
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->market->browse;
        $this->view->markets    = $this->market->getList($browseType, $queryID, $orderBy, $pager);
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->browseType = $browseType;

        $this->display();
    }

    /**
     * Add a market.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $marketID = $this->market->create();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('market', $marketID, 'created');
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse');

            $this->send($response);
        }

        $this->view->title = $this->lang->market->create;
        $this->display();
    }

    /**
     * Edit a market.
     *
     * @access public
     * @return void
     */
    public function edit($marketID)
    {
        $this->market->setMenu($marketID);
        if($_POST)
        {
            $changes = $this->market->update($marketID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('market', $marketID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $locate  = inlink('view', "marketID=$marketID");
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $locate;

            $this->send($response);
        }

        $this->view->title  = $this->lang->market->edit;
        $this->view->market = $this->market->getByID($marketID);
        $this->display();
    }

    /**
     * View a market.
     *
     * @access public
     * @return void
     */
    public function view($marketID)
    {
        $this->market->setMenu($marketID);
        $market = $this->market->getByID($marketID);
        if(!$market) return print(js::error($this->lang->notFound) . js::locate($this->createLink('market', 'browse')));

        $this->view->title       = $this->lang->market->view;
        $this->view->actions     = $this->loadModel('action')->getList('market', $marketID);
        $this->view->market      = $this->market->getByID($marketID);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->reportGroup = $this->market->getReportGroupByID($marketID);

        $this->display();
    }

    /**
     * Delete a market.
     *
     * @param  int    $marketID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($marketID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $market = $this->market->getByID($marketID);
            return print(js::confirm(sprintf($this->lang->market->confirmDelete, $market->name), $this->createLink('market', 'delete', "marketID=$marketID&confirm=yes"), ''));
        }

       $this->market->delete(TABLE_MARKET, $marketID);

       if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

       $locateLink = $this->createLink('market', 'browse');
       return print(js::locate($locateLink, 'parent'));
    }

    /**
     * AJAX: Get market drop menu.
     *
     * @param  int     $marketID
     * @param  string  $module
     * @param  string  $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($marketID, $module, $method)
    {
        $marketPairs = $this->market->getPairs();

        $this->view->link          = $this->market->getMarketLink($module, $method, $marketID);
        $this->view->marketID      = $marketID;
        $this->view->module        = $module;
        $this->view->method        = $method;
        $this->view->marketPairs   = $marketPairs;
        $this->view->marketsPinYin = common::convert2Pinyin($marketPairs);

        $this->display();
    }
}

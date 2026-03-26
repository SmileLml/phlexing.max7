<?php
/**
 * The control file of cmcl module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     cmcl
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class cmcl extends control
{
    /**
     * Browse cmcls.
     *
     * @param  string $type
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($type = 'waterfall', $browseType = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('cmclList',  $uri);

        $this->loadModel('stage')->setMenu($type);;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->cmcl->common . $this->lang->hyphen . $this->lang->cmcl->browse;
        $this->view->position[] = $this->lang->cmcl->browse;
        $this->view->cmcls      = $this->cmcl->getList($browseType, $orderBy, $pager, $type);
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->type       = $type;
        $this->view->method     = $type == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Browse waterfallplus cmcls.
     *
     * @param  string $type
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function waterfallplusBrowse($type = 'waterfallplus', $browseType = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('cmcl', 'browse', "type=waterfallplus&browseType=$browseType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Batch create cmcls.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchCreate($type = 'waterfall')
    {
        $method = $type == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
        $this->loadModel('stage')->setMenu($type);
        if($_POST)
        {
            $this->cmcl->batchCreate($type);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate'] = inlink($method);
            return $this->send($response);
        }

        $this->view->title      = $this->lang->cmcl->common . $this->lang->hyphen . $this->lang->cmcl->batchCreate;
        $this->view->method     = $method;
        $this->view->position[] = $this->lang->cmcl->batchCreate;

        $this->display();
    }

    /**
     * Edit a cmcl.
     *
     * @param  int    $cmclID
     * @access public
     * @return void
     */
    public function edit($cmclID)
    {
        $cmcl   = $this->cmcl->getById($cmclID);
        $method = $cmcl->projectType == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
        $this->loadModel('stage')->setMenu($cmcl->projectType);
        if($_POST)
        {
            $changes = $this->cmcl->update($cmclID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action');
            if(!empty($changes))
            {
                $actionID = $this->action->create('cmcl', $cmclID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['locate'] = inlink($method);
            return $this->send($response);
        }

        $this->view->title      = $this->lang->cmcl->common . $this->lang->hyphen . $this->lang->cmcl->edit;
        $this->view->position[] = $this->lang->cmcl->edit;

        $this->view->cmcl = $cmcl;
        $this->display();
    }

    /**
     * View a cmcl.
     *
     * @param  int    $cmclID
     * @access public
     * @return void
     */
    public function view($cmclID)
    {
        $this->loadModel('action');

        $cmcl         = $this->cmcl->getById($cmclID);
        $browseMethod = $cmcl->projectType == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
        $this->loadModel('stage')->setMenu($cmcl->projectType);

        $this->view->title      = $this->lang->cmcl->common . $this->lang->hyphen . $this->lang->cmcl->view;
        $this->view->position[] = $this->lang->cmcl->view;

        $this->view->cmcl         = $cmcl;
        $this->view->browseMethod = $browseMethod;
        $this->view->actions      = $this->action->getList('cmcl', $cmclID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Delete a cmcl.
     *
     * @param  int    $cmclID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($cmclID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->cmcl->confirm, $this->createLink('cmcl', 'delete', "cmcl=$cmclID&confirm=yes"), ''));
        }
        else
        {
            $cmcl   = $this->cmcl->getById($cmclID);
            $method = $cmcl->projectType == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
            $this->cmcl->delete(TABLE_CMCL, $cmclID);

            die(js::locate(inlink($method), 'parent'));
        }
    }
}

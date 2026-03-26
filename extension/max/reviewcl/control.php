<?php
/**
 * The control file of reviewcl module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     reviewcl
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class reviewcl extends control
{
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('baseline');
    }

    /**
     * Browse reviewcls.
     *
     * @param  string $type
     * @param  string $object PP|QAP|CMP|ITP|URS|SRS|HLDS|DDS|DBDS|ADS|Code|ITTC|STP|STTC|UM
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($type = 'waterfall', $object = 'PP', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session for review check list. */
        $this->session->set('reviewcl', $this->app->getURI(true));

        /* Init pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, $recPerPage, $pageID);

        $this->loadModel('stage')->setMenu($type);

        $this->view->title     = $this->lang->reviewcl->common;
        $this->view->reviewcls = $this->reviewcl->getList($object, $orderBy, $pager, $type);
        $this->view->object    = $object;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->view->type      = $type;
        $this->view->method    = $type == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
        $this->view->users     = $this->loadModel('user')->getPairs('noclosedo|noletter');
        $this->display();
    }

    /**
     * Waterfallplus browse reviewcls.
     *
     * @param  string $type
     * @param  string $object PP|QAP|CMP|ITP|URS|SRS|HLDS|DDS|DBDS|ADS|Code|ITTC|STP|STTC|UM
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function waterfallplusBrowse($type = 'waterfallplus', $object = 'PP', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('reviewcl', 'browse', "type=waterfallplus&object=$object&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Set the category items for the reviewcl.
     *
     * @param  string $lang2set
     * @param  string $type
     * @access public
     * @return void
     */
    public function setCategory($type = 'waterfall', $lang2set = '')
    {
        $this->app->loadLang('admin');
        $this->loadModel('custom');
        $lang    = $this->app->getClientLang();
        $section = $type . 'CategoryList';
        unset($this->lang->admin->menuList->model['tabMenu'][$type]['reviewcl']['subModule']);

        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->loadModel('custom')->deleteItems("lang={$data->lang}&module=reviewcl&section={$section}");

            foreach($data->keys as $index => $key)
            {
                $value = $data->values[$index];
                if(!$value or !$key) continue;
                $this->custom->setItem("{$data->lang}.reviewcl.{$section}.{$key}", $value);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('reviewcl', 'setcategory', "type=$type" . '&lang2set=' . ($data->lang == 'all' ? $data->lang : ''))));
        }

        $this->loadModel('stage')->setMenu($type);
        $this->view->title       = $this->lang->reviewcl->clcategory;
        $this->view->currentLang = $lang;
        $this->view->section     = $section;
        $this->view->lang2Set    = !empty($lang2Set) ? $lang2Set : $lang;
        $this->display();
    }

    /**
     * Create a reviewcl.
     *
     * @param  string $type
     * @param  string $object
     * @access public
     * @return void
     */
    public function create($type = 'waterfall', $object = '')
    {
        if($_POST)
        {
            $reviewclID = $this->reviewcl->create($type);

            if(!dao::isError())
            {
                $reviewcl = $this->reviewcl->getByID($reviewclID);

                $this->loadModel('action')->create('reviewcl', $reviewclID, 'Opened');
                $method = $type == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
                $response['result']  = 'success';
                $response['message'] = $this->lang->saveSuccess;
                $response['locate']  = inlink($method, "type={$type}&object={$reviewcl->object}");
                return $this->send($response);
            }

            $response['result']  = 'fail';
            $response['message'] = dao::getError();
            return $this->send($response);
        }

        $this->loadModel('stage')->setMenu($type);

        $this->view->title  = $this->lang->reviewcl->create;
        $this->view->object = $object;
        $this->view->type   = $type;

        $this->display();
    }

    /**
     * Batch create reviewcls.
     *
     * @param  string $type
     * @param  string $object
     * @access public
     * @return void
     */
    public function batchCreate($type = 'waterfall', $object = '')
    {
        if($_POST)
        {
            $this->reviewcl->batchCreate($type);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $method = $type == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink($method, "type=$type&object=");

            return $this->send($response);
        }

        $this->loadModel('stage')->setMenu($type);

        $this->view->title      = $this->lang->reviewcl->batchCreate;
        $this->view->position[] = $this->lang->reviewcl->batchCreate;
        $this->view->object     = $object;
        $this->view->type       = $type;

        $this->display();
    }

    /**
     * Edit a reviewcl.
     *
     * @param  int    $reviewclID
     * @access public
     * @return void
     */
    public function edit($reviewclID = 0)
    {
        if($_POST)
        {
            $changes = $this->reviewcl->update($reviewclID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('reviewcl', $reviewclID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('reviewcl', 'view', "id=$reviewclID");
            return $this->send($response);
        }

        $reviewcl = $this->reviewcl->getByID($reviewclID);
        $this->loadModel('stage')->setMenu($reviewcl->type);

        $this->view->title      = $this->lang->reviewcl->edit;
        $this->view->position[] = $this->lang->reviewcl->edit;

        $this->view->reviewcl = $reviewcl;

        $this->display();
    }

    /**
     * Delete a reviewcl.
     *
     * @param  int    $reviewclID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($reviewclID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->reviewcl->confirmDelete, inlink('delete', "reviewclID=$reviewclID&confirm=yes")));
        }
        else
        {
            $this->reviewcl->delete(TABLE_REVIEWCL, $reviewclID);

            die(js::locate($this->session->reviewcl, 'parent'));
        }
    }

    /**
     * View a reviewcl.
     *
     * @param  int    $reviewclID
     * @access public
     * @return void
     */
    public function view($reviewclID = 0)
    {
        $reviewcl = $this->reviewcl->getByID($reviewclID);
        $this->loadModel('stage')->setMenu($reviewcl->type);

        $this->view->title    = $this->lang->reviewcl->view;

        $this->view->reviewcl = $reviewcl;
        $this->view->actions  = $this->loadModel('action')->getList('reviewcl', $reviewclID);
        $this->view->users    = $this->loadModel('user')->getPairs('noclosedo|noletter');
        $this->view->method   = $reviewcl->type == 'waterfall' ? 'browse' : 'waterfallplusBrowse';

        $this->display();
    }
}

<?php
/**
 * The control file of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     meetingroom
 * @version     $Id: control.php 5107 2021-06-09 10:40:12Z lyc $
 * @link        https://www.zentao.net
 */
class meetingroom extends control
{
    /**
     * Browse meeting rooms.
     *
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('roomList', $uri, 'admin');

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('meetingroom', 'browse', "browseType=bysearch&queryID=myQueryID");
        $this->meetingroom->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->meetingroom->common . $this->lang->hyphen . $this->lang->meetingroom->browse;
        $this->view->position[] = $this->lang->meetingroom->browse;
        $this->view->rooms      = $this->meetingroom->getList($browseType, $param, $orderBy, $pager);
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Create a meeting.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $roomID = $this->meetingroom->create();

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(!$roomID)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('meetingroom', $roomID, 'Opened');

            /* Return meeting room id when call the API. */
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $roomID));

            $response['locate'] = inlink('browse');
            return $this->send($response);
        }

        $this->view->title      = $this->lang->meetingroom->common . $this->lang->hyphen . $this->lang->meetingroom->create;
        $this->view->position[] = $this->lang->meetingroom->create;
        $this->display();
    }

    /**
     * Edit a meeting room.
     *
     * @param  int    $roomID
     * @access public
     * @return void
     */
    public function edit($roomID)
    {
        if($_POST)
        {
            $changes = $this->meetingroom->update($roomID);

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
                $actionID = $this->action->create('meetingroom', $roomID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['locate'] = inlink('view', "roomID=$roomID");
            return $this->send($response);
        }

        $this->view->title      = $this->lang->meetingroom->common . $this->lang->hyphen . $this->lang->meetingroom->edit;
        $this->view->position[] = $this->lang->meetingroom->edit;
        $this->view->room       = $this->meetingroom->getById($roomID);
        $this->display();
    }

    /**
     * Delete a meeting room.
     *
     * @param  int    $roomID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($roomID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->meetingroom->confirmDelete, $this->createLink('meetingroom', 'delete', "roomID=$roomID&confirm=yes"), ''));
        }
        else
        {
            $now      = strtotime(helper::now());
            $meetings = $this->loadModel('meeting')->getListByRoom('future', $roomID);
            if(!empty($meetings)) die(js::alert($this->lang->meetingroom->booked));

            $this->meetingroom->delete(TABLE_MEETINGROOM, $roomID);

            die(js::locate(inlink('browse'), 'parent'));
        }
    }

    /**
     * Batch create meeting rooms.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        if($_POST)
        {
            $roomIDList = $this->meetingroom->batchCreate();

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            /* Return meeting room id list when call the API. */
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $roomIDList));

            $response['locate'] = inlink('browse');
            return $this->send($response);
        }

        $this->view->title      = $this->lang->meetingroom->common . $this->lang->hyphen . $this->lang->meetingroom->batchCreate;
        $this->view->position[] = $this->lang->meetingroom->batchCreate;

        $this->display();
    }

    /**
     * Batch edit meeting rooms.
     *
     * @access public
     * @return void
     */
    public function batchEdit()
    {
         if($this->post->name)
         {
             $allChanges = $this->meetingroom->batchUpdate();
             if($allChanges)
             {
                 $this->loadModel('action');
                 foreach($allChanges as $roomID => $changes )
                 {
                     if(empty($changes)) continue;

                     $actionID = $this->action->create('meetingroom', $roomID, 'Edited');
                     $this->action->logHistory($actionID, $changes);
                 }
             }

             die(js::locate(inlink('browse'), 'parent'));
         }

         $roomIDList = $this->post->roomIDList ? $this->post->roomIDList : die(js::locate(inlink('browse')));
         $roomIDList = array_unique($roomIDList);

         $this->view->title = $this->lang->meetingroom->common . $this->lang->hyphen . $this->lang->meetingroom->batchEdit;
         $this->view->rooms = $this->meetingroom->getByList($roomIDList);

         $this->display();
    }

    /**
     * View a meeting room.
     *
     * @param  int    $roomID
     * @access public
     * @return void
     */
    public function view($roomID)
    {
        $roomID = (int)$roomID;
        $room   = $this->meetingroom->getById($roomID);
        if(empty($room)) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->view->title      = $this->lang->meetingroom->common . $this->lang->hyphen . $this->lang->meetingroom->view;
        $this->view->position[] = $this->lang->meetingroom->view;
        $this->view->room       = $room;
        $this->view->actions    = $this->loadModel('action')->getList('meetingroom', $roomID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }
}

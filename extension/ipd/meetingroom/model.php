<?php
/**
 * The model file of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     meetingroom
 * @version     $Id: model.php 5079 2021-06-09 10:49:22Z lyc $
 * @link        https://www.zentao.net
 */
?>
<?php
class meetingroomModel extends model
{
    /**
     * Create a meeting room.
     *
     * @access public
     * @return bool|int
     */
    public function create()
    {
        $room = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();
        $room->equipment = !empty($room->equipment) ? implode(',', $room->equipment) : '';
        $room->openTime  = !empty($room->openTime) ? implode(',', $room->openTime) : '';

        $this->dao->insert(TABLE_MEETINGROOM)->data($room)->autoCheck()->batchCheck($this->config->meetingroom->create->requiredFields, 'notempty')->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Update a meeting room.
     *
     * @param  int    $roomID
     * @access public
     * @return array|bool
     */
    public function update($roomID)
    {
        $oldRoom = $this->dao->select('*')->from(TABLE_MEETINGROOM)->where('id')->eq($roomID)->fetch();

        $room = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $room->equipment = !empty($room->equipment) ? implode(',', $room->equipment) : '';
        $room->openTime  = !empty($room->openTime) ? implode(',', $room->openTime) : '';
        $this->dao->update(TABLE_MEETINGROOM)->data($room)
            ->autoCheck()
            ->batchCheck($this->config->meetingroom->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$roomID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldRoom, $room);
        return false;
    }

    /**
     * Batch create meeting room.
     *
     * @access public
     * @return array
     */
    public function batchCreate()
    {
        $data = fixer::input('post')->get();

        $this->loadModel('action');
        $roomIDList     = array();
        $requiredFields = trim($this->config->meetingroom->create->requiredFields, ',');
        foreach($data->name as $i => $name)
        {
            if(!$name) continue;

            $room = new stdclass();
            $room->name        = $name;
            $room->position    = $data->position[$i];
            $room->seats       = $data->seats[$i];
            $room->equipment   = empty($data->equipment[$i]) ? '' : implode(',', $data->equipment[$i]);
            $room->openTime    = empty($data->openTime[$i]) ? '' : implode(',', $data->openTime[$i]);
            $room->createdBy   = $this->app->user->account;
            $room->createdDate = helper::now();

            foreach(explode(',', $requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!empty($room->$field)) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->meetingroom->$field);
                return false;
            }

            $this->dao->insert(TABLE_MEETINGROOM)->data($room)->autoCheck()->exec();

            $roomID = $this->dao->lastInsertID();
            $this->action->create('meetingroom', $roomID, 'Opened');

            $roomIDList[] = $roomID;
        }

        return $roomIDList;
    }

    /**
     * Batch edit meeting room.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $allChanges     = array();
        $now            = helper::now();
        $data           = fixer::input('post')->get();
        $roomIDList     = $this->post->roomIDList;
        $oldRooms       = $roomIDList ? $this->getByList($roomIDList) : array();
        $requiredFields = trim($this->config->meetingroom->create->requiredFields, ',');
        foreach($roomIDList as $roomID)
        {
            $oldRoom = $oldRooms[$roomID];
            $oldRoom->equipment = implode(',', $oldRoom->equipment);
            $oldRoom->openTime  = implode(',', $oldRoom->openTime);

            $room = new stdclass();
            $room->name       = $data->name[$roomID];
            $room->position   = $data->position[$roomID];
            $room->seats      = $data->seats[$roomID];
            $room->equipment  = empty($data->equipment[$roomID]) ? '' : implode(',', $data->equipment[$roomID]);
            $room->openTime   = empty($data->openTime[$roomID]) ? '' : implode(',', $data->openTime[$roomID]);
            $room->editedBy   = $this->app->user->account;
            $room->editedDate = $now;

            foreach(explode(',', $requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!empty($room->$field)) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->meetingroom->$field);
                die(js::error('room#' . $roomID . dao::getError(true)));
            }

            $this->dao->update(TABLE_MEETINGROOM)->data($room)
                ->autoCheck()
                ->where('id')->eq((int)$roomID)
                ->exec();

            if(!dao::isError())
            {
                $allChanges[$roomID] = common::createChanges($oldRoom, $room);
            }
            else
            {
                die(js::error('room#' . $roomID . dao::getError(true)));
            }
        }
        return $allChanges;
    }

    /**
     * Get meeting rooms.
     *
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($browseType = '', $param = '', $orderBy = 'id_desc', $pager = null)
    {
        if($browseType == 'bysearch') return $this->getBySearch($param, $orderBy, $pager);

        $rooms = $this->dao->select('*')->from(TABLE_MEETINGROOM)
            ->where('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        /* Format rooms. */
        foreach($rooms as $room) $room = $this->formatRoom($room);
        return $rooms;
    }

    /**
     * Get pairs of meeting room .
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->dao->select('id,name')->from(TABLE_MEETINGROOM)
            ->where('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Get meeting rooms by id list.
     *
     * @param  array|string|int $roomIDList
     * @access public
     * @return array
     */
    public function getByList($roomIDList = '')
    {
        $rooms = $this->dao->select('*')->from(TABLE_MEETINGROOM)
            ->where('deleted')->eq(0)
            ->beginIF($roomIDList)->andWhere('id')->in($roomIDList)->fi()
            ->fetchAll('id');

        /* Format rooms. */
        foreach($rooms as $room) $room = $this->formatRoom($room);
        return $rooms;
    }

    /**
     * Get meeting room by roomID.
     *
     * @param  int    $roomID
     * @access public
     * @return object
     */
    public function getByID($roomID)
    {
        $room = $this->dao->select('*')->from(TABLE_MEETINGROOM)->where('id')->eq($roomID)->fetch();
        return $this->formatRoom($room);
    }

    /**
     * Get meeting rooms by search.
     *
     * @param  string $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getBySearch($queryID = '', $orderBy = 'id_desc', $pager = null)
    {
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('meetingroomQuery', $query->sql);
                $this->session->set('meetingroomForm', $query->form);
            }
            else
            {
                $this->session->set('meetingroomQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->meetingroomQuery == false) $this->session->set('meetingroomQuery', ' 1 = 1');
        }

        $meetingroomQuery = $this->session->meetingroomQuery;

        $rooms = $this->dao->select('*')->from(TABLE_MEETINGROOM)
            ->where($meetingroomQuery)
            ->andWhere('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        /* Format rooms. */
        foreach($rooms as $room) $room = $this->formatRoom($room);
        return $rooms;
    }

    /**
     * Format room info.
     *
     * @param  object $room
     * @access public
     * @return object
     */
    public function formatRoom($room)
    {
        $room->equipment = explode(',', $room->equipment);
        $room->openTime  = explode(',', $room->openTime);

        $equipmentList = array();
        $openTimeList  = array();
        foreach($room->equipment as $equipment) $equipmentList[] = zget($this->lang->meetingroom->equipmentList, $equipment);
        foreach($room->openTime as $openTime)   $openTimeList[]  = zget($this->lang->meetingroom->openTimeList, $openTime);

        $room->equipmentName = implode(',', $equipmentList);
        $room->openTimeName  = implode(',', $openTimeList);

        return $room;
    }

    /**
     * Build search form.
     *
     * @param  string $actionURL
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->meetingroom->search['actionURL'] = $actionURL;
        $this->config->meetingroom->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->meetingroom->search);
    }
}

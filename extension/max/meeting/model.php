<?php
/**
 * The model file of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     meeting
 * @version     $Id: model.php 5079 2021-06-09 10:49:22Z lyc $
 * @link        https://www.zentao.net
 */
?>
<?php
class meetingModel extends model
{
    /**
     * Create a metting.
     *
     * @param  int    $projectID
     * @access public
     * @return int|bool
     */
    public function create($projectID)
    {
        $meeting = fixer::input('post')
            ->setDefault('project', $projectID)
            ->setDefault('room, objectID, execution, dept', 0)
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('participant', array())
            ->remove('files,labels,contactListMenu')
            ->get();

        $meeting->date  = date(DT_DATE1, strtotime($meeting->begin));
        $meeting->begin = date(DT_TIME2, strtotime($meeting->begin));
        $meeting->end   = date(DT_TIME2, strtotime($meeting->end));

        $regEx = '/([0-1][0-9]|2[0-3]):([0-5][0-9])/';
        if(!preg_match($regEx, $meeting->begin) or !preg_match($regEx, $meeting->end))
        {
            if(!preg_match($regEx, $meeting->begin)) dao::$errors['begin'] = sprintf($this->lang->meeting->errorTime, $this->lang->meeting->begin);
            if(!preg_match($regEx, $meeting->end))   dao::$errors['end']   = sprintf($this->lang->meeting->errorTime, $this->lang->meeting->end);
            return false;
        }

        if($meeting->begin >= $meeting->end)
        {
            dao::$errors['message'][] = $this->lang->meeting->errorBegin;
            return false;
        }

        /* Whether the meeting is booked. */
        if(!empty($meeting->room))
        {
            $week = date('w', strtotime($meeting->date));
            if($week == 0) $week = 7;

            $room = $this->loadModel('meetingroom')->getByID($meeting->room);
            if(!in_array($week, $room->openTime))
            {
                dao::$errors['message'][] = $this->lang->meeting->notOpenTime;
                return false;
            }

            $bookedRoom = $this->dao->select('id')->from(TABLE_MEETING)
                ->where('room')->eq($meeting->room)
                ->andWhere('`date`')->eq($meeting->date)
                ->andWhere('deleted')->eq(0)
                ->andWhere('(`begin`')->between($meeting->begin, $meeting->end)
                ->orWhere('`end`')->between($meeting->begin, $meeting->end)
                ->orWhere('(`begin`')->lt($meeting->begin)
                ->andWhere('`end`')->gt($meeting->end)
                ->markRight(2)
                ->fetch();

            if($bookedRoom)
            {
                dao::$errors['message'][] = $this->lang->meeting->booked;
                return false;
            }
        }

        if(isset($meeting->participant))
        {
            $meeting->participant = implode(',', $meeting->participant);
            $meeting->participant = $meeting->participant ? ',' . trim($meeting->participant, ',') . ',' : '';
        }

        if($meeting->execution and !$meeting->project)
        {
            $execution        = $this->loadModel('execution')->getById($meeting->execution);
            $meeting->project = $execution->project;
        }

        $this->dao->insert(TABLE_MEETING)->data($meeting, 'contactList')
            ->autoCheck()
            ->batchCheck($this->config->meeting->create->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $meetingID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('meeting', $meetingID);

            $now         = helper::now();
            $participant = explode(',', $meeting->participant);
            if(!in_array($meeting->host, $participant)) $participant[] = $meeting->host;

            $this->loadModel('action');
            foreach($participant as $account)
            {
                if($account == '') continue;

                $todo = new stdclass();
                $todo->account      = $account;
                $todo->date         = $meeting->date;
                $todo->begin        = str_replace(':', '', $meeting->begin);
                $todo->end          = str_replace(':', '', $meeting->end);
                $todo->type         = 'meeting';
                $todo->objectID     = $meetingID;
                $todo->pri          = 3;
                $todo->name         = $meeting->name;
                $todo->status       = "wait";
                $todo->private      = 0;
                $todo->assignedBy   = $this->app->user->account;
                $todo->assignedTo   = $account;
                $todo->assignedDate = $now;

                $this->dao->insert(TABLE_TODO)->data($todo)->exec();

                $todoID = $this->dao->lastInsertID();
                $this->action->create('todo', $todoID, 'opened');
            }
            return $meetingID;
        }
        return false;
    }

    /**
     * Edit a metting.
     *
     * @param  int    $meetingID
     * @access public
     * @return bool
     */
    public function update($meetingID)
    {
        $oldMeeting = $this->getByID($meetingID);

        $meeting = fixer::input('post')
            ->setDefault('room', 0)
            ->setDefault('objectID', 0)
            ->setDefault('dept', 0)
            ->setDefault('execution', 0)
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', helper::now())
            ->setIF(!$this->post->participant, 'participant', array())
            ->remove('files,labels,contactListMenu')
            ->get();

        $meeting->date  = date(DT_DATE1, strtotime($meeting->begin));
        $meeting->begin = date(DT_TIME2, strtotime($meeting->begin));
        $meeting->end   = date(DT_TIME2, strtotime($meeting->end));

        $regEx = '/([0-1][0-9]|2[0-3]):([0-5][0-9])/';
        if(!preg_match($regEx, $meeting->begin) or !preg_match($regEx, $meeting->end))
        {
            if(!preg_match($regEx, $meeting->begin)) dao::$errors['begin'] = sprintf($this->lang->meeting->errorTime, $this->lang->meeting->begin);
            if(!preg_match($regEx, $meeting->end))   dao::$errors['end']   = sprintf($this->lang->meeting->errorTime, $this->lang->meeting->end);
            return false;
        }

        if($meeting->begin >= $meeting->end)
        {
            dao::$errors['message'][] = $this->lang->meeting->errorBegin;
            return false;
        }

        /* Determine if the meeting room is available for reservation. */
        if(!empty($meeting->room))
        {
            $week = date('w', strtotime($meeting->date));
            if($week == 0) $week = 7;

            $room = $this->loadModel('meetingroom')->getByID($meeting->room);
            if(!in_array($week, $room->openTime))
            {
                dao::$errors['message'][] = $this->lang->meeting->notOpenTime;
                return false;
            }

            $bookedRoom = $this->dao->select('id')->from(TABLE_MEETING)
                ->where('room')->eq($meeting->room)
                ->andWhere('id')->ne($meetingID)
                ->andWhere('`date`')->eq($meeting->date)
                ->andWhere('deleted')->eq(0)
                ->andWhere('(`begin`')->between($meeting->begin, $meeting->end)
                ->orWhere('`end`')->between($meeting->begin, $meeting->end)
                ->orWhere('(`begin`')->lt($meeting->begin)
                ->andWhere('`end`')->gt($meeting->end)
                ->markRight(2)
                ->fetch();

            if($bookedRoom)
            {
                dao::$errors['message'][] = $this->lang->meeting->booked;
                return false;
            }
        }

        /* Update meeting info. */
        if(isset($meeting->participant))
        {
            $meeting->participant = implode(',', $meeting->participant);
            $meeting->participant = $meeting->participant ? ',' . trim($meeting->participant, ',') . ',' : '';
        }

        if($meeting->execution and !$meeting->project)
        {
            $execution        = $this->loadModel('execution')->getById($meeting->execution);
            $meeting->project = $execution->project;
        }

        $this->dao->update(TABLE_MEETING)->data($meeting, 'renameFiles,deleteFiles,contactList')
            ->where('id')->eq($meetingID)
            ->autoCheck()
            ->batchCheck($this->config->meeting->edit->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $oldMeeting->participant = implode(',', $oldMeeting->participant);

            $this->loadModel('file')->processFileDiffsForObject('meeting', $oldMeeting, $meeting);
            $changes = common::createChanges($oldMeeting, $meeting);

            if(!empty($changes))
            {
                /* Delete old todos and add new todos. */
                $this->dao->update(TABLE_TODO)
                    ->set('deleted')->eq(1)
                    ->where('type')->eq('meeting')
                    ->andWhere('objectID')->eq($meetingID)
                    ->exec();

                $now         = helper::now();
                $participant = explode(',', $meeting->participant);
                if(!in_array($meeting->host, $participant)) $participant[] = $meeting->host;

                $this->loadModel('action');
                foreach($participant as $account)
                {
                    if($account == '') continue;

                    $todo = new stdclass();
                    $todo->account      = $account;
                    $todo->date         = $meeting->date;
                    $todo->begin        = str_replace(':', '', substr($meeting->begin, 0, 5));
                    $todo->end          = str_replace(':', '', substr($meeting->end, 0, 5));
                    $todo->type         = 'meeting';
                    $todo->objectID     = $meetingID;
                    $todo->pri          = 3;
                    $todo->name         = $meeting->name;
                    $todo->status       = "wait";
                    $todo->private      = 0;
                    $todo->assignedBy   = $this->app->user->account;
                    $todo->assignedTo   = $account;
                    $todo->assignedDate = $now;

                    $this->dao->insert(TABLE_TODO)->data($todo)->exec();

                    $todoID = $this->dao->lastInsertID();
                    $this->action->create('todo', $todoID, 'opened');
                }
            }
            return $changes;
        }
        return false;
    }

    /**
     * Minutes a meeting.
     *
     * @param  int    $meetingID
     * @access public
     * @return array|bool
     */
    public function minutes($meetingID)
    {
        $oldMeeting = $this->getById($meetingID);

        $meeting = fixer::input('post')
            ->setDefault('minutedBy', $this->app->user->account)
            ->setDefault('minutedDate', helper::now())
            ->setDefault('deleteFiles', array())
            ->stripTags($this->config->meeting->editor->minutes['id'], $this->config->allowedTags)
            ->remove('uid,labels,files,minutesFiles')
            ->get();
        $meeting = $this->loadModel('file')->processImgURL($meeting, $this->config->meeting->editor->minutes['id'], $this->post->uid);

        $this->dao->update(TABLE_MEETING)->data($meeting, 'deleteFiles')->autoCheck()->where('id')->eq($meetingID)->exec();

        if(!dao::isError())
        {
            $oldMeeting->participant = implode(',', $oldMeeting->participant);

            $this->file->processFile4Object('meeting', $oldMeeting, $meeting);
            return common::createChanges($oldMeeting, $meeting);
        }
    }

    /**
     * Get meeting by id.
     *
     * @param  int    $meetingID
     * @access public
     * @return object|bool
     */
    public function getByID($meetingID)
    {
        $meeting = $this->dao->select('*')->from(TABLE_MEETING)
            ->where('id')->eq($meetingID)
            ->fetch();

        if(!$meeting) return false;

        $meeting = $this->loadModel('file')->replaceImgURL($meeting, 'minutes');

        $meeting->participant = explode(',', trim($meeting->participant, ','));
        $users = $this->loadModel('user')->getPairs('all,noletter');

        foreach($meeting->participant as $account) $participantList[] = zget($users, $account);
        $meeting->participantName = implode(',', $participantList);
        $meeting->files           = $this->loadModel('file')->getByObject('meeting', $meeting->id);

        /* Get linked object name. */
        $meeting->objectName = '';
        if($meeting->objectID)
        {
            $this->app->loadConfig('action');
            $field = $this->config->action->objectNameFields[$meeting->objectType];
            $table = $this->config->objectTables[$meeting->objectType];
            $meeting->objectName = $this->dao->select($field)->from($table)->where('id')->eq($meeting->objectID)->fetch($field);
        }

        return $meeting;
    }

    /**
     * Get meeting list.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($projectID = '', $browseType = '', $param = '', $orderBy = 'id_desc', $pager = null)
    {
        if($browseType == 'bysearch') return $this->getBySearch($projectID, $param, $orderBy, $pager);

        return $this->dao->select('*')->from(TABLE_MEETING)
            ->where('deleted')->eq(0)
            ->beginIF($this->app->tab == 'project' && $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab == 'execution' && $projectID)->andWhere('execution')->eq($projectID)->fi()
            ->beginIF($browseType == 'booked')->andwhere('createdBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'participate')
            ->andwhere('(host')->eq($this->app->user->account)
            ->orWhere('participant')->like('%,' . $this->app->user->account . ',%')
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get meeting list by user.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  string $param
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getListByUser($browseType = '', $orderBy = 'id_desc', $param = '', $pager = null)
    {
        if($browseType == 'bysearch') return $this->getBySearch(0, $param, $orderBy, $pager);

        $today = helper::today();
        $now   = date('H:i:s', strtotime(helper::now()));

        return $this->dao->select('t1.*')->from(TABLE_MEETING)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted', true)->eq(0)
            ->orWhere('t2.id IS NULL')
            ->markRight(1)
            ->beginIF($browseType == 'futureMeeting')
            ->andWhere('(t1.date')->gt($today)
            ->orWhere('(t1.begin')->gt($now)
            ->andWhere('t1.date')->eq($today)
            ->markRight(2)
            ->fi()
            ->beginIF($browseType == 'all')
            ->andWhere('(t1.createdBy')->eq($this->app->user->account)
            ->orWhere('t1.host')->eq($this->app->user->account)
            ->orWhere('t1.participant')->like('%,' . $this->app->user->account . ',%')
            ->markRight(1)
            ->fi()
            ->beginIF($browseType == 'futureMeeting' or $browseType == 'participate')
            ->andwhere('(t1.host')->eq($this->app->user->account)
            ->orWhere('t1.participant')->like('%,' . $this->app->user->account . ',%')
            ->markRight(1)
            ->fi()
            ->beginIF($browseType == 'booked')->andwhere('t1.createdBy')->eq($this->app->user->account)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Gets objects that can be associated.
     *
     * @param  int    $projectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getObjectsByType($projectID = 0, $objectType = '')
    {
        $objects = array();
        if(!$objectType) return $objects;

        if($projectID)
        {
            if($objectType == 'story')
            {
                $objects = $this->dao->select('t1.id,t1.title')->from(TABLE_STORY)->alias('t1')
                    ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
                    ->where('t2.project')->eq($projectID)
                    ->andWhere('t1.deleted')->eq(0)
                    ->fetchPairs();
            }
            else
            {
                $this->app->loadConfig('action');
                $field   = $this->config->action->objectNameFields[$objectType];
                $objects = $this->dao->select("id,$field")->from($this->config->objectTables[$objectType])
                    ->where('project')->eq($projectID)
                    ->andWhere('deleted')->eq(0)
                    ->fetchPairs();
            }
        }
        else
        {
            $this->app->loadConfig('action');
            $field   = $this->config->action->objectNameFields[$objectType];
            $objects = $this->dao->select("id,$field")->from($this->config->objectTables[$objectType])
                ->where('deleted')->eq(0)
                ->andWhere('(' . $this->config->meeting->objectCreatedField[$objectType])->eq($this->app->user->account)
                ->orWhere('assignedTo')->eq($this->app->user->account)
                ->markRight(1)
                ->fetchPairs();
        }

        return $objects;
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL, $projectID = 0)
    {
        $this->app->loadClass('date');
        $project = $this->loadModel('project')->getByID($projectID);

        $this->config->meeting->search['actionURL'] = $actionURL;
        $this->config->meeting->search['queryID']   = $queryID;

        $this->config->meeting->search['params']['execution']['values'] = arrayUnion(array('' => ''), $this->loadModel('execution')->getPairs($projectID));
        $this->config->meeting->search['params']['project']['values']   = arrayUnion(array('' => ''), $this->loadModel('project')->getPairsByProgram());
        $this->config->meeting->search['params']['room']['values']      = arrayUnion(array('' => ''), $this->loadModel('meetingroom')->getPairs());
        $this->config->meeting->search['params']['dept']['values']      = arrayUnion(array('' => ''), $this->loadModel('dept')->getOptionMenu());

        if($this->app->tab == 'project' and isset($project->model) and $project->model == 'waterfall')
        {
            $this->config->meeting->search['fields']['type'] = $this->lang->meeting->type;
            $this->config->meeting->search['params']['type']['values'] = arrayUnion(array('' => ''), $this->loadModel('pssp')->getProcesses($projectID));
        }

        if($this->app->tab != 'my')
        {
            unset($this->config->meeting->search['fields']['project']);
            unset($this->config->meeting->search['fields']['execution']);
            unset($this->config->meeting->search['params']['project']);
            unset($this->config->meeting->search['params']['execution']);
        }

        $times    = date::buildTimeList(0, 23, 10);
        $timeList = array();
        foreach($times as $time) $timeList[$time] = $time;

        $this->config->meeting->search['params']['begin']['values'] = arrayUnion(array('' => ''), $timeList);
        $this->config->meeting->search['params']['end']['values']   = arrayUnion(array('' => ''), $timeList);
        if($this->app->rawMethod == 'work') $this->config->meeting->search['module'] = 'workMeeting';
        $this->loadModel('search')->setSearchParams($this->config->meeting->search);
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $meeting
     * @param  string    $actionType
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($meeting, $actionType)
    {
        /* Set toList and ccList. */
        $toList = $meeting->host;
        $ccList = str_replace(' ', '', trim(implode(',', $meeting->participant), ','));

        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }

        return array($toList, $ccList);
    }

    /**
     * Get meetings by search.
     *
     * @param  int    $projectID
     * @param  string $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySearch($projectID = 0, $queryID = '', $orderBy = 'id_desc', $pager = null)
    {
        $meetingQuery = $this->app->rawMethod == 'work' ? 'workMeetingQuery' : 'meetingQuery';
        $meetingForm  = $this->app->rawMethod == 'work' ? 'workMeetingForm'  : 'meetingForm';
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($meetingQuery, $query->sql);
                $this->session->set($meetingForm, $query->form);
            }
            else
            {
                $this->session->set($meetingQuery, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->{$meetingQuery} == false) $this->session->set($meetingQuery, ' 1 = 1');
        }

        $sql     = $this->session->{$meetingQuery};
        $account = $this->app->user->account;
        $today   = helper::today();
        $now     = helper::now();

        return $this->dao->select('*')->from(TABLE_MEETING)
            ->where($sql)
            ->beginIF($this->app->rawMethod == 'work')
            ->andWhere('(date')->gt($today)
            ->orWhere('(begin')->gt($now)
            ->andWhere('date')->eq($today)
            ->markRight(2)
            ->fi()
            ->andWhere('deleted')->eq('0')
            ->beginIF($this->app->tab == 'project')->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab == 'my')
            ->andWhere('(createdBy')->eq($account)
            ->orWhere('host')->eq($account)
            ->orWhere('participant')->like('%,' . $account . ',%')
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get meeting list by room.
     *
     * @param  string $type
     * @param  int    $roomID
     * @access public
     * @return array
     */
    public function getListByRoom($type = 'all', $roomID = 0)
    {
        $today = helper::today();
        $now   = date('H:i:s', strtotime(helper::now()));
        return $this->dao->select('*')->from(TABLE_MEETING)
            ->where('deleted')->eq('0')
            ->beginIF($roomID)->andWhere('room')->eq($roomID)
            ->beginIF($type == 'future')
            ->andWhere('(date')->gt($today)
            ->orWhere('(begin')->gt($now)
            ->andWhere('date')->eq($today)
            ->markRight(2)
            ->fi()
            ->fetchAll('id');
    }
}

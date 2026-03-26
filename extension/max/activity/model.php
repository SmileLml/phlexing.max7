<?php
/**
 * The model file of activity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     activity
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
?>
<?php
class activityModel extends model
{
    /**
     * Create an activity.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $activity = fixer::input('post')
            ->stripTags($this->config->activity->editor->create['id'], $this->config->allowedTags)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('labels')
            ->get();

        $this->dao->insert(TABLE_ACTIVITY)->data($activity)
            ->autoCheck()
            ->batchCheck($this->config->activity->create->requiredFields, 'notempty')
            ->exec();

        $activityID = $this->dao->lastInsertID();
        $this->dao->update(TABLE_ACTIVITY)->set('`order`')->eq($activityID * 5)->where('id')->eq($activityID)->exec();

        if(!dao::isError()) return $activityID;
    }

    /**
     * Batch create activities.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $data = fixer::input('post')->get();

        $this->loadModel('action');
        foreach($data->name as $i => $name)
        {
            if(!trim($name)) continue;

            $activity = new stdclass();
            $activity->process     = isset($data->process[$i]) ? $data->process[$i] : 0;
            $activity->name        = $name;
            $activity->optional    = $data->optional[$i];
            $activity->tailorNorm  = $data->tailorNorm[$i];
            $activity->content     = $data->content[$i];
            $activity->createdBy   = $this->app->user->account;
            $activity->createdDate = helper::now();

            $this->dao->insert(TABLE_ACTIVITY)->data($activity)
                ->autoCheck()
                ->batchCheck($this->config->activity->create->requiredFields, 'notempty')
                ->exec();

            $activityID = $this->dao->lastInsertID();
            $this->action->create('activity', $activityID, 'created');
            $this->dao->update(TABLE_ACTIVITY)->set('`order`')->eq($activityID * 5)->where('id')->eq($activityID)->exec();
        }

        return true;
    }

    /**
     * Get an activity by id.
     *
     * @param  int    $activityID
     * @access public
     * @return object
     */
    public function getByID($activityID = 0)
    {
        return $this->dao->select('*')->from(TABLE_ACTIVITY)->where('id')->eq($activityID)->fetch();
    }

    /**
     * Get an activity by search.
     *
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return void
     */
    public function getBySearch($queryID = 0, $orderBy = 'id_desc', $pager = null, $processID = '')
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('activityQuery', $query->sql);
                $this->session->set('activityForm', $query->form);
            }
            else
            {
                $this->session->set('activityQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->activityQuery == false) $this->session->set('activityQuery', ' 1 = 1');
        }

        $activityQuery = $this->session->activityQuery;

        $activities =  $this->dao->select('*')->from(TABLE_ACTIVITY)
            ->where($activityQuery)
            ->andWhere('deleted')->eq('0')
            ->beginIf($processID)->andWhere('process')->in($processID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $activities;
    }

    /**
     * Get activity params.
     *
     * @access public
     * @return object
     */
    public function getParams($processID = '')
    {
        return $this->dao->select('id,name')->from(TABLE_ACTIVITY)
            ->where('deleted')->eq('0')
            ->beginIf($processID)->andWhere('process')->in($processID)->fi()
            ->fetchPairs();
    }

    /**
     * Get activity list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $pager
     * @param  int    $processID
     * @access public
     * @return void
     */
    public function getList($browseType = 'all', $param = 0, $orderBy = 'id_desc', $pager = null, $processID = '')
    {
        if($browseType == 'bysearch')
        {
            $activities = $this->getBySearch($param, $orderBy, $pager, $processID);
        }
        else
        {
            $activities = $this->dao->select('*')->from(TABLE_ACTIVITY)
                ->where('deleted')->eq(0)
                ->beginIf($processID)->andWhere('process')->in($processID)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        return $activities;
    }

    /**
     * Get process pairs.
     *
     * @param  string $model
     * @access public
     * @return void
     */
    public function getProcessPairs($model = '')
    {
        return $this->dao->select('id, name')
            ->from(TABLE_PROCESS)
            ->where('deleted')->eq(0)
            ->beginIf($model)->andWhere('model')->eq($model)->fi()
            ->fetchPairs();
    }

    /**
     * Get output list.
     *
     * @param  int    $activityID
     * @access public
     * @return void
     */
    public function getOutputPairs($activityID)
    {
        $outputList = $this->dao->select('id, name')->from(TABLE_ZOUTPUT)
            ->where('deleted')->eq(0)
            ->andwhere('activity')->eq($activityID)
            ->fetchPairs();

        return $outputList;
    }

    /**
     * update an activity.
     *
     * @param  int    $activityID
     * @access public
     * @return void
     */
    public function update($activityID = 0)
    {
        $oldActivity = $this->getByID($activityID);

        $activity = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->activity->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid,labels')
            ->get();

        $this->dao->update(TABLE_ACTIVITY)->data($activity)
            ->autoCheck()
            ->batchCheck($this->config->activity->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$activityID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldActivity, $activity);
        return false;

    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID = 0, $actionURL = '')
    {
        $this->config->activity->search['actionURL'] = $actionURL;
        $this->config->activity->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->activity->search);
    }

    /**
     * Assign an activity.
     *
     * @param  int    $activityID
     * @access public
     * @return array|bool
     */
    public function assign($activityID = 0)
    {
        $oldActivity = $this->getByID($activityID);

        $activity = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->setDefault('assignedDate', helper::today())
            ->stripTags($this->config->activity->editor->assignto['id'], $this->config->allowedTags)
            ->remove('uid,comment,files,label')
            ->get();

        $this->dao->update(TABLE_ACTIVITY)->data($activity)->autoCheck()->where('id')->eq((int)$activityID)->exec();

        if(!dao::isError()) return common::createChanges($oldActivity, $activity);
        return false;
    }

    /**
     * Print assignedTo html.
     *
     * @param  object $activity
     * @param  array  $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($activity = '', $users = '')
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = zget($users, $activity->assignedTo);

        if(empty($activity->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->activity->noAssigned;
        }
        if($activity->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($activity->assignedTo) and $activity->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $activity->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('activity', 'assignTo', "activityID=$activity->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $activity->assignedTo) . "'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('activity', 'assignTo', $activity) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }
}

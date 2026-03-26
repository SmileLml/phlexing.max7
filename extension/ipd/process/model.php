<?php
/**
 * The model file of process module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     process
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class processModel extends model
{
    /**
     * Create a process.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $now  = helper::now();
        $data = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->stripTags($this->config->process->editor->create['id'], $this->config->allowedTags)
            ->get();

        $this->dao->insert(TABLE_PROCESS)->data($data)->batchCheck($this->config->process->create->requiredFields, 'notempty')->exec();
        $processID = $this->dao->lastInsertID();

        $this->dao->update(TABLE_PROCESS)->set('`order`')->eq($processID * 5)->where('id')->eq($processID)->exec();

        return $processID;
    }

    /**
     * Batch create process.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $now  = helper::now();
        $data = fixer::input('post')->get();

        $processes = array();
        foreach($data->dataList as $process)
        {
            if(!trim($process['name'])) continue;

            $process['createdBy']   = $this->app->user->account;
            $process['createdDate'] = $now;

            foreach(explode(',', $this->config->process->create->requiredFields) as $field)
            {
                $field = trim($field);
                if($field and empty($process[$field])) return dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->process->$field);
            }

            $processes[] = $process;
        }

        foreach($processes as $process) $this->dao->insert(TABLE_PROCESS)->data($process)->exec();

        return true;
    }

    /**
     * Get a process details.
     *
     * @param  int    $processID
     * @access public
     * @return object
     */
    public function getByID($processID)
    {
        return $this->dao->select('*')->from(TABLE_PROCESS)->where('id')->eq($processID)->andWhere('deleted')->eq('0')->fetch();
    }

    /**
     * Get process list data.
     *
     * @param  string    $browseType bySearch|all
     * @param  int       $queryID
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return object
     */
    public function getList($browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $processQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('processQuery', $query->sql);
                $this->session->set('processForm', $query->form);
            }
            if($this->session->processQuery == false) $this->session->set('processQuery', ' 1=1');
            $processQuery = $this->session->processQuery;
        }

        $processList = $this->dao->select('*')->from(TABLE_PROCESS)
            ->where('deleted')->eq('0')
            ->beginIF($browseType == 'bysearch')->andWhere($processQuery)->andWhere('model')->eq($this->session->model)->fi()
            ->beginIF($browseType == 'assignto')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'scrum')->andWhere('model')->eq('scrum')->fi()
            ->beginIF($browseType == 'waterfall')->andWhere('model')->eq('waterfall')->fi()
            ->beginIF($browseType == 'agileplus')->andWhere('model')->eq('agileplus')->fi()
            ->beginIF($browseType == 'waterfallplus')->andWhere('model')->eq('waterfallplus')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        return $processList;
    }

    /**
     * Update a process.
     *
     * @param  int    $processID
     * @access public
     * @return bool
     */
    public function update($processID)
    {
        $oldprocess = $this->getByID($processID);

        $now = helper::now();
        $data = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->addIF($this->post->assignedTo, 'assignedBy', $this->app->user->account)
            ->addIF($this->post->assignedTo, 'assignedDate', $now)
            ->stripTags($this->config->process->editor->edit['id'], $this->config->allowedTags)
            ->get();

        $this->dao->update(TABLE_PROCESS)->data($data)
            ->where('id')->eq($processID)
            ->batchCheck($this->config->process->edit->requiredFields, 'notempty')
            ->exec();

        return common::createChanges($oldprocess, $data);
    }

    /*
     * Delete a process;
     *
     * @param  int    $processID
     * @access public
     * @return void
     */
    public function deleteProess($processID = 0)
    {
        $this->dao->update(TABLE_PROCESS)->set('deleted')->eq('1')->where('id')->eq($processID)->exec();
        $activitys = $this->activityList($processID);

        if(!empty($activitys))
        {
            $this->dao->update(TABLE_ACTIVITY)->set('deleted')->eq('1')->where('process')->eq($processID)->exec();
            $activityKeys = array_keys($activitys);
            $outputs      = $this->outputList($activityKeys);

            $this->dao->update(TABLE_AUDITCL)->set('deleted')->eq('1')->where('objectType')->eq('activity')->andWhere('objectID')->in($activityKeys)->exec();

            if(!empty($outputs))
            {
                $ouputKeys = array_keys($outputs);
                $this->dao->update(TABLE_ZOUTPUT)->set('deleted')->eq('1')->where('id')->in($ouputKeys)->exec();
                $this->dao->update(TABLE_AUDITCL)->set('deleted')->eq('1')->where('objectType')->eq('zoutput')->andWhere('objectID')->in($ouputKeys)->exec();
            }
        }
    }

    /*
     * Build process search form.
     *
     * @param  string $actionURL
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function buildSearchForm($actionURL, $queryID)
    {
        $this->config->process->search['actionURL'] = $actionURL;
        $this->config->process->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->process->search);
    }

    /*
     * Get activity list.
     *
     * @param  int    $processID
     * @access public
     * @return void
     */
    public function activityList($processID)
    {
        return $this->dao->select('id, name')->from(TABLE_ACTIVITY)->where('deleted')->eq(0)->andWhere('process')->eq($processID)->fetchPairs();
    }

    /*
     * Get output list.
     *
     * @param  int    $activityID
     * @access public
     * @return void
     */
    public function outputList($activityID = 0)
    {
        if(empty($activityID)) return false;
        return $this->dao->select('id, name')->from(TABLE_ZOUTPUT)->where('deleted')->eq(0)->andWhere('activity')->in($activityID)->fetchPairs();
    }
}

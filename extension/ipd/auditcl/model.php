<?php
/**
 * The model file of auditcl module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     activity
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class auditclModel extends model
{
    /**
     * update a auditcl.
     *
     * @param  int    $auditclID
     * @access public
     * @return void
     */
    public function update($auditclID)
    {
        $oldAuditcl = $this->getByID($auditclID);

        $auditcl = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->remove('process')
            ->get();

        if($auditcl->zoutput)
        {
            $auditcl->objectType = 'zoutput';
            $auditcl->objectID   = $auditcl->zoutput;
        }
        else
        {
            $auditcl->objectType = 'activity';
            $auditcl->objectID   = $auditcl->activity;
        }

        unset($auditcl->zoutput);
        unset($auditcl->activity);

        $this->dao->update(TABLE_AUDITCL)->data($auditcl)->autoCheck()->where('id')->eq((int)$auditclID)->exec();

        if(!dao::isError()) return common::createChanges($oldAuditcl, $auditcl);
        return false;
    }

    /**
     * Set Menu.
     *
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function setMenu($browseType = '')
    {
        $this->app->loadLang('admin');
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        if(!isset($this->lang->admin->menuList->model['subMenu']['waterfall']['exclude']))     $this->lang->admin->menuList->model['subMenu']['waterfall']['exclude'] = '';
        if(!isset($this->lang->admin->menuList->model['subMenu']['scrum']['exclude']))         $this->lang->admin->menuList->model['subMenu']['scrum']['exclude']     = '';
        if(!isset($this->lang->admin->menuList->model['subMenu']['agileplus']['exclude']))     $this->lang->admin->menuList->model['subMenu']['agileplus']['exclude'] = '';
        if(!isset($this->lang->admin->menuList->model['subMenu']['waterfallplus']['exclude'])) $this->lang->admin->menuList->model['subMenu']['waterfallplus']['exclude'] = '';
        if($browseType == 'scrum')
        {
            $this->lang->admin->menuList->model['subMenu']['waterfall']['exclude']     .= ",{$moduleName}-{$methodName},{$methodName}";
            $this->lang->admin->menuList->model['subMenu']['agileplus']['exclude']     .= ",{$moduleName}-{$methodName},{$methodName}";
            $this->lang->admin->menuList->model['subMenu']['waterfallplus']['exclude'] .= ",{$moduleName}-{$methodName},{$methodName}";
        }
        if($browseType == 'waterfall')
        {
            $this->lang->admin->menuList->model['subMenu']['scrum']['exclude']         .= ",{$moduleName}-{$methodName},{$methodName}";
            $this->lang->admin->menuList->model['subMenu']['agileplus']['exclude']     .= ",{$moduleName}-{$methodName},{$methodName}";
            $this->lang->admin->menuList->model['subMenu']['waterfallplus']['exclude'] .= ",{$moduleName}-{$methodName},{$methodName}";
        }
        if($browseType == 'agileplus')
        {
            $this->lang->admin->menuList->model['subMenu']['scrum']['exclude']         .= ",{$moduleName}-{$methodName},{$methodName}";
            $this->lang->admin->menuList->model['subMenu']['waterfall']['exclude']     .= ",{$moduleName}-{$methodName},{$methodName}";
            $this->lang->admin->menuList->model['subMenu']['waterfallplus']['exclude'] .= ",{$moduleName}-{$methodName},{$methodName}";
        }
        if($browseType == 'waterfallplus')
        {
            $this->lang->admin->menuList->model['subMenu']['scrum']['exclude']     .= ",{$moduleName}-{$methodName},{$methodName}";
            $this->lang->admin->menuList->model['subMenu']['waterfall']['exclude'] .= ",{$moduleName}-{$methodName},{$methodName}";
            $this->lang->admin->menuList->model['subMenu']['agileplus']['exclude'] .= ",{$moduleName}-{$methodName},{$methodName}";
        }
    }

    /**
     * Batch create auditcl.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $data         = fixer::input('post')->get();
        $practiceArea = $data->practiceArea;
        $type         = $data->type;
        $titleList    = $data->title;
        $nowDate      = helper::now();

        $this->loadModel('action');

        foreach($titleList as $index => $object)
        {
            foreach($object as $objectID => $objectType)
            {
                $auditcl = new stdClass();
                $auditcl->objectID     = $objectID;
                $auditcl->createdBy    = $this->app->user->account;
                $auditcl->createdDate  = $nowDate;
                $auditcl->deleted      = '0';

                if(isset($objectType['activity']))
                {
                    foreach($objectType['activity'] as $activityTitle)
                    {
                        $activityTitle = trim($activityTitle);
                        if(empty($activityTitle)) continue;

                        $typeIndex = "$index-$objectID-activity";
                        $auditcl->title        = $activityTitle;
                        $auditcl->objectType   = 'activity';
                        $auditcl->type         = $type[$typeIndex][0];
                        $auditcl->model        = $this->post->model;
                        $auditcl->practiceArea = $practiceArea[$typeIndex][0];
                        $this->dao->insert(TABLE_AUDITCL)->data($auditcl)->autoCheck()->exec();
                        if(dao::isError()) return false;

                        $auditclID = $this->dao->lastInsertID();
                        $this->action->create('auditcl', $auditclID, 'Opened');
                    }
                }

                if(isset($objectType['zoutput']))
                {
                    foreach($objectType['zoutput'] as $activityTitle)
                    {
                        $activityTitle = trim($activityTitle);
                        if(empty($activityTitle)) continue;
                        $typeIndex = "$index-$objectID-zoutput";
                        $auditcl->title        = $activityTitle;
                        $auditcl->objectType   = 'zoutput';
                        $auditcl->type         = $type[$typeIndex][0];
                        $auditcl->model        = $this->post->model;
                        $auditcl->practiceArea = $practiceArea[$typeIndex][0];
                        $this->dao->insert(TABLE_AUDITCL)->data($auditcl)->autoCheck()->exec();
                        if(dao::isError()) return false;

                        $auditclID = $this->dao->lastInsertID();
                        $this->action->create('auditcl', $auditclID, 'Opened');
                    }
                }
            }
        }
    }

    /**
     * batch update auditcls.
     *
     * @access public
     * @return void
     */
    public function batchUpdate()
    {
        $data        = $_POST;
        $oldAuditcls = $this->dao->select('id,code,name')->from(TABLE_BASICMEAS)->where('id')->in(array_keys($data['practiceArea']))->fetchAll('id');
        $newAuditcls = array();
        $account     = $this->app->user->account;
        $editDate    = helper::now();

        $codeList = array();
        foreach($data['practiceArea'] as $id => $practiceArea)
        {
            $newAuditcls[$id]['practiceArea'] = trim($practiceArea);
            $newAuditcls[$id]['type']         = $data['type'][$id];
            $newAuditcls[$id]['title']        = $data['title'][$id];
            $newAuditcls[$id]['assignedTo']   = $data['assignedTo'][$id];
            $newAuditcls[$id]['editedBy']     = $account;
            $newAuditcls[$id]['editedDate']   = $editDate;

            if(empty($data['title'][$id])) die(js::error(sprintf($this->lang->auditcl->unitEmpty, $id)));
        }

        foreach($newAuditcls as $id => $auditcl)
        {
            $this->dao->update(TABLE_AUDITCL)->data($auditcl)->batchCheck($this->config->auditcl->requiredFields, 'notempty')->where('id')->eq($id)->exec();
            if(dao::isError()) return false;
        }
        return true;
    }

    /**
     * Get auditcl by id.
     *
     * @param  int    $auditclID
     * @access public
     * @return object
     */
    public function getByID($auditclID)
    {
        $auditcl = $this->dao->select('*')->from(TABLE_AUDITCL)->where('id')->eq((int)$auditclID)->fetch();

        $auditcl->activity = $auditcl->objectID;
        if($auditcl->objectType == 'zoutput') $auditcl->activity = $this->dao->findById($auditcl->objectID)->from(TABLE_ZOUTPUT)->fetch('activity');

        $auditcl->process = $this->dao->findById($auditcl->activity)->from(TABLE_ACTIVITY)->fetch('process');

        return $auditcl;
    }

    /**
     * Get auditcl list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $pager
     * @param  array  $activityKeys
     * @param  array  $outputKeys
     * @access public
     * @return object
     */
    public function getList($browseType = 'all', $param = 0, $orderBy = 'id_desc', $pager = null, $activityKeys = array(), $outputKeys = array())
    {
        $browseType = strtolower($browseType);
        if($browseType == 'bysearch') return $this->getBySearch($param, $orderBy, $pager, $activityKeys, $outputKeys);
        return $this->dao->select('*')->from(TABLE_AUDITCL)
            ->where('deleted')->eq('0')
            ->beginIF($activityKeys)->andWhere('objectType', true)->eq('activity')->andWhere('objectID')->in($activityKeys)->fi()
            ->beginIF($outputKeys)->orWhere('objectType')->eq('zoutput')->andWhere('objectID')->in($outputKeys)->markRight(1)->fi()
            ->beginIF($browseType != 'all')->andWhere('model')->eq($browseType)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get auditcl list by search.
     *
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int    $pager
     * @param  array  $activityKeys
     * @param  array  $outputKeys
     * @access public
     * @return void
     */
    public function getBySearch($queryID = '', $orderBy = 'id_desc', $pager = null, $activityKeys = array(), $outputKeys = array())
    {
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('auditclQuery', $query->sql);
                $this->session->set('auditclForm', $query->form);
            }
            else
            {
                $this->session->set('auditclQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->auditclQuery == false) $this->session->set('auditclQuery', ' 1 = 1');
        }

        $auditclQuery = $this->session->auditclQuery;

        return $this->dao->select('*')->from(TABLE_AUDITCL)
            ->where($auditclQuery)
            ->andWhere('deleted')->eq('0')
            ->beginIF($activityKeys)->andWhere('objectType', true)->eq('activity')->andWhere('objectID')->in($activityKeys)->fi()
            ->beginIF($outputKeys)->orWhere('objectType')->eq('zoutput')->andWhere('objectID')->in($outputKeys)->markRight(1)->fi()
            ->beginIF($this->session->model)->andWhere('model')->eq($this->session->model)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get process list.
     *
     * @param  bool $showTitle
     * @param  int  $processID
     * @access public
     * @return array
     */
    public function getProcessList($showTitle = false, $processID = 0, $model = '', $orderBy = '')
    {
        $processList = $this->dao->select('id, name')
            ->from(TABLE_PROCESS)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($model))->andWhere('model')->eq($model)->fi()
            ->beginIF($processID)->andWhere('id')->eq($processID)->fi()
            ->beginIF(!empty($orderBy))->orderBy($orderBy)->fi()
            ->fetchPairs('id','name');

        if($showTitle)
        {
            foreach($processList as $objectID => $process) $processList[$objectID] = $this->lang->auditcl->process . '：' . $process;
        }

        return $processList;
    }

    /**
     * Get object by processID.
     *
     * @param  int    $processID
     * @param  string $objectType
     * @access public
     * @return object
     */
    public function getObjectByID($processID = 0, $objectType = '')
    {
        $table  = ($objectType == 'activity') ? TABLE_ACTIVITY : TABLE_ZOUTPUT;
        $fields = ($objectType == 'activity') ? 'process' : 'activity';

        return $activities = $this->dao->select('id, name')->from($table)
            ->where($fields)->eq($processID)
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id','name');
    }

    /**
     * Get object list by processID.
     *
     * @param  string $objectType
     * @param  int    $processID
     * @param  int    $activityID
     * @param  int    $showTitle
     * @access public
     * @return array
     */
    public function getObjectList($objectType = 'activity', $processID = 0, $activityID = 0, $showTitle = false)
    {
        if($objectType == 'zoutput' and $activityID)
        {
            $objects = $this->dao->select('id, name')->from(TABLE_ZOUTPUT)
                ->where('deleted')->eq(0)
                ->andWhere('activity')->eq($activityID)
                ->fetchPairs('id','name');
        }
        else
        {
            $objects = $this->dao->select('id, name')->from(TABLE_ACTIVITY)
                ->where('deleted')->eq(0)
                ->beginIF($processID)->andWhere('process')->eq($processID)->fi()
                ->fetchPairs('id','name');

            if($objectType == 'zoutput' and !empty($objects))
            {
                $objects = $this->dao->select('id, name')->from(TABLE_ZOUTPUT)
                    ->where('deleted')->eq(0)
                    ->andWhere('activity')->in(array_keys($objects))
                    ->fetchPairs('id','name');
            }
        }

        if($showTitle)
        {
            foreach($objects as $key => $object) $objects[$key] = $this->lang->auditcl->$objectType . '：' . $object;
        }

        return $objects;
    }

    /**
     * Build Search Form.
     *
     * @param  int    $queryID
     * @param  int    $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->auditcl->search['actionURL'] = $actionURL;
        $this->config->auditcl->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->auditcl->search);
    }

    /**
     * Sort auditcls by processList.
     *
     * @param  array   $auditcls
     * @param  array   $processList
     * @param  string  $sortField
     * @access public
     * @return array   $output
     */
    public function sortByProcess($auditcls, $processList, $sortField)
    {
        $output = array();
        foreach($processList as $processID => $title)
        {
            foreach($auditcls as $id => $audit)
            {
                if($audit->$sortField == $processID) array_push($output, $auditcls[$id]);
            }
        }

        return $output;
    }
}

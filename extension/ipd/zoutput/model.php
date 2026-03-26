<?php
/**
 * The model file of zoutput module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     zoutput
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class zoutputModel extends model
{
    /**
     * Get a output details.
     *
     * @param  int    $outputID
     * @access public
     * @return object
     */
    public function getById($outputID)
    {
        $output        = $this->dao->select('*')->from(TABLE_ZOUTPUT)->where('id')->eq($outputID)->fetch();
        $output->files = $this->loadModel('file')->getByObject('zoutput', $output->id);

        return $output;
    }

    /**
     * Get output list data.
     *
     * @param  string  $browseType bySearch|all
     * @param  int     $queryID
     * @param  string  $orderBy
     * @param  object  $pager
     * @access public
     * @return object
     */
    public function getList($browseType = 'all', $queryID = 0, $orderBy = 'order_desc', $pager = null, $activityID = '')
    {
        $zoutputQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('zoutputQuery', $query->sql);
                $this->session->set('zoutputForm', $query->form);
            }
            if($this->session->zoutputQuery == false) $this->session->set('zoutputQuery', ' 1=1');
            $zoutputQuery = $this->session->zoutputQuery;
        }

        $zoutputList = $this->dao->select('*')->from(TABLE_ZOUTPUT)
            ->where('deleted')->eq('0')
            ->beginIF($browseType == 'bysearch')->andWhere($zoutputQuery)->fi()
            ->beginIF(is_array($activityID))->andWhere('activity')->in($activityID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        return $zoutputList;
    }

    /**
     * Create an zoutput.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $data = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('labels,files')
            ->stripTags($this->config->zoutput->editor->create['id'], $this->config->allowedTags)
            ->get();

        $this->dao->insert(TABLE_ZOUTPUT)->data($data)->batchCheck($this->config->zoutput->create->requiredFields, 'notempty')->exec();

        if(!dao::isError())
        {
            $outputID = $this->dao->lastInsertID();
            $order    = $outputID * 5;
            $this->dao->update(TABLE_ZOUTPUT)->set('order')->eq($order)->where('id')->eq($outputID)->exec();

            $this->loadModel('file')->saveUpload('zoutput', $outputID);
            return $outputID;
        }
    }

    /**
     * Batch create output.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $now  = helper::now();
        $data = fixer::input('post')->get();

        $outputs = array();
        foreach($data->dataList as $output)
        {
            if(!trim($output['name'])) continue;

            $output['createdBy']   = $this->app->user->account;
            $output['createdDate'] = $now;

            if(empty($output['activity'])) die(js::error(sprintf($this->lang->zoutput->activityEmpty, $id)));
            if(empty($output['name']))     die(js::error(sprintf($this->lang->zoutput->nameEmpty, $id)));

            $outputs[] = $output;
        }

        $outputIdList = array();
        foreach($outputs as $output)
        {
            $this->dao->insert(TABLE_ZOUTPUT)->data($output)->exec();

            $outputID = $this->dao->lastInsertID();
            $order    = $outputID * 5;

            $outputIdList[] = $outputID;
            $this->dao->update(TABLE_ZOUTPUT)->set('order')->eq($order)->where('id')->eq($outputID)->exec();
        }

        return $outputIdList;
    }

    /**
     * Update an zoutput.
     *
     * @param  int    $outputID
     * @access public
     * @return bool
     */
    public function update($outputID)
    {
        $oldzoutput = $this->getById($outputID);

        $data = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('labels,files')
            ->stripTags($this->config->zoutput->editor->edit['id'], $this->config->allowedTags)
            ->get();

        $this->dao->update(TABLE_ZOUTPUT)->data($data)
            ->where('id')->eq($outputID)
            ->batchCheck($this->config->zoutput->edit->requiredFields, 'notempty')
            ->exec();

        $this->loadModel('file')->saveUpload('zoutput', $outputID);

        return common::createChanges($oldzoutput, $data);
    }

    /**
     * Batch edit zoutput.
     *
     * @access public
     * @return void
     */
    public function batchEdit()
    {
        $data      = fixer::input('post')->get();
        $oldOutput = $this->dao->select('id,activity,name,optional')->from(TABLE_ZOUTPUT)->where('id')->in(array_keys($data->dataList))->fetchAll('id');
        $newOutput = array();
        $account   = $this->app->user->account;
        $editDate  = helper::now();

        foreach($data->dataList as $id => $output)
        {
            $output['editedBy']   = $account;
            $output['editedDate'] = $editDate;

            if(empty($output['activity'])) die(js::error(sprintf($this->lang->zoutput->activityEmpty, $id)));
            if(empty($output['name']))     die(js::error(sprintf($this->lang->zoutput->nameEmpty, $id)));

            $newOutput[$id] = $output;
        }

        $changes = array();
        foreach($newOutput as $id => $output)
        {
            $this->dao->update(TABLE_ZOUTPUT)->data($output)->batchCheck($this->config->zoutput->edit->requiredFields, 'notempty')->where('id')->eq($id)->exec();
            if(dao::isError()) return false;

            $changes[$id] = common::createChanges($oldOutput[$id], $output);
        }
        return $changes;
    }

    /**
     * Build output search form.
     *
     * @param  string $actionURL
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function buildSearchForm($actionURL, $queryID)
    {
        $this->config->zoutput->search['actionURL'] = $actionURL;
        $this->config->zoutput->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->zoutput->search);
    }
}

<?php
/**
 * The model file of workflowreport module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Dongdong Jia <jiadongdong@easycorp.ltd>
 * @package     workflowreport
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowreportModel extends model
{
    /**
     * Get reports of a flow.
     *
     * @param  string $module
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getList($module, $orderBy = '`order`,`id`')
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWREPORT)->where('module')->eq($module)->orderBy($orderBy)->fetchAll('id', false);
    }

    /**
     * Get a report by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $report = $this->dao->select('*')->from(TABLE_WORKFLOWREPORT)->where('id')->eq($id)->fetch();

        if(!$report) return false;

        return $this->decode($report);
    }

    /**
     * Get main table and sub tables dimensions.
     *
     * @param  object $flow
     * @access public
     * @return array
     */
    public function getDimension($flow)
    {
        $dimensionFields = array('' => '');
        $controlPairs    = array();

        /* Process main table. */
        $fields = $this->loadModel('workflowfield', 'flow')->getList($flow->module);
        foreach($fields as $field)
        {
            if(in_array($field->field, $this->config->workflowreport->excludeFields)) continue;

            $dimensionFields[$flow->module . '_' . $field->field] = $flow->name . '-' . $field->name;
            $controlPairs[$flow->module . '_' . $field->field]    = $field->control;
        }

        /* Process sub tables. */
        $subTables = $this->loadModel('workflow', 'flow')->getPairs($flow->module, 'table');
        foreach($subTables as $subModule => $subTable)
        {
            $fields = $this->workflowfield->getList($subModule);
            foreach($fields as $field)
            {
                if(isset($this->config->workflowfield->default->fields[$field->field])) continue;

                $dimensionFields[$subModule . '_' . $field->field] = $subTable . '-' . $field->name;
                $controlPairs[$subModule . '_' . $field->field]    = $field->control;
            }
        }

        return array($dimensionFields, $controlPairs);
    }

    /**
     * Get main table and sub tables fields.
     *
     * @param  object $flow
     * @access public
     * @return array
     */
    public function getFields($flow)
    {
        $fields = array('' => '');

        /* Process main table. */
        $fieldPairs = $this->loadModel('workflowfield','flow')->getNumberFields($flow->module, true);
        foreach($fieldPairs as $field => $name) $fields[$flow->module . '_' . $field] = $flow->name . '-' . $name;

        /* Process sub tables. */
        $subTablePairs = $this->loadModel('workflow', 'flow')->getPairs($flow->module, 'table');
        foreach($subTablePairs as $subModule => $subName)
        {
            $numberFields = $this->workflowfield->getNumberFields($subModule, true);
            foreach($numberFields as $field => $name) $fields[$subModule . '_' . $field] = $subName . '-' . $name;
        }

        return $fields;
    }

    /**
     * Create a report of this module.
     *
     * @param  string $module
     * @access public
     * @return int
     */
    public function create($module)
    {
        $report = fixer::input('post')
            ->add('module', $module)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        $report = $this->encode($report);

        $this->dao->insert(TABLE_WORKFLOWREPORT)->data($report)
            ->autoCheck()
            ->batchCheck($this->config->workflowreport->require->create, 'notempty')
            ->checkIF($report->countType !== 'count', 'fields', 'notempty')
            ->exec();

        return $this->dao->lastInsertId();
    }

    /**
     * Update a report of this module.
     *
     * @param  int    $id
     * @access public
     * @return boolean
     */
    public function update($id)
    {
        $oldReport = $this->getByID($id);
        $report    = fixer::input('post')->setDefault('fields', '')->get();
        $report    = $this->encode($report);

        $this->dao->update(TABLE_WORKFLOWREPORT)->data($report)
            ->where('id')->eq($id)
            ->autoCheck()
            ->batchCheck($this->config->workflowreport->require->edit, 'notempty')
            ->checkIF($report->countType !== 'count', 'fields', 'notempty')
            ->exec();

        return commonModel::createChanges($oldReport, $report);
    }

    /**
     * Delete a report by id.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        $this->dao->delete()->from(TABLE_WORKFLOWREPORT)->where('id')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * Encode dimension and fields to json format.
     *
     * @param  object $report
     * @access public
     * @return object
     */
    public function encode($report)
    {
        /* Process dimension and fields to json format. */
        if(strpos($report->dimension, '_') !== false)
        {
            list($module, $field) = explode('_', $report->dimension);
            $dimension = array('module' => $module, 'field' => $field);

            if(!empty($report->granularity)) $dimension['granularity'] = $report->granularity;

            $report->dimension = json_encode($dimension);
        }

        /* Process fields to json format. */
        if(!empty($report->fields)) $report->fields = array_filter($report->fields);
        if(!empty($report->fields))
        {
            $fields = array();
            foreach($report->fields as $field)
            {
                if(strpos($field, '_') == false) continue;

                list($module, $field) = explode('_', $field);
                $fields[] = array('module' => $module, 'field' => $field);
            }

            $report->fields = json_encode($fields);
        }
        else
        {
            $report->fields = '';
        }

        unset($report->granularity);
        return $report;
    }

    /**
     * Decode dimension and fields of a report.
     *
     * @param  object    $report
     * @access public
     * @return object
     */
    public function decode($report)
    {
        if(!empty($report->dimension))
        {
            $dimension = json_decode($report->dimension);
            $report->dimension = $dimension->module . '_' . $dimension->field;
            if(!empty($dimension->granularity)) $report->granularity = $dimension->granularity;
        }

        if(!empty($report->fields))
        {
            $fields = json_decode($report->fields);
            $report->fields = array();
            foreach($fields as $field) $report->fields[] = $field->module . '_' . $field->field;
        }

        return $report;
    }
}

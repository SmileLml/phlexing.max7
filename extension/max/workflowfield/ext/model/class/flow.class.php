<?php
class flowWorkflowField extends workflowFieldModel
{
    /**
     * Append data from flow.
     *
     * @param  array  $fields
     * @param  array  $objects
     * @param  string $module
     * @access public
     * @return array
     */
    public function appendDataFromFlow($fields, $objects, $module = '')
    {
        if(empty($module)) $module = $this->app->getModuleName();
        $allFields = $this->getList($module);
        $rawFields = '';
        if($this->post->exportFields)
        {
            $rawFields = $this->post->exportFields;
            foreach($rawFields as $key => $field)
            {
                $rawFields[$field] = $field;
                unset($rawFields[$key]);
            }
        }

        /* Get extend extort fields. */
        $canExportFields = array();
        foreach($allFields as $field)
        {
            if($field->buildin) continue;
            if(empty($field->canExport)) continue;
            if($rawFields and !isset($rawFields[$field->field])) continue;
            $field->options = $this->getFieldOptions($field);
            $canExportFields[$field->field] = $field;
        }

        if(empty($canExportFields)) return array($fields, $objects);

        foreach($canExportFields as $field) $fields[$field->field] = $field->name;

        $this->loadModel('flow');
        $this->loadModel('file');
        foreach($objects as $object)
        {
            foreach($canExportFields as $fieldName => $field)
            {
                if($field->control == 'file')
                {
                    $object->$fieldName = '';
                    $files = $this->file->getByObject($field->module, $object->id, $field->field);
                    if($files)
                    {
                        foreach($files as $file)
                        {
                            $fileURL = common::getSysURL() . helper::createLink('file', 'download', "fileID=$file->id");
                            $object->$fieldName .= baseHTML::a($fileURL, $file->title, '_blank') . '<br />';
                        }
                    }
                }
                else
                {
                    $object->$fieldName = $this->flow->getFieldValue($field, $object);
                }
            }
        }

        return array($fields, $objects);
    }

    /**
     * Process sub status of a record.
     *
     * @param  string $module
     * @param  object $record
     * @access public
     * @return string
     */
    public function processSubStatus($module, $record)
    {
        $status        = $this->getByField($module, 'status');
        $statusOptions = $this->getFieldOptions($status);
        $subStatus     = $this->getByField($module, 'subStatus');

        return zget($subStatus->options, $record->subStatus, zget($statusOptions, $record->status, ''));
    }

    public function setFlowListValue($module)
    {
        $fields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->eq($module)
            ->andWhere('buildin')->eq(0)
            ->andWhere('canExport')->eq(1)
            ->fetchAll('id', false);

        foreach($fields as $field)
        {
            if($field->control != 'select' and $field->control != 'radio' and $field->control != 'multi-select') continue;
            if(empty($field->options)) continue;

            $field   = $this->processFieldOptions($field);
            $options = $this->getFieldOptions($field, false);
            if($options)
            {
                $this->config->{$module}->export->listFields[] = $field->field;
                $this->post->set($field->field . 'List', $options);
            }
        }
    }

    public function getFieldOptions($field, $emptyOption = true, $keys = '', $search = '', $limit = 0, $importData = false)
    {
        $isDatasource = (is_int($field->options) or (is_string($field->options) && (int)$field->options > 0));
        $options      = parent::getFieldOptions($field, $emptyOption, $keys, $search, $limit, $importData);

        return $options;
    }

    /**
     * Get datasource pairs of field.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getDatasourcePairs($type)
    {
        $datasources = $this->loadModel('workflowdatasource', 'flow')->getPairs('noempty');

        if($this->config->systemMode == 'light')
        {
            $datasourceDB = $this->dao->select('code, id')->from(TABLE_WORKFLOWDATASOURCE)->where('code')->in(array('projects', 'programs', 'executions'))->fetchPairs();
            if(!empty($datasourceDB['programs'])) unset($datasources[$datasourceDB['programs']]);
        }
        foreach($datasources as $key => $datasource)
        {
            if(strpos(',deptManager,actor,today,now,form,record,', ",{$key},") !== false) unset($datasources[$key]);
        }

        $datasources = arrayUnion($datasources, $this->lang->workflowfield->optionTypeList);
        if($type == 'table') unset($datasources['prevModule']);
        return $datasources;
    }
}

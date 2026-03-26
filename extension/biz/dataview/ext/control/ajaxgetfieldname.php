<?php
/**
 * The control file of dataview module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <chunsheng@cnezsoft.com>
 * @package     dataview
 * @version     $Id: control.php 5086 2023-06-06 02:25:22Z
 * @link        http://www.zentao.net
 */
class dataview extends control
{
    /**
     * AJAX: Get field name.
     *
     * @access public
     * @return void
     */
    public function ajaxGetFieldName($useDtable = 'no')
    {
        $fields        = is_array($this->post->fields)        ? $this->post->fields        : json_decode($this->post->fields, true);
        $fieldSettings = is_array($this->post->fieldSettings) ? $this->post->fieldSettings : json_decode($this->post->fieldSettings, true);

        $workflowFields = array();
        $this->loadModel('workflowfield');
        foreach($fields as $field => $fieldName)
        {
            if(isset($fieldSettings[$field]))
            {
                if(empty($fieldSettings[$field]['object']) or empty($fieldSettings[$field]['field'])) continue;

                $relatedObject = $fieldSettings[$field]['object'];
                $relatedField  = $fieldSettings[$field]['field'];

                $this->app->loadLang($relatedObject);
                $fields[$field] = isset($this->lang->$relatedObject->$relatedField) ? $this->lang->$relatedObject->$relatedField : $field;

                if(!isset($workflowFields[$relatedObject])) $workflowFields[$relatedObject] = $this->workflowfield->getFieldPairs($relatedObject);
                if(isset($workflowFields[$relatedObject][$relatedField])) $fields[$field] = $workflowFields[$relatedObject][$relatedField];
            }
        }

        if($useDtable == 'no') return $this->send(array('result' => 'success', 'fields' => $fields));

        $cols = array();
        foreach($fields as $field => $title)
        {
            $col = array();
            $col['name']  = $field;
            $col['title'] = $title;
            $col['align'] = 'center';

            $cols[] = $col;
        }

        return $this->send(array('result' => 'success', 'fields' => $cols));
    }
}

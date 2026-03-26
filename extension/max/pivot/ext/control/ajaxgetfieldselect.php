<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class pivot extends control
{
    /**
     * Ajax get field select html.
     *
     * @access public
     * @return string
     */
    public function ajaxGetFieldSelect()
    {
        $fields     = $this->post->fields;
        $langs      = $this->post->langs;
        $clientLang = $this->app->getClientLang();

        $this->loadModel('workflowfield');
        $fieldPairs     = array();
        $workflowFields = array();
        foreach($fields as $field => $fieldList)
        {
            $fieldObject  = $fieldList['object'];
            $relatedField = $fieldList['field'];

            $this->app->loadLang($fieldObject);
            $fieldPairs[$field] = isset($this->lang->$fieldObject->$relatedField) ? $this->lang->$fieldObject->$relatedField : $field;

            if(!isset($workflowFields[$fieldObject])) $workflowFields[$fieldObject] = $this->workflowfield->getFieldPairs($fieldObject);
            if(isset($workflowFields[$fieldObject][$relatedField])) $fieldPairs[$field] = $workflowFields[$fieldObject][$relatedField];

            if(!isset($langs[$field])) continue;
            if(!empty($langs[$field][$clientLang])) $fieldPairs[$field] = $langs[$field][$clientLang];
        }

        echo html::select('fieldTpl', arrayUnion(array('' => ''), $fieldPairs), '', "class='form-control picker-select'");
    }
}

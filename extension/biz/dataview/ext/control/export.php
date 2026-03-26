<?php
/**
 * The control file of dataview module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <chunsheng@cnezsoft.com>
 * @package     dataview
 * @version     $Id: control.php 5086 2023-06-29 02:25:22Z
 * @link        http://www.zentao.net
 */
class dataview extends control
{
    /**
     * Export dataview data.
     *
     * @param  string $type table|view|query
     * @param  string $table
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function export($type = 'view', $table = '', $fileName = '')
    {
        $this->loadModel('dev');
        $this->loadModel('tree');
        $this->app->loadLang('file');

        if($type == 'view')
        {
            $dataview = $this->dataview->getByID($table);
            $fileName = !empty($dataview->name) ? $dataview->name : '';
        }
        elseif($type == 'table')
        {
            $dataview = null;
            $fileName = $this->dataview->getTableName($table);
        }
        elseif($type == 'query')
        {
            $dataview = null;
            $fileName = $fileName;
        }

        if(!empty($_POST))
        {
            if($type == 'view')
            {
                $columns = $this->dataview->getFields($table);
                $rows    = !empty($columns) ? $this->dataview->getTableData($table, $type, 0) : array();
            }
            elseif($type == 'table')
            {
                $columns = $this->dev->getFields($table);
                $rows    = !empty($columns) ? $this->dataview->getTableData($table, $type, 0) : array();
            }
            elseif($type == 'query')
            {
                $columns = json_decode($this->post->fields, true);
                $rows = $this->dbh->query($this->post->sql)->fetchAll();
            }

            /* Process columns to fileds. */
            $fields     = array();
            $clientLang = $this->app->getClientLang();

            if($type == 'view' or $type == 'table')
            {
                $langs = !empty($dataview->langs) ? json_decode($dataview->langs, true) : array();
                foreach($columns as $key => $column)
                {
                    $fieldName = isset($dataview->fieldSettings->$key->name) ? $dataview->fieldSettings->$key->name : $key;
                    if(!empty($langs)) $fieldName = $langs[$key][$clientLang] ? $langs[$key][$clientLang] : $fieldName;

                    $fields[$key] = $fieldName;
                }
            }
            elseif($type == 'query')
            {
                $langs = json_decode($this->post->langs, true);
                foreach($columns as $key => $column)
                {
                    $fieldName = $column['name'];
                    if(!empty($langs)) $fieldName = $langs[$key][$clientLang] ? $langs[$key][$clientLang] : $fieldName;
                    $fields[$key] = $fieldName;
                }
            }

            if(empty($fields)) return;

            unset($_POST['sql']);
            unset($_POST['fields']);
            unset($_POST['langs']);

            if(empty($_POST['fileName'])) $this->post->set('fileName', !empty($fileName) ? $fileName : $this->lang->file->untitled);
            $this->post->set('fields', $fields);
            $this->post->set('rows', $rows);
            $this->post->set('kind', 'dataview');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->view->type     = $type;
        $this->view->fileName = $fileName;
        $this->display();
    }
}

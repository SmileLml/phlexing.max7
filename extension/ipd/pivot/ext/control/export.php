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
     * Export a pivot.
     *
     * @param  int    $pivotID
     * @access public
     * @return void
     */
    public function export($pivotID)
    {
        $pivot = $this->pivot->getByID($pivotID);

        if($_POST)
        {
            if(!empty($_POST['pivot'])) $pivot = json_decode($this->post->pivot, true);
            $pivot = (array)$pivot;

            $filters      = !empty($pivot['searchFilters']) ? $pivot['searchFilters'] : $pivot['filters'];
            $filterFormat = array();
            foreach($filters as $filter)
            {
                $field   = $filter['field'];
                $default = $filter['default'];
                if(isset($filter['form']) and $filter['from'] == 'query')
                {
                    $queryDefault = $default;
                    if($filter['type'] == 'select')
                    {
                        $queryDefault = array_filter($default, function($val){return !empty($val);});
                        $queryDefault = implode("', '", $queryDefault);
                    }
                    $post->sql  = str_replace('$' . $filter['field'], "'{$queryDefault}'", $post->sql);
                    $filterType = 'query';
                }

                switch($filter['type'])
                {
                    case 'select':
                        $default = array_filter($default, function($val){return !empty($val);});
                        if(empty($default)) break;
                        $value = "('" . implode("', '", $default) . "')";
                        $filterFormat[$field] = array('operator' => 'IN', 'value' => $value);
                        break;
                    case 'input':
                        $filterFormat[$field] = array('operator' => 'like', 'value' => "'%$default%'");
                        break;
                    case 'date':
                    case 'datetime':
                        $begin = $default['begin'];
                        $end   = $default['end'];

                        if(empty($begin) or empty($end)) break;

                        $value = "'$begin' and '$end'";
                        $filterFormat[$field] = array('operator' => 'BETWEEN', 'value' => $value);
                        break;
                }
            }

            $_POST['step'] = 4;
            list($data, $configs) = $this->pivot->genSheet(json_decode(json_encode($pivot['fieldSettings']), true), $pivot['settings'], $pivot['sql'], $filterFormat, json_decode($pivot['langs'], true));

            $cols = array();
            foreach(zget($data, 'cols', array()) as $field) $cols[] = $field->label;

            $rows = array();
            $dataList = array();
            foreach(zget($data, 'array', array()) as $dataList) $rows[] = (object)$dataList;

            $index  = 0;
            $fields = array();
            foreach($dataList as $field => $value)
            {
                $fields[$field] = $cols[$index];
                $index ++;
            }

            $fieldKeys = array_keys($fields);
            foreach($configs as $key => $config)
            {
                foreach($config as $index => $number)
                {
                    $field = $fieldKeys[$index];
                    $rowspan[$key]['rows'][$field] = $number;
                }
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $rows);
            $this->post->set('kind', 'pivot');
            $this->post->set('rowspan', $rowspan);
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }
        unset($this->lang->exportTypeList['selected']);

        unset($this->lang->exportFileTypeList['csv']);
        unset($this->lang->exportFileTypeList['xml']);
        $this->view->fileName = !empty($pivot->name) ? $pivot->name : '';
        $this->display();
    }
}

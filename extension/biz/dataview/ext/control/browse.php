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
     * Browse page.
     *
     * @param  string $type table|view
     * @access public
     * @return void
     */
    public function browse($type = 'view', $table = '', $recTotal = 0,  $recPerPage = 25, $pageID = 1)
    {
        $this->session->set('dataViewList', $this->app->getURI(true));

        $groupTree = $this->loadModel('tree')->getGroupTree(0, 'dataview', $table);
        foreach($groupTree as $group)
        {
            if(empty($group->items)) continue;

            foreach($group->items as $item) $item->selected = $item->key == $table;
        }

        $originTableTree = $this->dataview->getOriginTreeMenu($table);
        foreach($originTableTree as $firstOriginTable)
        {
            if(empty($firstOriginTable->items)) continue;

            foreach($firstOriginTable->items as $secondOriginTable)
            {
                $secondOriginTable->selected = 'zt_' . $secondOriginTable->key == $table;

                if(empty($secondOriginTable->items)) continue;
                foreach($secondOriginTable->items as $thirdOriginTable) $thirdOriginTable->selected = 'zt_' . $thirdOriginTable->key == $table;
            }
        }

        $this->loadModel('dev');
        $fields = array();
        if($table) $fields = $type == 'table' ? $this->dev->getFields($table) : $this->dataview->getFields($table);

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $dataview = $type == 'view' ? $this->dataview->getByID($table) : null;

        $this->view->title         = $this->lang->dataview->common;
        $this->view->tab           = 'db';
        $this->view->table         = $table;
        $this->view->type          = $type;
        $this->view->dataview      = $dataview;
        $this->view->dataTitle     = $type == 'view' ? (!empty($dataview->name) ? $dataview->name : '') : $this->dataview->getTableName($table);
        $this->view->tables        = $this->dev->getTables();
        $this->view->fields        = $fields;
        $this->view->data          = !empty($fields) ? $this->dataview->getTableData($table, $type, 0, $pager) : array();
        $this->view->pager         = $pager;
        $this->view->groups        = $this->tree->getOptionMenu(0, 'dataview');
        $this->view->groupTree     = $groupTree;
        $this->view->originTable   = $originTableTree;
        $this->view->clientLang    = $this->app->getClientLang();
        $this->display();
    }
}

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
     * @return array
     */
    public function ajaxGetTableData()
    {
        $pageID     = $_POST['pageID'];
        $recPerPage = $_POST['recPerPage'];
        $recTotal   = $_POST['recTotal'];
        $table      = $_POST['table'];
        $type       = $_POST['type'];

        $dataview = $type == 'view' ? $this->dataview->getByID($table) : null;
        if(!empty($table)) $fields = $type == 'table' ? $this->loadModel('dev')->getFields($table) : $this->dataview->getFields($table);

        /* Get rows use limit. */
        $limit = "limit " . ($pageID - 1) * $recPerPage . ", $recPerPage";
        if($type == 'table')
        {
            $sql      = "select * from $table ";
            $limitSql = $sql . $limit;
        }
        else
        {
            $table    = $this->dataview->getByID($table);
            $sql      = "select * from {$table->view} ";
            $limitSql = $sql . $limit;
        }
        $datas     = $this->dbh->query($limitSql)->fetchAll();
        $rowsCount = $this->dbh->query($sql)->fetchAll();

        $clientLang = $this->app->getClientLang();

        $this->loadModel('setting');
        $daoPerPage = $this->setting->getItem("owner={$this->app->user->account}&module=dataview&section=browse&key=recPerPage");
        if($daoPerPage != $recPerPage) $this->setting->setItem($this->app->user->account . '.dataview.browse.recPerPage', $recPerPage);

        return $this->send(array('fields' => $fields, 'datas' => $datas, 'dataview' => $dataview, 'recTotal' => count($rowsCount), 'fieldCount' => count($fields), 'clientLang' => $clientLang));
    }
}

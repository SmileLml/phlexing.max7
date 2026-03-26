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
     * Delete a dataview.
     *
     * @param  int    $dataviewID
     * @access public
     * @return int
     */
    public function delete($dataviewID)
    {
        $dataview = $this->dataview->getByID($dataviewID);

        $this->dataview->delete(TABLE_DATAVIEW, $dataviewID);
        $this->dataview->deleteViewInDB($dataview->view);

        $locateLink = $this->session->dataviewList ? $this->session->dataviewList : inlink('browse', 'type=view');
        return $this->send(array('result' => 'success', 'load' => $locateLink));
    }
}

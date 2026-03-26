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
     * Delete a pivot.
     *
     * @param  int    $pivotID
     * @param  string $confirm     yes|no
     * @param  string $isOld       yes|no
     * @access public
     * @return void
     */
    public function delete($pivotID, $from = 'preview')
    {
        $this->pivot->delete(TABLE_PIVOT, $pivotID);

        if($from == 'preivew')
        {
            $dimensionID = $this->session->backDimension ? $this->session->backDimension : 0;
            $groupID     = $this->session->backGroup     ? $this->session->backGroup     : 0;
            unset($_SESSION['backDimension']);
            unset($_SESSION['backGroup']);
            return $this->send(array('result' => 'success', 'status' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => inlink('preview', "dimension=$dimensionID&group=$groupID")));
        }

        return $this->send(array('result' => 'success', 'status' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
    }
}

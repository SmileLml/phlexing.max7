<?php
/**
 * The control file of dimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <1097180981@qq.com>
 * @package     dimension
 * @version     $Id: control.php 4157 2022-11-1 10:24:12Z $
 * @link        http://www.zentao.net
 */
class dimension extends control
{
    /**
     * Delete a dimension.
     *
     * @param  int    $dimensionID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($dimensionID)
    {
        $this->dimension->delete(TABLE_DIMENSION, $dimensionID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }
}

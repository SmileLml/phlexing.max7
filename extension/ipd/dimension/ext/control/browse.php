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
     * Browse page.
     *
     * @param  int    $dimensionID
     * @access public
     * @return void
     */
    public function browse($dimensionID = 0)
    {
        $dimensions  = $this->dimension->getList();
        $dimensionID = $this->dimension->saveState($dimensionID, $dimensions);

        $this->loadModel('setting')->setItem($this->app->user->account . 'common.dimension.lastDimension', $dimensionID);

        $this->view->title      = $this->lang->dimension->common;
        $this->view->dimensions = $dimensions;
        $this->display();
    }
}

<?php
/**
 * The control file of dimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@cnezsoft.com>
 * @package     dimension
 * @version     $Id $
 * @link        http://www.zentao.net
 */
class dimension extends control
{
    /**
     * Browse page.
     *
     * @param  int    $dimensionID
     * @access public
     * @return bool
     */
    public function ajaxCheckBIContent($dimensionID)
    {
        die($this->dimension->checkBIContent($dimensionID));
    }
}

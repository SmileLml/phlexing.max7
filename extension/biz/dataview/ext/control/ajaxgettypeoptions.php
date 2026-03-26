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
     * Ajax get type options.
     *
     * @param  string   $objectName
     * @access public
     * @return void
     */
    public function ajaxGetTypeOptions($objectName)
    {
        $options = $this->dataview->getTypeOptions($objectName);
        return $this->send(array('result' => 'success', 'options' => $options));
    }
}

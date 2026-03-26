<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <songchenxuan@cnezsoft.com>
 * @package     pivot
 * @version     $
 * @link        http://www.zentao.net
 */
class pivot extends control
{
    /**
     * Ajax get preview drill result.
     *
     * @access public
     * @return string
     */
    public function ajaxGetPreviewResult()
    {
        $object        = $_POST['object'];
        $whereSQL      = $_POST['whereSql'];
        $filters       = json_decode($_POST['filters'], true);
        $previewResult = $this->pivot->getDrillResult($object, $whereSQL, $filters);

        echo json_encode($previewResult);
    }
}

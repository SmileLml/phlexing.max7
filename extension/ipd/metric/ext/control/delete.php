<?php
/**
 * The control file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song
 * @package     metric
 * @version     $Id: control.php 5086 2023-06-06 02:25:22Z
 * @link        http://www.zentao.net
 */
class metric extends control
{
    /**
     * 删除度量项。
     * View metric details.
     *
     * @param  int    $metricID
     * @access public
     * @return void
     */
    public function delete($metricID)
    {
        $this->dao->update(TABLE_METRIC)->set('deleted')->eq(1)->where('id')->eq($metricID)->exec();

        $this->loadModel('action')->create('metric', $metricID, 'deleted', '', ACTIONMODEL::CAN_UNDELETED);
        $locateLink = $this->createLink('metric', 'browse');
        return $this->send(array('result' => 'success', 'load' => $locateLink, 'closeModal' => true));
    }
}

<?php
/**
 * The control file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class chart extends control
{
    /**
     * Delete a chart.
     *
     * @param  int    $chartID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($chartID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $used          = $this->loadModel('screen')->checkIFChartInUse($chartID, 'chart');
            $confirmDelete = $used ? $this->lang->chart->confirm->delete : $this->lang->chart->confirmDelete;
            return print(js::confirm($confirmDelete, inlink('delete', "id=$chartID&confirm=yes")));
        }
        else
        {
            $this->chart->delete(TABLE_CHART, $chartID);

            if(isonlybody()) return print(js::reload('parent.parent'));

            $locateLink = $this->session->chartList ? $this->session->chartList : inlink('browse');
            return print(js::locate($locateLink, 'parent'));
        }
    }
}

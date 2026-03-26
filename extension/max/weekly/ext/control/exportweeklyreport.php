<?php
/**
 * The control file of weekly module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      ChenTao<chentao@easycorp.ltd>
 * @package     weekly
 * @link        https://www.zentao.net
 */
helper::importControl('weekly');
class myWeekly extends weekly
{
    /**
     * Export weekly report.
     *
     * @param  int    $module
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function exportweeklyreport($module, $projectID = 0, $productID = 0)
    {
        if($_POST)
        {
            $this->loadModel($module);

            $this->post->set('data', $this->{$module}->getReportData($projectID, $this->post->selectedWeekBegin, true));
            $this->post->set('kind', $module);

            return $this->fetch('file', 'exportweeklyreport', $_POST);
        }

        $this->view->module    = $module;
        $this->view->projectID = $projectID;
        $this->view->productID = $productID;

        $this->display();
    }
}

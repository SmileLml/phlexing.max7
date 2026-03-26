<?php
/**
 * The control file of report module of zentaopms.
 *
 * @copyright   copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     zpl (http://zpl.pub/page/zplv12.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     report
 * @link        https://www.zentao.net
 */
helper::importControl('report');
class myReport extends report
{
    /**
     * Browse report.
     *
     * @param  string $module
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseReport($module = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->session->set('reportList', $this->app->getURI(true), $this->app->tab);

        $this->view->title      = $this->lang->crystal->browse;
        $this->view->position[] = $this->lang->crystal->browse;

        $this->view->reports       = $this->report->getReportList($module, $orderBy, $pager);
        $this->view->pager         = $pager;
        $this->view->currentModule = $module;
        $this->display();
    }
}

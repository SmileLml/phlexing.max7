<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @link        https://www.zentao.net
 */
helper::importControl('pivot');
class myPivot extends pivot
{
    /**
     * Work summary.
     *
     * @param  int    $begin
     * @param  int    $end
     * @param  int    $dept
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function workSummary($begin = 0, $end = 0, $dept = 0, $recTotal = 0, $recPerPage = 5, $pageID = 1)
    {
        $this->app->loadLang('task');
        $begin = $begin ? date('Y-m-d', strtotime($begin)) : date('Y-m-d', strtotime('last month', strtotime(date('Y-m',time()) . '-01 00:00:01')));
        $end   = $end   ? date('Y-m-d', strtotime($end))   : date('Y-m-d', strtotime('now'));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->pivot->workSummary;
        $this->view->position[] = $this->lang->pivot->workSummary;

        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->projects   = $this->loadModel('project')->getPairsByProgram();
        $this->view->executions = $this->loadModel('execution')->getPairs(0, 'all', 'all|noprefix');
        $this->view->begin      = $begin;
        $this->view->end        = $end;
        $this->view->dept       = $dept;
        $this->view->userTasks  = $this->pivot->getWorkSummary($begin, $end, $dept, 'worksummary', $pager);
        $this->view->submenu    = 'staff';

        $this->setPagerParam2View($pager);
        $this->display();
    }
}

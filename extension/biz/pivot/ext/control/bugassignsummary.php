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
     * Summary of Bug Assignment.
     *
     * @param  int    $dept
     * @param  int    $begin
     * @param  int    $end
     * @access public
     * @return void
     */
    public function bugAssignSummary($dept = 0, $begin = 0 , $end = 0)
    {
        $this->app->loadLang('bug');
        $begin = $begin ? date('Y-m-d', strtotime($begin)) : date('Y-m-d', strtotime('last month', strtotime(date('Y-m',time()) . '-01 00:00:01')));
        $end   = $end   ? date('Y-m-d', strtotime($end))   : date('Y-m-d', strtotime('now'));


        $this->view->title      = $this->lang->pivot->bugAssignSummary;
        $this->view->position[] = $this->lang->pivot->bugAssignSummary;

        $this->view->users    = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->depts    = $this->loadModel('dept')->getOptionMenu();
        $this->view->dept     = $dept;
        $this->view->begin    = $begin;
        $this->view->end      = $end;
        $this->view->userBugs = $this->pivot->getBugSummary($dept, $begin, $end, 'bugassignsummary');
        $this->view->submenu  = 'staff';
        $this->display();
    }
}

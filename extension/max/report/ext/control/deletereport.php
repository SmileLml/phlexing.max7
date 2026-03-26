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
     * Delete report.
     *
     * @param  int    $reportID
     * @param  string $confirm yes|no
     * @param  string $from
     * @access public
     * @return void
     */
    public function deleteReport($reportID = 0, $confirm = 'no', $from = '')
    {
        if($from) $this->lang->navGroup->report = 'system';

        if($confirm == 'no')
        {
            die(js::confirm($this->lang->crystal->confirmDelete, $this->createLink('report', 'deleteReport', "reportID=$reportID&confirm=yes")));
        }

        $this->dao->delete()->from(TABLE_REPORT)->where('id')->eq($reportID)->exec();
        die(js::reload('parent'));
    }
}

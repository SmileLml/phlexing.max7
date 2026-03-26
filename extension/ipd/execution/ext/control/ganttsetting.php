<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
helper::importControl('execution');
class myexecution extends execution
{
    /**
     * 甘特图设置。
     * Show relation of execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ganttSetting($executionID = 0)
    {
        $this->loadModel('setting');
        $account      = $this->app->user->account;
        $isBranch     = false;
        $customFields = array();
        $zooming      = $this->setting->getItem("owner=$account&module=execution&section=ganttCustom&key=zooming");

        if(!empty($_POST))
        {
            if($account == 'guest') return $this->send(array('result' => 'fail', 'target' => $target, 'message' => 'guest.'));

            $data        = fixer::input('post')->get();
            $zooming     = empty($data->zooming) ? '' : $data->zooming;
            $ganttFields = empty($data->ganttFields) ? '' : implode(',', $data->ganttFields);

            $this->setting->setItem("$account.execution.ganttCustom.ganttFields", $ganttFields);
            $this->setting->setItem("$account.execution.ganttCustom.zooming", $zooming);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => 'dao error.'));
            return $this->send(array('result' => 'success', 'locate' => 'parent', 'message' => $this->lang->saveSuccess));
        }

        /* Set Custom. */
        foreach(explode(',', $this->config->execution->custom->customGanttFields) as $field)
        {
            $customFields[$field] = $this->lang->execution->ganttCustom[$field];
        }

        $branchs = $this->execution->getBranches($executionID);
        if($branchs)
        {
            $branchProducts  = $this->execution->getBranchByProduct(array_keys($branchs));
            if($branchProducts) $isBranch = true;
        }

        if(!$isBranch) unset($customFields['branch']);

        $this->view->title        = $this->lang->execution->common . $this->lang->hyphen . $this->lang->execution->ganttSetting;
        $this->view->zooming      = $zooming;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->execution->ganttCustom->ganttFields;

        $this->display();
    }
}

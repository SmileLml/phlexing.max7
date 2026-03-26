<?php
/**
 * The control file of milestone module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     milestone
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class milestone extends control
{
    public function index($projectID = 0, $executionID = 0, $productID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);
        $this->loadModel('execution');
        list($this->lang->modulePageNav, $executionID) = $this->milestone->getPageNav($projectID, $executionID, $productID);

        $this->view->title = $this->lang->milestone->title;

        if(!$executionID)
        {
            $this->view->executionID = $executionID;
            $this->display();
            die;
        }

        $execution = $this->loadModel('execution')->getByID($executionID);
        $children  = $this->milestone->getStagesOfMilestone($execution);
        $productID = $this->loadModel('product')->getProductIDByProject($executionID);
        $stageList = $this->loadModel('programplan')->getPairs($projectID, $productID);
        unset($stageList[0]);

        $this->view->executionID    = $executionID;
        $this->view->projectID      = $projectID;
        $this->view->stageList      = $stageList;
        $this->view->basicInfo      = $this->milestone->getBasicInfo($execution);
        $this->view->process        = $this->milestone->getProcess($execution);
        $this->view->charts         = $this->milestone->getCharts($execution);
        $this->view->productQuality = $this->milestone->getProductQuality($execution);
        $this->view->workhours      = $this->milestone->getWorkhours($execution);
        $this->view->measures       = $this->milestone->getMeasures($projectID, $children);
        $this->view->executionRisk  = $this->milestone->getProjectRisk($projectID);
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->stageInfo      = $this->milestone->getStageDemand($projectID, $productID, $stageList);
        $this->view->otherproblems  = $this->milestone->otherProblemsList($projectID, $children);

        $this->view->nextMilestone  = $this->milestone->getNextMilestone($execution, $stageList);

        $this->display();
    }

    public function ajaxAddMeasures()
    {
        $this->milestone->ajaxAddMeasures();
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    public function saveOtherProblem()
    {
        $re = $this->milestone->saveOtherProblem();
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    public function ajaxSaveEstimate()
    {
        $taskID = $this->post->taskID;
        $estimate = $this->post->estimate;
        $re = $this->milestone->ajaxSaveEstimate($taskID,$estimate);
        return $this->send(array('result' => 'success','message' => $this->lang->saveSuccess));
    }
}

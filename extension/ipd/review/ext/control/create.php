<?php
helper::importControl('review');
class myReview extends review
{
    public function create($projectID = 0, $object = '', $productID = 0, $reviewRange = 'all', $checkedItem = '')
    {
        $reviewedPoints = '';
        $project        = $this->loadModel('project')->getByID($projectID);

        if($project->model == 'ipd')
        {
            $reviewedPoints = $this->review->getReviewPointByProject($projectID);

            /* 过滤掉没有启用的评审点。*/
            foreach($this->lang->baseline->ipd->pointList as $point)
            {
                if($point == 'other' || $point == '') continue;
                if(!isset($reviewedPoints[$point])) unset($this->lang->baseline->ipd->pointList[$point]);
            }

            $this->view->project        = $project;
            $this->view->reviewedPoints = $reviewedPoints;
        }

        if($_POST && $project->model == 'ipd' && in_array($_POST['object'], $this->config->review->ipdPointOrder))
        {
            $_POST['point'] = $_POST['object'];
            $stage = $this->review->getStageByPoint($_POST['point'], $projectID);
            if($stage && $_POST['point'] != 'other')
            {
                if($_POST['begin']    && $_POST['begin'] < $stage->begin)  dao::$errors['begin']    = sprintf($this->lang->review->errorBeginTip, $stage->begin);
                if($_POST['deadline'] && $_POST['deadline'] > $stage->end) dao::$errors['deadline'] = sprintf($this->lang->review->errorDeadlineTip, $stage->end);
            }

            if(!empty($_POST['deadline']) && $_POST['begin'] > $_POST['deadline']) return $this->send(array('result' => 'fail', 'message' => $this->lang->review->errorLetter));

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return parent::create($projectID, $object, $productID, $reviewRange, $checkedItem);
    }
}

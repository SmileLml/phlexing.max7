<?php
helper::importControl('review');
class myReview extends review
{
    public function edit($reviewID = 0)
    {
        $stage = $this->review->getStageByReview($reviewID);
        $this->view->stageBeginDate = $stage ? $stage->begin : '';
        $this->view->stageEndDate   = $stage ? $stage->end : '';

        return parent::edit($reviewID);
    }
}

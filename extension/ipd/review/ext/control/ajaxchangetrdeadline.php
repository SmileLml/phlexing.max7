<?php
helper::importControl('review');
class myReview extends review
{
    /**
     * Ajax change TR deadline.
     *
     * @access public
     * @return void
     */
    public function ajaxChangeTRDeadline()
    {
        if(!$_POST['deadline']) return;
        $ID            = $_POST['id'] ? $_POST['id'] : 0;
        $projectID     = $_POST['projectID'] ? $_POST['projectID'] : 0;
        $deadline      = $_POST['deadline'];
        $point         = explode('-', $ID);
        $pointID       = $point[2];
        $pointCategory = $point[1];

        $point = $this->dao->select('t1.*,t2.id as reviewID, t2.status as rawStatus')->from(TABLE_OBJECT)->alias('t1')
            ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.id=t2.object')
            ->where('t1.id')->eq($pointID)
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t1.category')->eq($pointCategory)
            ->fetch();

        if(!$point) return;
        if($point->reviewID and $point->rawStatus != 'draft')
        {
            $oldReview = $this->loadModel('review')->getById($point->reviewID);
            $this->dao->update(TABLE_REVIEW)->set('deadline')->eq($deadline)->where('id')->eq($point->reviewID)->exec();

            $review = new stdclass();
            $review->deadline = $deadline;

            $changes  = common::createChanges($oldReview, $review);
            $actionID = $this->loadModel('action')->create('review', $point->reviewID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        $this->dao->update(TABLE_OBJECT)->set('end')->eq($deadline)->where('id')->eq($pointID)->exec();
    }
}

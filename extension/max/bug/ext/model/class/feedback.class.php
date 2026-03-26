<?php
class feedbackBug extends bugModel
{
    /**
     * @return int|false
     * @param object $bug
     * @param string $from
     */
    public function create($bug, $from = '')
    {
        if(empty($_POST['feedback']) && empty($_POST['ticket']))
        {
            $bugID    = parent::create($bug, $from);
            $relation = new stdClass();
            $relation->relation = 'generated';
            $relation->BID      = $bugID;
            $relation->BType    = 'bug';
            $relation->product  = 0;
            if(!empty($bug->story))
            {
                $relation->AID   = $bug->story;
                $relation->AType = 'story';
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
            if(!empty($bug->task))
            {
                $relation->AID   = $bug->task;
                $relation->AType = 'task';
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
            if(!empty($bug->case))
            {
                $relation->AID   = $bug->case;
                $relation->AType = 'testcase';
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
            return $bugID;
        }

        $this->dao->insert(TABLE_BUG)->data($bug, 'laneID')
            ->autoCheck()
            ->checkIF(!empty($bug->notifyEmail), 'notifyEmail', 'email')
            ->batchCheck($this->config->bug->create->requiredFields, 'notempty')
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        $bugID = $this->dao->lastInsertID();
        $this->loadModel('score')->create('bug', 'create', $bugID);

        /* If bug is from feedback, record action for feedback and add files to bug from feedback. */
        if($this->post->feedback)
        {
            $feedbackID  = $this->post->feedback;
            $oldFeedback = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->eq($feedbackID)->fetch();
            $this->loadModel('action')->create('bug', $bugID, 'fromFeedback', '', $feedbackID);

            $feedback = new stdclass();
            $feedback->status        = 'commenting';
            $feedback->result        = $bugID;
            $feedback->processedBy   = $this->app->user->account;
            $feedback->processedDate = helper::now();
            $feedback->solution      = 'tobug';

            $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($feedbackID)->exec();

            $this->loadModel('action')->create('feedback', $feedbackID, 'ToBug', '', $bugID);
            if($oldFeedback->status != 'commenting') $this->action->create('feedback', $feedbackID, 'syncDoingByBug', '', $bugID);

            $relation = new stdClass();
            $relation->AType    = 'feedback';
            $relation->AID      = $feedbackID;
            $relation->relation = 'transferredto';
            $relation->BType    = 'bug';
            $relation->BID      = $bugID;
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

            $this->loadModel('feedback')->updateSubStatus($feedbackID, $feedback->status);
        }

        /* If story is from feedback, record action for feedback and add files to story from feedback. */
        if($this->post->ticket)
        {
            $ticketID = $this->post->ticket;
            $this->loadModel('action')->create('bug', $bugID, 'fromTicket', '', $ticketID);

            $ticket = new stdClass();
            $ticket->ticketId   = $ticketID;
            $ticket->objectId   = $bugID;
            $ticket->objectType = 'bug';

            $this->dao->insert(TABLE_TICKETRELATION)->data($ticket)->exec();

            $this->loadModel('action')->create('ticket', $ticketID, 'ToBug', '', $bugID);

            $relation = new stdClass();
            $relation->AType    = 'ticket';
            $relation->AID      = $ticketID;
            $relation->relation = 'transferredto';
            $relation->BType    = 'bug';
            $relation->BID      = $bugID;
            $relation->product  = 0;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
        }

        return $bugID;
    }

    /**
     * @return object|false
     * @param int $bugID
     * @param bool $setImgSize
     */
    public function getByID($bugID, $setImgSize = false)
    {
        $bug = parent::getById($bugID, $setImgSize);

        if(!empty($bug->feedback))
        {
            $feedback = $this->loadModel('feedback')->getById($bug->feedback);
            $bug->feedbackTitle = $feedback->title;
        }

        return $bug;
    }
}

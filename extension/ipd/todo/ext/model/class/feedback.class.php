<?php
class feedbackTodo extends todoModel
{
    /**
     * @return int|false
     * @param object $todo
     */
    public function create($todo)
    {
        $todoID = parent::create($todo);
        if(empty($todoID)) return false;

        if($this->post->feedback) $this->dao->update(TABLE_TODO)->set('feedback')->eq($this->post->feedback)->where('id')->eq($todoID)->exec();

        /* If todo is from feedback, record action for feedback. */
        $todo = $this->getByID($todoID);
        if($todo->type == 'feedback' && $todo->objectID)
        {
            $oldFeedback = $this->dao->select('*')->from(TABLE_FEEDBACK)->where('id')->eq($todo->objectID)->fetch();

            $feedback = new stdclass();
            $feedback->status        = 'commenting';
            $feedback->result        = $todoID;
            $feedback->processedBy   = $this->app->user->account;
            $feedback->processedDate = helper::now();
            $feedback->solution      = 'totodo';

            $this->dao->update(TABLE_FEEDBACK)->data($feedback)->where('id')->eq($todo->objectID)->exec();

            $this->loadModel('action')->create('feedback', $todo->objectID, 'totodo', '', $todoID);
            if($oldFeedback->status != 'commenting') $this->action->create('feedback', $todo->objectID, 'syncDoingByTodo', '', $todoID);

            $this->loadModel('feedback')->updateStatus('todo', $todo->objectID, $todo->status);
            if(!in_array($todo->status, array('doing', 'done'))) $this->feedback->updateSubStatus($todo->objectID, $feedback->status);
        }

        return $todoID;
    }
}

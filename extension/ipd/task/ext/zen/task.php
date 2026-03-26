<?php
/**
 * 通过传入的对象ID设置任务信息。
 * Set task through the input object ID.
 *
 * @param  int    $storyID
 * @param  int    $moduleID
 * @param  int    $taskID
 * @param  int    $todoID
 * @param  int    $bugID
 * @param  array  $output
 * @access public
 * @return object
 */
public function setTaskByObjectID($storyID, $moduleID, $taskID, $todoID, $bugID, $output = array())
{
    if(!isset($output['feedbackID'])) return parent::setTaskByObjectID($storyID, $moduleID, $taskID, $todoID, $bugID, $output);

    $this->loadModel('feedback');
    $feedbackID = $output['feedbackID'];
    $feedback   = $this->feedback->getById($feedbackID);
    $actions    = $this->loadModel('action')->getList('feedback', $feedbackID);
    foreach($actions as $action)
    {
        if($action->action == 'reviewed' and $action->comment)
        {
            $feedback->desc .= $feedback->desc ? '<br/>' . $this->lang->feedback->reviewOpinion . '：' . $action->comment : $this->lang->feedback->reviewOpinion . '：' . $action->comment;
        }
    }

    /* Init vars. */
    $task = $this->config->task->create->template;
    $task->module = $moduleID ? $moduleID : (int)$this->cookie->lastTaskModule;
    $task->name   = $feedback->title;
    $task->desc   = $feedback->desc;

    /* Set Menu. */
    $this->feedback->setMenu($feedback->product);
    $this->lang->feedback->menu->browse['subModule'] = 'task';

    $this->view->feedbackID = $feedbackID;

    return $task;
}

/**
 * 处理创建后选择跳转的返回信息。
 * Process the return information for selecting a jump after creation.
 *
 * @param  object    $task
 * @param  int       $executionID
 * @param  string    $afterChoose continueAdding|toTaskList|toStoryList
 * @access public
 * @return array
 */
public function generalCreateResponse($task, $executionID, $afterChoose)
{
    $feedbackID = $this->post->feedback;
    if(empty($feedbackID)) return parent::generalCreateResponse($task, $executionID, $afterChoose);

    return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('feedback', 'adminView', "feedbackID=$feedbackID"));
}

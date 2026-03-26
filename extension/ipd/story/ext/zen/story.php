<?php
/**
 * 初始化创建需求的一些字段的数据。
 * Init story for create.
 *
 * @param  int       $planID
 * @param  int       $storyID
 * @param  int       $bugID
 * @param  int       $todoID
 * @param  string    $extra feedback扩展使用
 * @access public
 * @return object
 */
public function initStoryForCreate($planID, $storyID, $bugID, $todoID, $extra = '')
{
    $fields = parent::initStoryForCreate($planID, $storyID, $bugID, $todoID, $extra);

    $extra = str_replace(array(',', ' '), array('&', ''), $extra);
    parse_str($extra, $output);
    extract($output);

    if(!empty($fromType))
    {
        /* Get information and history of from object. */
        $fromObject   = $this->loadModel($fromType)->getById($fromID);
        $fromOpenedBy = $this->loadModel('user')->getById($fromObject->openedBy);
        $actions      = $this->loadModel('action')->getList($fromType, $fromID);

        /* Change desc if feedback has been reviewed. */
        if($fromType == 'feedback')
        {
            foreach($actions as $action)
            {
                if($action->action == 'reviewed' and $action->comment) $fromObject->desc .= $fromObject->desc ? '<br/>' . $this->lang->feedback->reviewOpinion . '：' . $action->comment : $this->lang->feedback->reviewOpinion . '：' . $action->comment;
            }
        }

        $fields->source     = $fromOpenedBy->role;
        $fields->sourceNote = $fromOpenedBy->realname;
        $fields->productID  = $fromObject->product;
        $fields->title      = $fromObject->title;
        $fields->module     = $fromObject->module;
        $fields->spec       = $fromObject->desc;
    }

    return $fields;
}

/**
 * 获取创建需求后的跳转地址。
 * Get location when after create story.
 *
 * @param  int       $productID
 * @param  string    $branch
 * @param  int       $objectID
 * @param  int       $storyID
 * @param  string    $storyType
 * @param  string    $extra
 * @access public
 * @return string
 */
public function getAfterCreateLocation($productID, $branch, $objectID, $storyID, $storyType, $extra = '')
{
    $extra = str_replace(array(',', ' '), array('&', ''), $extra);
    parse_str($extra, $output);
    extract($output);

    if(empty($fromType)) return parent::getAfterCreateLocation($productID, $branch, $objectID, $storyID, $storyType, $extra);

    $location = $fromType == 'feedback' ? $this->createLink('feedback', 'adminView', "feedbackID=$fromID") : $this->createLink($fromType, 'view', "fromObjectID=$fromID");
    return $location;
}

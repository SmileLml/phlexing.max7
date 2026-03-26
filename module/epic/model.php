<?php
class epicModel extends model
{
    /**
     * @param object $data
     * @param string $action
     */
    public static function isClickable($data, $action)
    {
        global $app;
        $app->control->loadModel('story');
        return call_user_func_array(array('storyModel', 'isClickable'), array($data, $action));
    }

    /**
     * @param object $story
     * @param string $actionType
     */
    public function getToAndCcList($story, $actionType)
    {
        return $this->loadModel('story')->getToAndCcList($story, $actionType);
    }
}

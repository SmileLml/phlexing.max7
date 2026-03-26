<?php
class action extends control
{
    /**
     * @param string $objectType
     * @param int $objectID
     */
    public function ajaxGetList($objectType, $objectID)
    {
        $this->app->loadLang($objectType);
        $actions = $this->action->getList($objectType, $objectID);
        if($objectType == 'feedback') $actions = $this->loadModel('feedback')->processActions($objectID, $actions);

        $actions = $this->action->buildActionList($actions);
        return $this->send($actions);
    }
}

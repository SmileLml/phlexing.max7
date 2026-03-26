<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * @param int|string $extra
     * @param int $objectID
     * @param string $objectType
     */
    public function confirmDemandRetract($objectID = 0, $objectType = '', $extra = '')
    {
        if($_POST)
        {
            $this->loadModel('action')->create($objectType, $objectID, 'confirmedRetract', '', $extra);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' =>true));
        }

        $this->view->title      = $this->lang->story->confirmDemandRetract;
        $this->view->stories    = $this->story->getByList($extra, 'requirement');
        $this->view->objectType = $objectType;

        $this->display();
    }
}

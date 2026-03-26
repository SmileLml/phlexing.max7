<?php
class custom extends control
{
    /**
     * 新增关联关系。
     * Create relation.
     *
     * @access public
     * @return void
     */
    public function createRelation()
    {
        if($_POST)
        {
            $formData = $this->customZen->processRelationData();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->custom->createRelation($formData);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->allRelationName = $this->custom->getAllRelationName();
        $this->display();
    }
}

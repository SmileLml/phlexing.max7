<?php
class custom extends control
{
    /**
     * 编辑关联关系。
     * Edit relation.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editRelation($id)
    {
        if($_POST)
        {
            $formData = $this->customZen->processRelationData();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->custom->editRelation($id, $formData);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->allRelationName = $this->custom->getAllRelationName($id);
        $this->view->relation        = $this->custom->getRelationByID($id);
        $this->display();
    }
}

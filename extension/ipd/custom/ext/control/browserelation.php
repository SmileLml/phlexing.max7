<?php
class custom extends control
{
    /**
     * 关联关系列表。
     * Browse relation.
     *
     * @access public
     * @return void
     */
    public function browseRelation()
    {
        $this->view->title        = $this->lang->custom->browseRelation;
        $this->view->relationList = $this->custom->getRelationList();

        $this->display();
    }
}

<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Mini program view.
     *
     * @param string $id
     * @access public
     * @return void
     */
    public function miniProgramView($id)
    {
        $miniProgram = $this->ai->getMiniProgramByID($id);
        if(empty($miniProgram)) return $this->sendError($this->lang->ai->noMiniProgram);
        if(empty($miniProgram->model)) $miniProgram->model = 'default';

        $this->view->miniProgram  = $miniProgram;
        $this->view->models       = $this->ai->getLanguageModelNamesWithDefault();
        $this->view->categoryList = array_merge($this->lang->ai->miniPrograms->categoryList, $this->ai->getCustomCategories());
        $this->view->preAndNext   = $this->loadModel('common')->getPreAndNextObject('miniprogram', $id);
        $this->view->actions      = $this->loadModel('action')->getList('miniProgram', $id);
        $this->view->fields       = $this->ai->getMiniProgramFields($id);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->title        = "{$this->lang->ai->miniPrograms->common}#{$miniProgram->id} $miniProgram->name";
        $this->display();
    }
}

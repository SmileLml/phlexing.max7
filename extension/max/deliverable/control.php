<?php
class deliverable extends control
{
    /**
     * 交付物列表。
     * Browse deliverables.
     *
     * @param string $browseType
     * @param int    $param
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 0, $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID   = $browseType == 'bysearch' ? (int)$param : 0;
        $actionURL = $this->createLink('deliverable', 'browse', "browseType=bysearch&queryID=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->deliverable->buildBrowseSearchForm($queryID, $actionURL);

        $this->view->title        = $this->lang->deliverable->common;
        $this->view->deliverables = $this->deliverable->getList($browseType, $queryID, $orderBy, $pager);
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->browseType   = $browseType;
        $this->view->param        = $param;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter');
        $this->display();
    }

    /**
     * 创建交付物。
     * Create deliverable.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $deliverable = form::data($this->config->deliverable->form->create)
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::today())
                ->get();

            $this->deliverable->create($deliverable);

            if(dao::isError())  return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => inlink('browse')));
        }

        $this->view->title     = $this->lang->deliverable->create;
        $this->view->modelList = $this->deliverable->buildModelList('project');
        $this->display();
    }

    /**
     * 编辑交付物。
     * Edit deliverable.
     *
     * @param int     $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        if($_POST)
        {
            $deliverable = $this->deliverableZen->buildDeliverableForEdit($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->deliverable->update($id, $deliverable);
            if(dao::isError())  return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->sendSuccess(array('load' => inlink('browse')));
        }

        $deliverable = $this->deliverable->getByID($id);

        $this->view->title       = $this->lang->deliverable->edit;
        $this->view->deliverable = $deliverable;
        $this->view->modelList   = $this->deliverable->buildModelList($deliverable->module);
        $this->display();
    }

    /**
     * 交付物详情页。
     * Deliverable view.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $deliverable = $this->deliverable->getByID($id);

        $this->view->title       = $this->lang->deliverable->view;
        $this->view->deliverable = $deliverable;
        $this->view->modelList   = $this->deliverable->buildModelList('all');
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 删除交付物详。
     * Delete deliverable.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->deliverable->delete(TABLE_DELIVERABLE, $id);
        return $this->sendSuccess(array('load' => inlink('browse')));
    }

    /**
     * Ajax获取交付物适用范围列表。
     * Ajax get deliverable model list.
     *
     * @param string $type execution|project
     * @access public
     * @return void
     */
    public function ajaxGetModelList($type = 'execution')
    {
        $items     = array();
        $modelList = $this->deliverable->buildModelList($type);
        foreach($modelList as $key => $value)
        {
            $items[] = array('value' => $key, 'text' => $value);
        }

        return print(json_encode($items));
    }
}

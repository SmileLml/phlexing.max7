<?php
helper::importControl('tree');
class mytree extends tree
{
    /**
     * Browse Groups.
     *
     * @param  int    $dimensionID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function browseGroup($dimensionID = 0, $groupID = 0, $type = 'chart')
    {
        $this->loadModel('dimension');
        $this->app->loadLang('chart');

        /* Set menu for second link active. */
        $moduleName = $type == 'report' ? 'custom' : $type;
        $this->lang->bi->menu->{$moduleName}['subModule'] = 'tree';

        if($dimensionID)
        {
            $dimensions  = $this->dimension->getList();
            $dimensionID = $this->dimension->saveState($dimensionID, $dimensions);
            $this->dimension->setSwitcherMenu($dimensionID, $type);
            $this->loadModel('setting')->setItem($this->app->user->account . 'common.dimension.lastDimension', $dimensionID);
        }

        $backMethod = array('pivot' => 'browse', 'chart' => 'browse', 'dataview' => 'browse', 'report' => 'browsereport');
        if($type == 'dataview') $this->config->excludeDropmenuList[] = 'tree-browsegroup';

        $this->view->gobackLink     = $this->createLink($type, $backMethod[$type]);
        $this->view->title          = $this->lang->tree->manageGroup;
        $this->view->rootID         = $dimensionID;
        $this->view->dimensionID    = $dimensionID;
        $this->view->viewType       = $type;
        $this->view->placeholder    = $this->lang->tree->name;
        $this->view->sons            = $this->tree->getSons($dimensionID, $groupID, $type);
        $this->view->currentModuleID = $groupID;
        $this->view->parentModules   = $this->tree->getParents($groupID);
        $this->view->tree            = $this->tree->getGroupStructure($dimensionID, 0, $type);

        $this->display();
    }
}

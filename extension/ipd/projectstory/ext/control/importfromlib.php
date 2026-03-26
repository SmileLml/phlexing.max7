<?php
class myProjectstory extends projectstory
{
    /**
     * Import from library.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $libID
     * @param  string $storyType
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importFromLib($projectID, $productID, $libID = 0, $storyType = 'story', $orderBy = 'id_desc', $browseType = 'all', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project')->setMenu($projectID);

        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;

        if($_POST)
        {
            $this->projectstory->importFromLib($projectID, $productID);
            return $this->send(array('locate' => $this->session->storyList));
        }

        $libraries = $this->loadModel('assetlib')->getPairs('story');
        if(empty($libraries)) return $this->send(array('load'=> array('locate' => $this->session->storyList, 'alert' => $this->lang->assetlib->noLibrary)));
        if(empty($libID) or !isset($libraries[$libID])) $libID = key($libraries);

        /* Build the search form. */
        $actionURL = $this->createLink('projectstory', 'importFromLib', "projectID=$projectID&productID=$productID&libID=$libID&storyType=$storyType&orderBy=$orderBy&browseType=bysearch&queryID=myQueryID");
        $this->config->projectstory->search['actionURL'] = $actionURL;
        $this->config->projectstory->search['queryID']   = $queryID;
        $this->config->projectstory->search['fields']['title'] = $this->lang->story->name;
        $this->config->projectstory->search['fields']['lib']   = $this->lang->assetlib->lib;
        $this->config->projectstory->search['params']['lib']   = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $libID => $libraries[$libID], 'all' => $this->lang->projectstory->allLib));
        $this->loadModel('search')->setSearchParams($this->config->projectstory->search);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $stories = $this->projectstory->getNotImported($libraries, $libID, $projectID, $productID, $orderBy, $browseType, $queryID);
        $pager   = pager::init(count($stories), $recPerPage, $pageID);
        $stories = array_chunk($stories, $pager->recPerPage);
        $product = $this->loadModel('product')->getByID($productID);
        if($product->type != 'normal') $this->view->branches = $this->loadModel('branch')->getPairs($productID, '', $projectID);

        $this->view->title = $this->lang->projectstory->common . $this->lang->hyphen . $this->lang->projectstory->importFromLib;

        $this->view->libraries  = $libraries;
        $this->view->libID      = $libID;
        $this->view->projectID  = $projectID;
        $this->view->productID  = $productID;
        $this->view->product    = $product;
        $this->view->stories    = empty($stories) ? $stories : $stories[$pageID - 1];;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->queryID    = $queryID;
        $this->view->storyType  = $storyType;
        $this->view->gradeGroup = $this->loadModel('story')->getGradeGroup();
        $this->view->showGrade  = $this->config->edition == 'ipd';

        $this->display();
    }
}

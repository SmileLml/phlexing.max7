<?php
helper::importControl('product');
class myProduct extends product
{
    /**
     * Browse a product.
     *
     * @param  int         $productID
     * @param  string      $branch
     * @param  string      $browseType
     * @param  int         $param
     * @param  string      $storyType
     * @param  string      $orderBy
     * @param  int         $recTotal
     * @param  int         $recPerPage
     * @param  int         $pageID
     * @param  int         $projectID
     * @param  string      $from
     * @param  int         $blockID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = '', $browseType = '', $param = 0, $storyType = 'requirement', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1, $projectID = 0, $from = 'product', $blockID = 0)
    {
        if(!in_array($storyType, array('requirement', 'epic'))) $storyType = 'requirement';
        $this->loadModel('datatable');
        $this->loadModel('roadmap');
        $datatableId  = $this->moduleName . ucfirst($this->methodName);
        $vision       = $this->config->vision;
        if(!isset($this->config->datatable->$datatableId->mode))
        {
            $this->loadModel('setting')->setItem("{$this->app->user->account}.datatable.$datatableId.mode", 'datatable');
            $this->config->datatable->$datatableId = new stdclass();
            $this->config->datatable->$datatableId->mode = 'datatable';
        }

        if(!is_string($this->cookie->preBranch) and !is_int($this->cookie->preBranch)) $this->cookie->preBranch = (int)$this->cookie->preBranch;
        $product = $this->product->getById($productID);
        if($product and $product->type != 'normal')
        {
            $branchPairs = $this->loadModel('branch')->getPairs($productID, 'all');
            $branch      = ($this->cookie->preBranch !== '' and $branch === '' and isset($branchPairs[$this->cookie->preBranch])) ? $this->cookie->preBranch : $branch;
        }
        else
        {
            $branch = 'all';
        }

        $this->app->loadLang($storyType);
        $this->config->product->search['params']['stage']['values'] = $this->lang->{$storyType}->stageList;

        $roadBranch = ($product and $product->type != 'normal' and (int)$branch === 0) ? 'all' : $branch;
        $this->view->roadmaps = $this->roadmap->getPairs($productID, $roadBranch, 'linkRoadmap');
        parent::browse($productID, $branch, $browseType, $param, $storyType, $orderBy, $recTotal, $recPerPage, $pageID, $projectID, $from, $blockID);
    }
}

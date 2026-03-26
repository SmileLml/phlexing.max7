<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @link        https://www.zentao.net
 */
helper::importControl('pivot');
class myPivot extends pivot
{
    /**
     * Version statistics table.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function build($productID = 0)
    {
        $this->app->loadLang('bug');

        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        if(!$productID) $productID = key($products);

        $projectID = $this->lang->navGroup->pivot == 'project' ? $this->session->project : 0;
        $buildBugs = $this->pivot->getBuildBugs($productID);

        $this->view->title     = $this->lang->pivot->build;
        $this->view->products  = $products;
        $this->view->productID = $productID;
        $this->view->bugs      = $buildBugs['bugs'];
        $this->view->summary   = $buildBugs['summary'];
        $this->view->projects  = $this->loadModel('product')->getProjectPairsByProduct($productID);
        $this->view->builds    = $this->loadModel('build')->getBuildPairs($productID, 'all', '');
        $this->view->submenu   = 'test';
        $this->display();
    }
}

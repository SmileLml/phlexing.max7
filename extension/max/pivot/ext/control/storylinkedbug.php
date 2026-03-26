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
     * Story related bug summary table.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function storyLinkedBug($productID = 0, $moduleID = 0)
    {
        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        if(!$productID) $productID = key($products);

        $this->app->loadLang('bug');
        $this->view->title     = $this->lang->pivot->storyLinkedBug;
        $this->view->products  = $products;
        $this->view->modules   = arrayUnion(array('/'), $this->loadModel('tree')->getOptionMenu($productID, 'story', 0, 'all'));
        $this->view->productID = $productID;
        $this->view->moduleID  = $moduleID;
        $this->view->stories   = $this->pivot->getStoryBugs($productID, $moduleID);
        $this->view->submenu   = 'test';
        $this->display();
    }
}

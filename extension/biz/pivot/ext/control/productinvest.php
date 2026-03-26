<?php
/**
 * The control file of pivot module of zentaopms.
 *
 * @copyright   copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     zpl (http://zpl.pub/page/zplv12.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @link        https://www.zentao.net
 */
helper::importControl('pivot');
class myPivot extends pivot
{
    /**
     * Product invest pivot.
     *
     * @access public
     * @return void
     */
    public function productInvest($conditions = '')
    {
        $this->app->loadLang('story');
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');

        $this->view->title      = $this->lang->pivot->productInvest;
        $this->view->investData = $this->pivot->getProductInvest($conditions);
        $this->view->submenu    = 'product';
        $this->view->conditions = $conditions;
        $this->display();
    }
}

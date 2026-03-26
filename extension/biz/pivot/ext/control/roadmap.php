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
     * Product roadmap.
     *
     * @param  string $conditions
     * @access public
     * @return void
     */
    public function roadmap($conditions = '')
    {
        $this->app->loadConfig('productplan');
        $roadmaps = $this->pivot->getRoadmaps($conditions);

        $this->view->title      = $this->lang->pivot->roadmap;
        $this->view->position[] = $this->lang->pivot->roadmap;
        $this->view->products   = $roadmaps['products'];
        $this->view->plans      = $roadmaps['plans'];
        $this->view->submenu    = 'product';
        $this->view->conditions = $conditions;
        $this->display();
    }
}

<?php
/**
 * The control file of watermark module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 */
?>
<?php
class watermark extends control
{
    /**
     * @var watermarkModel;
     */
    public $watermark;

    /**
     * index html.
     *
     * @param string $type
     * @access public
     * @return void
     */
    public function index($type = '')
    {
        if($_POST)
        {
            $this->watermark->setConfig();
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('index')));
        }

        $watermarkConfig = $this->watermark->getConfig();

        $this->view->title   = $this->lang->watermark->common;
        $this->view->type    = $type;
        $this->view->enabled = isset($watermarkConfig->enabled) ? $watermarkConfig->enabled : 0;
        $this->view->content = isset($watermarkConfig->content) ? $watermarkConfig->content : '';

        $this->view->displayName = $this->app->user->realname;
        $this->view->account     = $this->app->user->account;
        $this->view->phone       = $this->app->user->phone;
        $this->view->email       = $this->app->user->email;

        $this->display();
    }
}

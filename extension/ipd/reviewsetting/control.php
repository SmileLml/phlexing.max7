<?php
/**
 * The control file of reviewsetting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     reviewsetting
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class reviewsetting extends control
{
    /**
     * Configure version Numbers for various objects.
     *
     * @param  string $type
     * @param  string $object
     * @access public
     * @return void
     */
    public function version($type = 'waterfall', $object = 'PP')
    {
        $this->app->loadLang('baseline');
        $this->app->loadLang('reviewcl');
        unset($this->lang->baseline->objectList['']);

        $setting = $this->loadModel('setting');
        $this->loadModel('stage')->setMenu($type);

        $owner   = 'system';
        $module  = 'company';
        $section = $type == 'waterfall' ? 'version' : 'plusVersion';
        $method  = $type == 'waterfall' ? 'version' : 'waterfallplusVersion';

        if($_POST)
        {
            $data = fixer::input('post')->get();
            if($data->object)
            {
                $unitGroup = array_chunk($data->unit, 4);

                foreach ($unitGroup as $key => $unit)
                {
                    if($unit[0] == 'fixed' && trim($unit[1]) == false) unset($unitGroup[$key]);
                    if(empty($unit[0])) unset($unitGroup[$key]);
                }
                $data->unit = $unitGroup;
                $setting->setItem("{$owner}.{$module}.{$section}.{$data->object}", json_encode($data));
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('reviewsetting', $method, "type={$type}&object={$data->object}")));
        }

        $result     = $setting->getItem("owner={$owner}&module={$module}&section={$section}&key={$object}");
        $objectList = $type == 'waterfall' ? 'objectList' : 'plusObjectList';

        $this->view->title       = $this->lang->reviewsetting->setting . $this->lang->reviewsetting->version;
        $this->view->joint       = $this->lang->reviewsetting->joint;
        $this->view->object      = $object;
        $this->view->type        = $type;
        $this->view->method      = $method;
        $this->view->result      = json_decode($result);
        $this->view->objectType  = $this->lang->baseline->{$objectList};
        $this->view->versionForm = $this->lang->reviewsetting->versionForm;
        $this->display();
    }
    /**
     * Configure version Numbers for various objects.
     *
     * @param  string $type
     * @param  string $object
     * @access public
     * @return void
     */
    public function waterfallplusVersion($type = 'waterfallplus', $object = 'PP')
    {
        echo $this->fetch('reviewsetting', 'version', "type=waterfallplus&object=$object");
    }
}

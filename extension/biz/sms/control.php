<?php
/**
 * The control file of sms of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sms
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class sms extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('message');
    }

    public function index()
    {
        if($_POST)
        {
            $this->sms->saveSettings();
            if(dao::isError()) die(js::error(dao::getError()));
            echo js::alert($this->lang->sms->successSave);
            die(js::reload('parent'));
        }
        $this->view->title     = $this->lang->sms->common;
        $this->view->smsConfig = empty($this->app->config->sms) ? '' : $this->app->config->sms;
        $this->display();
    }

    public function reset()
    {
        $this->loadModel('setting')->deleteItems('owner=system&module=sms');
        $this->locate(inlink('index'));
    }

    public function test()
    {
        $mobile = $this->app->user->mobile;
        if(empty($mobile))die(js::alert($this->lang->sms->noMobile));
        $result = $this->sms->sendContent($mobile, $this->lang->sms->test);
        if(!empty($result)) die(js::alert($this->lang->sms->error . ', ' . $this->lang->sms->result . json_encode($result)));
        die(js::alert($this->lang->sms->successSend . $this->lang->sms->result . json_encode($result)));
    }
}

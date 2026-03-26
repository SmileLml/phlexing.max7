<?php
/**
 * The control file of classify module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     classify
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class classify extends control
{
    /**
     * Configure the category items for the process.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function browse($type = 'waterfall')
    {
        $this->session->set('model', $type);

        $lang   = 'all';
        $module = 'process';
        $field  = $type == 'waterfall' ? 'classify' : $type . 'Classify';
        $this->loadModel('custom');
        $this->loadModel('auditcl')->setMenu($this->session->model);

        if($_POST)
        {
            $this->custom->deleteItems("lang=$lang&module=$module&section=$field");
            $data = fixer::input('post')->get();
            foreach($data->keys as $index => $key)
            {
                $value  = $data->values[$index];
                $system = $data->systems[$index];
                if(empty($key) || empty($value)) continue;
                $this->custom->setItem("{$lang}.{$module}.{$field}.{$key}.{$system}", $value);
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success','message' => $this->lang->saveSuccess, 'locate' => $this->createLink('classify', 'browse', "type=$type")));
        }

        $this->view->title       = $this->lang->classify->classifyAdd;
        $this->view->processLang = $this->custom->getItems("lang=$lang&module=$module&section=$field");
        $this->view->model       = $this->session->model;
        $this->display();
    }
}

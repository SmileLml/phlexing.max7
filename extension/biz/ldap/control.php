<?php
/**
 * The control file of ldap of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     ldap
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class ldap extends control
{
    public function index()
    {
        $this->locate(inlink('set'));
    }

    public function set()
    {
        $this->session->set('ldapBackLink', $this->app->getURI(true));

        if($_POST)
        {
            $this->ldap->saveSettings();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(empty($_POST['turnon']) or !common::hasPriv('user', 'importldap')) return $this->send(array('result' => 'success', 'message' => $this->lang->ldap->successSave, 'locate' => inlink('set')));
            if(!empty($_POST['turnon'])) return $this->send(array('result' => 'success', 'callback' => "importTip"));
        }

        $this->view->title      = $this->lang->ldap->common;
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->view->ldapConfig = empty($this->app->config->ldap) ? '' : $this->app->config->ldap;
        $this->display();
    }
}


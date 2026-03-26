<?php
helper::importControl('user');
class myUser extends user
{
    public function importLDAP($type = 'all', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('ldap');

        $type    = strtolower($type);
        $queryID = $type == 'bysearch' ? (int)$param : 0;

        if($this->config->edition != 'open')
        {
            if(function_exists('ioncube_license_properties')) $properties = ioncube_license_properties();
            $userCount = 0;
            $maxUsers  = false;
            if(!empty($properties['user']) and $this->config->vision == 'rnd')
            {
                $userCount = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->andWhere('visions')->like('%rnd%')->fetch('count');
                $maxUsers  = $properties['user']['value'] <= $userCount;
            }
            elseif(!empty($properties['lite']) and $this->config->vision == 'lite')
            {
                $userCount = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->andWhere('visions')->like('%lite%')->fetch('count');
                $maxUsers  = $properties['lite']['value'] <= $userCount;
            }

            if($maxUsers) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->error->userLimit));

            if($_POST)
            {
                foreach($this->post->add as $i => $add)
                {
                    if(!$maxUsers)
                    {
                        $userCount++;
                        if($this->config->vision == 'rnd'  and isset($properties['user']) and $properties['user']['value'] <= $userCount) $maxUsers = true;
                        if($this->config->vision == 'lite' and isset($properties['lite']) and $properties['lite']['value'] <= $userCount) $maxUsers = true;
                    }
                    else
                    {
                        unset($_POST['add'][$i]);
                    }
                }
            }
        }

        if($_POST)
        {
            $error = $this->user->importLDAP($type, $queryID);
            if(!empty($error))
            {
                if(is_array($error))
                {
                    if(isset($error['repeat'])) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->user->error->repeat, join(',', $error['repeat']))));
                    if(isset($error['ill'])) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->user->error->illaccount, join(',', $error['ill']))));
                }

                if(is_string($error)) return $this->send(array('result' => 'fail', 'message' => $error));

                return $this->send(array('result' => 'success', 'load' => true));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('company', 'browse')));
        }

        if(!extension_loaded('ldap'))
        {
            $this->app->loadLang('ldap');
            return $this->send(array('result' => 'fail', 'message' => $this->lang->ldap->noldap->header, 'load' => $this->createLink('ldap', 'set')));
        }

        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        $users = $this->user->getLDAPUser($type, $queryID);
        if($users == 'off') return $this->send(array('result' => 'fail', 'message' => $this->lang->user->notice->ldapoff, 'load' => $this->createLink('ldap', 'index')));

        $ldapError = '';
        if(is_array($users) && isset($users['result']) && $users['result'] == 'fail')
        {
            $ldapError = $users['message'];
            $users = array();
        }

        $recTotal = count($users);
        if($this->cookie->pagerUserImportldap) $recPerPage = $this->cookie->pagerUserImportldap;

        $users = array_chunk($users, $recPerPage);
        $this->app->loadClass('pager', $static = true);

        $actionURL = $this->createLink('user', 'importLDAP', "type=bysearch&param=myQueryID");
        $this->ldap->buildSearchForm($queryID, $actionURL);

        $this->view->title        = $this->lang->user->importLDAP;
        $this->view->type         = $type;
        $this->view->pager        = pager::init($recTotal, $recPerPage, $pageID);
        $this->view->depts        = arrayUnion($this->loadModel('dept')->getOptionMenu(), array('ditto' => $this->lang->user->ditto));
        $this->view->groups       = arrayUnion($this->loadModel('group')->getPairs(), array('ditto' => $this->lang->user->ditto));
        $this->view->roles        = arrayUnion($this->lang->user->roleList, array('ditto' => $this->lang->user->ditto));
        $this->view->genders      = arrayUnion(array('' => ''), $this->lang->user->genderList, array('ditto' => $this->lang->user->ditto));
        $this->view->users        = empty($users) ? $users : $users[$pageID - 1];
        $this->view->localUsers   = arrayUnion(array('' => ''), $this->user->getUserWithoutLDAP());
        $this->view->defaultGroup = empty($this->app->config->ldap->group) ? '' : $this->app->config->ldap->group;
        $this->view->ldapError    = $ldapError;
        $this->display();
    }
}

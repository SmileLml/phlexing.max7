<?php
class ldapauthUser extends userModel
{
    /**
     * Extend identify method for ldap.
     *
     * @param  string      $account
     * @param  string      $password
     * @param  int         $passwordStrength
     * @param  object|bool $user
     * @access public
     * @return object
     */
    public function identify($account, $password, $passwordStrength = 0, $user = null)
    {
        if(empty($account)) return $user;

        $isLink = false;
        if(empty($user))
        {
            $ldap = $this->dao->select('account')->from(TABLE_USER)->where('ldap')->eq($account)->fetch('account');
            if(!empty($ldap))
            {
                $isLink = true;
                $user   = parent::identify($ldap, $password, $passwordStrength);
            }
        }
        if(empty($password)) return $user;

        $ldapConfig = $this->getLDAPConfig();
        if(empty($ldapConfig)) return $user;
        if(empty($ldapConfig->turnon)) return $user;
        $ldapConn = $this->loadModel('ldap')->ldapConnect($ldapConfig);
        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, isset($ldapConfig->version) ? $ldapConfig->version : 3);
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

        if(isset($ldapConfig->anonymous))
        {
            $ldapBind = ldap_bind($ldapConn);
        }
        else
        {
            $ldapPassword = html_entity_decode(helper::decryptPassword($ldapConfig->password));
            $ldapBind = ldap_bind($ldapConn, $ldapConfig->admin, $ldapPassword);
        }
        if(!$ldapBind)
        {
            ldap_unbind($ldapConn);
            dao::$errors[] = $this->lang->user->error->connect;
            return $user;
        }

        $ldapList = ldap_search($ldapConn, $ldapConfig->baseDN, "({$ldapConfig->account}=$account)");
        $infos    = ldap_get_entries($ldapConn, $ldapList);

        if(!isset($infos[0])) return $user;
        $info = $infos[0];
        if(empty($info['dn'])) return $user;
        $ldapBind = @ldap_bind($ldapConn, $info['dn'], html_entity_decode($password));
        if($ldapBind)
        {
            /* Ignore disabled account by useraccountcontrol in AD. */
            if(isset($info['useraccountcontrol']) and $info['useraccountcontrol'][0] == '514') return $user;

            $field = $isLink ? 'ldap' : 'account';
            $user  = $this->dao->select('*')->from(TABLE_USER)->where($field)->eq($account)->fetch();
            if(isset($user->deleted) and $user->deleted == '1') return false;

            if(empty($ldapConfig->autoCreate) and empty($user))
            {
                $this->lang->user->loginFailed = $this->lang->user->error->noImport;
                return $user;
            }
            if(!empty($ldapConfig->autoCreate) and empty($user))
            {
                /* Check user limit. */
                if(function_exists('ioncube_license_properties')) $properties = ioncube_license_properties();
                if(!empty($properties['user']))
                {
                    $userCount = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)
                        ->where('deleted')->eq(0)
                        ->beginIF($this->config->edition != 'open')->andWhere('feedback')->eq(0)->fi()
                        ->fetch('count');
                    if($properties['user']['value'] <= $userCount)
                    {
                        $this->lang->user->loginFailed = $this->lang->user->error->userLimit;
                        return $user;
                    }
                }
            }

            $newUser = empty($user);
            if(empty($user))
            {
                $user = new stdclass();
                $user->account  = $account;
                $user->realname = '';
                $user->email    = '';
                $user->phone    = '';
                $user->mobile   = '';
                $user->password = '';
                $user->dept     = 0;
                $user->ldap     = $account;
                $user->visions  = $this->config->vision;
            }

            if(!empty($ldapConfig->realname) and !empty($info[$ldapConfig->realname])) $user->realname = $info[$ldapConfig->realname];
            if(!empty($ldapConfig->email)    and !empty($info[$ldapConfig->email]))    $user->email    = $info[$ldapConfig->email];
            if(!empty($ldapConfig->phone)    and !empty($info[$ldapConfig->phone]))    $user->phone    = $info[$ldapConfig->phone];
            if(!empty($ldapConfig->mobile)   and !empty($info[$ldapConfig->mobile]))   $user->mobile   = $info[$ldapConfig->mobile];

            if(is_array($user->realname)) $user->realname = $user->realname[0];
            if(is_array($user->email))    $user->email    = $user->email[0];
            if(is_array($user->phone))    $user->phone    = $user->phone[0];
            if(is_array($user->mobile))   $user->mobile   = $user->mobile[0];
            if(empty($user->realname))    $user->realname = $account;
            if(!empty($ldapConfig->charset) and $ldapConfig->charset != 'utf-8') $user->realname = helper::convertEncoding($user->realname, $ldapConfig->charset);
            $user->realname = $this->getUniqueRealname($user);

            if($user->password != md5($password)) $user->password = md5($password);
            if(!$newUser)
            {
                if(empty($user->birthday)) $user->birthday = null;
                $this->dao->update(TABLE_USER)->data($user)->where('account')->eq($user->account)->exec();
            }
            else
            {
                /* Check user limit. */
                if(function_exists('ioncube_license_properties')) $properties = ioncube_license_properties();
                if(!empty($properties['user']))
                {
                    $userCount = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)
                        ->where('deleted')->eq(0)
                        ->beginIF($this->config->edition != 'open')->andWhere('feedback')->eq(0)->fi()
                        ->fetch('count');
                    if($properties['user']['value'] <= $userCount)
                    {
                        if($this->app->getViewType() == 'json') die(helper::removeUTF8Bom(json_encode(array('status' => 'failed', 'reason' => $this->lang->user->error->userLimit))));
                        die(js::alert($this->lang->user->error->userLimit));
                    }
                }

                $user->role   = '';
                $user->avatar = '';
                $this->dao->insert(TABLE_USER)->data($user)->check('account', 'unique')->check('account', 'account')->exec();
                $user->id = $this->dao->lastInsertID();

                /* Init group priv. */
                if(!empty($ldapConfig->group))
                {
                    $group = $ldapConfig->group;
                }
                else
                {
                    $group = $this->dao->select('id')->from(TABLE_GROUP)->where('name')->eq('guest')->limit(1)->fetch('id');
                }

                if($group)
                {
                    $data = new stdClass();
                    $data->account = $account;
                    $data->group   = $group;

                    $this->dao->replace(TABLE_USERGROUP)->data($data)->exec();
                }
            }

            $ip   = $this->server->remote_addr;
            $last = $this->server->request_time;

            $user->lastTime = zget($user, 'last', '');
            $user->last     = date(DT_DATETIME1, $last);
            $user->admin    = strpos($this->app->company->admins, ",{$user->account},") !== false;

            $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($user->account)->exec();

            /* Create cycle todo in login. */
            $todoList = $this->dao->select('*')->from(TABLE_TODO)->where('cycle')->eq(1)->andWhere('deleted')->eq('0')->andWhere('account')->eq($user->account)->fetchAll('id');
            $this->loadModel('todo')->createByCycle($todoList);
        }

        ldap_free_result($ldapList);
        ldap_unbind($ldapConn);
        return $user;
    }

    /**
     * Get LDAP config.
     *
     * @access public
     * @return object
     */
    public function getLDAPConfig()
    {
        if(isset($this->config->system->ldap))
        {
            $ldapConfig = new stdclass();
            foreach($this->config->system->ldap as $ldap) $ldapConfig->{$ldap->key} = $ldap->value;
            return $ldapConfig;
        }

        $ldapConfig = $this->dao->select('*')->from(TABLE_CONFIG)->where('module')->eq('ldap')->andWhere('owner')->eq('system')->fetchPairs('key', 'value');
        return (object)$ldapConfig;
    }

    /**
     * Get LDAP user.
     *
     * @param  string $type
     * @param  int    $queryID
     * @access public
     * @return array.
     */
    public function getLDAPUser($type = 'all', $queryID = 0)
    {
        $users = array();
        $ldapConfig = $this->getLDAPConfig();
        if(empty($ldapConfig)) die(js::locate(helper::createLink('ldap', 'index')));
        if(empty($ldapConfig->turnon)) return 'off';
        $ldapConn = $this->loadModel('ldap')->ldapConnect($ldapConfig);
        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, isset($ldapConfig->version) ? $ldapConfig->version : 3);
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

        if(isset($ldapConfig->anonymous))
        {
            $ldapBind = ldap_bind($ldapConn);
        }
        else
        {
            $ldapPassword = html_entity_decode(helper::decryptPassword($ldapConfig->password));
            $ldapBind = ldap_bind($ldapConn, $ldapConfig->admin, $ldapPassword);
        }
        if(!$ldapBind)
        {
            ldap_unbind($ldapConn);
            return $users;
        }

        $condition = "({$ldapConfig->account}=*)";
        if($type == 'bysearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('ldapQuery', $query->sql);
                    $this->session->set('ldapForm', $query->form);
                }
                else
                {
                    $this->session->set('ldapQuery', ' 1 = 1');
                }
            }
            $condition = $this->session->ldapQuery;
        }

        $ldapList  = @ldap_search($ldapConn, "$ldapConfig->baseDN", $condition, array('*'), $attrsonly = 0, $sizelimit = 0);
        if(!$ldapList)
        {
            $ldapErrno = ldap_errno($ldapConn);
            if($ldapErrno) return array('result' => 'fail', 'message' => sprintf($this->lang->user->error->ldap, $ldapErrno, ldap_error($ldapConn)));
        }
        $infos     = ldap_get_entries($ldapConn, $ldapList);
        $allUsers  = $this->dao->select('account,realname')->from(TABLE_USER)->fetchPairs('account', 'realname');
        $linkUsers = $this->dao->select('ldap,account')->from(TABLE_USER)->where('ldap')->ne('')->fetchPairs('ldap', 'account');

        if($this->config->debug) file_put_contents($this->app->getTmpRoot() . 'log/ldap.log.php', "<?php\n die(); \n?" . ">\n" . var_export($infos, true));

        foreach($infos as $key => $info)
        {
            if(!isset($info[$ldapConfig->account])) continue;

            /* Code for task #8991. */
            if(isset($info['objectclass']))
            {
                unset($info['objectclass']['count']);
                $isComputer = false;
                foreach(explode(',', $this->config->ldap->computerTags) as $computerTag)
                {
                    if(in_array($computerTag, $info['objectclass']))
                    {
                        $isComputer = true;
                        break;
                    }
                }
                if($isComputer) continue;
            }
            /* Ignore disabled account by useraccountcontrol in AD. */
            if(isset($info['useraccountcontrol']) and $info['useraccountcontrol'][0] == '514') continue;

            $account = $info[$ldapConfig->account][0];
            if(empty($account)) continue;
            if(isset($allUsers[$account]) || isset($linkUsers[$account])) continue;

            $realname = $account;
            $ldapConfigRealname = strtolower($ldapConfig->realname);
            if(!empty($ldapConfig->realname) and !empty($info[$ldapConfigRealname])) $realname = $info[strtolower($ldapConfig->realname)];

            $user['account']  = $account;
            $user['realname'] = $realname;
            $user['email']    = (empty($ldapConfig->email) or empty($info[$ldapConfig->email])) ? '' : $info[$ldapConfig->email];
            $user['mobile']   = (empty($ldapConfig->mobile) or empty($info[$ldapConfig->mobile])) ? '' : $info[$ldapConfig->mobile];
            $user['phone']    = (empty($ldapConfig->phone) or empty($info[$ldapConfig->phone])) ? '' : $info[$ldapConfig->phone];

            if(is_array($user['realname'])) $user['realname'] = $user['realname'][0];
            if(is_array($user['email']))    $user['email']    = $user['email'][0];
            if(is_array($user['phone']))    $user['phone']    = $user['phone'][0];
            if(is_array($user['mobile']))   $user['mobile']   = $user['mobile'][0];

            if(!empty($ldapConfig->charset) and $ldapConfig->charset != 'utf-8') $user['realname'] = helper::convertEncoding($user['realname'], $ldapConfig->charset);

            $users[$account] = $user;
        }
        ldap_free_result($ldapList);
        ldap_unbind($ldapConn);
        return $users;
    }

    /**
     * Get unique realname.
     *
     * @param  array|object $user
     * @access public
     * @return string
     */
    public function getUniqueRealname($user)
    {
        $account  = is_object($user) ? $user->account : $user['account'];
        $realname = is_object($user) ? $user->realname : $user['realname'];

        static $ldapConfig, $realnames, $deptPairs, $index;
        if(empty($ldapConfig)) $ldapConfig = $this->getLDAPConfig();
        if(empty($realnames))  $realnames  = $this->dao->select('realname')->from(TABLE_USER)->where('account')->ne($account)->fetchPairs('realname', 'realname');
        if(empty($deptPairs))  $deptPairs  = $this->dao->select('id,name')->from(TABLE_DEPT)->fetchPairs('id', 'name');
        if(empty($index))      $index      = 2;

        $repeatPolicy = isset($ldapConfig->repeatPolicy) ? $ldapConfig->repeatPolicy : 'number';
        if(isset($realnames[$realname]))
        {
            if($repeatPolicy == 'dept')
            {
                $deptID = 0;
                if(is_object($user) and isset($user->dept))  $deptID = $user->dept;
                if(is_array($user) and isset($user['dept'])) $deptID = $user['dept'];

                $realname = $realname . '(' . zget($deptPairs, $deptID) . ')';
                $realnames[$realname] = $realname;
            }
            elseif($repeatPolicy == 'number')
            {
                $realname = $realname . $index;
                $index++;

                if(isset($realnames[$realname])) $realname = $this->getUniqueRealname($user);
                $realnames[$realname] = $realname;
            }
        }

        $index = 0;
        return $realname;
    }

    /**
     * Import selected LDAP user.
     *
     * @param  string $type        all|bysearch
     * @param  int    $queryID
     * @access public
     * @return object
     */
    public function importLDAP($type = 'all', $queryID = 0)
    {
        $users      = fixer::input('post')->get();
        $ldapUsers  = $this->getLDAPUser($type, $queryID);

        /** Check duplicated link for local account.**/
        if(!empty($users->link))
        {
            $links = array();
            foreach($users->link as $userID)
            {
                if(!empty($userID)) $links[] = $userID;
            }
            if(count($links) != count(array_unique($links))) return $this->lang->user->error->duplicated;
        }
        if(empty($users->add)) return true;

        $addUsers  = array();
        $addGroups = array();
        foreach($users->account as $id => $account)
        {
            if(!isset($users->group[$id])) $users->group[$id] = empty($this->app->config->ldap->group) ? 0 : $this->app->config->ldap->group;

            if(empty($account)) continue;
            if(!isset($ldapUsers[$account]) and empty($users->link[$id])) continue;

            if(isset($ldapUsers[$account]))
            {
                $user = $ldapUsers[$account];
                $user['qq']       = isset($users->qq[$id])     ? $users->qq[$id]     : '';
                $user['dept']     = isset($users->dept[$id])   ? $users->dept[$id]   : 0;
                $user['role']     = isset($users->role[$id])   ? $users->role[$id]   : '';
                $user['gender']   = isset($users->gender[$id]) ? $users->gender[$id] : 'f';
                $user['ldap']     = !empty($users->link[$id])  ? $users->link[$id]   : '';
                $user['realname'] = $this->getUniqueRealname($user);
                if(empty($user['birthday'])) $user['birthday'] = null;
            }
            elseif(!empty($users->link[$id]))
            {
                $user = array();
                $user['account'] = $account;
                $user['ldap']    = $users->link[$id];
            }

            $user['visions'] = isset($users->visions[$id]) ? $users->visions[$id] : $this->config->vision;

            /* Change for append field, such as feedback.*/
            if(!empty($this->config->user->batchAppendFields))
            {
                $appendFields = explode(',', $this->config->user->batchAppendFields);
                foreach($appendFields as $appendField)
                {
                    if(empty($appendField)) continue;
                    if(!isset($users->$appendField)) continue;

                    /* Code for task #8991. */
                    $preVarName = 'pre' . $appendField;
                    if(!isset($$preVarName)) $$preVarName = '';

                    $fieldList = $users->$appendField;
                    $user[$appendField] = $fieldList[$id] == 'ditto' ? $$preVarName : $fieldList[$id];
                    $$preVarName = $fieldList[$id] == 'ditto' ? $$preVarName : $fieldList[$id];
                }
            }

            $group = new stdclass();
            $group->account = $user['account'];
            $group->group   = isset($users->group[$id]) ? $users->group[$id] : 0;

            if(isset($users->add[$id]))
            {
                $addUsers[$account] = $user;
                $addGroups[$account] = $group;

                if(empty($user['role']) && empty($user['ldap'])) return sprintf($this->lang->user->error->role, $id + 1);
            }
        }
        if(empty($addUsers)) return true;

        foreach($addUsers as $account => $user)
        {
            if(strpos($user['phone'], ',') !== false) $user['phone'] = substr($user['phone'], 0, strpos($user['phone'], ','));

            if(empty($user['ldap']))
            {
                $user['ldap'] = $user['account'];
                $this->dao->replace(TABLE_USER)->data($user)->check('account', 'account')->exec();
                if(!dao::isError())
                {
                    $this->dao->delete()->from(TABLE_USERGROUP)->where('account')->eq($addGroups[$account]->account)->andWhere('`group`')->eq($addGroups[$account]->group)->exec();
                    $this->dao->insert(TABLE_USERGROUP)->data($addGroups[$account])->exec();
                }
            }
            else
            {
                $this->dao->update(TABLE_USER)->set('ldap')->eq($user['account'])->where('id')->eq((int)$user['ldap'])->exec();
            }

            $error = array();
            if(dao::isError())
            {
                $error['ill'][] = $user['account'];
                //$this->app->saveError(E_WARNING, dao::getError(true), __FILE__, __LINE__);
            }
        }
        return $error;
    }

    /**
     * Get users without link LDAP.
     *
     * @access public
     * @return array
     */
    public function getUserWithoutLDAP()
    {
        return $this->dao->select('id,account')->from(TABLE_USER)->where('ldap')->eq('')->andWhere('deleted')->eq(0)->fetchPairs('id', 'account');
    }
}

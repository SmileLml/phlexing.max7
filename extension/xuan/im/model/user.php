<?php
class imUser extends model
{
    /**
     * Extends identify a user with plain password function,
     * creates an auth token for user when authenticating with password.
     *
     * @param   string $account     the account
     * @param   string $password    md5 hash of the password, or a 64 byte string of padded token.
     * @access  public
     * @return  object|bool|string  if is valid user, return the user object.
     *                              if no valid user, return false.
     *                              if user is locked, return locked status as string.
     */
    public function identify($account, $password)
    {
        if(strlen($password) == 64) return $this->identifyWithToken($account, $password);
        $originPassword = $password;

        /* Calculate hash of $password$account if not using email as username. */
        $user = $this->loadModel('user')->identify($account, $password);
        if(empty($user))
        {
            if($this->loadModel('ldap') !== false && method_exists($this->ldap, 'getConfiguration'))
            {
                $ldap = $this->ldap->getConfiguration();
                if(isset($ldap->enabled) && $ldap->enabled) $user = $this->identifyWithLDAP($account, $originPassword);
            }
        }
        if(is_object($user))
        {
            /* User was logon with password, generate auth token. */
            $token = $this->getAuthToken($user->id, $this->app->input['device']);
            $user->token = $token->token;
        }
        return $user;
    }

    /**
     * Auth with username and token. Token is valid for around 30 seconds.
     *
     * @param  string             $account
     * @param  string             $token
     * @param  string             $device
     * @access public
     * @return object|bool|string
     */
    public function identifyWithToken($account, $token, $device = '')
    {
        $tokenAuthWindow = (int)zget($this->config->xuanxuan, 'tokenAuthWindow', 20);
        $now = (int)round(time() / $tokenAuthWindow);
        $token = substr($token, 0, 32); // Use first 32 chars of token.
        $userTokens = $this->dao->select('t1.*, t2.device, t2.token, t2.validUntil')->from(TABLE_USER)->alias('t1')
                ->leftJoin(TABLE_IM_USERDEVICE)->alias('t2')->on('t1.id = t2.user')
                ->where('t1.account')->eq($account)
                ->andWhere('t1.deleted')->eq('0')
                ->beginIF(!empty($device))->andWhere('t2.device')->eq($device)->fi()
                ->andWhere('t2.validUntil', true)->gt(helper::now())
                ->orWhere('t2.validUntil is null')->markRight(1)
                ->fetchAll();

        if(empty($userTokens)) return 'invalid_token';

        foreach($userTokens as $userToken)
        {
            $authTokens = array();
            $authTokens[] = md5($userToken->account . $userToken->token . $now);
            $authTokens[] = md5($userToken->account . $userToken->token . ($now - 1));
            $authTokens[] = md5($userToken->account . $userToken->token . ($now + 1));

            if(in_array($token, $authTokens))
            {
                if($userToken->locked != null)
                {
                    $dateDiff = (strtotime($userToken->locked) - time()) / 60;
                    if($dateDiff > 0) return 'locked';
                }

                $tokenLifetime = zget($this->config->xuanxuan, 'tokenLifetime', 30);
                $tokenLifetime *= 24 * 60 * 60;
                if(strtotime($userToken->validUntil) - time() < $tokenLifetime / 3) $userToken->tokenNeedRenew = true;

                /* Update user data. */
                $updateUser=new stdclass();
                $updateUser->ip     = helper::getRemoteIp();
                $updateUser->last   = helper::now();
                $updateUser->fails  = 0;
                $updateUser->visits = ++ $userToken->visits;

                /* Update password when create password by oldCreatePassword function. */
                $this->dao->update(TABLE_USER)->data($updateUser)->where('account')->eq($account)->exec();

                unset($userToken->password);
                unset($userToken->device);
                unset($userToken->token);
                unset($userToken->validUntil);
                return $userToken;
            }
        }

        return 'invalid_token';
    }

    /**
     * Auth with username and ldap password.
     * @param string $account
     * @param string $password
     * @return object|bool
     */
    public function identifyWithLDAP($account, $password)
    {
        $ldapConfig = $this->loadModel('ldap')->getConfiguration();
        if(empty($ldapConfig) || empty($ldapConfig->enabled)) return false;

        $ldapConn = ldap_connect($ldapConfig->host, $ldapConfig->port);
        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, isset($ldapConfig->version) ? $ldapConfig->version : 3);
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);
        $ldapBind = @ldap_bind($ldapConn, $ldapConfig->admin, $ldapConfig->password);
        if(!$ldapBind)
        {
            $this->unbindLDAP($ldapConn, false);
            return false;
        }
        $searchList  = ldap_search($ldapConn, $ldapConfig->baseDN, "({$ldapConfig->account}=$account)");
        $infos       = ldap_get_entries($ldapConn, $searchList);
        if(!isset($infos[0]))
        {
            $this->unbindLDAP($ldapConn, $searchList);
            return false;
        }
        $info = $infos[0];
        if(empty($info['dn']))
        {
            $this->unbindLDAP($ldapConn, $searchList);
            return false;
        }
        $ldapBind = @ldap_bind($ldapConn, $info['dn'], $password);
        if(!$ldapBind)
        {
            $this->unbindLDAP($ldapConn, $searchList);
            return false;
        }
        $user = $this->loadModel('user')->getByAccount($account);
        if(empty($user))
        {
            if(!empty($ldapConfig->autoCreate))
            {
                $user           = new stdClass();
                $user->account  = $account;
                $user->password = $password;
                if(isset($info['mail'][0]))                   $user->email    = $info['mail'][0];
                if(isset($info['mobile'][0]))                 $user->mobile   = $info['mobile'][0];
                if(isset($info[$ldapConfig->displayName][0])) $user->realname = $info[$ldapConfig->displayName][0];
                if(isset($info['postalcode'][0]))             $user->zipcode  = $info['postalcode'][0];
                $result = $this->user->apiCreate($user, false);
                $this->unbindLDAP($ldapConn, $searchList);
                if($result) return $this->loadModel('user')->getByAccount($account);
                return false;
            }
            $this->unbindLDAP($ldapConn, $searchList);
            return false;
        }
        $this->unbindLDAP($ldapConn, $searchList);

        if($user->deleted == '0') return $user;
        return false;
    }

    /**
     * Free search result and unbind ldap.
     * @param \LDAP\Connection $ldapConn
     * @param \LDAP\Result|array|false $searchList
     * @return void
     */
    public function unbindLDAP($ldapConn, $searchList)
    {
        if(!empty($searchList)) ldap_free_result($searchList);
        if(!empty($ldapConn)) ldap_unbind($ldapConn);
    }


    /**
     * Get a user.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id = 0)
    {
		$user = $this->dao->select('id, account, realname, avatar, role, dept, clientStatus, gender, email, mobile, phone,  qq, deleted, address, weixin')
			->from(TABLE_USER)
			->where('id')->eq($id)
			->fetch();
        if(!$user) return (object)array();

        return $this->format($user);
    }

    /**
     * Get user list by id or account.
     *
     * @param  string $status
     * @param  array  $characters    can be an array of uids or accounts, single type only.
     * @param  bool   $idAsKey
     * @access public
     * @return array
     */
    public function getList($status = '', $characters = array(), $idAsKey = true)
    {
        $dao = $this->dao->select('id, account, realname, avatar, role, dept, clientStatus, gender, email, mobile, phone,  qq, deleted, address, weixin')
            ->from(TABLE_USER)
            ->where(1)
            ->beginIF(empty($characters))
            ->andWhere('deleted')->eq('0')
            ->fi()
            ->beginIF($status && $status == 'online')->andWhere('clientStatus')->ne('offline')->fi()
            ->beginIF($status && $status != 'online')->andWhere('clientStatus')->eq($status)->fi()
            ->beginIF($characters &&  is_numeric(current($characters)))->andWhere('id')->in($characters)->fi()
            ->beginIF($characters && !is_numeric(current($characters)))->andWhere('account')->in($characters)->fi();

        $users = $idAsKey ? $dao->fetchAll('id') : $dao->fetchAll();

        return $this->format($users);
    }

    /**
     * Get user id list by dept with pager and sort rule
     *
     * @param  string   $deptID
     * @param  array    $exclude
     * @param  object   $pager
     * @param  string   $orderBy
     * @param  boolean  $onlySelf   if true, exclude subdepts' members from result
     * @access public
     * @return array
     */
    public function getIDListByDept($deptID = 0, $exclude = array(), $pager = null, $orderBy = '', $onlySelf = false)
    {
        $depts = $deptID ? ($onlySelf ? array($deptID) : $this->loadModel('tree')->getFamily($deptID, 'dept')) : 0;
        return $this->dao->select('id')
            ->from(TABLE_USER)
            ->where('deleted')->eq('0')
            ->beginIF($deptID)->andWhere('dept')->in($depts)->fi() // Fetch all members if $deptID is 0.
            ->beginIF(!$deptID && $onlySelf)->andWhere('dept')->eq(0)->fi()
            ->beginIF(!empty($exclude))->andWhere('id')->notin($exclude)->fi()
            ->orderBy($orderBy)
            ->beginIF($pager)->page($pager)->fi()
            ->fetchPairs('id');
    }

    /**
     * Get user count.
     *
     * @access public
     * @return int
     */
    public function getCount()
    {
        return $this->dao->select('COUNT(*)')->from(TABLE_USER)->where('deleted')->eq('0')->fetch('COUNT(*)');
    }

    /**
     * Update a user.
     *
     * @param  object $user
     * @access public
     * @return object
     */
    public function update($user = null)
    {
        if(empty($user->id)) return null;

        $currentClientStatus = $this->dao->select('clientStatus')->from(TABLE_USER)->where('id')->eq($user->id)->fetch('clientStatus');
        if($currentClientStatus == 'offline' && !in_array(strtolower($this->app->getMethodName()), array('userlogin')))
        {
            dao::$errors['clientStatus'][] = 'User is offline.';
            return null;
        }

        $data = array();

        /* Updates status. */
        if(isset($user->clientStatus) && !empty($user->clientStatus)) $data['clientStatus'] = $user->clientStatus;

        /* Changes password. */
        if(!empty($user->account) && !empty($user->password)) $data['password'] = $user->password;

        /* Updates contact info. */
        if(empty($data))
        {
            foreach($this->config->im->user->canEditFields as $field)
            {
                if(isset($user->$field)) $data[$field] = $user->$field;
            }
        }
        if(empty($data)) return null;

        $data['clientLang'] = $this->session->clientLang;
        $this->dao->update(TABLE_USER)->data($data)->where('id')->eq($user->id)->exec();

        /* Revoke user's tokens on password change. */
        if(!dao::isError() && !empty($user->password)) $this->revokeAuthToken($user->id);

        return $this->getByID($user->id);
    }

    /**
     * Format users.
     *
     * @param  mixed  $users  object | array
     * @access public
     * @return object | array
     */
    public function format($users)
    {
        $isObject = false;
        if(is_object($users))
        {
            $isObject = true;
            $users    = array($users);
        }

$admins = $this->dao->select('admins')->from(TABLE_COMPANY)->where('id')->eq($this->app->company->id)->fetch('admins');$adminArray = explode(',', $admins);
        foreach($users as $user)
        {
            $user->id      = (int)$user->id;
            $user->dept    = (int)$user->dept;
            $user->deleted = isset($user->deleted) ? ((bool)$user->deleted ? 1 : 0) : 0;
            $user->status  = isset($user->clientStatus) ? $user->clientStatus : 0;

            if(isset($user->avatar))  $user->avatar  = (!empty($user->avatar) && substr($user->avatar, 0, 7) !== 'http://' && substr($user->avatar, 0, 8) !== 'https://') ? $this->loadModel('im')->getServer() . $user->avatar : $user->avatar;
            if(!isset($user->signed)) $user->signed  = 0;
$user->admin = in_array($user->account, $adminArray) ? 'super' : '';
        }

        if($isObject) return reset($users);

        return $users;
    }

    /**
     * Reset user status.
     *
     * @param  string $status
     * @access public
     * @return bool
     */
    public function resetStatus($status = 'offline')
    {
        $this->dao->update(TABLE_USER)->set('clientStatus')->eq($status)->exec();
        return !dao::isError();
    }

    /**
     * Set user status to offline.
     *
     * @param  array $users
     * @access public
     * @return bool
     */
    public function setOffline($users = array())
    {
        if(empty($users)) return true;
        $this->dao->update(TABLE_USER)->set('clientStatus')->eq('offline')->where('id')->in($users)->exec();
        return !dao::isError();
    }

    /**
     * Get list of users / depts that were created / edited / deleted in the last polling interval.
     *
     * @param  string $type  user | dept
     * @access public
     * @return array
     */
    public function hasChanges($type = 'user')
    {
        $timeStr = isset($this->config->xuanxuan->pollingInterval)
            ? "- {$this->config->xuanxuan->pollingInterval} seconds"
            : '- 60 seconds';

        return $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq($type == 'dept' ? 'deptCategory' : 'user')
            ->andWhere('action')->in('create,edit,delete')
            ->andWhere('date')->gt(date(DT_DATETIME1, strtotime($timeStr)))
            ->fetchPairs();
    }

    /**
     * Update login or logout time of a user-device pair.
     *
     * @param  int    $user    userID
     * @param  string $device  device token
     * @param  string $type    login | logout
     * @access public
     * @return bool
     */
    public function updateDevice($user, $device, $type = 'logout', $version = '')
    {
        $data = new stdclass();
        $data->user          = $user;
        $data->device        = $device;
        $data->{"last$type"} = helper::now();
        $data->online        = $type == 'login' ? 1 : 0;
        $data->version       = $version;

        $stmt = $this->dao->insert(TABLE_IM_USERDEVICE)->data($data)->get();
        $stmt .= " ON DUPLICATE KEY UPDATE `last$type` = '{$data->{"last$type"}}',`online` = '{$data->online}'";
        if($type == 'login') $stmt .= ",`version` = '{$data->version}'";

        $this->dao->exec($stmt);
        return !dao::isError();
    }

    /**
     * Get last logout time of user (on device).
     *
     * @param  int    $user
     * @param  string $device
     * @access public
     * @return string
     */
    public function getLastLogout($user, $device = '')
    {
        return $this->dao->select('MAX(lastLogout)')->from(TABLE_IM_USERDEVICE)
            ->where('user')->eq($user)
            ->beginIF(!empty($device))->andWhere('device')->eq($device)->fi()
            ->fetch('MAX(lastLogout)');
    }

    /**
     * Regenerate pinyin for users.
     *
     * @param  array  $users
     * @access public
     * @return bool
     */
    public function reindexPinyin($users = array())
    {
        $realnames = $this->dao->select('id,realname')->from(TABLE_USER)
                    ->beginIF(!empty($users))->where('id')->in($users)->fi()
                    ->fetchPairs();
        $converted = commonModel::convert2Pinyin($realnames);

        $pinyinData = array();
        foreach($realnames as $id => $realname) $pinyinData[] = "($id,'{$converted[$realname]}')";

        $query = "INSERT INTO " . TABLE_USER . "(`id`,`pinyin`) VALUES " . join(',', $pinyinData) . " ON DUPLICATE KEY UPDATE `pinyin`=VALUES(`pinyin`)";
        $this->dao->query($query); $this->dao->setCache(trim(TABLE_USER, "`"));
        return !dao::isError();
    }

    /**
     * Search for user with account / realname / pinyin in group / dept.
     *
     * @param  string  $search
     * @param  object  $options    (object)array('chat' => '', 'dept' => '', 'limit' => 51, 'exclude' => [3, 5, 7])
     * @param  boolean $returnID
     * @access public
     * @return void
     */
    public function search($search, $options = array(), $returnID = false, $pager = null)
    {
        $depts       = array();
        $chatMembers = array();
        $exclude     = array();

        if(!is_object($options) && is_array($options)) $options = (object)$options;
        if(property_exists($options, 'dept') && $options->dept != 0)
        {
            $depts = $this->loadModel('tree')->getFamily($options->dept, 'dept');
        }
        if(property_exists($options, 'chat'))
        {
            $chat = $this->loadModel('im')->chatGetByGid($options->chat, true);
            $chatMembers = $chat->members;
        }
        if(property_exists($options, 'exclude')) $exclude = $options->exclude;

        $result = $this->dao->select($returnID ? 'id' : 'id, account, realname, avatar, role, dept, clientStatus, gender, email, mobile, phone,  qq, deleted, address, weixin')->from(TABLE_USER)
            ->where('deleted')->eq('0')
            ->beginIF(!empty($depts))->andWhere('dept')->in($depts)->fi()
            ->beginIF(!empty($chatMembers))->andWhere('id')->in($chatMembers)->fi()
            ->beginIF(!empty($exclude))->andWhere('id')->notin($exclude)->fi()
            ->andWhere('account', $markLeft = true)->like("%$search%")
            ->orWhere('pinyin')->like("%$search%")
            ->orWhere('realname')->like("%$search%")->markRight(1)
            ->beginIF($pager)->page($pager)->fi()
            ->beginIF(!$pager)->limit(isset($options->limit) ? $options->limit : 51)->fi()
            ->fetchAll();

        return $returnID ? array_map(function($obj){return (int)$obj->id;}, $result) : $this->format($result);
    }

    /**
     * Generate a 64 byte hex string as auth token using phpaes.
     *
     * @access private
     * @return string
     */
    function generateAuthToken()
    {
        $random = $this->app->loadClass('phpaes')->randomString(32);
        return bin2hex($random);
    }

    /**
     * Get or create an auth token for user's device, binds unused token automatically if there is one.
     *
     * @param  int         $userID
     * @param  string      $deviceType
     * @param  string      $deviceID
     * @access public
     * @return object|bool
     */
    public function getAuthToken($userID, $deviceType = '', $deviceID = '')
    {
        if(!empty($deviceType))
        {
            /* Try to fetch unused token and bond token of the device. */
            $userTokens = $this->dao->select('*')->from(TABLE_IM_USERDEVICE)
                    ->where('user')->eq($userID)
                    ->fetchAll();
            $userTokens = array_filter(
                $userTokens,
                function($userToken) use ($deviceType, $deviceID)
                {
                    return ($userToken->device == '' && $userToken->deviceID == '')
                        || !($userToken->device != $deviceType || !empty($deviceID) && $userToken->deviceID != $deviceID);
                }
            );

            if(!empty($userTokens))
            {
                /* If got both an unused token and a device auth token: */
                if(count($userTokens) > 1)
                {
                    $bondTokens = array_filter(
                        $userTokens,
                        function($userToken) use ($deviceType, $deviceID)
                        {
                            return $userToken->device == $deviceType
                                && (empty($deviceID) || !empty($deviceID) && $userToken->deviceID == $deviceID);
                        }
                    );
                    $bondToken = current($bondTokens);
                    if(strtotime($bondToken->validUntil) <= time()) $bondToken = $this->renewAuthToken($userID, $deviceType, $deviceID);
                    return $bondToken;
                }

                $userToken = current($userTokens);
                if(empty($userToken->token) || (!empty($userToken->validUntil) && strtotime($userToken->validUntil) <= time()))
                {
                    return $this->renewAuthToken($userID, $deviceType, $deviceID);
                }
                if($userToken->device != '') return $userToken;

                $this->dao->update(TABLE_IM_USERDEVICE)
                    ->set('device')->eq($deviceType)
                    ->set('deviceID')->eq($deviceID)
                    ->where('id')->eq($userToken->id)
                    ->exec();
                $userToken->device   = $deviceType;
                $userToken->deviceID = $deviceID;
                return $userToken;
            }
        }

        return $this->renewAuthToken($userID, $deviceType, $deviceID);
    }

    /**
     * Renew or just generate auth token for user's device.
     *
     * @param  int         $userID
     * @param  string      $deviceType
     * @param  string      $deviceID
     * @access public
     * @return object|bool
     */
    public function renewAuthToken($userID, $deviceType = '', $deviceID = '')
    {
        $authToken = $this->generateAuthToken();
        $tokenLifetime = zget($this->config->xuanxuan, 'tokenLifetime', 30);

        $userDevice = new stdclass();
        $userDevice->device     = $deviceType;
        $userDevice->deviceID   = $deviceID;
        $userDevice->user       = $userID;
        $userDevice->token      = $authToken;
        $userDevice->validUntil = date('Y-m-d H:i:s', strtotime("+ $tokenLifetime days"));

        $stmt = $this->dao->insert(TABLE_IM_USERDEVICE)->data($userDevice)->get();
        $stmt .= " ON DUPLICATE KEY UPDATE `deviceID` = '{$userDevice->deviceID}', `token` = '{$userDevice->token}', `validUntil` = '{$userDevice->validUntil}';"; // TODO: make deviceID persistent.
        $this->dao->exec($stmt);

        if(dao::isError()) return false;
        return $userDevice;
    }

    /**
     * Revoke user's auth token for specific device.
     *
     * @param  int    $userID
     * @param  string $deviceType
     * @param  string $deviceID
     * @access public
     * @return bool
     */
    public function revokeAuthToken($userID, $deviceType = '', $deviceID = '')
    {
        $this->dao->update(TABLE_IM_USERDEVICE)
            ->set('validUntil')->eq(helper::now())
            ->where('user')->eq($userID)
            ->beginIF(!empty($deviceType))->andWhere('device')->eq($deviceType)->fi()
            ->beginIF(!empty($deviceID))->andWhere('deviceID')->eq($deviceID)->fi()
            ->exec();
        return !dao::isError();
    }

    /**
     * Get list of users who changed password but did not re-login.
     *
     * @access public
     * @return array
     */
    public function getChangedPassword()
    {
        $actionObjectIDs       = array();
        $passwordChangeActions = array();
        $loginHistoryActions   = array();
        $passwordChangeActions = $this->loadModel('action')->getListSinceLastPoll('changepassword');
        $loginHistoryActions   = $this->loadModel('action')->getListSinceLastPoll('loginxuanxuan');

        foreach($passwordChangeActions as $passwordChange)
        {
            $loginExist = false;
            foreach($loginHistoryActions as $login)
            {
                if ($passwordChange->objectID === $login->objectID)
                {
                    $loginExist = true;
                    if( strtotime($login->date) < strtotime($passwordChange->date)) $actionObjectIDs[] = (int)$passwordChange->objectID;
                }
            }
            if(!$loginExist) $actionObjectIDs[] = (int)$passwordChange->objectID;
        }

        return $actionObjectIDs;
    }

    /**
     * Get user ID list of deleted online users.
     *
     * @access public
     * @return array
     */
    public function getOnlineDeleted()
    {
        $userIDs = $this->dao->select('id')->from(TABLE_USER)
            ->where('deleted')->eq(1)
            ->andWhere('clientStatus')->ne('offline')
            ->fetchAll('id');
        if(dao::isError()) return array();
        return array_values(array_map(function($obj){return (int)$obj->id;}, $userIDs));
    }

    /**
     * Get user ID list of forbidden online users.
     *
     * @access public
     * @return array
     */
    public function getOnlineForbidden()
    {
        $userIDs = $this->dao->select('id')->from(TABLE_USER)
            ->where('locked')->ge(helper::now())
            ->andWhere('clientStatus')->ne('offline')
            ->fetchAll('id');
        if(dao::isError()) return array();
        return array_values(array_map(function($obj){return (int)$obj->id;}, $userIDs));
    }

    /**
     * Add user action.
     *
     * @param  int|string   $user       userID or user's account.
     * @param  string       $actionType
     * @param  string       $result
     * @param  string       $comment
     * @param  bool         $common
     * @access public
     * @return void
     */
    public function addAction($user, $actionType, $result, $comment = '', $common = false)
    {
        if(!zget($this->config->xuanxuan, 'logLevel', 1) && !$common) return;

        $account = '';
        $userID  = 0;
        if(is_int($user))
        {
            $account = $this->dao->select('account')->from(TABLE_USER)->where('id')->eq($user)->fetch('account');
            $userID = $user;
        }
        if(is_string($user))
        {
            $userID = $this->dao->select('id')->from(TABLE_USER)->where('account')->eq($user)->fetch('id');
            $account = $user;
        }

        $actor   = !empty($account) ? $account : '';
        $extra   = json_encode(array('actorId' => $userID));
        $this->loadModel('action')->create('user', $userID, $actionType, $result, $comment, $extra, $actor);
    }

    /**
     * Compare a user online device version with a given version.
     *
     * @param  int    $userID
     * @param  string $compareVersion
     * @param  string $deviceType
     * @access public
     * @return bool
     */
    public function isDeviceVersionGe($userID, $compareVersion, $deviceType = 'desktop')
    {
        $version = $this->dao->select('version')->from(TABLE_IM_USERDEVICE)
            ->where('user')->eq($userID)
            ->andWhere('device')->eq($deviceType)
            ->fetch('version');
        if(dao::isError() || empty($version)) return false;
        return version_compare($version, $compareVersion, '>=');
    }
}

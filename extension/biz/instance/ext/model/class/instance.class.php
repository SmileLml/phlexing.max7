<?php
class instanceInstance extends instanceModel
{
    /**
     * 判断按钮是否可点击。
     * Adjust the action clickable.
     *
     * @param  object $instance
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($instance, $action)
    {
        if(!isset($instance->type)) $instance->type = 'store';
        if($instance->type !== 'store')
        {
            if($action == 'visit') return true;
            if(!common::hasPriv('instance', 'manage')) return false;
        }
        else
        {
            if(!empty($instance->source) && $instance->source == 'system')
            {
                if(strpos($instance->chart, 'zentao') === false)
                {
                    if(strtolower($instance->appName) == 'gitfox' && $action == 'visit') return false;
                    if(in_array($action, array('edit', 'ajaxUninstall', 'bindUser')))    return false;
                }
                else
                {
                    if(!in_array($action, array('terminal', 'showLogs', 'showEvents'))) return false;
                }
            }
            if($action == 'terminal') return !empty($instance->domain) && $this->canDo('visit', $instance) && common::hasPriv('instance', 'manage');
        }

        return parent::isClickable($instance, $action);
    }

    /**
     * 构造安装应用的配置。
     * Mount installation settings by custom data.
     *
     * @param  object  $customData
     * @param  object  $dbInfo
     * @param  object  $instance
     * @access public
     * @return object
     */
    public function installationSettingsMap($customData, $dbInfo, $instance)
    {
        $settingsMap = parent::installationSettingsMap($customData, $dbInfo, $instance);

        $customFields = $this->session->{"instanceFields{$instance->appID}"};
        if(empty($customFields) || empty($this->config->instance->form->custom)) return $settingsMap;

        $customData  = (array)form::data($this->config->instance->form->custom)->get();
        if($customData) $settingsMap->custom = $customData;
        return $settingsMap;
    }

    /**
     * 根据实例信息、当前IP和用户信息获取访问令牌
     * Get access token by instance info, current IP and user info.
     *
     * @param  object $instance
     * @access public
     * @return bool|object
     */
    public function checkAccessForWS($instance)
    {
        if(empty($instance)) return false;
        $apiUrl = '/api/cne/app/access_token';

        $remoteIP = helper::getRemoteIP();
        if(!$remoteIP) return false;

        $user = $this->app->user->account;
        if(!$user) return false;

        $data = array();
        $data['namespace'] = $instance->spaceData->k8space;
        $data['name']      = $instance->k8name;
        $data['client_ip'] = $remoteIP;
        $data['username']  = $user;

        return $this->loadModel('cne')->apiGet($apiUrl, $data, $this->config->CNE->api->headers);
    }

    /**
     * 获取ZenTao应用信息。
     * Get ZenTao app info.
     *
     * @access public
     * @return void
     */
    public function getZenTaoApp()
    {
        $instance = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('source')->eq('system')
            ->andWhere('name')->eq('ZenTao')
            ->fetch();

        if($instance && $instance->appVersion != $this->config->version)
        {
            $instance->appVersion = $this->config->version;
            $this->updateByID($instance->id, array('appVersion' => $this->config->version));
        }
        return $instance;
    }

    /**
     * 删除ZenTao应用。
     * Delete ZenTao app.
     *
     * @access public
     * @return void
     */
    public function deleteZenTaoApp()
    {
        return $this->dao->delete()->from(TABLE_INSTANCE)
            ->where('source')->eq('system')
            ->andWhere('name')->eq('ZenTao')
            ->exec();
    }

    /*
     * 停止应用实例。
     * Stop app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    /**
     * @return bool|object
     * @param object $instance
     */
    public function stop($instance)
    {
        if($instance->source == 'system' && strtolower($instance->appName) != 'gitfox')
        {
            $result = new stdclass();
            $result->code = 200;
            return $result;
        }

        return parent::stop($instance);
    }

    /**
     * 根据条件获取自定义配置的选项。
     * Get custom options by conditions.
     *
     * @param  object $query
     * @access public
     * @return array
     */
    public function getOptionsByApi($query)
    {
        if(empty($query->query)) return array();

        $result = $this->loadModel('cne')->apiGet('/api/cne/custom_options', array('query' => $query->query), $this->config->CNE->api->headers);
        if(!$result || $result->code != 200) return array();

        return $result->data;
    }
}

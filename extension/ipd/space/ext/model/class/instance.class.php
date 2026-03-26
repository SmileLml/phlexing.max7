<?php
class instanceSpace extends spaceModel
{
    /**
     * 构建平台禅道应用基本信息。
     * Basic information on building platform ZenTao applications.
     *
     * @param  object $instance
     * @access public
     * @return bool|object
     */
    public function buildZenTaoApp()
    {
        $zentaoApp = $this->loadModel('instance')->getZenTaoApp();
        if(!empty($zentaoApp))
        {
            if($zentaoApp->domain == 'zentao.' . $this->loadModel('cne')->sysDomain())
            {
                return $zentaoApp->id;
            }
            else
            {
                $this->instance->deleteZenTaoApp();
            }
        }

        $appBaseInfo = $this->getZenTaoAppBaseInfo();
        if(empty($appBaseInfo)) return false;

        $space       = $this->loadModel('space')->getSpacesByAccount('system');
        $channel     = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;

        $appBaseInfo->version     = getenv('CHART_VERSION') ? getenv('CHART_VERSION') : $appBaseInfo->version;
        $appBaseInfo->app_version = $this->config->version;
        $appBaseInfo->chart       = 'zentaopaas';

        $domain = 'zentao';
        $name   = 'ZenTao';
        $k8name = 'zentaopaas';
        $instance = $this->instance->createInstance($appBaseInfo, $space[0], $domain, $name, $k8name, $channel);
        if(!$instance) return false;

        $newInstance = new stdclass();
        $newInstance->source = 'system';
        $this->instance->updateByID($instance->id, $newInstance);
        if(dao::isError()) return false;

        $this->loadModel('action')->create('instance', $instance->id, 'install', '');
        return !dao::isError();
    }

    /**
     * 获取ZenTao应用基本信息。
     * Get basic information of ZenTao applications.
     *
     * @access public
     * @return object|bool|null
     */
    public function getZenTaoAppBaseInfo()
    {
        $edition = $this->config->edition == 'open' ? 'zentao' : 'zentao-' . $this->config->edition;

        $apiUrl = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/applist?channel='. $this->config->cloud->api->channel;
        $apiUrl .= "&q=" . rawurlencode(trim($edition));

        $result = commonModel::apiGet($apiUrl, array(), $this->config->cloud->api->headers);
        if(empty($result->data)) return false;

        $appInfo = $this->loadModel('store')->getAppInfo($result->data->apps[0]->id);
        return $appInfo;
    }
}

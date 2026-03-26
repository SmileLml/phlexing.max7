<?php
helper::importControl('instance');;
class myInstance extends instance
{
    /**
     * getInstance
     *
     * @param  int    $repoID
     * @param  string $token
     * @access public
     * @return void
     * @param int $id
     */
    public function terminal($id)
    {
        $instance = $this->instance->getByID($id);

        $sysURL = $this->config->cneExternalUrl;
        if($sysURL && strpos($sysURL, '://') === false) $sysURL = 'http://' . $sysURL;

        if(empty($sysURL)) $sysURL = common::getSysURL();
        $domain = parse_url($sysURL, PHP_URL_HOST);

        helper::header('Content-Security-Policy', "form-action 'self' {$domain};");

        $token = $this->instance->checkAccessForWS($instance);
        $token = zget($token->data, 'token', '');
        if(!$token) $this->locate(helper::createLink('user', 'deny', 'module=instance&method=manage'));

        $wsBaseURL    = str_replace(array('http://', 'https://'), array('ws://', 'wss://'), $sysURL);
        $webSocketURL = "{$wsBaseURL}/api/cne/app/terminal?namespace={$instance->spaceData->k8space}&name={$instance->k8name}&access_token={$token}";

        $this->action->create('instance', $id, 'terminal', '', json_encode(array('result' => '', 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));

        $this->view->title        = $this->lang->instance->terminal;
        $this->view->webSocketURL = $webSocketURL;

        $this->display();
    }
}

<?php
/**
 * The model file of sms module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sms
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class smsModel extends model
{
    /**
     * Save settings.
     *
     * @access public
     * @return void
     */
    public function saveSettings()
    {
        $settings = fixer::input('post')->get();
        $this->loadModel('setting');
        $this->setting->deleteItems('owner=system&module=sms');
        $items = array();
        foreach($settings as $key => $setting)
        {
            if($key == 'value') continue;
            if($key == 'key')
            {
                foreach($setting as $i => $param)
                {
                    if(empty($param)) continue;
                    $items['params'][$param] = $settings->value[$i];
                }
                continue;
            }
            $items[$key] = $setting;
        }
        $this->setting->setItems('system.sms', $items);
    }

    /**
     * Send SMS
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @access public
     * @return string
     */
    public function send($objectType, $objectID, $actionType)
    {
        if(empty($this->app->config->sms))   return false;
        if(isset($this->app->config->sms->turnon) and !$this->app->config->sms->turnon) return false;

        $this->loadModel('message');
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);
        $conditions = isset($messageSetting['sms']['condition'][$objectType]) ? $messageSetting['sms']['condition'][$objectType] : array();

        $table  = $this->config->objectTables[$objectType];
        $object = $this->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        if(empty($object)) return false;

        foreach($conditions as $fieldName => $values)
        {
            if(isset($object->$fieldName) and !in_array($object->$fieldName, $values)) return false;
        }

        $mobiles = $this->getMobiles($object, $objectType, $actionType);
        if(empty($mobiles)) return false;
        $content = $this->getContent($object, $objectType, $actionType);

        $this->sendContent($mobiles, $content);
    }

    public function sendContent($mobiles, $content)
    {
        if(empty($this->app->config->sms)) return false;
        if(isset($this->app->config->sms->turnon) and !$this->app->config->sms->turnon) return false;

        set_time_limit(60);
        $smsConfig = $this->app->config->sms;
        if(!empty($smsConfig->signature)) $content = $smsConfig->signature . $content;
        if(isset($smsConfig->encode) and $smsConfig->encode != 'utf-8') $content = iconv('utf-8', $smsConfig->encode, $content);

        $url = isset($smsConfig->url) ? $smsConfig->url : '';
        if(empty($url)) return false;

        if($smsConfig->method == 'get')
        {
            $params = '';
            if(isset($smsConfig->params))
            {
                foreach($smsConfig->params as $key => $value) $params .= $key . "=" . $value . "&";
            }
            $params .= $smsConfig->mobile  . "=" . $mobiles . "&";
            $params .= $smsConfig->content . "=" . urlencode($content);

            $linkSign = strpos($url, '?') !== false ? '&' : '?';
            $url      = $url . $linkSign . $params;
            $response = common::http($url);
        }
        else
        {
            $params = array();
            foreach($smsConfig->params as $key => $value) $params[$key] = $value;
            $params[$smsConfig->mobile]  = $mobiles;
            $params[$smsConfig->content] = urlencode($content);

            $response = common::http($url, $params);
        }

        if(!empty($smsConfig->debug) and $this->config->debug)
        {
            $results = is_array($response) ? join("\n", $response) : $response;
            trigger_error($results);
        }

        return $response == $smsConfig->successcall ? '' : $response; // Fix bug#970
    }

    /**
     * Get mobiles.
     *
     * @param  object    $object
     * @param  string    $objectType
     * @param  string    $actionType
     * @access public
     * @return array
     */
    public function getMobiles($object, $objectType, $actionType)
    {
        if(in_array($objectType, array('story', 'task')))
        {
            list($toList, $ccList) = $this->loadModel($objectType)->getToAndCcList($object, $actionType);
        }
        elseif(in_array($objectType, array('bug', 'testtask')))
        {
            list($toList, $ccList) = $this->loadModel($objectType)->getToAndCcList($object);
        }
        if(empty($toList) and empty($ccList)) return false;

        $accounts  = empty($ccList) ? $toList : $toList . ',' . $ccList;
        $accounts  = $this->dao->select('mobile')->from(TABLE_USER)->where('account')->in($accounts)->andWhere('deleted')->eq(0)->fetchAll();
        $mobiles   = array();
        $delimiter = isset($this->app->config->sms->delimiter) ? $this->app->config->sms->delimiter : ',';
        foreach($accounts as $account)
        {
            if($account->mobile) $mobiles[$account->mobile] = $account->mobile;
        }

        return join($delimiter, $mobiles);
    }

    /**
     * Get sms content.
     *
     * @param  object    $object
     * @param  string    $objectType
     * @param  string    $actionType
     * @access public
     * @return string
     */
    public function getContent($object, $objectType, $actionType)
    {
        $this->app->loadConfig('action');
        $nameFields = $this->config->action->objectNameFields[$objectType];
        $title      = zget($object, $nameFields, '');

        return $this->loadModel('mail')->getSubject($objectType, $object, $title, $actionType);
    }
}

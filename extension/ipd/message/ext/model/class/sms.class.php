<?php
class smsMessage extends messageModel
{
    /**
     * @param string $objectType
     * @param int $objectID
     * @param string $actionType
     * @param int $actionID
     * @param string $actor
     * @param string $extra
     */
    public function send($objectType, $objectID, $actionType, $actionID, $actor = '', $extra = '')
    {
        if(!$this->app->isServing()) return;

        /* 工作流走工作流的发信。 */
        $workflow = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('module')->eq($objectType)->fetch();
        if($workflow && $workflow->buildin == '0') return;

        parent::send($objectType, $objectID, $actionType, $actionID, $actor, $extra);

        if(!isset($this->config->message->setting)) return;
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);

        if(!empty($messageSetting) && isset($messageSetting['sms']['setting']))
        {
            $actions = $messageSetting['sms']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                $this->loadModel('sms')->send($objectType, $objectID, $actionType);
            }
        }
    }
}

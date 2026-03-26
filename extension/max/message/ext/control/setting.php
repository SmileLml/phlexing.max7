<?php
class myMessage extends message
{
    public function setting()
    {
        /* Approval send mail. */
        $approvalflows = $this->dao->select('module')->from(TABLE_WORKFLOW)->where('approval')->eq('enabled')->fetchAll();
        foreach($approvalflows as $flow)
        {
            $this->config->message->objectTypes[$flow->module][] = 'submit';
            $this->config->message->objectTypes[$flow->module][] = 'cancel';
            $this->config->message->objectTypes[$flow->module][] = 'review';

            foreach(array('message', 'mail', 'sms', 'xuanxuan', 'webhook') as $module)
            {
                $this->config->message->available[$module][$flow->module][] = 'submit';
                $this->config->message->available[$module][$flow->module][] = 'cancel';
                $this->config->message->available[$module][$flow->module][] = 'review';
            }
        }

        /* Action send mail. */
        $actions = $this->dao->select('t1.module,t1.action,t1.method,t1.name,t2.buildin as flowBuildin')
            ->from(TABLE_WORKFLOWACTION)->alias('t1')
            ->leftJoin(TABLE_WORKFLOW)->alias('t2')->on('t1.module=t2.module')
            ->where('t1.buildin')->eq('0')
            ->andWhere('t1.status')->eq('enable')
            ->andWhere('t1.action')->notin('approvalsubmit,approvalcancel,approvalreview')
            ->fetchAll();

        foreach($actions as $action)
        {
            if(!in_array($action->method, array('create', 'edit', 'operate', 'batchcreate', 'batchoperate'))) continue; // 只有创建、编辑、操作才支持发信。
            if($action->flowBuildin == '1' && !isset($this->config->message->objectTypes[$action->module])) continue; // 内置流程不支持发信的则不追加动作。

            $this->lang->message->label->{$action->action}         = $action->name;
            $this->config->message->objectTypes[$action->module][] = $action->action;

            foreach(array('message', 'mail', 'sms', 'xuanxuan', 'webhook') as $module)
            {
                $this->config->message->available[$module][$action->module][] = $action->action;
            }
        }

        parent::setting();
    }
}

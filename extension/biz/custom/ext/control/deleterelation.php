<?php
class custom extends control
{
    /**
     * 删除关联关系。
     * Delete relation.
     *
     * @param  int    $key
     * @param  string $confirm no|yes
     * @access public
     * @return void
     */
    public function deleteRelation($key = 0, $confirm = 'no')
    {
        if($confirm != 'yes')
        {
            $count = $this->custom->getRelationObjectCount($key);
            if($count) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert({icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x',  message: '{$this->lang->custom->deleteRelationTip}'})"));

            $confirmURL = inlink('deleteRelation', "key=$key&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$this->lang->custom->notice->confirmDelete}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '{$confirmURL}'});});"));
        }

        $lang = $this->app->getClientLang();
        $this->custom->deleteItems("lang=$lang&section=relationList&key=$key");
        $this->custom->deleteItems("lang=all&section=relationList&key=$key");
        return $this->sendSuccess(array('load' => true));
    }
}

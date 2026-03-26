<?php
class custom extends control
{
    /**
     * 恢复自定义立项配置。
     * Reset charter info.
     *
     * @access public
     * @return void
     */
    public function resetCharterInfo()
    {
        $this->loadModel('setting')->setItem('system.custom.charterFiles', json_encode($this->lang->custom->charterFiles));
        return $this->sendSuccess(array('load' => true));
    }
}

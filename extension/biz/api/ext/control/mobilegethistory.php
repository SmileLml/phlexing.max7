<?php
class api extends control
{
    /**
     * Get history.
     * 
     * @param  int    $id
     * @param  string $type  task / bug / todo / story / project / product
     * @access public
     * @return void
     */
    public function mobileGetHistory($id, $type)
    {
        if($this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('api');
        }

        if(!$this->loadModel('user')->isLogon())die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failLogin)));

        $data = $this->api->getHistoryByID($id, $type);
        $data = $this->api->format('history', $data);

        die($this->api->compression($data));
    }
}

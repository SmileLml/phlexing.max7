<?php
class api extends control
{
    /**
     * Get data info.
     * 
     * @param  int    $id
     * @param  string $type  task / bug / todo / story / project / product
     * @access public
     * @return void
     */
    public function mobileGetInfo($id, $type, $history = 0)
    {
        if($this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('api');
        }

        if(!$this->loadModel('user')->isLogon())die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failLogin)));

        $data = $this->api->getDataByID($id, $type);
        if(empty($data)) die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failNoFind)));
        if(isset($data->deleted) and $data->deleted != 0) die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failDeleted)));

        $data->files = $this->loadModel('file')->getByObject($type, $id);

        $data = $this->api->format($type, $data, 'all');

        if($history)
        {
            $historyData = $this->api->getHistoryByID($id, $type);
            $historyData = $this->api->format('history', $historyData);
            $data->history = $historyData;
        }

        die($this->api->compression($data));
    }
}

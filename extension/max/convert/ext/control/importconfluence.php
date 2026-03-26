<?php
helper::importControl('convert');
class myConvert extends convert
{
    /**
     * 导入confluence数据。
     * Import jira main logic.
     *
     * @param  string $mode
     * @param  string $type   user|space
     * @param  string $nextObject
     * @param  bool   $createTable
     * @access public
     * @return void
     */
    public function importConfluence($mode = 'show', $type = 'user', $nextObject = 'no', $createTable = false)
    {
        set_time_limit(0);

        if($mode == 'import')
        {
            $result = $this->convert->importConfluenceData($type, $nextObject, $createTable);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($result['finished'])) return $this->send(array('result' => 'finished', 'message' => $this->lang->convert->confluence->successfully));

            $type = zget($this->lang->convert->confluence->objectList, $result['type'], $result['type']);

            $response['result']  = 'unfinished';
            $response['type']    = $type;
            $response['count']   = $result['count'];
            $response['message'] = sprintf($this->lang->convert->jira->importResult, $type, $type, $result['count']);
            $response['next']    = inlink('importConfluence', "mode={$mode}&type={$result['type']}&nextObject=yes");
            return $this->send($response);
        }

        if(empty($_SESSION['confluenceUser'])) $this->locate(inlink('index'));

        $this->view->title = $this->lang->convert->confluence->importData;
        $this->display();
    }
}

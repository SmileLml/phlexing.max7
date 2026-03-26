<?php
class custom extends control
{
    public function libreOffice()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            if($data->libreOfficeTurnon)
            {
                if($data->convertType == 'libreoffice')
                {
                    if(!file_exists($data->sofficePath)) return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->errorSofficePath));

                    $data->collaboraPath = '';
                    exec("{$data->sofficePath} --version 2>&1", $out, $result);
                    if($result != 0) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->custom->errorRunSoffice, join($out))));
                }
                else
                {
                    if($this->config->requestType != 'PATH_INFO') return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->cannotUseCollabora));

                    $data->sofficePath  = '';
                    $collaboraDiscovery = $this->loadModel('file')->getCollaboraDiscovery($data->collaboraPath);
                    if(empty($collaboraDiscovery)) return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->errorRunCollabora));
                }
            }

            $this->loadModel('setting')->setItems('system.file', $data);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->lang->admin->menu->system['subModule'] = 'custom';
        $this->loadModel('file');

        $this->view->title = $this->lang->custom->libreOffice;
        $this->display();
    }
}

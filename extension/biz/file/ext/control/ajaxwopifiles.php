<?php
class file extends control
{
    public function ajaxWopiFiles($fileID, $canEdit = 0)
    {
        if(isset($_GET['access_token'])) helper::restartSession($_GET['access_token']);
        if(!($this->loadModel('user')->isLogon() or ($this->app->company->guest and $this->app->user->account == 'guest'))) die();

        $file = $this->file->getById($fileID);

        $method   = strtoupper($_SERVER['REQUEST_METHOD']);
        $content  = false;
        if(strpos($_SERVER['PATH_INFO'], '/contents') !== false) $content = true;

        if($content)
        {
            if($method == 'GET')
            {
                header("Content-type: application/octet-stream");
                die(file_get_contents($file->realPath));
            }
            if($method == 'POST' and $canEdit)
            {
                $content = fopen('php://input', 'r');
                file_put_contents($file->realPath, $content);
            }
        }
        else
        {
            die($this->file->getFileInfo4Wopi($file, $canEdit));
        }
    }
}

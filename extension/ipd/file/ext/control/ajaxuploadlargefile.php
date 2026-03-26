<?php
helper::import(dirname(dirname(dirname(__FILE__))) . "/control.php");
class myfile extends file
{
    /**
     * Ajax upload large file.
     *
     * @param  int    $module
     * @param  string $uid
     * @access public
     * @return void
     */
    public function ajaxUploadLargeFile($module, $uid = '')
    {
        if($_FILES)
        {
            $file = $this->file->getUploadFile('file');
            if(!$file) die(json_encode(array('result' => 'fail', 'message' => $this->lang->error->noData)));
            if(empty($file['extension']) or !in_array($file['extension'], $this->config->file->allAllowExtensions))
            {
                die(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormate)));
            }

            $uploadedFile = $this->file->saveUploadFile($file, $uid);
            if($uploadedFile === false)
            {
                die(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileMove)));
            }
            else
            {
                if(!empty($uploadedFile))
                {
                    $extension = $uploadedFile['extension'];
                    if($extension == 'mp4') $uploadedFile['duration'] = $_POST['duration'];

                    $sessionName   = $module . 'UploadedFile';
                    $uploadedFiles = $this->session->$sessionName;
                    $fileName      = basename($uploadedFile['title']);
                    $uploadedFiles[$fileName] = $uploadedFile;

                    $this->session->set($sessionName, $uploadedFiles);
                }
                die(json_encode(array('result' => 'success', 'file' => $file, 'message' => $this->lang->file->uploadSuccess)));
            }
        }

        $this->view->uid    = empty($uid) ? uniqid() : $uid;
        $this->view->module = $module;

        $this->display();
    }
}

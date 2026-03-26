<?php
helper::importControl('file');
class myfile extends file
{
    /**
     * @param int|string $edit
     * @param int $fileID
     * @param string $mouse
     */
    public function download($fileID, $mouse = '', $edit = 0)
    {
        $sessionID = session_id();
        if($sessionID != $this->app->sessionID) helper::restartSession($this->app->sessionID);

        $file = $this->file->getById($fileID);
        if(!$this->file->checkPriv($file))
        {
            echo(js::alert($this->lang->file->accessDenied));
            if(isonlybody()) return print(js::reload('parent.parent'));
            return print(js::locate(helper::createLink('my', 'index'), 'parent.parent'));
        }

        if($this->config->file->libreOfficeTurnon)
        {
            $officeTypes = 'doc|docx|xls|xlsx|ppt|pptx|pdf';
            if(stripos($officeTypes, $file->extension) !== false and $mouse == 'left')
            {
                if(isset($this->config->file->convertType) and $this->config->file->convertType == 'collabora' and $this->config->requestType == 'PATH_INFO')
                {
                    $discovery = $this->file->getCollaboraDiscovery();
                    if(empty($discovery)) die(js::alert(sprintf($this->lang->file->collaboraFail, $this->config->file->collaboraPath)));
                    if($discovery and isset($discovery[$file->extension]))
                    {
                        $withTID = helper::isWithTID();
                        if($withTID)
                        {
                            $tid = $_GET['tid'];
                            unset($_GET['tid']);
                        }

                        $wopiSrc     = common::getSysURL() . $this->createLink('file', 'ajaxWopiFiles', "fileID=$fileID");
                        $wopiEditSrc = common::getSysURL() . $this->createLink('file', 'ajaxWopiFiles', "fileID=$fileID&canEdit=1");

                        if($withTID) $_GET['tid'] = $tid;

                        $action       = $discovery[$file->extension]['action'];
                        $collaboraUrl = $discovery[$file->extension]['urlsrc'];
                        $this->view->collaboraUrl  = $collaboraUrl . 'WOPISrc=' . $wopiSrc . '&access_token=' . $sessionID . '&lang=' . $this->app->getClientLang();
                        if($action == 'edit') $this->view->collaboraEdit = $collaboraUrl . 'WOPISrc=' . $wopiEditSrc . '&access_token=' . $sessionID . '&lang=' . $this->app->getClientLang();
                        $isEditing    = ($edit && $edit != 'never') ? common::hasPriv('file', 'edit') : false;
                        $isEditable   = !$isEditing && $edit != 'never';
                        if($isEditing) $this->view->collaboraUrl = $this->view->collaboraEdit;
                        $this->view->edit        = $isEditing;
                        $this->view->isEditable  = $isEditable;
                        $this->view->title       = $file->title;
                        die($this->display('file', 'collabora'));
                    }
                }
                else
                {
                    $sofficePath = isset($this->config->file->sofficePath) ? $this->config->file->sofficePath : '';
                    if(file_exists($file->realPath) and !empty($sofficePath) and file_exists($sofficePath))
                    {
                        $convertedFile = $file->extension == 'pdf' ? $file->realPath : $this->file->convertOffice($file);
                        if($convertedFile)
                        {
                            $mime = strpos($convertedFile, '.html') ? "text/html" : 'application/pdf';
                            header("Content-type: $mime");

                            $handle = fopen($convertedFile, "r");
                            if($handle)
                            {
                                while(!feof($handle)) echo str_replace('<title xml:lang="en-US"/>', '<title xml:lang="en-US"></title>', fgets($handle));
                                fclose($handle);
                                die();
                            }
                        }
                        elseif(file_exists($this->app->getCacheRoot() . 'convertoffice/lock'))
                        {
                            die("<html><head><meta charset='utf-8'></head><body>{$this->lang->file->officeBusy}</body></html>");
                        }
                    }
                }
            }
        }

        return parent::download($fileID, $mouse);
    }
}

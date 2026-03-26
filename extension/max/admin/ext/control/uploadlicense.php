<?php
class admin extends control
{
    public function uploadLicense()
    {
        $configRoot = $this->app->getConfigRoot();
        if($_FILES || $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $tmpName  = $_FILES['file']['tmp_name'][0];
            $fileName = $_FILES['file']['name'][0];
            $dest     = $this->app->getTmpRoot() . "/extension/$fileName";

            $pathinfo = pathinfo($fileName);
            if(empty($pathinfo['extension']) || $pathinfo['extension'] != 'zip') return $this->send(array('result' => 'fail', 'message' => $this->lang->admin->notZip));

            move_uploaded_file($tmpName, $dest);

            /* Extract files. */
            $extractedFile = basename($fileName, '.zip');
            $extractedPath = $this->app->getTmpRoot() . "/extension/$extractedFile";
            $this->app->loadClass('pclzip', true);
            $zip = new pclzip($dest);
            $files = $zip->listContent();
            $removePath = $files[0]['filename'];
            if($zip->extract(PCLZIP_OPT_PATH, $extractedPath, PCLZIP_OPT_REMOVE_PATH, $removePath) == 0)
            {
                unlink($dest);
                return $this->sendError($zip->errorInfo(true));
            }

            $grantCount = 0;
            foreach($files as $file)
            {
                if(strpos($file['filename'], 'config/license/maxcallback.php') !== false)
                {
                    $grantFile = $extractedPath . '/config/license/maxcallback.php';
                    $handle    = fopen($grantFile, "r");
                    while(!feof($handle))
                    {
                        $content = fgets($handle);
                        if(preg_match('/[\s]*\$users[\s]*=[\s]*[\'|"]?([0-9]*)[\'|"]?;/i', $content, $matches))
                        {
                            if(!empty($matches[1])) $grantCount = $matches[1];
                            break;
                        }
                    }
                    fclose($handle);
                    break;
                }
            }
            if($grantCount > 0)
            {
                $userCount = $this->admin->getUserCount();
                if($grantCount < $userCount) return $this->sendError(sprintf($this->lang->admin->grantCountError, $userCount, $grantCount), $this->createLink('admin', 'license'));
            }

            $classFile = $this->app->loadClass('zfile');
            $classFile->copyDir($extractedPath . '/config/', $configRoot);
            $classFile->removeDir($extractedPath);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $fixFile = array();
        if(!is_writable($configRoot)) $fixFile[] = $configRoot;
        if(is_dir($configRoot . 'license') and !is_writable($configRoot . 'license')) $fixFile[] = $configRoot . 'license';

        $this->view->fixFile = $fixFile;
        $this->display();
    }
}

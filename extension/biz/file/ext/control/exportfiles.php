<?php
helper::importControl('file');
class myfile extends file
{
    /**
     * Export files as a compressed file.
     *
     * @access public
     * @return void
     */
    public function exportFiles()
    {
        $headerName = $this->post->fileName;

        $saveBasePath = $this->app->getCacheRoot() . DS . $this->app->user->account . time() . DS . $headerName . DS;
        if(!file_exists($saveBasePath)) mkdir($saveBasePath, 0777, true);

        $fileIdList = $_POST['fileIdList'];
        $fileList   = $this->dao->select('id,pathname,title,extension')->from(TABLE_FILE)->where('id')->in($fileIdList)->fetchAll('id');

        $fileBasePath = $this->app->wwwRoot . "data/upload/{$this->app->company->id}" . DS;
        foreach($fileList as $fileID => $file)
        {
            $filename = str_replace(strrchr($file->pathname, '.'), '', $file->pathname);
            $dotPos   = strrpos($file->title, '.');
            $realname = substr($file->title, 0, $dotPos);
            $realExt  = substr($file->title, $dotPos + 1);

            $extension    = !empty($realExt) ? $realExt : $file->extension;
            $fileSavePath = $saveBasePath . $realname . '.' . $extension;
            if(file_exists($fileSavePath))
            {
                if(md5_file($fileBasePath . $filename) == md5_file($fileSavePath)) continue;
                $fileSavePath = $saveBasePath . $realname . '_' . $fileID . '.' . $extension;
            }
            copy($fileBasePath . $filename, $fileSavePath);
        }

        $parentSavePath = dirname($saveBasePath);
        $zipSavePath    = $parentSavePath . DS . $headerName . '.zip';

        helper::cd($parentSavePath);
        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($zipSavePath);
        $zip->create($headerName);

        helper::cd();
        $fileData = file_get_contents($zipSavePath);
        $zfile    = $this->app->loadClass('zfile');
        $zfile->removeDir($parentSavePath);
        $this->file->sendDownHeader($headerName . '.zip', 'zip', $fileData);
    }
}

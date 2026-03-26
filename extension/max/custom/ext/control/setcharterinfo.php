<?php
class custom extends control
{
    /**
     * 自定义立项配置。
     * Set charter info.
     *
     * @access public
     * @return void
     */
    public function setCharterInfo()
    {
        if(!empty($_POST))
        {
            $this->lang->custom->type = $this->lang->custom->charter->type;
            $formData = form::batchData($this->config->custom->form->setCharterInfo)->get();

            $levels = array();
            foreach($formData as $index => $charterFile)
            {
                if(empty($charterFile->level)) continue;
                if(!empty($levels[$charterFile->level])) dao::$errors["level[$index]"] = $this->lang->custom->charter->tips->sameLevel;

                $levels[$charterFile->level] = $charterFile->level;
                if(empty($charterFile->key)) $charterFile->key = 'key' . mt_rand();

                $indexs = $charterFile->index;
                $names  = $charterFile->name;
                foreach($indexs as $type => $files)
                {
                    $fileNames = array();
                    foreach($files as $fileIndex => $fileKey)
                    {
                        if(empty($fileKey)) $fileKey = 'charter' . mt_rand();
                        $fileName = $names[$type][$fileIndex];
                        if(!empty($fileName)) $fileNames[] = array('index' => $fileKey, 'name' => $fileName);
                    }
                    if(empty($fileNames)) dao::$errors["index[{$index}][{$type}][]"] = $this->lang->custom->charter->tips->leastOne;
                    $charterFile->$type = $fileNames;
                }

                if(!isset($charterFile->type)) $charterFile->type = 'plan';
                $charterFiles[$charterFile->key] = $charterFile;
            }
            if(empty($charterFiles)) dao::$errors["level"] = $this->lang->custom->charter->tips->leastOne;
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('setting')->setItem('system.custom.charterFiles', json_encode($charterFiles));
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->custom->setCharterInfo;
        $this->display();
    }
}

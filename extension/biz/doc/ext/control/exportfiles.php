<?php
helper::importControl('doc');
class mydoc extends control
{
    public function exportFiles($objectID, $objectType)
    {
        if(!in_array($objectType, array('product', 'project', 'execution'))) return false;

        $table      = $this->config->objectTables[$objectType];
        $objectName = $this->dao->select('name')->from($table)->where('id')->eq($objectID)->fetch('name');

        $files = $this->doc->getLibFiles($objectType, $objectID, '', 'id_desc');
        $allFilesIdList = implode(',', array_keys($files));

        if($_POST)
        {
            $this->post->set('fileIdList', $this->post->range == 'selected' ? $this->cookie->checkedItem : $allFilesIdList);
            $this->post->set('range', $this->post->range);
            $this->post->set('format', $this->post->format);
            $this->post->set('fileName', $this->post->fileName);

            return $this->fetch('file', 'exportFiles');
        }

        $this->view->title    = $this->lang->export;
        $this->view->fileName = $objectName . '-' . $this->lang->doclib->files;
        $this->display();
    }
}

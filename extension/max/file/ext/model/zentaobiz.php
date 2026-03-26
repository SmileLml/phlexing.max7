<?php
public function convertOffice($file, $type = '')
{
    return $this->loadExtension('zentaobiz')->convertOffice($file, $type);
}

public function getCollaboraDiscovery($collaboraPath = '')
{
    return $this->loadExtension('zentaobiz')->getCollaboraDiscovery($collaboraPath);
}

public function getFileInfo4Wopi($file, $canEdit = false)
{
    return $this->loadExtension('zentaobiz')->getFileInfo4Wopi($file, $canEdit);
}

public function getExportLibs()
{
    return $this->loadExtension('zentaobiz')->getExportLibs();
}

public function processLibs($libs)
{
    return $this->loadExtension('zentaobiz')->processLibs($libs);
}

public function getDocExportData($libID)
{
    return $this->loadExtension('zentaobiz')->getDocExportData($libID);
}

public function getAPIExportData($libID)
{
    return $this->loadExtension('zentaobiz')->getAPIExportData($libID);
}

public function getWikiExportData($libID)
{
    return $this->loadExtension('zentaobiz')->getWikiExportData($libID);
}

public function copyObjectFiles($objectType, $object = null)
{
    return $this->loadExtension('zentaobiz')->copyObjectFiles($objectType, $object);
}

<?php
/**
 * @param string $ext
 * @param string $checkDate
 */
public function checkExtLicense($ext, $checkDate = '')
{
    return $this->loadExtension('zentaobiz')->checkExtLicense($ext, $checkDate);
}

public function checkPriv()
{
    return $this->loadExtension('zentaobiz')->checkPriv();
}

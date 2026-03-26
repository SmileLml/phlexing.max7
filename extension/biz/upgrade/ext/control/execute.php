<?php
helper::importControl('upgrade');
class myupgrade extends upgrade
{
    /**
     * @param string $fromVersion
     */
    public function execute($fromVersion = '')
    {
        parent::execute($fromVersion);
    }
}

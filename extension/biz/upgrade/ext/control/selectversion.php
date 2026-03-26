<?php
helper::importControl('upgrade');
class myupgrade extends upgrade
{
    public function selectVersion()
    {
        if(strpos($this->config->installedVersion, 'pro') === false)
        {
            $bizext = $this->dao->select('*')->from(TABLE_EXTENSION)->where('code')->like('bizext%')->andWhere('status')->eq('installed')->fetch();
            if(!empty($bizext)) $this->config->installedVersion = 'pro' . $bizext->version;
        }
        return parent::selectVersion();
    }
}

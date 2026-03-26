<?php
$this->loadModel('effort')->convertEstToEffort();
if(version_compare($this->config->version, '3.1', '<='))
{
    $sql = "ALTER TABLE  `zt_task` ADD  `estStarted` DATE NOT NULL AFTER  `assignedDate` , ADD  `realStarted` DATE NOT NULL AFTER  `estStarted`;";
    $sql = str_replace('zt_', $this->config->db->prefix, $sql);
    try
    {
        $this->dbh->exec($sql);
    }
    catch (PDOException $e) 
    {
    }
}

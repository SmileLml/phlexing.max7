<?php
if(version_compare($this->config->version, '3.1', '<='))
{
    $sql = "ALTER TABLE `zt_task` DROP `estStarted`, DROP `realStarted`;";
    $sql = str_replace('zt_', $this->config->db->prefix, $sql);
    try
    {
        $this->dbh->exec($sql);
    }
    catch (PDOException $e) 
    {
    }
}

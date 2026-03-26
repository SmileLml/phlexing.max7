<?php
$info = $this->extension->getInfoFromDB('fullcalendar');
if($info and $info->status == 'installed')
{
    $this->extension->removePackage('fullcalendar');
    $sql  = "DELETE FROM `zt_grouppriv` WHERE `module` = 'todo' AND `method` = 'fullcalendar';";
    $sql .= "DELETE FROM `zt_grouppriv` WHERE `module` = 'effort' AND `method` = 'fullcalendar';";
    $sql .= "DELETE FROM `zt_grouppriv` WHERE `module` = 'project' AND `method` = 'fullcalendar';";
    $sql .= "DELETE FROM `zt_grouppriv` WHERE `module` = 'user' AND `method` = 'effortfullcalendar';";
    $sql .= "DELETE FROM `zt_grouppriv` WHERE `module` = 'user' AND `method` = 'todofullcalendar';";
    $sql .= "DELETE FROM `zt_extension` WHERE `code`='fullcalendar';";
    $sql = str_replace('zt_', $this->config->db->prefix, $sql);
    $this->dao->exec($sql);
}

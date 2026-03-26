ALTER TABLE `zt_host` ADD `unit` enum('GB','TB') NOT NULL DEFAULT 'GB' AFTER `diskSize`;

ALTER TABLE `zt_workflowdatasource` ADD `view` varchar(20) NOT NULL AFTER `datasource`;
ALTER TABLE `zt_workflowdatasource` ADD `keyField` varchar(50) NOT NULL AFTER `view`;
ALTER TABLE `zt_workflowdatasource` ADD `valueField` varchar(50) NOT NULL AFTER `keyField`;

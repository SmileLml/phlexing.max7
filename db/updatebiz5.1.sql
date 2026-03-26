ALTER TABLE `zt_service` ADD `external` enum('0','1') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_service` ADD `port` smallint(5) unsigned NOT NULL AFTER `external`;
ALTER TABLE `zt_service` ADD `entry` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `port`;
ALTER TABLE `zt_service` ADD `deploy` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `entry`;

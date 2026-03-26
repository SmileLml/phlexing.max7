ALTER TABLE `zt_im_conference` ADD `topic` text NULL AFTER `openedDate`;
ALTER TABLE `zt_im_conference` ADD `startTime` datetime NULL AFTER `topic`;
ALTER TABLE `zt_im_conference` ADD `endTime` datetime NULL AFTER `startTime`;
ALTER TABLE `zt_im_conference` ADD `password` char(20) NOT NULL DEFAULT '' AFTER `endTime`;
ALTER TABLE `zt_im_conference` ADD `type` enum('default', 'periodic', 'scheduled') NOT NULL DEFAULT 'default' AFTER `password`;
ALTER TABLE `zt_im_conference` ADD `number` char(20) NOT NULL DEFAULT '' AFTER `type`;
CREATE INDEX `status` ON `zt_im_conference` (`status`);

ALTER TABLE `zt_im_userdevice` ADD `online` tinyint(1) DEFAULT 0 NOT NULL AFTER `lastLogout`;
ALTER TABLE `zt_im_userdevice` ADD `version` char(10) DEFAULT '' NOT NULL AFTER `online`;

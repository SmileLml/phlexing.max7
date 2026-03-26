ALTER TABLE `zt_im_conference` ADD `subscribers` text NULL AFTER `participants`;
ALTER TABLE `zt_im_conference` ADD `note` text NULL AFTER `number`;
ALTER TABLE `zt_im_conference` ADD `sentNotify` tinyint(1) NOT NULL DEFAULT 0 AFTER `note`;
ALTER TABLE `zt_im_conference` ADD `reminderTime` int NOT NULL DEFAULT 0 AFTER `sentNotify`;
ALTER TABLE `zt_im_conference` MODIFY `status` enum ('closed', 'open', 'notStarted') DEFAULT 'closed' NOT NULL;

-- DROP TABLE IF EXISTS `zt_im_conferenceuser`;
CREATE TABLE IF NOT EXISTS `zt_im_conferenceuser` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `conference` mediumint(8) NOT NULL DEFAULT 0,
  `user` mediumint(8) NOT NULL DEFAULT 0,
  `hide` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `conferenceuser` (`conference`, `user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;

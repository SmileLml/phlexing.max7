
ALTER TABLE `zt_im_conference` ADD `moderators` text NULL AFTER `reminderTime`;
ALTER TABLE `zt_im_conference` ADD `isPrivate` enum ('0', '1') NOT NULL DEFAULT '0' AFTER `moderators`;
ALTER TABLE `zt_im_conference` ADD `isInner` enum('0', '1') NOT NULL DEFAULT '1' AFTER `isPrivate`;
ALTER TABLE `zt_im_conference` ALTER COLUMN `status` SET DEFAULT 'notStarted';

-- DROP TABLE IF EXISTS `zt_im_conferenceinvite`;
CREATE TABLE IF NOT EXISTS `zt_im_conferenceinvite` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `conferenceID` mediumint(8) unsigned NOT NULL,
  `inviteeID` mediumint(8) unsigned NOT NULL,
  `status` enum('pending', 'accepted', 'rejected') NOT NULL DEFAULT 'pending',
  `createdDate` datetime NULL,
  `updatedDate` datetime NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conference_user` (`conferenceID`, `inviteeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;

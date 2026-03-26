ALTER TABLE `zt_user` ADD `pinyin` varchar(255) NOT NULL DEFAULT '' AFTER `realname`;
ALTER TABLE `zt_im_chat` ADD `lastMessage` int(11) unsigned NOT NULL DEFAULT 0 AFTER `lastActiveTime`;
ALTER TABLE `zt_im_conference` ADD `invitee` text NULL AFTER `participants`;
ALTER TABLE `zt_im_message` CHANGE `id` `id` int(11) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `zt_im_message_backup` (
  `id` int(11) unsigned NOT NULL,
  `gid` char(40) NOT NULL DEFAULT '',
  `cgid` char(40) NOT NULL DEFAULT '',
  `user` varchar(30) NOT NULL DEFAULT '',
  `date` datetime NULL,
  `order` bigint(8) unsigned NOT NULL,
  `type` enum('normal', 'broadcast', 'notify') NOT NULL DEFAULT 'normal',
  `content` text NULL,
  `contentType` enum('text', 'plain', 'emotion', 'image', 'file', 'object', 'code') NOT NULL DEFAULT 'text',
  `data` text NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `zt_im_message_index` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tableName` char(64) NOT NULL,
  `start` int(11) unsigned NOT NULL,
  `end` int(11) unsigned NOT NULL,
  `startDate` datetime NULL,
  `endDate` datetime NULL,
  `chats` text NULL,
  PRIMARY KEY (`id`),
  KEY `tableName` (`tableName`),
  KEY `start` (`start`),
  KEY `end` (`end`),
  KEY `startDate` (`startDate`),
  KEY `endDate` (`endDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `zt_im_chat_message_index` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gid` char(40) NOT NULL,
  `tableName` char(64) NOT NULL,
  `start` int(11) unsigned NOT NULL,
  `end` int(11) unsigned NOT NULL,
  `startDate` datetime NULL,
  `endDate` datetime NULL,
  `count` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chattable` (`gid`,`tableName`),
  KEY `start` (`start`),
  KEY `end` (`end`),
  KEY `startDate` (`startDate`),
  KEY `endDate` (`endDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `zt_im_userdevice` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `user` mediumint(8) NOT NULL DEFAULT 0,
  `device` char(40) NOT NULL DEFAULT 'default',
  `lastLogin` datetime NULL,
  `lastLogout` datetime NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `lastLogin` (`lastLogin`),
  KEY `lastLogout` (`lastLogout`),
  UNIQUE KEY `userdevice` (`user`, `device`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;

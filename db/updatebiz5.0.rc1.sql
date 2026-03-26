CREATE TABLE IF NOT EXISTS `zt_account` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `adminURI` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `extra` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `status` varchar(30) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  key `name` (`name`),
  key `provider` (`provider`),
  key `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_domain`(
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `adminURI` varchar(255) NOT NULL,
  `resolverURI` varchar(255) NOT NULL,
  `register` varchar(255) NOT NULL,
  `expiredDate` datetime NOT NULL,
  `renew` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  key `domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_host` add `admin` smallint(5) UNSIGNED NOT NULL DEFAULT 0 AFTER assetID;

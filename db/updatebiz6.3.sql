CREATE TABLE `zt_traincourse` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `category` mediumint(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL,
  `teacher` varchar(30) NOT NULL default '',
  `desc` text NOT NULL,
  `createdBy` varchar(255) NOT NULL,
  `createdDate` date NOT NULL,
  `editedBy` varchar(255) NOT NULL,
  `editedDate` date NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_traincontents` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `course` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `order` mediumint(8) NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_traincategory` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `grade` tinyint(3) NOT NULL,
  `order` mediumint(8) NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `path` (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_trainrecords` (
  `user` char(30) NOT NULL,
  `objectId` mediumint(8) unsigned NOT NULL,
  `objectType` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`user`, `objectId`, `objectType`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

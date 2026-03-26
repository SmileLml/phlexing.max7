UPDATE `zt_process` SET `name` = '技术解决方案' , `abbr` = 'TS' WHERE `id` = 18;

CREATE TABLE `zt_approval` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `flow` mediumint(8) NOT NULL,
  `objectType` varchar(30) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `nodes` mediumtext NOT NULL,
  `version` mediumint(9) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'doing',
  `result` varchar(20) NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_approvalflow` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `desc` mediumtext NOT NULL,
  `version` mediumint(8) NOT NULL DEFAULT '1',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `type` varchar(30) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_approvalflowobject` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `root` int(8) NOT NULL,
  `flow` int(8) NOT NULL,
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `extra` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_approvalflowspec` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `flow` mediumint(8) NOT NULL,
  `version` mediumint(8) NOT NULL,
  `nodes` mediumtext NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_approvalnode` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `approval` mediumint(8) NOT NULL,
  `type` enum('review','cc') NOT NULL,
  `title` varchar(255) NOT NULL,
  `account` char(30) NOT NULL,
  `node` varchar(100) NOT NULL,
  `reviewType` varchar(100)  NOT NULL DEFAULT 'manual',
  `multipleType` enum('and','or') NOT NULL DEFAULT 'and',
  `prev` mediumtext NOT NULL,
  `next` mediumtext NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'wait',
  `result` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `opinion` mediumtext NOT NULL,
  `extra` mediumtext NOT NULL,
  `reviewedBy` char(30) NOT NULL,
  `reviewedDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_approvalobject` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `approval` int(8) NOT NULL,
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(8) NOT NULL,
  `extra` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_approvalrole` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` char(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `users` longtext NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

REPLACE INTO `zt_approvalflow` (`id`, `name`, `code`, `desc`, `version`, `createdBy`, `createdDate`, `type`, `deleted`) VALUES
(1, '最简审批', 'simple', '', 1, 'admin', '2022-04-29 08:46:40', 'project', 0);

REPLACE INTO `zt_approvalflowspec` (`id`, `flow`, `version`, `nodes`, `createdBy`, `createdDate`) VALUES
(1, 1, 1, '[{\"type\":\"start\",\"ccs\":[]},{\"id\":\"3ewcj92p55e\",\"type\":\"approval\",\"title\":\"审批\",\"reviewType\":\"manual\",\"multiple\":\"and\",\"agentType\":\"pass\",\"reviewers\":[{\"type\":\"select\"}],\"ccs\":[]},{\"type\":\"end\",\"ccs\":[]}]', 'admin', '2022-04-29 08:46:40');

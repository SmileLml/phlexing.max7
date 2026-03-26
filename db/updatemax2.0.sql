-- DROP TABLE IF EXISTS `zt_opportunity`;
CREATE TABLE IF NOT EXISTS `zt_opportunity` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `source` char(30) NOT NULL,
  `type` char(30) NOT NULL,
  `strategy` char(30) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `impact` mediumint(8) NOT NULL,
  `chance` mediumint(8) NOT NULL,
  `ratio` mediumint(8) NOT NULL,
  `pri` char(30) NOT NULL,
  `identifiedDate` date NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedDate` date NOT NULL,
  `prevention` text NOT NULL,
  `plannedClosedDate` date NOT NULL,
  `actualClosedDate` date NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `activatedBy` varchar(30) NOT NULL,
  `activatedDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL,
  `closedDate` datetime NOT NULL,
  `canceledBy` varchar(30) NOT NULL,
  `canceledDate` datetime NOT NULL,
  `cancelReason` char(30) NOT NULL,
  `hangupedBy` varchar(30) NOT NULL,
  `hangupedDate` datetime NOT NULL,
  `resolution` text NOT NULL,
  `resolvedBy` varchar(30) NOT NULL,
  `resolvedDate` datetime NOT NULL,
  `lastCheckedBy` varchar(30) NOT NULL,
  `lastCheckedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_trainplan`;
CREATE TABLE IF NOT EXISTS `zt_trainplan` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `place` varchar(255) NOT NULL,
  `trainee` text NOT NULL,
  `lecturer` varchar(20) NOT NULL,
  `type` enum('inside','outside') NOT NULL DEFAULT 'inside',
  `status` varchar(20) NOT NULL,
  `summary` text NOT NULL,
  `createdBy` char(30),
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_gapanalysis`;
CREATE TABLE IF NOT EXISTS `zt_gapanalysis` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) unsigned NOT NULL,
  `account` varchar(30) NOT NULL,
  `role` varchar(20) NOT NULL,
  `definition` varchar(255) NOT NULL,
  `analysis` text NOT NULL,
  `isTrain` enum('0','1') NOT NULL DEFAULT '0',
  `createdBy` char(30),
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_account` (`project`,`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_todo` MODIFY COLUMN `type` char(15) NOT NULL;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES
(1, 'opportunity', 'browse'),
(1, 'opportunity', 'create'),
(1, 'opportunity', 'edit'),
(1, 'opportunity', 'delete'),
(1, 'opportunity', 'activate'),
(1, 'opportunity', 'close'),
(1, 'opportunity', 'hangup'),
(1, 'opportunity', 'cancel'),
(1, 'opportunity', 'track'),
(1, 'opportunity', 'view'),
(1, 'opportunity', 'assignTo'),
(1, 'trainplan', 'browse'),
(1, 'trainplan', 'create'),
(1, 'trainplan', 'edit'),
(1, 'trainplan', 'delete'),
(1, 'trainplan', 'finish'),
(1, 'trainplan', 'summary'),
(1, 'trainplan', 'view'),
(1, 'gapanalysis', 'browse'),
(1, 'gapanalysis', 'create'),
(1, 'gapanalysis', 'edit'),
(1, 'gapanalysis', 'delete'),
(1, 'gapanalysis', 'view');

-- DROP TABLE IF EXISTS `zt_researchplan`;
CREATE TABLE IF NOT EXISTS `zt_researchplan` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `stakeholder` varchar(255) NOT NULL,
  `objective` varchar(255) NOT NULL,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `team` varchar(255) NOT NULL,
  `method` enum('','videoConference','interview','questionnaire','telephoneInterview') NOT NULL,
  `outline` text NOT NULL,
  `schedule` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_researchreport`;
CREATE TABLE IF NOT EXISTS `zt_researchreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) unsigned NOT NULL,
  `relatedPlan` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `customer` varchar(255) NOT NULL,
  `researchObjects` varchar(255) NOT NULL,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `method` enum('','videoConference','interview','questionnaire','telephoneInterview') NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_meeting`;
CREATE TABLE IF NOT EXISTS `zt_meeting` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `begin` time NOT NULL,
  `end` time NOT NULL,
  `dept` mediumint(8) NOT NULL,
  `mode` varchar(255) NOT NULL,
  `host` varchar(30) NOT NULL,
  `participant` text NOT NULL,
  `date` date NOT NULL,
  `room` int NOT NULL,
  `minutes` text NOT NULL,
  `minutedBy` varchar(30) NOT NULL,
  `minutedDate` datetime NOT NULL,
  `objectType` varchar(30) NOT NULL,
  `objectID` mediumint(8) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_meetingroom`;
CREATE TABLE IF NOT EXISTS `zt_meetingroom` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `position` varchar(30) NOT NULL,
  `seats` int NOT NULL,
  `equipment` varchar(255) NOT NULL,
  `openTime` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8;

ALTER TABLE `zt_gapanalysis` CHANGE `definition` `definition` text NOT NULL;
ALTER TABLE `zt_gapanalysis` CHANGE `isTrain` `needTrain` enum('no','yes') NOT NULL DEFAULT 'no';
ALTER TABLE `zt_activity` ADD COLUMN `tailorNorm` varchar(255) NOT NULL AFTER `optional`;
ALTER TABLE `zt_zoutput` ADD COLUMN `tailorNorm` varchar(255) NOT NULL AFTER `optional`;

-- DROP TABLE IF EXISTS `zt_assetlib`;
CREATE TABLE IF NOT EXISTS `zt_assetlib` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8;

ALTER TABLE `zt_story` ADD `lib` mediumint(8) unsigned NOT NULL default 0 after `mailto`;
ALTER TABLE `zt_story` ADD `fromStory` mediumint(8) unsigned NOT NULL default 0 after `lib`;
ALTER TABLE `zt_story` ADD `fromVersion` smallint(6) NOT NULL default 1 after `fromStory`;
ALTER TABLE `zt_story` ADD `approvedDate` date NOT NULL after `assignedDate`;

ALTER TABLE `zt_issue` ADD `lib` mediumint(8) unsigned NOT NULL default 0 after `owner`;
ALTER TABLE `zt_issue` ADD `from` mediumint(8) unsigned NOT NULL default 0 after `lib`;
ALTER TABLE `zt_issue` ADD `version` smallint(6) NOT NULL default 1 after `from`;
ALTER TABLE `zt_issue` ADD `approvedDate` date NOT NULL after `assignedDate`;

ALTER TABLE `zt_risk` ADD `lib` mediumint(8) unsigned NOT NULL default 0 after `actualClosedDate`;
ALTER TABLE `zt_risk` ADD `from` mediumint(8) unsigned NOT NULL default 0 after `lib`;
ALTER TABLE `zt_risk` ADD `version` smallint(6) NOT NULL default 1 after `from`;
ALTER TABLE `zt_risk` ADD `approvedDate` date NOT NULL after `assignedDate`;

ALTER TABLE `zt_opportunity` ADD `lib` mediumint(8) unsigned NOT NULL default 0 after `actualClosedDate`;
ALTER TABLE `zt_opportunity` ADD `from` mediumint(8) unsigned NOT NULL default 0 after `lib`;
ALTER TABLE `zt_opportunity` ADD `version` smallint(6) NOT NULL default 1 after `from`;
ALTER TABLE `zt_opportunity` ADD `approvedDate` date NOT NULL after `assignedDate`;

ALTER TABLE `zt_doc` ADD `assetLib` mediumint(8) unsigned NOT NULL default 0 after `views`;
ALTER TABLE `zt_doc` ADD `assetLibType` varchar(30) NOT NULL default '' after `assetLib`;
ALTER TABLE `zt_doc` ADD `from` mediumint(8) unsigned NOT NULL default 0 after `assetLibType`;
ALTER TABLE `zt_doc` ADD `fromVersion` smallint(6) NOT NULL default 1 after `from`;
ALTER TABLE `zt_doc` ADD `assignedTo` varchar(30) NOT NULL after `addedDate`;
ALTER TABLE `zt_doc` ADD `assignedDate` date NOT NULL after `assignedTo`;
ALTER TABLE `zt_doc` ADD `approvedDate` date NOT NULL after `assignedDate`;
ALTER TABLE `zt_doc` ADD `status` varchar(30) NOT NULL after `type`;

ALTER TABLE `zt_review` ADD `template` mediumint(8) NOT NULL AFTER `object`;

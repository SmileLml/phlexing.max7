ALTER TABLE `zt_roadmap` ADD `closedReason` enum('done','canceled') DEFAULT NULL AFTER `closedDate`;

ALTER TABLE `zt_charter` ADD `closedBy`      char(30) NULL;
ALTER TABLE `zt_charter` ADD `closedDate`    datetime NULL;
ALTER TABLE `zt_charter` ADD `closedReason`  varchar(255) NULL;
ALTER TABLE `zt_charter` ADD `activatedBy`   char(30) NULL;
ALTER TABLE `zt_charter` ADD `activatedDate` datetime NULL;

UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'requirement' AND `method` = 'import';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'requirement' AND `method` = 'exportTemplate';

REPLACE INTO `zt_config` (`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('', 'system', 'common', 'global', 'mode', 'PLM');

REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('demand', 'export', '643', ',ipd,', ',or,', '1', '210');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'de',    'demand-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'en',    'demand-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'fr',    'demand-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'zh-cn', 'demand-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'zh-tw', 'demand-export', '', '');

REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('demand', 'exportTemplate', '643', ',ipd,', ',or,', '1', '220');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'de',    'demand-exportTemplate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'en',    'demand-exportTemplate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'fr',    'demand-exportTemplate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'zh-cn', 'demand-exportTemplate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'zh-tw', 'demand-exportTemplate', '', '');

REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('demand', 'import', '643', ',ipd,', ',or,', '1', '230');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'de',    'demand-import', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'en',    'demand-import', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'fr',    'demand-import', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'zh-cn', 'demand-import', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'zh-tw', 'demand-import', '', '');

REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2109, 'depend',    2075);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2109, 'recommend', 2110);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2109, 'recommend', 2111);

REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2110, 'depend',    2075);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2110, 'recommend', 2109);

REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2111, 'depend',    2075);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2111, 'depend',    2110);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2111, 'recommend', 2109);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2111, 'recommend', 2076);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2111, 'recommend', 2077);

REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('requirement', 'batchChangeRoadmap', '32', ',ipd,', ',or,', '1', '125');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'de',    'requirement-batchChangeRoadmap', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'en',    'requirement-batchChangeRoadmap', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'fr',    'requirement-batchChangeRoadmap', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'zh-cn', 'requirement-batchChangeRoadmap', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'zh-tw', 'requirement-batchChangeRoadmap', '', '');

REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2112, 'depend',    65);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2112, 'recommend', 121);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2112, 'recommend', 122);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2112, 'recommend', 123);

UPDATE `zt_privlang` SET `value` = '创建维护立项' WHERE `objectID` = 638 and `objectType` = 'manager';

REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('charter', 'close', '638', ',ipd,', ',or,', '1', '80');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'de',    'charter-close', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'en',    'charter-close', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'fr',    'charter-close', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'zh-cn', 'charter-close', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'zh-tw', 'charter-close', '', '');

REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('charter', 'activate', '638', ',ipd,', ',or,', '1', '90');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'de',    'charter-activate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'en',    'charter-activate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'fr',    'charter-activate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'zh-cn', 'charter-activate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'zh-tw', 'charter-activate', '', '');

REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2113, 'depend',    2061);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2113, 'depend',    2064);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2113, 'recommend', 2062);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2113, 'recommend', 2063);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2113, 'recommend', 2114);

REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2114, 'depend',    2061);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2114, 'depend',    2064);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2114, 'recommend', 2062);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2114, 'recommend', 2063);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2114, 'recommend', 2113);

REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('requirement', 'relation', '32', ',max,ipd,', ',rnd,', '1', '130');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'de',    'requirement-relation', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'en',    'requirement-relation', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'fr',    'requirement-relation', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'zh-cn', 'requirement-relation', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'zh-tw', 'requirement-relation', '', '');

UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'user' AND `method` = 'export';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'user' AND `method` = 'exportTemplate';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'user' AND `method` = 'import';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'user' AND `method` = 'importldap';

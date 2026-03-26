ALTER TABLE `zt_workflow` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `status`;
ALTER TABLE `zt_workflowdatasource` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `buildin`;
ALTER TABLE `zt_workflowaction` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `status`;
ALTER TABLE `zt_workflowlayout` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd';

ALTER TABLE `zt_workflow` DROP INDEX `unique`;
ALTER TABLE `zt_workflow` ADD UNIQUE `unique` (`app`, `module`, `vision`);
ALTER TABLE `zt_workflowaction` DROP INDEX `unique`;
ALTER TABLE `zt_workflowaction` ADD UNIQUE `unique` (`module`, `action`, `vision`);
ALTER TABLE `zt_workflowlayout` DROP INDEX `unique`;
ALTER TABLE `zt_workflowlayout` ADD UNIQUE `unique` (`module`, `action`, `field`, `vision`);

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('system',      '项目',           'liteprojects',        '1', 'lite', '{\"app\":\"system\",\"module\":\"project\",\"method\":\"getPairsByModel\",\"methodDesc\":\"Get project pairs by model and project.\",\"params\":[{\"name\":\"model\",\"type\":\"string\",\"desc\":\"all|scrum|waterfall\",\"value\":\"all\"},{\"name\":\"programID\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"param\",\"type\":\"\",\"desc\":\"\",\"value\":\"\"}]}',  '',     '',     ''),
('sql',         '任务',           'litetasks',           '1', 'lite', 'select id,name from zt_task where deleted=\"0\" and vision=\"lite\"',      'view_datasource_46',    'id',   'name'),
('system',      '权限分组',       'litegroups',          '1', 'lite', '{\"app\":\"system\",\"module\":\"group\",\"method\":\"getPairs\",\"methodDesc\":\"\",\"params\":[]}',  '',     '',     ''),
('system',      '用户',           'liteusers',           '1', 'lite', '{\"app\":\"system\",\"module\":\"user\",\"method\":\"getPairs\",\"methodDesc\":\"\",\"params\":[{\"name\":\"params\",\"type\":\"\",\"desc\":\"\",\"value\":\"noclosed|noletter\"},{\"name\":\"usersToAppended\",\"type\":\"\",\"desc\":\"\",\"value\":\"\"}]}',        '',     '',     ''),
('sql',         '模块',           'litemodules',         '1', 'lite', 'select id,name from zt_module where deleted=\"0\"',    'view_datasource_11',   'id',   'name'),
('lang',        '项目类型',       'liteprojectType',     '1', 'lite', 'projectType',    '',     '',     ''),
('lang',        '项目状态',       'liteprojectStatus',   '1', 'lite', 'projectStatus',  '',     '',     ''),
('lang',        '项目访问控制',   'liteprojectAcl',      '1', 'lite', 'projectAcl',     '',     '',     ''),
('lang',        '任务类型',       'litetaskType',        '1', 'lite', 'taskType',       '',     '',     ''),
('lang',        '任务优先级',     'litetaskPri',         '1', 'lite', 'taskPri',        '',     '',     ''),
('lang',        '任务状态',       'litetaskStatus',      '1', 'lite', 'taskStatus',     '',     '',     ''),
('lang',        '反馈状态',       'litefeedbackStatus',  '1', 'lite', 'feedbackStatus', '',     '',     ''),
('system',      '反馈分支',       'litefeedbackModules', '1', 'lite', '{\"app\":\"system\",\"module\":\"tree\",\"method\":\"getOptionMenu\",\"methodDesc\":\"Create an option menu in html.\",\"params\":[{\"name\":\"rootID\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"type\",\"type\":\"string\",\"desc\":\"\",\"value\":\"feedback\"},{\"name\":\"startModule\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"branch\",\"type\":\"\",\"desc\":\"\",\"value\":\"0\"}]}',   '',     '',     '');

DROP VIEW IF EXISTS `view_datasource_5`;
CREATE VIEW `view_datasource_5`  AS select `id`,`name` from `zt_task` where `deleted` = '0' and vision = 'rnd';

DROP VIEW IF EXISTS `view_datasource_46`;
CREATE VIEW `view_datasource_46` AS select `id`,`name` from `zt_task` where `deleted` = '0' and vision = 'lite';

UPDATE `zt_user` SET `visions` = 'lite', `feedback` = '0' WHERE `feedback` = '1';

UPDATE  zt_group SET `vision` = 'lite' where `role` = 'feedback';

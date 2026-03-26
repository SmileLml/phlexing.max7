ALTER TABLE `zt_action` CHANGE `project` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_action` ADD `execution` mediumint(8) unsigned NOT NULL AFTER `project`;
ALTER TABLE `zt_auditplan` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_budget` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_bug` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_bug` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_build` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_build` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_burn` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_case` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_case` ADD `execution` mediumint(8) unsigned NOT NULL AFTER `product`;
ALTER TABLE `zt_design` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_design` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_doc` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_doc` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_doclib` ADD `execution` mediumint(8) unsigned NOT NULL AFTER `project`;
ALTER TABLE `zt_durationestimation` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_effort` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_effort` ADD `project` mediumint(8) unsigned NOT NULL AFTER `product`;
ALTER TABLE `zt_expect` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_group` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_intervention` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_issue` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_measrecords` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_measrecords` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_nc` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_object` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_programactivity` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_programoutput` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_programprocess` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_programreport` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_project` ADD `lastEditedBy` varchar(30) NOT NULL AFTER `openedVersion`;
ALTER TABLE `zt_project` ADD `lastEditedDate` datetime NOT NULL AFTER `lastEditedBy`;
ALTER TABLE `zt_relation` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_relation` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_relationoftasks` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_release` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_review` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_reviewissue` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_risk` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_solutions` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_solutions` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_story` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_task` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_task` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_testreport` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_testreport` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_testsuite` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_testtask` CHANGE `project` `execution` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_testtask` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_todo` ADD `deleted` ENUM( "0", "1" ) NOT NULL DEFAULT '0';
ALTER TABLE `zt_usergroup` CHANGE `PRJ` `project` text NOT NULL;
ALTER TABLE `zt_weeklyreport` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_workestimation` CHANGE `PRJ` `project` mediumint(8) unsigned NOT NULL;

ALTER TABLE `zt_team` MODIFY `type` enum('project','task','execution') NOT NULL DEFAULT 'project' AFTER `root`;
UPDATE `zt_team` set `type` = 'execution' where `type` = 'project';

TRUNCATE `zt_block`;
DELETE FROM `zt_config` WHERE `key` = 'blockInited';

UPDATE `zt_report` SET `sql` = 'select t1.account,t1.consumed,t1.`date`,if($dept=\'0\',0,t2.dept) as dept from TABLE_EFFORT as t1 left join TABLE_USER as t2 on t1.account=t2.account where t1.`deleted`=\'0\' and if($startDate=\'\',1,t1.`date`>=$startDate) and if($endDate=\'\',1,t1.`date`<=$endDate) having dept=$dept order by `date` asc' WHERE `code` = 'effort';
CREATE OR REPLACE VIEW `ztv_executionsummary` AS select `zt_task`.`execution` AS `execution`,sum(if((`zt_task`.`parent` >= '0'),`zt_task`.`estimate`,0)) AS `estimate`,sum(if((`zt_task`.`parent` >= '0'),`zt_task`.`consumed`,0)) AS `consumed`,sum(if(((`zt_task`.`status` <> 'cancel') and (`zt_task`.`status` <> 'closed') and (`zt_task`.`parent` >= '0')),`zt_task`.`left`,0)) AS `left`,count(0) AS `number`,sum(if(((`zt_task`.`status` <> 'done') and (`zt_task`.`status` <> 'closed')),1,0)) AS `undone`,sum((if((`zt_task`.`parent` >= '0'),`zt_task`.`consumed`,0) + if(((`zt_task`.`status` <> 'cancel') and (`zt_task`.`status` <> 'closed') and (`zt_task`.`parent` >= '0')),`zt_task`.`left`,0))) AS `totalReal` from `zt_task` where (`zt_task`.`deleted` = '0') group by `zt_task`.`execution`;
CREATE OR REPLACE VIEW `ztv_projectsummary` AS select `zt_task`.`project` AS `project`,sum(if((`zt_task`.`parent` >= '0'),`zt_task`.`estimate`,0)) AS `estimate`,sum(if((`zt_task`.`parent` >= '0'),`zt_task`.`consumed`,0)) AS `consumed`,sum(if(((`zt_task`.`status` <> 'cancel') and (`zt_task`.`status` <> 'closed') and (`zt_task`.`parent` >= '0')),`zt_task`.`left`,0)) AS `left`,count(0) AS `number`,sum(if(((`zt_task`.`status` <> 'done') and (`zt_task`.`status` <> 'closed')),1,0)) AS `undone`,sum((if((`zt_task`.`parent` >= '0'),`zt_task`.`consumed`,0) + if(((`zt_task`.`status` <> 'cancel') and (`zt_task`.`status` <> 'closed') and (`zt_task`.`parent` >= '0')),`zt_task`.`left`,0))) AS `totalReal` from `zt_task` where (`zt_task`.`deleted` = '0') group by `zt_task`.`project`;
CREATE OR REPLACE VIEW `ztv_projectstories` AS select `t1`.`project` AS `execution`,count('*') AS `stories`,sum(if((`t2`.`status` = 'closed'),0,1)) AS `undone` from ((`zt_projectstory` `t1` left join `zt_story` `t2` on((`t1`.`story` = `t2`.`id`))) left join `zt_project` `t3` on((`t1`.`project` = `t3`.`id`))) where ((`t2`.`deleted` = '0') and (`t3`.`type` in ('sprint','stage'))) group by `t1`.`project`;
CREATE OR REPLACE VIEW `ztv_projectteams` AS select `zt_team`.`root` AS `execution`,count('*') AS `teams` from `zt_team` where (`zt_team`.`type` = 'execution') group by `zt_team`.`root`;
CREATE OR REPLACE VIEW `ztv_projectbugs` AS select `zt_bug`.`execution` AS `execution`,count(0) AS `bugs`,sum(if((`zt_bug`.`resolution` = ''),0,1)) AS `resolutions`,sum(if((`zt_bug`.`severity` <= 2),1,0)) AS `seriousBugs` from `zt_bug` where (`zt_bug`.`deleted` = '0') group by `zt_bug`.`execution`;
REPLACE INTO `zt_report` (`code`, `name`, `module`, `sql`, `vars`, `langs`, `params`, `step`, `desc`, `addedBy`, `addedDate`) VALUES
('project-invest',       '{\"zh-cn\":\"\\u9879\\u76ee\\u6295\\u5165\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Invest Report\",\"de\":\"Project Invest Report\",\"fr\":\"Project Invest Report\",\"vi\":\"Project Invest Report\",\"ja\":\"Project Invest Report\"}', ',project',     'select t1.id,t5.name as project,t1.name as execution,CONCAT(t1.begin,\' ~ \',t1.end) as timeLimit,t2.teams,t3.stories,round(t4.consumed,1) as consumed,t4.number, t1.status as projectstatus \r\nfrom TABLE_EXECUTION as t1\r\n left join ztv_projectteams as t2 on t1.id=t2.execution\r\nleft join ztv_projectstories as t3 on t1.id=t3.execution\r\n left join ztv_executionsummary as t4 on t1.id=t4.execution \r\nleft join TABLE_PROJECT as t5 on t1.project=t5.id \r\nwhere t1.deleted=\'0\' and t1.type in (\'sprint\',\'stage\') and if($project=\'\',1,t5.id=$project) and if($status=\'\',1,t1.status=$status) and if($beginDate=\'\',1,t1.begin>=$beginDate) and if($endDate=\'\',1,t1.end<=$endDate)',     '{\"varName\":[\"project\",\"status\",\"beginDate\",\"endDate\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u72b6\\u6001\",\"\\u6267\\u884c\\u8d77\\u59cb\\u65e5\\u671f\",\"\\u6267\\u884c\\u7ed3\\u675f\\u65e5\\u671f\"],\"requestType\":[\"select\",\"select\",\"date\",\"date\"],\"selectList\":[\"project\",\"project.status\",\"user\",\"user\"],\"default\":[\"\",\"\",\"$MONTHBEGIN\",\"$MONTHEND\"]}',    '{\"timeLimit\":{\"zh-cn\":\"\\u5de5\\u671f\"},\"teams\":{\"zh-cn\":\"\\u4eba\\u6570\"},\"stories\":{\"zh-cn\":\"\\u9700\\u6c42\\u6570\"},\"consumed\":{\"zh-cn\":\"\\u603b\\u6d88\\u8017\"},\"number\":{\"zh-cn\":\"\\u4efb\\u52a1\\u6570\"},\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',    '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"number\",\"stories\",\"teams\",\"consumed\"],\"reportType\":[\"sum\",\"sum\",\"sum\",\"sum\"],\"sumAppend\":[\"number\",\"stories\",\"teams\",\"consumed\"]}',     2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u5217\\u51fa\\uff1a\\u4efb\\u52a1\\u6570\\uff0c\\u9700\\u6c42\\u6570\\uff0c\\u4eba\\u6570\\uff0c\\u603b\\u6d88\\u8017\\u5de5\\u65f6\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',  'admin',        '2015-07-22 16:37:38'),
('projectstory-status',  '{\"zh-cn\":\"\\u9879\\u76ee\\u9700\\u6c42\\u72b6\\u6001\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Story Status\",\"de\":\"Project Story Status\",\"fr\":\"Project Story Status\",\"vi\":\"Project Story Status\",\"ja\":\"Project Story Status\"}',   ',project',     'select t2.id, t4.name as project,t2.name as execution,t3.status from TABLE_PROJECTSTORY as t1 \r\nleft join TABLE_EXECUTION as t2 on t1.project=t2.id \r\nleft join TABLE_STORY as t3 on t1.story=t3.id \r\nleft join TABLE_PROJECT as t4 on t4.id=t2.project\r\nwhere t2.deleted=\'0\' and t2.type in(\'sprint\', \'stage\') and if($project=\'\',1,t4.id=$project) and if($execution=\'\',1,t2.id=$execution) and if($status=\'\',1,t2.status=$status)',     '{\"varName\":[\"project\",\"execution\",\"status\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\",\"\\u6267\\u884c\\u72b6\\u6001\"],\"requestType\":[\"select\",\"select\",\"select\"],\"selectList\":[\"project\",\"execution\",\"project.status\"],\"default\":[\"\",\"\",\"\"]}', '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"status\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',       2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1\\u9700\\u6c42\\u7684\\u72b6\\u6001\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',  'admin',        '2015-07-23 15:35:08'),
('project-stage',        '{\"zh-cn\":\"\\u9879\\u76ee\\u9700\\u6c42\\u9636\\u6bb5\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Stage Report\",\"de\":\"Project Stage Report\",\"fr\":\"Project Stage Report\",\"vi\":\"Project Stage Report\",\"ja\":\"Project Stage Report\"}',   ',project',     'select t2.id, t4.name as project,t2.name as execution,t3.stage from TABLE_PROJECTSTORY as t1 \r\nleft join TABLE_EXECUTION as t2 on t1.project=t2.id \r\nleft join TABLE_STORY as t3 on t1.story=t3.id \r\nleft join TABLE_PROJECT as t4 on t4.id=t2.project\r\nwhere t2.deleted=\'0\' and t2.type in(\'sprint\', \'stage\') and if($project=\'\',1,t4.id=$project) and if($execution=\'\',1,t2.id=$execution) and if($status=\'\',1,t2.status=$status)',      '{\"varName\":[\"project\",\"execution\",\"status\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\",\"\\u6267\\u884c\\u72b6\\u6001\"],\"requestType\":[\"select\",\"select\",\"select\"],\"selectList\":[\"project\",\"execution\",\"project.status\"],\"default\":[\"\",\"\",\"\"]}', '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"stage\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',        2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1\\u9700\\u6c42\\u9636\\u6bb5\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}', 'admin',        '2015-07-23 15:38:18'),
('projectbug-resolution',        '{\"zh-cn\":\"\\u9879\\u76eeBug\\u89e3\\u51b3\\u65b9\\u6848\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Bug Resolution\",\"de\":\"Project Bug Resolution\",\"fr\":\"Project Bug Resolution\",\"vi\":\"Project Bug Resolution\",\"ja\":\"Project Bug Resolution\"}',        ',project,test',        'select t1.id,t3.name as project,t3.id,t1.name as execution,t1.id as bugID,t2.resolution from TABLE_EXECUTION as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.execution\r\nleft join TABLE_PROJECT as t3 on t3.id=t1.project\r\n where t1.deleted=\'0\' and t2.deleted=\'0\' and t2.resolution!=\'\' having bugID!=\'\' and if($project=\'\',1,t3.id=$project) and if($execution=\'\',1,t1.id=$execution)',     '{\"varName\":[\"project\",\"execution\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\"],\"requestType\":[\"select\",\"select\"],\"selectList\":[\"project\",\"execution\"],\"default\":[\"\",\"\"]}',        '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"resolution\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',   2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1Bug\\u7684\\u89e3\\u51b3\\u65b9\\u6848\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',       'admin',        '2015-07-23 16:04:46'),
('projectbug-status',    '{\"zh-cn\":\"\\u9879\\u76eeBug\\u72b6\\u6001\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Bug Status\",\"de\":\"Project Bug Status\",\"fr\":\"Project Bug Status\",\"vi\":\"Project Bug Status\",\"ja\":\"Project Bug Status\"}',      ',project,test',        'select t1.id,t3.name as project,t3.id,t1.name as execution,t1.id as bugID,t2.status from TABLE_EXECUTION as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.execution\r\nleft join TABLE_PROJECT as t3 on t3.id=t1.project\r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\' having bugID!=\' \' and if($project=\'\',1,t3.id=$project) and if($execution=\'\',1,t1.id=$execution)', '{\"varName\":[\"project\",\"execution\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\"],\"requestType\":[\"select\",\"select\"],\"selectList\":[\"project\",\"execution\"],\"default\":[\"\",\"\"]}',        '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"status\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"]}',       2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1Bug\\u7684\\u72b6\\u6001\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',     'admin',        '2015-07-23 15:48:03'),
('projectbug-opened',    '{\"zh-cn\":\"\\u9879\\u76eeBug\\u521b\\u5efa\\u8005\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Bug Opened\",\"de\":\"Project Bug Opened\",\"fr\":\"Project Bug Opened\",\"vi\":\"Project Bug Opened\",\"ja\":\"Project Bug Opened\"}',       ',project,test',        'select t1.id,t3.name as project,t3.id,t1.name as execution,t1.id as bugID,t2.openedBy from TABLE_EXECUTION as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.execution\r\nleft join TABLE_PROJECT as t3 on t3.id=t1.project\r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\' having bugID!=\'\' and if($project=\'\',1,t3.id=$project) and if($execution=\'\',1,t1.id=$execution)',        '{\"varName\":[\"project\",\"execution\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\"],\"requestType\":[\"select\",\"select\"],\"selectList\":[\"project\",\"execution\"],\"default\":[\"\",\"\"]}',        '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"openedBy\"],\"isUser\":{\"reportField\":[[\"1\"]]},\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',      2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1Bug\\u7684\\u521b\\u5efa\\u8005\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',      'admin',        '2015-07-23 16:08:10'),
('projectbug-resolve',   '{\"zh-cn\":\"\\u9879\\u76eeBug\\u89e3\\u51b3\\u8005\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Bug Resolve\",\"de\":\"Project Bug Resolve\",\"fr\":\"Project Bug Resolve\",\"vi\":\"Project Bug Resolve\",\"ja\":\"Project Bug Resolve\"}',       ',project,test',        'select t1.id,t3.name as project,t3.id,t1.name as execution,t1.id as bugID,t2.resolvedBy from TABLE_EXECUTION as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.execution\r\nleft join TABLE_PROJECT as t3 on t3.id=t1.project\r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\' and t2.status!=\'active\' and t2.resolvedBy!=\'\' having bugID!=\'\' and if($project=\'\',1,t3.id=$project) and if($execution=\'\',1,t1.id=$execution)',    '{\"varName\":[\"project\",\"execution\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\"],\"requestType\":[\"select\",\"select\"],\"selectList\":[\"project\",\"execution\"],\"default\":[\"\",\"\"]}',        '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"resolvedBy\"],\"isUser\":{\"reportField\":[[\"1\"]]},\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',    2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1Bug\\u7684\\u89e3\\u51b3\\u8005\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',      'admin',        '2015-07-23 16:13:16'),
('projectbug-assign',    '{\"zh-cn\":\"\\u9879\\u76eeBug\\u6307\\u6d3e\\u7ed9\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Bug Assign\",\"de\":\"Project Bug Assign\",\"fr\":\"Project Bug Assign\",\"vi\":\"Project Bug Assign\",\"ja\":\"Project Bug Assign\"}',       ',project,test',        'select t1.id,t3.name as project,t3.id,t1.name as execution,t1.id as bugID,t2.assignedTo from TABLE_EXECUTION as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.execution \r\nleft join TABLE_PROJECT as t3 on t3.id=t1.project\r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\' having bugID!=\'\' and if($project=\'\',1,t3.id=$project) and if($execution=\'\',1,t1.id=$execution)',     '{\"varName\":[\"project\",\"execution\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\"],\"requestType\":[\"select\",\"select\"],\"selectList\":[\"project\",\"execution\"],\"default\":[\"\",\"\"]}',        '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"assignedTo\"],\"isUser\":{\"reportField\":[[\"1\"]]},\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',    2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1Bug\\u7684\\u6307\\u6d3e\\u7ed9\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',      'admin',        '2015-07-23 16:29:10'),
('project-progress',     '{\"zh-cn\":\"\\u9879\\u76ee\\u8fdb\\u5c55\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Progress Report\",\"de\":\"Project Progress Report\",\"fr\":\"Project Progress Report\",\"vi\":\"Project Progress Report\",\"ja\":\"Project Progress Report\"}',       ',project',     'select t1.id,t4.name as project,t4.id,t1.name as execution,t1.status,t2.number as tasks,round(t2.consumed,2) as consumed,round(t2.`left`,2) as `left`,t3.stories,t2.undone as undoneTask,t3.undone as undoneStory,t2.totalReal from TABLE_EXECUTION as t1 \r\nleft join ztv_executionsummary as t2 on t1.id=t2.execution\r\nleft join ztv_projectstories as t3 on t1.id=t3.execution\r\nleft join TABLE_PROJECT as t4 on t4.id=t1.project\r\nwhere t1.deleted=\'0\' and t1.type in (\'sprint\',\'stage\') and if($project=\'\',1,t4.id=$project) and if($execution=\'\',1,t1.id=$execution) and if($status=\'\',1,t1.status=$status)', '{\"varName\":[\"project\",\"execution\",\"status\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\",\"\\u6267\\u884c\\u72b6\\u6001\"],\"requestType\":[\"select\",\"select\",\"select\"],\"selectList\":[\"project\",\"execution\",\"project.status\"],\"default\":[\"\",\"\",\"\"]}', '{\"stories\":{\"zh-cn\":\"\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u9700\\u6c42\\u6570\",\"en\":\"Stories\"},\"tasks\":{\"zh-cn\":\"\\u4efb\\u52a1\\u6570\",\"zh-tw\":\"\\u4efb\\u52a1\\u6570\",\"en\":\"Tasks\"},\"undoneStory\":{\"zh-cn\":\"\\u5269\\u4f59\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u5269\\u4f59\\u9700\\u6c42\\u6570\",\"en\":\"Undone Story\"},\"undoneTask\":{\"zh-cn\":\"\\u5269\\u4f59\\u4efb\\u52a1\\u6570\",\"zh-tw\":\"\\u5269\\u4f59\\u4efb\\u52a1\\u6570\",\"en\":\"Undone Task\"},\"consumed\":{\"zh-cn\":\"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6\",\"zh-tw\":\"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6\",\"en\":\"Cost(h)\"},\"left\":{\"zh-cn\":\"\\u5269\\u4f59\\u5de5\\u65f6\",\"zh-tw\":\"\\u5269\\u4f59\\u5de5\\u65f6\",\"en\":\"Left(h)\"},\"consumedPercent\":{\"zh-cn\":\"\\u8fdb\\u5ea6\",\"zh-tw\":\"\\u8fdb\\u5ea6\",\"en\":\"Process\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',    '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"stories\",\"undoneStory\",\"tasks\",\"undoneTask\",\"left\",\"consumed\"],\"reportType\":[\"sum\",\"sum\",\"sum\",\"sum\",\"sum\",\"sum\"],\"sumAppend\":[\"stories\",\"undoneStory\",\"tasks\",\"undoneTask\",\"left\",\"consumed\"],\"percent\":{\"5\":\"1\"},\"contrast\":{\"5\":\"totalReal\"},\"showAlone\":{\"5\":\"1\"}}',  2,      '{\"zh-cn\":\"\\u9879\\u76ee\\u7684\\u9700\\u6c42\\u6570\\uff0c\\u4efb\\u52a1\\u6570\\uff0c\\u5df2\\u6d88\\u8017\\u5de5\\u65f6\\uff0c\\u5269\\u4f59\\u5de5\\u65f6\\uff0c\\u5269\\u4f59\\u9700\\u6c42\\u6570\\uff0c\\u5269\\u4f59\\u4efb\\u52a1\\u6570\\uff0c\\u8fdb\\u5ea6\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',       'admin',        '2015-07-23 14:03:06'),
('project-quality',      '{\"zh-cn\":\"\\u9879\\u76ee\\u8d28\\u91cf\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Quality\",\"de\":\"Project Quality\",\"fr\":\"Project Quality\",\"vi\":\"Project Quality\",\"ja\":\"Project Quality\"}',       ',project',     'select t1.id, t5.name as project,t5.id,t1.name as execution,t2.stories,(t2.stories-t2.undone) as doneStory,t3.number,(t3.number-t3.undone) as doneTask,t4.bugs,t4.resolutions, round(t4.bugs/(t2.stories-t2.undone),2) as bugthanstory,round(t4.bugs/(t3.number-t3.undone),2) as bugthantask,t4.seriousBugs from TABLE_EXECUTION as t1 \r\nleft join ztv_projectstories as t2 on t1.id=t2.execution\r\nleft join ztv_executionsummary as t3 on t1.id=t3.execution\r\nleft join ztv_projectbugs as t4 on t1.id=t4.execution\r\nleft join TABLE_PROJECT as t5 on t5.id=t1.project\r\nwhere t1.deleted=\'0\' and t1.type in (\'sprint\',\'stage\') and t1.grade=\'1\' and if($project=\'\',1,t5.id=$project) and if($execution=\'\',1,t1.id=$execution)', '{\"varName\":[\"project\",\"execution\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\"],\"requestType\":[\"select\",\"select\"],\"selectList\":[\"project\",\"execution\"],\"default\":[\"\",\"\"]}',        '{\"stories\":{\"zh-cn\":\"\\u9700\\u6c42\\u603b\\u6570\"},\"doneStory\":{\"zh-cn\":\"\\u5b8c\\u6210\\u9700\\u6c42\\u6570\"},\"number\":{\"zh-cn\":\"\\u4efb\\u52a1\\u603b\\u6570\"},\"doneTask\":{\"zh-cn\":\"\\u5b8c\\u6210\\u4efb\\u52a1\\u6570\"},\"bugs\":{\"zh-cn\":\"Bug\\u6570\"},\"resolutions\":{\"zh-cn\":\"\\u89e3\\u51b3Bug\\u6570\"},\"bugthanstory\":{\"zh-cn\":\"Bug\\/\\u5b8c\\u6210\\u9700\\u6c42\"},\"bugthantask\":{\"zh-cn\":\"Bug\\/\\u5b8c\\u6210\\u4efb\\u52a1\"},\"seriousBugs\":{\"zh-cn\":\"\\u91cd\\u8981Bug\\u6570\"},\"seriousBugsPercent\":{\"zh-cn\":\"\\u4e25\\u91cdBug\\u6bd4\\u7387\"},\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"stories\",\"doneStory\",\"number\",\"doneTask\",\"bugs\",\"resolutions\",\"bugthanstory\",\"bugthantask\",\"seriousBugs\"],\"reportType\":[\"sum\",\"sum\",\"sum\",\"sum\",\"sum\",\"sum\",\"sum\",\"sum\",\"sum\"],\"sumAppend\":[\"stories\",\"doneStory\",\"number\",\"doneTask\",\"bugs\",\"resolutions\",\"bugthanstory\",\"bugthantask\",\"seriousBugs\"]}', 2,      '{\"zh-cn\":\"\\u5217\\u51fa\\u9879\\u76ee\\u7684\\u9700\\u6c42\\u603b\\u6570\\uff0c\\u5b8c\\u6210\\u9700\\u6c42\\u6570\\uff0c\\u4efb\\u52a1\\u603b\\u6570\\uff0c\\u5b8c\\u6210\\u7684\\u4efb\\u52a1\\u6570\\uff0cBug\\u6570\\uff0c\\u89e3\\u51b3\\u7684Bug\\u6570\\uff0cBug\\/\\u9700\\u6c42\\uff0cBug\\/\\u4efb\\u52a1\\uff0c\\u91cd\\u8981Bug\\u6570\\u91cf(\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e0d\\u5927\\u4e8e3\\uff09\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}', 'admin',        '2015-07-23 17:03:10'),
('projectbug-type',      '{\"zh-cn\":\"\\u9879\\u76eeBug\\u7c7b\\u578b\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\",\"en\":\"Project Bug Type\",\"de\":\"Project Bug Type\",\"fr\":\"Project Bug Type\",\"vi\":\"Project Bug Type\",\"ja\":\"Project Bug Type\"}',      ',project,test',        'select t1.id,t3.name as project,t3.id,t1.name as execution,t2.id as bugID,t2.type from TABLE_EXECUTION as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.execution\r\nleft join TABLE_PROJECT as t3 on t3.id=t1.project\r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\' and if($project=\'\',1,t3.id=$project) and if($execution=\'\',1,t1.id=$execution)',       '{\"varName\":[\"project\",\"execution\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u5217\\u8868\"],\"requestType\":[\"select\",\"select\"],\"selectList\":[\"project\",\"execution\"],\"default\":[\"\",\"\"]}',        '{\"stories\":{\"zh-cn\":\"\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u9700\\u6c42\\u6570\",\"en\":\"Stories\"},\"tasks\":{\"zh-cn\":\"\\u4efb\\u52a1\\u6570\",\"zh-tw\":\"\\u4efb\\u52a1\\u6570\",\"en\":\"Tasks\"},\"undoneStory\":{\"zh-cn\":\"\\u5269\\u4f59\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u5269\\u4f59\\u9700\\u6c42\\u6570\",\"en\":\"Undone Story\"},\"undoneTask\":{\"zh-cn\":\"\\u5269\\u4f59\\u4efb\\u52a1\\u6570\",\"zh-tw\":\"\\u5269\\u4f59\\u4efb\\u52a1\\u6570\",\"en\":\"Undone Task\"},\"consumed\":{\"zh-cn\":\"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6\",\"zh-tw\":\"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6\",\"en\":\"Cost(h)\"},\"left\":{\"zh-cn\":\"\\u5269\\u4f59\\u5de5\\u65f6\",\"zh-tw\":\"\\u5269\\u4f59\\u5de5\\u65f6\",\"en\":\"Left(h)\"},\"consumedPercent\":{\"zh-cn\":\"\\u8fdb\\u5ea6\",\"zh-tw\":\"\\u8fdb\\u5ea6\",\"en\":\"Process\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',    '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"type\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"]}', 2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1Bug\\u7684\\u7c7b\\u578b\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',     'admin',        '2015-08-04 13:54:22'),
('task-finish',  '{\"zh-cn\":\"\\u9879\\u76ee\\u4efb\\u52a1\\u5b8c\\u6210\\u8005\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\",\"en\":\"Task Finish Report\",\"de\":\"Task Finish Report\",\"fr\":\"Task Finish Report\",\"vi\":\"Task Finish Report\",\"ja\":\"Task Finish Report\"}',    ',project',     'select t1.id,t3.name as project,t1.name as execution,t2.finishedBy,t2.id as taskID, t1.status as projectstatus from TABLE_EXECUTION as t1 \r\nleft join TABLE_TASK as t2 on t1.id=t2.execution\r\nleft join TABLE_PROJECT as t3 on t1.project=t3.id \r\nwhere t1.deleted=\'0\' and t1.type in (\'sprint\',\'stage\') and t2.deleted=\'0\' and t2.finishedBy!=\'\' and if($project=\'\',1,t3.id=$project) and if($status=\'\',1,t1.status=$status) and if($beginDate=\'\',1,t1.begin>=$beginDate) and if($endDate=\'\',1,t1.end<=$endDate)',    '{\"varName\":[\"project\",\"status\",\"beginDate\",\"endDate\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u72b6\\u6001\",\"\\u6267\\u884c\\u8d77\\u59cb\\u65e5\\u671f\",\"\\u6267\\u884c\\u7ed3\\u675f\\u65e5\\u671f\"],\"requestType\":[\"select\",\"select\",\"date\",\"date\"],\"selectList\":[\"project\",\"project.status\",\"user\",\"user\"],\"default\":[\"\",\"\",\"$MONTHBEGIN\",\"$MONTHEND\"]}',    '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"finishedBy\"],\"isUser\":{\"reportField\":[[\"1\"]]},\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',    2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1\\u4efb\\u52a1\\u7684\\u5b8c\\u6210\\u8005\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',   'admin',        '2015-07-22 13:16:21'),
('task-assign',  '{\"zh-cn\":\"\\u9879\\u76ee\\u4efb\\u52a1\\u6307\\u6d3e\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\",\"en\":\"Task Assign Report\",\"de\":\"Task Assign Report\",\"fr\":\"Task Assign Report\",\"vi\":\"Task Assign Report\",\"ja\":\"Task Assign Report\"}',   ',project',     'select t1.id,t4.name as project,t1.name as execution,if(t3.account is not null, t3.account,t2.assignedTo) as assignedTo,t2.id as taskID, t1.status as projectstatus from TABLE_EXECUTION as t1\r\n left join TABLE_TASK as t2 on t1.id=t2.execution\r\n left join TABLE_TEAM as t3 on t3.type=\'task\' && t3.root=t2.id \r\nleft join TABLE_PROJECT as t4 on t1.project=t4.id\r\nwhere t1.deleted=\'0\' and t1.type in (\'sprint\',\'stage\') and t2.deleted=\'0\' and if($project=\'\',1,t4.id=$project) and if($status=\'\',1,t1.status=$status) and if($beginDate=\'\',1,t1.begin>=$beginDate) and if($endDate=\'\',1,t1.end<=$endDate)',   '{\"varName\":[\"project\",\"status\",\"beginDate\",\"endDate\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u72b6\\u6001\",\"\\u6267\\u884c\\u8d77\\u59cb\\u65e5\\u671f\",\"\\u6267\\u884c\\u7ed3\\u675f\\u65e5\\u671f\"],\"requestType\":[\"select\",\"select\",\"date\",\"date\"],\"selectList\":[\"project\",\"project.status\",\"user\",\"user\"],\"default\":[\"\",\"\",\"$MONTHBEGIN\",\"$MONTHEND\"]}',    '{\"assignedTo\":{\"zh-cn\":\"\\u6307\\u6d3e\\u7ed9\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',    '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"assignedTo\"],\"isUser\":{\"reportField\":[[\"1\"]]},\"reportType\":[\"count\"],\"sumAppend\":[\"\"]}',    2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1\\u4efb\\u52a1\\u7684\\u6307\\u6d3e\\u7ed9\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',   'admin',        '2015-07-22 13:13:28'),
('task-status',  '{\"zh-cn\":\"\\u4efb\\u52a1\\u72b6\\u6001\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\",\"en\":\"Task Status Report\",\"de\":\"Task Status Report\",\"fr\":\"Task Status Report\",\"vi\":\"Task Status Report\",\"ja\":\"Task Status Report\"}', ',project',     'select t1.id,t3.name as project,t1.name,t2.status,t1.name as execution,t2.id as taskID,  t1.status as projectstatus, (case when t2.deadline < CURDATE() and t2.deadline != \'0000-00-00\' and t2.status != \'closed\' and t2.status != \'done\' and t2.status != \'cancel\' then 1 else 0 end) as timeout from TABLE_EXECUTION as t1\r\n left join TABLE_TASK as t2 on t1.id=t2.execution\r\n left join TABLE_PROJECT as t3 on t3.id=t1.project\r\n where t1.deleted=\'0\' and t1.type in (\'sprint\',\'stage\') and t2.deleted=\'0\' and if($project=\'\',1,t3.id=$project) and if($status=\'\',1,t1.status=$status) and if($beginDate=\'\',1,t1.begin>=$beginDate) and if($endDate=\'\',1,t1.end<=$endDate)',        '{\"varName\":[\"project\",\"status\",\"beginDate\",\"endDate\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u72b6\\u6001\",\"\\u6267\\u884c\\u8d77\\u59cb\\u65e5\\u671f\",\"\\u6267\\u884c\\u7ed3\\u675f\\u65e5\\u671f\"],\"requestType\":[\"select\",\"select\",\"date\",\"date\"],\"selectList\":[\"project\",\"project.status\",\"user\",\"user\"],\"default\":[\"\",\"\",\"$MONTHBEGIN\",\"$MONTHEND\"]}',    '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"status\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',       2,      '{\"zh-cn\":\"\\u6309\\u7167\\u6267\\u884c\\u7edf\\u8ba1\\u4efb\\u52a1\\u7684\\u72b6\\u6001\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',  'admin',        '2015-07-22 11:28:33'),
('task-type',    '{\"zh-cn\":\"\\u4efb\\u52a1\\u7c7b\\u578b\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\",\"en\":\"Task Type Report\",\"de\":\"Task Type Report\",\"fr\":\"Task Type Report\",\"vi\":\"Task Type Report\",\"ja\":\"Task Type Report\"}', ',project',     'select t1.id,t3.name as project,t1.name as execution,t2.type,t2.id as taskID, t1.status as projectstatus from TABLE_EXECUTION as t1 \r\nleft join TABLE_TASK as t2 on t1.id=t2.execution\r\nleft join TABLE_PROJECT as t3 on t3.id=t1.project\r\nwhere t1.deleted=\'0\' and t1.type in (\'sprint\',\'stage\') and t2.deleted=\'0\' and if($project=\'\',1,t3.id=$project) and if($status=\'\',1,t1.status=$status) and if($beginDate=\'\',1,t1.begin>=$beginDate) and if($endDate=\'\',1,t1.end<=$endDate)',   '{\"varName\":[\"project\",\"status\",\"beginDate\",\"endDate\"],\"showName\":[\"\\u9879\\u76ee\\u5217\\u8868\",\"\\u6267\\u884c\\u72b6\\u6001\",\"\\u6267\\u884c\\u8d77\\u59cb\\u65e5\\u671f\",\"\\u6267\\u884c\\u7ed3\\u675f\\u65e5\\u671f\"],\"requestType\":[\"select\",\"select\",\"date\",\"date\"],\"selectList\":[\"project\",\"project.status\",\"user\",\"user\"],\"default\":[\"\",\"\",\"$MONTHBEGIN\",\"$MONTHEND\"]}',    '{\"project\":{\"zh-cn\":\"\\u9879\\u76ee\\u540d\\u79f0\"},\"execution\":{\"zh-cn\":\"\\u6267\\u884c\\u540d\\u79f0\"}}',        '{\"group1\":\"project\",\"group2\":\"execution\",\"reportField\":[\"type\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}', 2,      '{\"zh-cn\":\"\\u6309\\u7167\\u9879\\u76ee\\u7edf\\u8ba1\\u4efb\\u52a1\\u7684\\u7c7b\\u578b\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\",\"en\":\"\",\"de\":\"\",\"fr\":\"\",\"vi\":\"\",\"ja\":\"\"}',  'admin',        '2015-07-22 13:06:46'),
('productbug-resolve',   '{\"zh-cn\":\"\\u4ea7\\u54c1Bug\\u89e3\\u51b3\\u65b9\\u6848\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\\u7522\\u54c1Bug\\u89e3\\u6c7a\\u65b9\\u6848\\u7d71\\u8a08\\u8868\",\"en\":\"Bug Solution of Product\"}',       ',product,test',        'select t1.id,t1.name,t2.id as bugID,t2.resolution from TABLE_PRODUCT as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.product \r\nleft join TABLE_PROGRAM as t3 on t1.program=t3.id \r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\'\r\norder by t3.`order` asc, t1.line desc, t1.`order` asc',  '',     '{\"count\":{\"zh-cn\":\"\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u9700\\u6c42\\u6570\",\"en\":\"Stories\"},\"done\":{\"zh-cn\":\"\\u5b8c\\u6210\\u6570\",\"zh-tw\":\"\\u5b8c\\u6210\\u6570\",\"en\":\"Done\"}}',  '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"resolution\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',       2,      '{\"zh-cn\":\"\\u6309\\u7167\\u4ea7\\u54c1\\u7edf\\u8ba1Bug\\u7684\\u89e3\\u51b3\\u65b9\\u6848\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\\u6309\\u7167\\u7522\\u54c1\\u7d71\\u8a08Bug\\u7684\\u89e3\\u6c7a\\u65b9\\u6848\\u5206\\u4f48\\u60c5\\u51b5\\u3002\",\"en\":\"Solution distribution of bugs.\"}',      'admin',        '2015-07-24 13:55:46'),
('productbug-type',      '{\"zh-cn\":\"\\u4ea7\\u54c1Bug\\u7c7b\\u578b\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\\u7522\\u54c1Bug\\u985e\\u578b\\u7d71\\u8a08\\u8868\",\"en\":\"Bug Type of Product\"}',       ',product,test',        'select t1.id,t1.name,t2.id as bugID,t2.type from TABLE_PRODUCT as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.product \r\nleft join TABLE_PROGRAM as t3 on t1.program=t3.id \r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\'\r\norder by t3.`order` asc, t1.line desc, t1.`order` asc',        '',     '{\"count\":{\"zh-cn\":\"\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u9700\\u6c42\\u6570\",\"en\":\"Stories\"},\"done\":{\"zh-cn\":\"\\u5b8c\\u6210\\u6570\",\"zh-tw\":\"\\u5b8c\\u6210\\u6570\",\"en\":\"Done\"}}',  '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"type\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',     2,      '{\"zh-cn\":\"\\u6309\\u7167\\u4ea7\\u54c1\\u7edf\\u8ba1Bug\\u7684\\u7c7b\\u578b\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\\u6309\\u7167\\u7522\\u54c1\\u7d71\\u8a08Bug\\u7684\\u985e\\u578b\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"en\":\"Type distribution of Bugs.\"}',      'admin',        '2015-07-24 13:48:22'),
('product-invest',       '{\"zh-cn\":\"\\u4ea7\\u54c1\\u6295\\u5165\\u8868\",\"zh-tw\":\"\\u7522\\u54c1\\u6295\\u5165\\u8868\",\"en\":\"Product Investment\"}',  ',product',     'select t1.id,t1.name,1 as projects, round(t3.consumed,2) as consumed from TABLE_PRODUCT as t1\r\n left join TABLE_PROJECTPRODUCT as t2 on t1.id=t2.product\r\n left join ztv_projectsummary as t3 on t2.project=t3.project\r\n left join TABLE_PROJECT as t4 on t2.project=t4.id\r\n left join TABLE_PROGRAM as t5 on t1.program=t5.id\r\n where t1.deleted=\'0\' and t4.deleted=\'0\' and t4.type=\'project\'\r\norder by t5.`order` asc, t1.line desc, t1.`order` asc',      '',     '{\"projects\":{\"zh-cn\":\"\\u9879\\u76ee\\u6570\",\"zh-tw\":\"\\u9879\\u76ee\\u6570\",\"en\":\"Projects\"},\"consumed\":{\"zh-cn\":\"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6\",\"zh-tw\":\"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6\",\"en\":\"Cost(h)\"}}',   '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"projects\",\"consumed\"],\"reportType\":[\"sum\",\"sum\"],\"sumAppend\":[\"projects\",\"consumed\"]}', 2,      '{\"zh-cn\":\"\\u5217\\u51fa\\u6bcf\\u4e2a\\u4ea7\\u54c1\\u7684\\u9879\\u76ee\\u603b\\u6570\\uff0c\\u5df2\\u7ecf\\u6d88\\u8017\\u7684\\u5de5\\u65f6\\u3002\",\"zh-tw\":\"\\u5217\\u51fa\\u6bcf\\u500b\\u7522\\u54c1\\u7684\\u9805\\u76ee\\u7e3d\\u6578\\uff0c\\u5df2\\u7d93\\u6d88\\u8017\\u7684\\u5de5\\u6642\\u3002 \",\"en\":\"Number of projects and consumed hours.\"}',   'admin',        '2015-07-20 14:21:30'),
('product-progress',     '{\"zh-cn\":\"\\u4ea7\\u54c1\\u5b8c\\u6210\\u5ea6\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\\u7522\\u54c1\\u5b8c\\u6210\\u5ea6\\u7d71\\u8a08\\u8868\",\"en\":\"Product Progress\"}',  ',product',     'select t1.*,t2.name, (case when t1.status = \'closed\' or t1.stage = \'released\' then 1 else 0 end) as done, 1 as count from TABLE_STORY as t1 \r\nleft join TABLE_PRODUCT as t2 on t1.product=t2.id \r\nleft join TABLE_PROGRAM as t3 on t2.program=t3.id \r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\'\r\norder by t3.`order` asc, t2.line desc, t2.`order` asc', '',     '{\"count\":{\"zh-cn\":\"\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u9700\\u6c42\\u6570\",\"en\":\"Stories\"},\"done\":{\"zh-cn\":\"\\u5b8c\\u6210\\u6570\",\"zh-tw\":\"\\u5b8c\\u6210\\u6570\",\"en\":\"Done\"}}',  '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"count\",\"done\"],\"reportType\":[\"sum\",\"sum\"],\"sumAppend\":[\"count\",\"done\"],\"percent\":{\"1\":\"1\"},\"contrast\":{\"1\":\"count\"},\"showAlone\":{\"1\":\"1\"}}',  2,      '{\"zh-cn\":\"\\u6309\\u7167\\u4ea7\\u54c1\\u5217\\u51fa\\u9700\\u6c42\\u603b\\u6570\\uff0c\\u5b8c\\u6210\\u7684\\u603b\\u6570(\\u72b6\\u6001\\u662f\\u5173\\u95ed\\uff0c\\u6216\\u8005\\u7814\\u53d1\\u9636\\u6bb5\\u662f\\u53d1\\u5e03)\\uff0c\\u5b8c\\u6210\\u7684\\u767e\\u5206\\u6bd4\\u3002\",\"zh-tw\":\"\\u6309\\u7167\\u7522\\u54c1\\u5217\\u51fa\\u9700\\u6c42\\u7e3d\\u6578\\uff0c\\u5b8c\\u6210\\u7684\\u7e3d\\u6578\\uff08\\u72c0\\u614b\\u662f\\u95dc\\u9589\\uff0c\\u6216\\u8005\\u7814\\u767c\\u968e\\u6bb5\\u662f\\u767c\\u4f48\\uff09\\uff0c\\u5b8c\\u6210\\u7684\\u767e\\u5206\\u6bd4\\u3002\",\"en\":\"Number of total stories,done stories(state is closed, or stage is released), percent of completion.\"}',     'admin',        '2015-07-21 15:07:48'),
('product-quality',      '{\"zh-cn\":\"\\u4ea7\\u54c1\\u8d28\\u91cf\\u8868\",\"zh-tw\":\"\\u7522\\u54c1\\u8cea\\u91cf\\u8868\",\"en\":\"Product Quality\"}',     ',product',     'select t1.id,t1.name,t2.stories,(t2.stories-t2.undone) as doneStory,t3.bugs,t3.resolutions,round(t3.bugs/(t2.stories-t2.undone),2) as bugthanstory,t3.seriousBugs from TABLE_PRODUCT as t1 \r\nleft join ztv_productstories as t2 on t1.id=t2.product \r\nleft join ztv_productbugs as t3 on t1.id=t3.product \r\nleft join TABLE_PROGRAM as t4 on t1.program=t4.id \r\nwhere t1.deleted=\'0\'\r\norder by t4.`order` asc, t1.line desc, t1.`order` asc',      '',     '{\"stories\":{\"zh-cn\":\"\\u9700\\u6c42\\u603b\\u6570\",\"zh-tw\":\"\\u9700\\u6c42\\u603b\\u6570\",\"en\":\"Stories\"},\"doneStory\":{\"zh-cn\":\"\\u5b8c\\u6210\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u5b8c\\u6210\\u9700\\u6c42\\u6570\",\"en\":\"Finished Stories\"},\"bugs\":{\"zh-cn\":\"Bug\\u6570\",\"zh-tw\":\"Bug\\u6570\",\"en\":\"Bugs\"},\"resolutions\":{\"zh-cn\":\"\\u89e3\\u51b3Bug\\u6570\",\"zh-tw\":\"\\u89e3\\u51b3Bug\\u6570\",\"en\":\"Solved Bugs\"},\"bugthanstory\":{\"zh-cn\":\"Bug\\/\\u5b8c\\u6210\\u9700\\u6c42\",\"zh-tw\":\"Bug\\/\\u5b8c\\u6210\\u9700\\u6c42\",\"en\":\"Bug\\/Finished Story\"},\"seriousBugs\":{\"zh-cn\":\"\\u91cd\\u8981Bug\\u6570\",\"zh-tw\":\"\\u91cd\\u8981Bug\\u6570\",\"en\":\"Serious Bugs\"},\"seriousBugsPercent\":{\"zh-cn\":\"\\u4e25\\u91cdbug\\u6bd4\\u7387\",\"zh-tw\":\"\\u4e25\\u91cdbug\\u6bd4\\u7387\",\"en\":\"Serious Bugs %\"}}',     '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"stories\",\"doneStory\",\"bugs\",\"resolutions\",\"bugthanstory\",\"seriousBugs\"],\"reportType\":[\"sum\",\"sum\",\"sum\",\"sum\",\"sum\",\"sum\"],\"sumAppend\":[\"stories\",\"doneStory\",\"bugs\",\"resolutions\",\"bugthanstory\",\"seriousBugs\"],\"percent\":{\"5\":\"1\"},\"contrast\":{\"5\":\"bugs\"},\"showAlone\":{\"5\":\"1\"}}', 2,      '{\"zh-cn\":\"\\u5217\\u51fa\\u4ea7\\u54c1\\u7684\\u9700\\u6c42\\u6570\\uff0c\\u5b8c\\u6210\\u7684\\u9700\\u6c42\\u603b\\u6570\\uff0cBug\\u6570\\uff0c\\u89e3\\u51b3\\u7684Bug\\u603b\\u6570\\uff0cBug\\/\\u9700\\u6c42\\uff0c\\u91cd\\u8981Bug\\u6570\\u91cf(\\u4e25\\u91cd\\u7a0b\\u5ea6\\u4e0d\\u5927\\u4e8e3)\\u3002\",\"zh-tw\":\"\\u5217\\u51fa\\u7522\\u54c1\\u7684\\u9700\\u6c42\\u6578\\uff0c\\u5b8c\\u6210\\u7684\\u9700\\u6c42\\u7e3d\\u6578\\uff0cBug\\u6578\\uff0c\\u89e3\\u51b3\\u7684Bug\\u7e3d\\u6578\\uff0cBug\\/\\u9700\\u6c42\\uff0c\\u91cd\\u8981Bug\\u6578\\u91cf\\uff08\\u56b4\\u91cd\\u7a0b\\u5ea6\\u4e0d\\u5927\\u65bc3\\uff09\\u3002\",\"en\":\"Serious Bug (severity is less than 3).\"}',    'admin',        '2015-07-23 17:17:40'),
('product-release',      '{\"zh-cn\":\"\\u4ea7\\u54c1\\u53d1\\u5e03\\u6570\\u91cf\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\\u7522\\u54c1\\u767c\\u4f48\\u6578\\u91cf\\u7d71\\u8a08\\u8868\",\"en\":\"Product Release\"}',     ',product',     'select t2.name, 1 as releases from TABLE_RELEASE as t1 \r\nleft join TABLE_PRODUCT as t2 on t1.product=t2.id \r\nleft join TABLE_PROGRAM as t3 on t2.program=t3.id \r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\'\r\norder by t3.`order` asc, t2.line desc, t2.`order` asc',  '',     '{\"count\":{\"zh-cn\":\"\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u9700\\u6c42\\u6570\",\"en\":\"Stories\"},\"done\":{\"zh-cn\":\"\\u5b8c\\u6210\\u6570\",\"zh-tw\":\"\\u5b8c\\u6210\\u6570\",\"en\":\"Done\"}}',  '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"releases\"],\"reportType\":[\"sum\"],\"sumAppend\":[\"releases\"]}',   2,      '{\"zh-cn\":\"\\u6309\\u7167\\u4ea7\\u54c1\\u5217\\u51fa\\u53d1\\u5e03\\u7684\\u6570\\u91cf\\u3002\",\"zh-tw\":\"\\u6309\\u7167\\u7522\\u54c1\\u5217\\u51fa\\u767c\\u4f48\\u7684\\u6578\\u91cf\\u3002\",\"en\":\"Product Release.\"}',  'admin',        '2015-07-21 16:00:52'),
('productbug-resolve',   '{\"zh-cn\":\"\\u4ea7\\u54c1Bug\\u89e3\\u51b3\\u65b9\\u6848\\u7edf\\u8ba1\\u8868\",\"zh-tw\":\"\\u7522\\u54c1Bug\\u89e3\\u6c7a\\u65b9\\u6848\\u7d71\\u8a08\\u8868\",\"en\":\"Bug Solution of Product\"}',       ',product,test',        'select t1.id,t1.name,t2.id as bugID,t2.resolution from TABLE_PRODUCT as t1 \r\nleft join TABLE_BUG as t2 on t1.id=t2.product \r\nleft join TABLE_PROGRAM as t3 on t1.program=t3.id \r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\'\r\norder by t3.`order` asc, t1.line desc, t1.`order` asc',  '',     '{\"count\":{\"zh-cn\":\"\\u9700\\u6c42\\u6570\",\"zh-tw\":\"\\u9700\\u6c42\\u6570\",\"en\":\"Stories\"},\"done\":{\"zh-cn\":\"\\u5b8c\\u6210\\u6570\",\"zh-tw\":\"\\u5b8c\\u6210\\u6570\",\"en\":\"Done\"}}',  '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"resolution\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',       2,      '{\"zh-cn\":\"\\u6309\\u7167\\u4ea7\\u54c1\\u7edf\\u8ba1Bug\\u7684\\u89e3\\u51b3\\u65b9\\u6848\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\\u6309\\u7167\\u7522\\u54c1\\u7d71\\u8a08Bug\\u7684\\u89e3\\u6c7a\\u65b9\\u6848\\u5206\\u4f48\\u60c5\\u51b5\\u3002\",\"en\":\"Solution distribution of bugs.\"}',      'admin',        '2015-07-24 13:55:46'),
('story-status', '{\"zh-cn\":\"\\u4ea7\\u54c1\\u9700\\u6c42\\u72b6\\u6001\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\\u7522\\u54c1\\u9700\\u6c42\\u72c0\\u614b\\u5206\\u4f48\\u8868\",\"en\":\"Story Status\"}',        ',product',     'select t1.*,t2.name from TABLE_STORY as t1\r\n left join TABLE_PRODUCT as t2 on t1.product=t2.id \r\nleft join TABLE_PROGRAM as t3 on t2.program=t3.id \r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\'\r\norder by t3.`order` asc, t2.line desc, t2.`order` asc',      '',     '',     '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"status\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',   2,      '{\"zh-cn\":\"\\u6309\\u7167\\u4ea7\\u54c1\\u5217\\u51fa\\u9700\\u6c42\\u603b\\u6570\\uff0c\\u72b6\\u6001\\u7684\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\\u6309\\u7167\\u7522\\u54c1\\u5217\\u51fa\\u9700\\u6c42\\u7e3d\\u6578\\uff0c\\u72c0\\u614b\\u7684\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"en\":\"Total number and status distribution of stories.\"}',        'admin',        '2015-07-21 15:35:38'),
('story-stage',  '{\"zh-cn\":\"\\u4ea7\\u54c1\\u9700\\u6c42\\u9636\\u6bb5\\u5206\\u5e03\\u8868\",\"zh-tw\":\"\\u7522\\u54c1\\u9700\\u6c42\\u968e\\u6bb5\\u5206\\u4f48\\u8868\",\"en\":\"Story Stage\"}', ',product',     'select t1.*,t2.name from TABLE_STORY as t1\r\n left join TABLE_PRODUCT as t2 on t1.product=t2.id \r\nleft join TABLE_PROGRAM as t3 on t2.program=t3.id \r\nwhere t1.deleted=\'0\' and t2.deleted=\'0\'\r\norder by t3.`order` asc, t2.line desc, t2.`order` asc',      '',     '',     '{\"group1\":\"name\",\"group2\":\"\",\"reportField\":[\"stage\"],\"reportType\":[\"count\"],\"sumAppend\":[\"\"],\"reportTotal\":[\"1\"]}',    2,      '{\"zh-cn\":\"\\u6309\\u7167\\u4ea7\\u54c1\\u5217\\u51fa\\u9700\\u6c42\\u603b\\u6570\\uff0c\\u7814\\u53d1\\u9636\\u6bb5\\u7684\\u5206\\u5e03\\u60c5\\u51b5\\u3002\",\"zh-tw\":\"\\u6309\\u7167\\u7522\\u54c1\\u5217\\u51fa\\u9700\\u6c42\\u7e3d\\u6578\\uff0c\\u7814\\u767c\\u968e\\u6bb5\\u7684\\u5206\\u5e03\\u60c5\\u51b5\\u3002 \",\"en\":\"Total number and stage distribution of stories \"}',    'admin',        '2015-07-21 15:38:34');

-- DROP TABLE IF EXISTS `zt_workflowreport`;
CREATE TABLE IF NOT EXISTS `zt_workflowreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('pie', 'line', 'bar') NOT NULL DEFAULT 'pie',
  `countType` enum('sum', 'count') NOT NULL DEFAULT 'sum',
  `displayType` enum('value', 'percent') NOT NULL DEFAULT 'value',
  `dimension` varchar(130) NOT NULL,
  `fields` text NOT NULL,
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_workflowaction` ADD `status` varchar(10) NOT NULL DEFAULT 'enable' AFTER `desc`;
ALTER TABLE `zt_workflowaction` ADD `blocks` text NOT NULL AFTER `toList`;
ALTER TABLE `zt_workflowaction` ADD `virtual` tinyint(1) unsigned NOT NULL AFTER `buildin`;

ALTER TABLE `zt_workflowfield` ADD `expression` text NOT NULL AFTER `control`;
ALTER TABLE `zt_workflowfield` ADD `searchOrder` smallint(5) UNSIGNED NOT NULL DEFAULT '0' AFTER `order`;
ALTER TABLE `zt_workflowfield` ADD `exportOrder` smallint(5) UNSIGNED NOT NULL DEFAULT '0' AFTER `searchOrder`;

ALTER TABLE `zt_workflowlabel` ADD `orderBy` text NOT NULL AFTER `params`;

ALTER TABLE `zt_workflowdatasource` CHANGE `type` `type` enum('system', 'sql', 'func', 'option', 'lang', 'category') NOT NULL DEFAULT 'option';
ALTER TABLE `zt_workflowlayout` CHANGE `totalShow` `summary` varchar(20) NOT NULL;

ALTER TABLE `zt_workflowfield` DROP `isKey`;

UPDATE `zt_workflowfield`  SET `control` = 'richtext' WHERE `control` = 'textarea';
UPDATE `zt_workflowlayout` SET `summary` = ''         WHERE `summary` = '0';
UPDATE `zt_workflowlayout` SET `summary` = 'sum'      WHERE `summary` = '1';

UPDATE `zt_workflowdatasource` SET `datasource` = '{\"app\":\"system\",\"module\":\"execution\",\"method\":\"getPairs\",\"methodDesc\":\"Get project pairs.\",\"params\":[{\"name\":\"mode\",\"type\":\"string\",\"desc\":\"all|noclosed or empty\",\"value\":\"all\"}]}' WHERE `id` = 2;
UPDATE `zt_workflowdatasource` SET `datasource` = '{\"app\":\"system\",\"module\":\"product\",\"method\":\"getLinePairs\",\"methodDesc\":\"Get line pairs.\",\"params\":[{\"name\":\"useShort\",\"type\":\"bool\",\"desc\":\"\",\"value\":\"\"}]}' WHERE `id` = 3;
UPDATE `zt_workflowdatasource` SET `datasource` = '{\"app\":\"system\",\"module\":\"user\",\"method\":\"getLinePairs\",\"methodDesc\",\"getTeamMemberPairs\":\"Get line pairs.\",\"params\":[{\"name\":\"useShort\",\"type\":\"bool\",\"desc\":\"\",\"value\":\"\"}]}' WHERE `id` = 8;

CREATE TABLE `zt_projectcase`(
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `case` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` smallint(6) NOT NULL DEFAULT '1',
  `order` smallint(6) unsigned NOT NULL,
  UNIQUE KEY `project` (`project`,`case`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

SET global log_bin_trust_function_creators = 1;
SET global sql_mode = '';
USE `__TABLE__`;

DROP FUNCTION IF EXISTS `get_monday`;
CREATE FUNCTION `get_monday`(day date) RETURNS date
  begin if date_format(day, '%w') = 0 then return subdate(day, date_format(day, '%w') - 6)__DELIMITER__
  else  return subdate(day, date_format(day, '%w') -1)__DELIMITER__
  end if__DELIMITER__
END;

DROP FUNCTION IF EXISTS `get_sunday`;
CREATE FUNCTION `get_sunday`(day date) RETURNS date
begin
  if date_format(day, '%w') = 0 then return day__DELIMITER__
  else return subdate(day, date_format(day, '%w') - 7)__DELIMITER__
  end if__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_cminited`;
CREATE FUNCTION qc_cminited($project int, $category varchar(30)) returns int
begin
    declare products int default 0__DELIMITER__
    declare objects  int default 0__DELIMITER__
    select COUNT(1) from zt_projectproduct where project = $project into products__DELIMITER__
    select COUNT(DISTINCT product) from zt_object where project = $project and category = $category and type = 'taged' and product in (select product from zt_projectproduct where project = $project) into objects__DELIMITER__
    IF products = objects THEN
    return 1__DELIMITER__
    ELSEIF products != objects THEN
    return 0__DELIMITER__
    END IF__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_initscale`;
CREATE FUNCTION qc_initscale($project int, $category varchar(30), $estimateType varchar(30)) RETURNS float(10,2)
BEGIN
    declare $estimate int default 0__DELIMITER__
    declare $storyEst varchar(30) default 'storyEst'__DELIMITER__
    declare $requestEst varchar(30) default 'requestEst'__DELIMITER__
    if($estimateType = $storyEst) THEN SELECT sum(storyEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = $category and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__
    end if__DELIMITER__
    if($estimateType = $requestEst) THEN SELECT sum(requestEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = $category and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__
    end if__DELIMITER__
    RETURN @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmplanscale`;
CREATE FUNCTION `qc_pgmplanscale`($project int) RETURNS float(10,2)
BEGIN
   declare programScale float (10,2) default 0__DELIMITER__
   select `scale` from zt_workestimation where project = $project into @programScale__DELIMITER__
   return @programScale__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmsrinitscale`;
CREATE FUNCTION `qc_pgmsrinitscale`($project int) RETURNS float(10,2)
begin
    declare scale int default 0__DELIMITER__
    declare inited int default 0__DELIMITER__
    select qc_cminited($project, 'SRS') into inited__DELIMITER__
    IF inited = 1 THEN
    select qc_initscale($project, 'SRS', 'storyEst') into scale __DELIMITER__
    return scale __DELIMITER__
    ELSE
    return 0__DELIMITER__
    END IF__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmsrrealscale`;
CREATE FUNCTION `qc_pgmsrrealscale`($project int) RETURNS float(10,2)
BEGIN
  declare totalEstimate float(10,2) default 0__DELIMITER__
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_story where project=$project and type='story' and deleted='0' and closedReason not in ('subdivided', 'duplicate', 'willnotdo', 'cancel', 'bydesign') into totalEstimate__DELIMITER__
  return totalEstimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmurinitscale`;
CREATE FUNCTION `qc_pgmurinitscale`($project int) RETURNS float(10,2)
begin
    declare scale int default 0__DELIMITER__
    declare inited int default 0__DELIMITER__
    select qc_cminited($project, 'URS') into inited__DELIMITER__
    IF inited = 1 THEN
    select qc_initscale($project, 'URS', 'requestEst') into scale__DELIMITER__
    return scale__DELIMITER__
    ELSE
    return 0__DELIMITER__
    END IF__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmurrealscale`;
CREATE FUNCTION `qc_pgmurrealscale`($project int) RETURNS float(10,2)
BEGIN
  declare totalEstimate float(10,2) default 0__DELIMITER__
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_story where project=$project and type='requirement' and deleted='0' and closedReason not in ('subdivided', 'duplicate', 'willnotdo', 'cancel', 'bydesign') into totalEstimate__DELIMITER__
  return totalEstimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmallrequirementstage`;
CREATE FUNCTION `qc_pgmallrequirementstage`($project int) RETURNS int(1)
BEGIN
    -- 获取项目产品总数
    select COUNT(1) AS products from zt_projectproduct where project = $project into @totalproduct__DELIMITER__
    -- 获取已经设置需求阶段的产品总数
    select COUNT(1) AS product from (select product from zt_projectproduct where project in (select id from zt_project where project = $project and type = 'stage' and attribute = 'request' and deleted = '0') GROUP BY product) as product into @product__DELIMITER__
    -- 让项目产品总数和已设置需求阶段产品总数比较,都设置返回1,否则返回0
    if @totalproduct = @product then return 1__DELIMITER__
    end if__DELIMITER__
    RETURN 0__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdesigntplandays`;
CREATE FUNCTION `qc_pgmdesigntplandays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeplanneddays($project,'design') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdesigntrealdays`;
CREATE FUNCTION `qc_pgmdesigntrealdays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeactualdays($project,'design') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdevelplandays`;
CREATE FUNCTION `qc_pgmdevelplandays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeplanneddays($project,'dev') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdevelrealdays`;
CREATE FUNCTION `qc_pgmdevelrealdays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeactualdays($project,'dev') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrequestplandays`;
CREATE FUNCTION `qc_pgmrequestplandays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeplanneddays($project,'request') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrequestrealdays`;
CREATE FUNCTION `qc_pgmrequestrealdays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeactualdays($project,'request') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmspecifiedtypeactualdays`;
CREATE FUNCTION `qc_pgmspecifiedtypeactualdays`($project int,$attribute varchar(50)) RETURNS int(10)
BEGIN
    -- 查询某类型的阶段总数
    select COUNT(1) from zt_project where project = $project and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where project = $project and attribute = $attribute and grade = 2 group by parent) into @totalstory__DELIMITER__
    -- 查询某类型已设置实际工期的阶段总数
    select COUNT(1) from zt_project where project = $project and attribute = $attribute and deleted = '0' and realDuration > 0 and id not in (select parent from zt_project where project = $project and attribute = $attribute and grade = 2 group by parent) into @setstory__DELIMITER__
    -- 查询项目下某类型阶段实际工期总数
    select sum(realDuration) as realDuration from zt_project where project = $project and attribute = $attribute and deleted = '0' and realDuration > 0 and id not in (select parent from zt_project where project = $project and attribute = $attribute and grade = 2 group by parent) into @days__DELIMITER__
    -- 判断项目下某类型的阶段是否都已设置实际工期
    if @totalstory != @setstory then
        set @days = 0__DELIMITER__
    end if__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmspecifiedtypeplanneddays`;
CREATE FUNCTION `qc_pgmspecifiedtypeplanneddays`($project int,$attribute varchar(50)) RETURNS int(10)
BEGIN
    select sum(planDuration) as planDuration from zt_project where project = $project and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where project = $project and attribute = $attribute and grade = 2 group by parent) into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmstageactualduration`;
CREATE FUNCTION `qc_pgmstageactualduration`($product int, $attribute varchar(50)) RETURNS int(10)
BEGIN
    -- 查找某类型的阶段总数
    select COUNT(1) AS totalduration from zt_project where id in (select project from zt_projectproduct where product = $product) and type = 'stage' and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and grade = 2 group by parent) into @totalduration__DELIMITER__
    -- 查某类型阶段已设置实际工期的总数
    select COUNT(1) AS setduration from zt_project where id in (select project from zt_projectproduct where product = $product) and type = 'stage' and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and grade = 2 group by parent) and realDuration > 0 into @setduration__DELIMITER__
    -- 指定产品下某类型的阶段实际工期总和
    select sum(realDuration) as duration from zt_project where id in (select project from zt_projectproduct where product = $product) and type = 'stage' and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and grade = 2 group by parent) and realDuration > 0 into @duration__DELIMITER__
    -- 需要判断该类型阶段都已设置实际工期,否则不统计
    if @totalduration != @setduration then
        set @duration = 0__DELIMITER__
    end if__DELIMITER__
    return @duration__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmstageplannedduration`;
CREATE FUNCTION `qc_pgmstageplannedduration`($product int, $attribute varchar(50)) RETURNS int(10)
BEGIN
    -- 查找某产品对应阶段
    select sum(planDuration) as duration from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and grade = 2 group by parent) and planDuration > 0 into @duration__DELIMITER__
    RETURN @duration__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmtestplandays`;
CREATE FUNCTION `qc_pgmtestplandays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeplanneddays($project,'qa') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmtestrealdays`;
CREATE FUNCTION `qc_pgmtestrealdays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeactualdays($project,'qa') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prddesigntplandays`;
CREATE FUNCTION `qc_prddesigntplandays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageplannedduration($project, $product, 'design') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prddesigntrealdays`;
CREATE FUNCTION `qc_prddesigntrealdays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageactualduration($project, $product, 'design') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prddevelplandays`;
CREATE FUNCTION `qc_prddevelplandays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageplannedduration($project, $product, 'dev') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prddevelrealdays`;
CREATE FUNCTION `qc_prddevelrealdays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageactualduration($project, $product, 'dev') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prdrequestplandays`;
CREATE FUNCTION `qc_prdrequestplandays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageplannedduration($project, $product, 'request') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prdrequestrealdays`;
CREATE FUNCTION `qc_prdrequestrealdays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageactualduration($project, $product, 'request') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prdtestplandays`;
CREATE FUNCTION `qc_prdtestplandays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageplannedduration($project, $product, 'qa') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prdtestrealdays`;
CREATE FUNCTION `qc_prdtestrealdays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageactualduration($project, $product, 'qa') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdesgignrealesthours`;
CREATE FUNCTION `qc_pgmdesgignrealesthours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmesthoursbytype($project, 'design')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdesignrealhours`;
CREATE FUNCTION `qc_pgmdesignrealhours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmrealhoursbytype($project, 'design')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdevelrealesthours`;
CREATE FUNCTION `qc_pgmdevelrealesthours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmesthoursbytype($project, 'devel')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdevelrealhours`;
CREATE FUNCTION `qc_pgmdevelrealhours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmrealhoursbytype($project, 'devel')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrealesthours`;
CREATE FUNCTION `qc_pgmrealesthours`($project int) RETURNS float(10,2)
BEGIN
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_task where project=$project and parent >= 0 and status != 'cancel' and deleted = '0' into @estimate__DELIMITER__
  return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmesthoursbytype`;
CREATE FUNCTION `qc_pgmesthoursbytype`($project int, $type char(30)) RETURNS float(10,2)
BEGIN
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_task where project=$project and type = $type and parent >= 0 and status != 'cancel' and deleted = '0' into @estimate__DELIMITER__
  return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrealhours`;
CREATE FUNCTION `qc_pgmrealhours`($project int) RETURNS float(10,2)
BEGIN
  select CAST(sum(consumed) as DECIMAL(10,2)) as consumed from zt_task where project=$project and parent >= 0 and status != 'cancel' and deleted = '0' into @consumed__DELIMITER__
  return @consumed__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrealhoursbytype`;
CREATE FUNCTION `qc_pgmrealhoursbytype`($project int, $type char(30)) RETURNS float(10,2)
BEGIN
  select CAST(sum(consumed) as DECIMAL(10,2)) as consumed from zt_task where project=$project and type = $type and parent >= 0 and status != 'cancel' and deleted = '0' into @consumed__DELIMITER__
  return @consumed__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrequestrealesthours`;
CREATE FUNCTION `qc_pgmrequestrealesthours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmesthoursbytype($project, 'request')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrequestrealhours`;
CREATE FUNCTION `qc_pgmrequestrealhours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmrealhoursbytype($project, 'request')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmtestrealesthours`;
CREATE FUNCTION `qc_pgmtestrealesthours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmesthoursbytype($project, 'test')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmtestrealhours`;
CREATE FUNCTION `qc_pgmtestrealhours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmrealhoursbytype($project, 'test')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getdevelfirstesthours`;
CREATE FUNCTION `qc_getdevelfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(devEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getdesignfirstesthours`;
CREATE FUNCTION `qc_getdesignfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(designEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getstoryfirstesthours`;
CREATE FUNCTION `qc_getstoryfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(requestEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_gettestfirstesthours`;
CREATE FUNCTION `qc_gettestfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(testEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getfirstesthours`;
CREATE FUNCTION `qc_getfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(taskEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getdevlastesthours`;
CREATE FUNCTION `qc_getdevlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(devEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getrequestlastesthours`;
CREATE FUNCTION `qc_getrequestlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(requestEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_gettestlastesthours`;
CREATE FUNCTION `qc_gettestlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(testEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getdesignlastesthours`;
CREATE FUNCTION `qc_getdesignlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(designEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getlastesthours`;
CREATE FUNCTION `qc_getlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(taskEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmlastesthours`;
CREATE FUNCTION `qc_pgmlastesthours`($project int) RETURNS float(10,2)
BEGIN
    declare estimate float(10,2) default 0__DELIMITER__
    declare inited int default 0__DELIMITER__
    select qc_cminited($project,'PP') into inited__DELIMITER__
    IF inited = 1 THEN
    select qc_getlastesthours($project) into estimate__DELIMITER__
    return estimate__DELIMITER__
    ELSE
    return 0__DELIMITER__
    END IF__DELIMITER__
END;

DELETE FROM `zt_config` where `key` = 'preferenceSetted';
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'project', '', 'unitList', 'CNY,USD');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'project', '', 'defaultCurrency', 'CNY');

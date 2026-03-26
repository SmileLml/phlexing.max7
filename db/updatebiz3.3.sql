ALTER TABLE `zt_task` ADD `feedback` mediumint(8) unsigned NOT NULL AFTER `fromBug`;
ALTER TABLE `zt_todo` ADD `feedback` mediumint(8) unsigned NOT NULL AFTER `end`;

ALTER TABLE `zt_report` CHANGE `vars` `vars` text COLLATE 'utf8_general_ci' NOT NULL AFTER `sql`,
CHANGE `params` `params` text COLLATE 'utf8_general_ci' NOT NULL AFTER `vars`;

ALTER TABLE `zt_feedback` ADD `reviewedBy` varchar(255) NOT NULL AFTER `openedDate`;
ALTER TABLE `zt_feedback` ADD `reviewedDate` datetime NOT NULL AFTER `reviewedBy`;
ALTER TABLE `zt_feedback` ADD `faq` mediumint(8) unsigned NOT NULL AFTER `result`;

CREATE TABLE IF NOT EXISTS `zt_faq` (
`id` mediumint(9) NOT NULL AUTO_INCREMENT,
`module` mediumint(9) NOT NULL,
`product` mediumint(9) NOT NULL,
`question` varchar(255) NOT NULL,
`answer` text NOT NULL,
`addedtime` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `zt_lieu` ADD `trip` char(255) NOT NULL AFTER `overtime`;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES
(1, 'faq', 'browse'),
(4, 'faq', 'browse'),
(5, 'faq', 'browse'),
(8, 'faq', 'browse'),
(13,'faq', 'browse'),
(13,'faq', 'create'),
(13,'faq', 'edit'),
(13,'faq', 'delete');

ALTER TABLE `zt_task` ADD `feedback` mediumint(8) unsigned NOT NULL AFTER `fromBug`;

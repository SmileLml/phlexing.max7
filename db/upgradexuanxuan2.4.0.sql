ALTER TABLE `zt_im_messagestatus` ADD `message` INT(11)  UNSIGNED  NOT NULL  AFTER `user`;

CREATE TABLE IF NOT EXISTS `zt_im_xxcversion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `version` char(10) NOT NULL DEFAULT '',
  `desc` varchar(100) NOT NULL DEFAULT '',
  `readme` text NULL,
  `strategy` varchar(10) NOT NULL DEFAULT '',
  `downloads` text NULL,
  `createdDate` datetime NULL,
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zt_product` ADD `feedback` varchar(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `RD`;

ALTER TABLE `zt_feedback`
ADD `module` mediumint(8) unsigned NOT NULL AFTER `product`,
ADD `assignedTo` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `editedDate`,
ADD `assignedDate` datetime NOT NULL AFTER `assignedTo`,
ADD `mailto` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `assignedDate`;

UPDATE `zt_product` SET `feedback` = `PO`;

ALTER TABLE `zt_feedbackproduct` RENAME TO `zt_feedbackview`;
ALTER TABLE `zt_feedbackview` ADD `product` mediumint unsigned NOT NULL AFTER `account`;
ALTER TABLE `zt_feedbackview`
ADD UNIQUE `account_product` (`account`, `product`),
DROP INDEX `account`;

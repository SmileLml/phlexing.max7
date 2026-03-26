ALTER TABLE `zt_im_message` ADD `order` mediumint(8) unsigned NOT NULL AFTER `date`;
ALTER TABLE `zt_im_message` ADD `data` text NULL AFTER `contentType`;
ALTER TABLE `zt_im_chatuser` ADD `category` varchar(40) NOT NULL DEFAULT '' AFTER `quit`;
ALTER TABLE `zt_im_chat` ADD `dismissDate` datetime NULL AFTER `lastActiveTime`;

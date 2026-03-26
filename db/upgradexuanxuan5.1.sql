ALTER TABLE `zt_im_conference` CHANGE `rid` `rid` CHAR(40) NOT NULL DEFAULT '';
ALTER TABLE `zt_im_conferenceaction` CHANGE `rid` `rid` CHAR(40) NOT NULL DEFAULT '';
ALTER TABLE `zt_im_conferenceaction` CHANGE `type` `type` enum('create','invite','join','leave','close','publish') NOT NULL DEFAULT 'create';
ALTER TABLE `zt_im_conferenceaction` ADD `data` text NULL AFTER `type`;

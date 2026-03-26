ALTER TABLE `zt_im_chat` ADD `mergedDate` datetime NULL AFTER `editedDate`;
ALTER TABLE `zt_im_chat` ADD `mergedChats` text NULL AFTER `pinnedMessages`;


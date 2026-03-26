ALTER TABLE `zt_im_chat` ADD `lastMessageIndex` int(11) unsigned NOT NULL DEFAULT 0 AFTER `lastMessage`;
ALTER TABLE `zt_im_chatuser` ADD `lastReadMessageIndex` int(11) unsigned NOT NULL DEFAULT 0 AFTER `lastReadMessage`;

ALTER TABLE `zt_im_message` ADD `index` int(11) unsigned NOT NULL DEFAULT 0 AFTER `date`;
ALTER TABLE `zt_im_message_backup` ADD `index` int(11) unsigned NOT NULL DEFAULT 0 AFTER `date`;

ALTER TABLE `zt_im_chat_message_index` ADD `startIndex` int(11) unsigned NOT NULL AFTER `end`;
ALTER TABLE `zt_im_chat_message_index` ADD `endIndex` int(11) unsigned NOT NULL AFTER `startIndex`;
ALTER TABLE `zt_im_chat_message_index` ADD KEY `chatstartindex` (`gid`,`startIndex`);
ALTER TABLE `zt_im_chat_message_index` ADD KEY `chatendindex` (`gid`,`endIndex`);


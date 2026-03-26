ALTER TABLE `zt_im_chat` ADD `pinnedMessages` text NULL AFTER `dismissDate`;
ALTER TABLE `zt_im_message` CHANGE `type` `type` enum('normal', 'broadcast', 'notify', 'bulletin') NOT NULL DEFAULT 'normal';

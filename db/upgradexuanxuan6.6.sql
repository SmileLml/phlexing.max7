ALTER TABLE `zt_im_message` MODIFY COLUMN `type` enum('normal','broadcast','notify','bulletin','botcommand') NOT NULL DEFAULT 'normal' AFTER `index`;
ALTER TABLE `zt_im_message` DROP INDEX `unique_msg`;

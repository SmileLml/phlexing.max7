ALTER TABLE `zt_im_message` MODIFY COLUMN `contentType` enum('text', 'plain', 'emotion', 'image', 'file', 'object', 'code', 'merge') NOT NULL DEFAULT 'text'

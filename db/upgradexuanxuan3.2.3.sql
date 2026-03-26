ALTER TABLE `zt_user` CHANGE `clientStatus` `clientStatus` enum('online', 'away', 'busy', 'offline', 'meeting') NOT NULL DEFAULT 'offline';

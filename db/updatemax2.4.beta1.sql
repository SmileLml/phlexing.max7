ALTER TABLE `zt_nc` ADD `activateDate` date NOT NULL AFTER `assignedTo`;
ALTER TABLE `zt_nc` ADD `assignedDate` date NOT NULL AFTER `activateDate`;
ALTER TABLE `zt_reviewresult` MODIFY `opinion` text NOT NULL;
ALTER TABLE `zt_gapanalysis` DROP COLUMN `definition`;

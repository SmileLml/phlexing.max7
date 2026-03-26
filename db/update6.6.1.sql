CREATE OR REPLACE VIEW `ztv_projectteams` AS select `zt_team`.`root` AS `project`,count('*') AS `teams` from `zt_team` where `type` = 'project' group by `zt_team`.`root`;

UPDATE `zt_workflowdatasource`
SET `datasource` = '{\"app\":\"system\",\"module\":\"product\",\"method\":\"getPairs\",\"methodDesc\":\"Get product pairs.\",\"params\":[{\"name\":\"mode\",\"type\":\"string\",\"desc\":\"\",\"value\":\"all\"}]}'
WHERE `datasource` like '%,"module":"product","method":"getPairs",%' AND `datasource` like '%,"value":"noclosed"%';
UPDATE `zt_workflowdatasource`
SET `datasource` = '{\"app\":\"system\",\"module\":\"project\",\"method\":\"getPairs\",\"methodDesc\":\"Get project pairs.\",\"params\":[{\"name\":\"mode\",\"type\":\"string\",\"desc\":\"all|noclosed or empty\",\"value\":\"all\"}]}'
WHERE `datasource` like '%,"module":"project","method":"getPairs",%' AND `datasource` like '%,"value":"noclosed"%';

UPDATE zt_effort SET product = CONCAT(',', TRIM(BOTH ',' FROM product), ',');

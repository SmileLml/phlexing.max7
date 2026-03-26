<?php
if(!isset($lang->excel)) $lang->excel = new stdclass();
if(!isset($lang->excel->help)) $lang->excel->help = new stdclass();
$lang->excel->help->task = "添加任務時，任務名稱,任務類型,是必填欄位，如果不填導入時會忽略該條數據；\n如需添加多人任務，請在“最初預計”列裡面，按照“用戶名:{$lang->hourCommon}”格式添加，多個用戶之間用換行分隔。用戶名在“系統數>據”工作表的G列查看。\n多人任務請填寫“任務模式”，非多人任務填寫“任務模式”導入時，系統會自動將“任務模式”置空。";

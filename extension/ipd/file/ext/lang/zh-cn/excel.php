<?php
$lang->file->onlySupportXLSX = '只支持XLSX格式导入，请转换XLSX格式再导入。';

$lang->excel->fileField = '附件';

$lang->excel->title              = new stdclass();
$lang->excel->title->testcase    = '用例';
$lang->excel->title->bug         = 'Bug';
$lang->excel->title->task        = '任务';
$lang->excel->title->story       = "{$lang->SRCommon}";
$lang->excel->title->epic        = "{$lang->ERCommon}";
$lang->excel->title->requirement = "{$lang->URCommon}";
$lang->excel->title->caselib     = '用例库';
$lang->excel->title->sysValue    = '系统数据';
$lang->excel->title->tree        = '树状图';
$lang->excel->title->user        = '用户';
$lang->excel->title->project     = '项目';

$lang->excel->error = new stdclass();
$lang->excel->error->info       = '您输入的值不在下拉框列表内。';
$lang->excel->error->title      = '输入有误';
$lang->excel->error->noFile     = '没有文件';
$lang->excel->error->noData     = '没有有效的数据';
$lang->excel->error->canNotRead = '不能解析该文件';

$lang->excel->help              = new stdclass();
$lang->excel->help->testcase    = '添加用例时，每个用例步骤在新行用数字 + ‘.’来标记。同样的，预期也是用数字 + ‘.’与步骤对应。%s是必填字段，如果不填导入时会忽略该条数据。';
$lang->excel->help->caselib     = '添加用例时，每个用例步骤在新行用数字 + ‘.’来标记。同样的，预期也是用数字 + ‘.’与步骤对应。用例名称是必填字段，如果不填导入时会忽略该条数据。';
$lang->excel->help->bug         = '添加Bug时，%s是必填字段，如果不填导入时会忽略该条数据。';
$lang->excel->help->task        = "添加任务时，%s是必填字段，如果不填导入时会忽略该条数据；\n如需导入父子任务，则需要填写层级一列，层级格式：x、x.y、x.y.z。例如：1是1.1的父任务，1.1是1.1.1的父任务，层级必须是唯一的。\n如需添加多人任务，请在“最初预计”列里面，按照“用户名:{$lang->hourCommon}”格式添加，多个用户之间用换行分隔。用户名在“系统数据”工作表的G列查看。\n多人任务请填写“任务模式”，非多人任务填写“任务模式”导入时，系统会自动将“任务模式”置空。\n多人任务下不能再拆分子任务。";
$lang->excel->help->story       = "添加需求时，%s是必填字段，如果不填导入时会忽略该条数据。\n如需导入父子需求，则需要填写层级一列，层级格式：x、x.y、x.y.z。例如：1是1.1的父需求，1.1是1.1.1的父需求，层级必须是唯一的。";
$lang->excel->help->epic        = $lang->excel->help->story;
$lang->excel->help->requirement = $lang->excel->help->story;
$lang->excel->help->user        = "添加用户时，“用户名”和“姓名”是必填字段，如果不填导入时会忽略该条数据。";
$lang->excel->help->feedback    = "添加反馈时，%s是必填字段，如果不填导入时会忽略该条数据。";
$lang->excel->help->ticket      = "添加工单时，标题、所属{$lang->productCommon}、所属模块是必填字段，如果不填导入时会忽略该条数据。";
$lang->excel->help->demand      = "添加需求时，%s是必填字段，如果不填导入时会忽略该条数据。";

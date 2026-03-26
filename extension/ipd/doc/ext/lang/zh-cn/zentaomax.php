<?php
$lang->doc->templateType         = '模板类型';
$lang->doc->template             = '模板';
$lang->doc->selectTemplate       = '选择模板';
$lang->doc->createByTemplate     = '由模板创建';
$lang->doc->assignedTo           = '指派给';
$lang->doc->approver             = '审批人';
$lang->doc->importToPracticeLib  = '导入最佳实践库';
$lang->doc->importToComponentLib = '导入组件库';
$lang->doc->practiceLib          = '最佳实践库';
$lang->doc->componentLib         = '组件库';
$lang->doc->isExistPracticeLib   = '最佳实践库中已有此条记录，请勿重复提交！';
$lang->doc->isExistComponentLib  = '组件库中已有此条记录，请勿重复提交！';
$lang->doc->replaceContentTip    = '您确定要使用该模板吗，使用模板后，正文内容将被清空。';
$lang->doc->selectExecution      = '选择执行';
$lang->doc->selectProduct        = '选择产品';
$lang->doc->selectProject        = '选择项目';

$lang->docTemplate->filter     = '筛选器';
$lang->docTemplate->param      = '配置参数';
$lang->docTemplate->searchTab  = '检索标签';
$lang->docTemplate->zentaoData = '禅道列表数据配置';
$lang->docTemplate->filterTip  = '使用检索标签快捷配置相应对象筛选器。';
$lang->docTemplate->paramTip   = '此区块会根据配置的参数以及所选模板中对象筛选器的配置，展示相应的列表数据。';
$lang->docTemplate->configTip  = '使用该模板时，此区块会根据筛选器的配置展示相应的%s数据。';
$lang->docTemplate->next       = '下一步';
$lang->docTemplate->noPriv     = '您没有%s的权限。';

$lang->doc->docLang->createByTemplate = $lang->doc->createByTemplate;

$lang->docTemplate->zentaoList = array();
$lang->docTemplate->zentaoList['story']          = $lang->SRCommon;
$lang->docTemplate->zentaoList['productStory']   = $lang->productCommon . $lang->SRCommon;
$lang->docTemplate->zentaoList['projectStory']   = $lang->projectCommon . $lang->SRCommon;
$lang->docTemplate->zentaoList['executionStory'] = $lang->execution->common . $lang->SRCommon;

$lang->docTemplate->zentaoList['design'] = $lang->design->common;
$lang->docTemplate->zentaoList['HLDS']   = $lang->design->HLDS;
$lang->docTemplate->zentaoList['DDS']    = $lang->design->DDS;
$lang->docTemplate->zentaoList['DBDS']   = $lang->design->DBDS;
$lang->docTemplate->zentaoList['ADS']    = $lang->design->ADS;

$lang->docTemplate->zentaoList['task']        = $lang->task->common;
$lang->docTemplate->zentaoList['case']        = $lang->testcase->common;
$lang->docTemplate->zentaoList['productCase'] = $lang->productCommon . $lang->testcase->common;
$lang->docTemplate->zentaoList['projectCase'] = $lang->projectCommon . $lang->testcase->common;
$lang->docTemplate->zentaoList['bug']         = $lang->bug->common;
$lang->docTemplate->zentaoList['gantt']       = '甘特图';

$lang->docTemplate->searchTabList = array();
$lang->docTemplate->searchTabList['productStory'] = array();
$lang->docTemplate->searchTabList['productStory']['allstory']       = '全部';
$lang->docTemplate->searchTabList['productStory']['unclosed']       = '未关闭';
$lang->docTemplate->searchTabList['productStory']['draftstory']     = '草稿';
$lang->docTemplate->searchTabList['productStory']['activestory']    = '激活';
$lang->docTemplate->searchTabList['productStory']['changingstory']  = '变更中';
$lang->docTemplate->searchTabList['productStory']['reviewingstory'] = '评审中';
$lang->docTemplate->searchTabList['productStory']['willclose']      = '待关闭';
$lang->docTemplate->searchTabList['productStory']['closedstory']    = '已关闭';
$lang->docTemplate->searchTabList['productStory']['feedback']       = '来自反馈';

$lang->docTemplate->searchTabList['projectStory'] = array();
$lang->docTemplate->searchTabList['projectStory']['allstory']          = '全部';
$lang->docTemplate->searchTabList['projectStory']['unclosed']          = '未关闭';
$lang->docTemplate->searchTabList['projectStory']['draft']             = '草稿';
$lang->docTemplate->searchTabList['projectStory']['reviewing']         = '评审中';
$lang->docTemplate->searchTabList['projectStory']['changing']          = '变更中';
$lang->docTemplate->searchTabList['projectStory']['closed']            = '已关闭';
$lang->docTemplate->searchTabList['projectStory']['linkedexecution']   = '已关联' . $lang->execution->common;
$lang->docTemplate->searchTabList['projectStory']['unlinkedexecution'] = '未关联' . $lang->execution->common;

$lang->docTemplate->searchTabList['executionStory'] = array();
$lang->docTemplate->searchTabList['executionStory']['all']       = '全部';
$lang->docTemplate->searchTabList['executionStory']['unclosed']  = '未关闭';
$lang->docTemplate->searchTabList['executionStory']['draft']     = '草稿';
$lang->docTemplate->searchTabList['executionStory']['reviewing'] = '评审中';

$lang->docTemplate->searchTabList['task'] = array();
$lang->docTemplate->searchTabList['task']['all']         = '全部';
$lang->docTemplate->searchTabList['task']['unclosed']    = '未关闭';
$lang->docTemplate->searchTabList['task']['needconfirm'] = "{$lang->SRCommon}变更";
$lang->docTemplate->searchTabList['task']['wait']        = '未开始';
$lang->docTemplate->searchTabList['task']['doing']       = '进行中';
$lang->docTemplate->searchTabList['task']['undone']      = '未完成';
$lang->docTemplate->searchTabList['task']['done']        = '已完成';
$lang->docTemplate->searchTabList['task']['closed']      = '已关闭';
$lang->docTemplate->searchTabList['task']['cancel']      = '已取消';
$lang->docTemplate->searchTabList['task']['delayed']     = '已延期';

$lang->docTemplate->searchTabList['bug']['all']           = '全部';
$lang->docTemplate->searchTabList['bug']['unclosed']      = '未关闭';
$lang->docTemplate->searchTabList['bug']['unresolved']    = '未解决';
$lang->docTemplate->searchTabList['bug']['unconfirmed']   = '未确认';
$lang->docTemplate->searchTabList['bug']['assigntonull']  = '未指派';
$lang->docTemplate->searchTabList['bug']['longlifebugs']  = '久未处理';
$lang->docTemplate->searchTabList['bug']['toclosed']      = '待关闭';
$lang->docTemplate->searchTabList['bug']['postponedbugs'] = '被延期';
$lang->docTemplate->searchTabList['bug']['overduebugs']   = '过期Bug';
$lang->docTemplate->searchTabList['bug']['needconfirm']   = "{$lang->SRCommon}变动";
$lang->docTemplate->searchTabList['bug']['feedback']      = '来自反馈';

$lang->docTemplate->searchTabList['productCase'] = array();
$lang->docTemplate->searchTabList['productCase']['all']         = '全部';
$lang->docTemplate->searchTabList['productCase']['wait']        = '待评审';
$lang->docTemplate->searchTabList['productCase']['needconfirm'] = "{$lang->common->story}变动";

$lang->docTemplate->searchTabList['projectCase'] = array();
$lang->docTemplate->searchTabList['projectCase']['all']         = '全部';
$lang->docTemplate->searchTabList['projectCase']['wait']        = '待评审';
$lang->docTemplate->searchTabList['projectCase']['needconfirm'] = "{$lang->common->story}变动";

$lang->docTemplate->searchTabList['HLDS'] = array();
$lang->docTemplate->searchTabList['HLDS']['all'] = '全部';

$lang->docTemplate->searchTabList['DDS'] = array();
$lang->docTemplate->searchTabList['DDS']['all'] = '全部';

$lang->docTemplate->searchTabList['DBDS'] = array();
$lang->docTemplate->searchTabList['DBDS']['all'] = '全部';

$lang->docTemplate->searchTabList['ADS'] = array();
$lang->docTemplate->searchTabList['ADS']['all'] = '全部';

$lang->doc->featureBar['selecttemplate'] = array();
$lang->doc->featureBar['selecttemplate']['all'] = '全部';

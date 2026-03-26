<?php
$lang->custom->libreOffice       = 'Office转换设置';
$lang->custom->libreOfficeTurnon = '功能开关';
$lang->custom->type              = '类型';
$lang->custom->libreOfficePath   = 'soffice路径';
$lang->custom->collaboraPath     = 'Collabora路径';

$lang->custom->errorSofficePath   = 'soffice文件不存在';
$lang->custom->errorRunSoffice    = "程序运行失败。错误信息：%s";
$lang->custom->errorRunCollabora  = "连接Collabora失败，请确认Collabora是否配置正确，或者网络是否能成功访问。";
$lang->custom->cannotUseCollabora = "要使用Collabora，必须配置禅道系统为静态访问。";

$lang->custom->turnonList[1] = '开启';
$lang->custom->turnonList[0] = '关闭';

$lang->custom->typeList['libreoffice'] = 'LibreOffice';
$lang->custom->typeList['collabora']   = 'Collabora Online';

$lang->custom->sofficePlaceholder   = '填写LibreOffice中的soffice文件路径。如 /opt/libreoffice/program/soffice';
$lang->custom->collaboraPlaceholder = '填写Collabora的访问URL，如 https://127.0.0.1:9980';

$lang->custom->feedback = new stdclass();
$lang->custom->feedback->fields['required']         = $lang->custom->required;
$lang->custom->feedback->fields['review']           = '评审流程';
$lang->custom->feedback->fields['closedReasonList'] = '关闭原因';
$lang->custom->feedback->fields['typeList']         = '反馈类型';
$lang->custom->feedback->fields['priList']          = '优先级';

$lang->custom->ticket = new stdclass();
$lang->custom->ticket->fields['required']         = $lang->custom->required;
$lang->custom->ticket->fields['priList']          = '优先级';
$lang->custom->ticket->fields['typeList']         = '工单类型';
$lang->custom->ticket->fields['closedReasonList'] = '关闭原因';

$lang->custom->browseRelation    = "关联关系列表";
$lang->custom->createRelation    = "新增关联关系";
$lang->custom->editRelation      = "编辑关联关系";
$lang->custom->deleteRelation    = "删除关联关系";
$lang->custom->relativeRelation  = "对应关系";
$lang->custom->relationTip       = '用户可以对需求、任务、Bug、用例、文档、设计、问题、风险、提交、反馈、工单，以及工作流对象配置关联关系。“关联关系”与“对应关系”是双向的，例如A依赖B，则B被依赖A。';
$lang->custom->hasRelationTip    = '"%s"关系在系统内已存在，是否仍要保存？';
$lang->custom->relation          = '关联关系';
$lang->custom->relateObject      = '关联对象';
$lang->custom->removeObjects     = '解除关联关系';
$lang->custom->removeObjectTip   = '您确定解除该关联关系吗？';
$lang->custom->deleteRelationTip = '系统内已有对象配置了此关系，无法删除。';
$lang->custom->defaultRelation   = '系统内置的关系跟随用户操作流程记录，此处不能解除关联。';
$lang->custom->relationGraph     = '关系图谱';

$lang->custom->relationList = array();
$lang->custom->relationList['transferredto']   = '转化为';
$lang->custom->relationList['transferredfrom'] = '转化于';
$lang->custom->relationList['twin']            = '孪生';
$lang->custom->relationList['subdivideinto']   = '细分为';
$lang->custom->relationList['subdividefrom']   = '细分于';
$lang->custom->relationList['generated']       = '产生了';
$lang->custom->relationList['derivedfrom']     = '产生于';
$lang->custom->relationList['completedin']     = '被实现';
$lang->custom->relationList['completedfrom']   = '实现了';
$lang->custom->relationList['interrated']      = '关联到';

$lang->custom->setCharterInfo   = '自定义立项配置';
$lang->custom->resetCharterInfo = '恢复默认立项配置';

$lang->custom->charter = new stdclass();
$lang->custom->charter->level            = '项目等级';
$lang->custom->charter->type             = '规划方式';
$lang->custom->charter->projectApproval  = '立项资料';
$lang->custom->charter->completeApproval = '结项资料';
$lang->custom->charter->cancelApproval   = '取消立项资料';

$lang->custom->charter->tips = new stdclass();
$lang->custom->charter->tips->sameLevel = '已存在相同的项目等级。';
$lang->custom->charter->tips->leastOne  = '请设置资料。';

$lang->custom->charterFiles = array();
$lang->custom->charterFiles['1'] = array('key' => '1', 'type' => 'plan', 'level' => '1', 'projectApproval'  => array(array('index' => 'BP', 'name' => '业务计划书'), array('index' => 'charter', 'name' => '项目任务书'), array('index' => 'other', 'name' => '其他')), 'completeApproval' => array(array('index' => 'summary', 'name' => '项目总结报告'), array('index' => 'finance', 'name' => '财务报告'), array('index' => 'assess', 'name' => '评估报告'), array('index' => 'other', 'name' => '其他')), 'cancelApproval' => array(array('index' => 'reason', 'name' => '取消原因分析'), array('index' => 'finance', 'name' => '财务报告'), array('index' => 'assess', 'name' => '风险和影响评估'), array('index' => 'other', 'name' => '其他')));
$lang->custom->charterFiles['2'] = array('key' => '2', 'type' => 'plan', 'level' => '2', 'projectApproval'  => array(array('index' => 'BP', 'name' => '业务计划书'), array('index' => 'charter', 'name' => '项目任务书'), array('index' => 'other', 'name' => '其他')), 'completeApproval' => array(array('index' => 'summary', 'name' => '项目总结报告'), array('index' => 'finance', 'name' => '财务报告'), array('index' => 'assess', 'name' => '评估报告'), array('index' => 'other', 'name' => '其他')), 'cancelApproval' => array(array('index' => 'reason', 'name' => '取消原因分析'), array('index' => 'finance', 'name' => '财务报告'), array('index' => 'assess', 'name' => '风险和影响评估'), array('index' => 'other', 'name' => '其他')));
$lang->custom->charterFiles['3'] = array('key' => '3', 'type' => 'plan', 'level' => '3', 'projectApproval'  => array(array('index' => 'BP', 'name' => '业务计划书'), array('index' => 'charter', 'name' => '项目任务书'), array('index' => 'other', 'name' => '其他')), 'completeApproval' => array(array('index' => 'summary', 'name' => '项目总结报告'), array('index' => 'finance', 'name' => '财务报告'), array('index' => 'other', 'name' => '其他')), 'cancelApproval' => array(array('index' => 'reason', 'name' => '取消原因分析'), array('index' => 'finance', 'name' => '财务报告'), array('index' => 'other', 'name' => '其他')));
$lang->custom->charterFiles['4'] = array('key' => '4', 'type' => 'plan', 'level' => '4', 'projectApproval'  => array(array('index' => 'BP', 'name' => '业务计划书'), array('index' => 'charter', 'name' => '项目任务书'), array('index' => 'other', 'name' => '其他')), 'completeApproval' => array(array('index' => 'summary', 'name' => '项目总结报告'), array('index' => 'finance', 'name' => '财务报告'), array('index' => 'other', 'name' => '其他')), 'cancelApproval' => array(array('index' => 'reason', 'name' => '取消原因分析'), array('index' => 'finance', 'name' => '财务报告'), array('index' => 'other', 'name' => '其他')));

$lang->custom->approvalflow = new stdclass();
$lang->custom->approvalflow->fields['browse'] = '审批流';
$lang->custom->approvalflow->fields['role']   = '审批角色';


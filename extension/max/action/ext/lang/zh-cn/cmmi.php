<?php
$lang->action->desc->createrequirements    = '$date, 由 <strong>$actor</strong> 分解' . $lang->SRCommon . '<strong>$extra</strong>。' . "\n";
$lang->action->desc->linked2process        = '$date 由 <strong>$actor</strong> 关联。';
$lang->action->desc->linked2activity       = '$date 由 <strong>$actor</strong> 关联。';
$lang->action->desc->linked2output         = '$date 由 <strong>$actor</strong> 关联。';
$lang->action->desc->checked               = '$date 由 <strong>$actor</strong> 进行检查。';
$lang->action->desc->communicate           = '$date 由 <strong>$actor</strong> 进行添加沟通记录。';
$lang->action->desc->designconfirmed       = '$date, 由 <strong>$actor</strong> 确认设计变更，最新版本为<strong>#$extra</strong>。' . "\n";
$lang->action->desc->issueconfirmed        = '$date, 由 <strong>$actor</strong> 确认。' . "\n";
$lang->action->desc->minuted               = '$date, 由 <strong>$actor</strong> 记录。' . "\n";
$lang->action->desc->import2storylib       = '$date, 由 <strong>$actor</strong> 导入。' . "\n";
$lang->action->desc->import2issuelib       = '$date, 由 <strong>$actor</strong> 导入。' . "\n";
$lang->action->desc->import2risklib        = '$date, 由 <strong>$actor</strong> 导入。' . "\n";
$lang->action->desc->import2opportunitylib = '$date, 由 <strong>$actor</strong> 导入。' . "\n";
$lang->action->desc->import2practicelib    = '$date, 由 <strong>$actor</strong> 导入。' . "\n";
$lang->action->desc->import2componentlib   = '$date, 由 <strong>$actor</strong> 导入。' . "\n";
$lang->action->desc->removed               = '$date, 由 <strong>$actor</strong> 移除。' . "\n";
$lang->action->desc->reverted              = '$date, 由 <strong>$actor</strong> 回退。' . "\n";
$lang->action->desc->forwarded             = '$date, 由 <strong>$actor</strong> 转交。' . "\n";
$lang->action->desc->addnode               = '$date, 由 <strong>$actor</strong> 加签。' . "\n";
$lang->action->desc->managedeliverable     = '$date, 由 <strong>$actor</strong> 维护交付物。' . "\n";
$lang->action->desc->changeDeliverable     = '变更了 <strong><i>%s</i></strong> 交付物，旧值为"%s"，新值为"%s"。<br />' . "\n";
$lang->action->desc->addDeliverable        = '新增了 <strong><i>%s</i></strong> 交付物，新值为"%s"。<br />' . "\n";
$lang->action->desc->removeDeliverable     = '删除了 <strong><i>%s</i></strong> 交付物，旧值为"%s"。<br />' . "\n";

$lang->action->approve = new stdclass();
$lang->action->approve->pass   = '$date, 由 <strong>$actor</strong> 审批。' . '审批结果为<strong>通过</strong>。' ."\n";
$lang->action->approve->reject = '$date, 由 <strong>$actor</strong> 审批。' . '审批结果为<strong>拒绝</strong>。' ."\n";

$lang->action->label->toaudit                  = '提交审计';
$lang->action->label->issueconfirmed           = '确认了';
$lang->action->label->checked                  = '检查了';
$lang->action->label->editbasic                = '编辑了';
$lang->action->label->minuted                  = '记录了';
$lang->action->label->import2storylib          = "在需求库中导入了";
$lang->action->label->import2issuelib          = "在问题库中导入了";
$lang->action->label->import2risklib           = "在风险库中导入了";
$lang->action->label->import2opportunitylib    = "在机会库中导入了";
$lang->action->label->import2practicelib       = "在最佳实践库中导入了";
$lang->action->label->import2componentlib      = "在组件库中导入了";
$lang->action->label->importfromstorylib       = '从需求库导入';
$lang->action->label->importfromissuelib       = '从问题库导入';
$lang->action->label->importfromrisklib        = '从风险库导入';
$lang->action->label->importfromopportunitylib = '从机会库导入';
$lang->action->label->approved                 = '审批了';
$lang->action->label->removed                  = '移除了';
$lang->action->label->recall                   = '撤回了';
$lang->action->label->audited                  = '审计了';
$lang->action->label->createbasic              = '创建了';
$lang->action->label->submit                   = '提交了';
$lang->action->label->designconfirmed          = '确认了设计';
$lang->action->label->reverted                 = '回退了';
$lang->action->label->forwarded                = '转交了';
$lang->action->label->addnode                  = '加签了';
$lang->action->label->enabled                  = '启用了';
$lang->action->label->disabled                 = '停用了';
$lang->action->label->managedeliverable        = '维护了交付物';

$lang->action->objectTypes['projecttemplate'] = '项目模板';
$lang->action->objectTypes['review']          = '评审';
$lang->action->objectTypes['waterfall']       = '瀑布' . $lang->projectCommon . '审批';
$lang->action->objectTypes['approval']        = '审批流';
$lang->action->objectTypes['approvalflow']    = '审批流';
$lang->action->objectTypes['opportunity']     = '机会';
$lang->action->objectTypes['trainplan']       = '培训计划';
$lang->action->objectTypes['meeting']         = '会议';
$lang->action->objectTypes['meetingroom']     = '会议室';
$lang->action->objectTypes['gapanalysis']     = '能力差距分析';
$lang->action->objectTypes['auditcl']         = 'QA检查项';
$lang->action->objectTypes['auditplan']       = '质量保证计划';
$lang->action->objectTypes['auditresult']     = '检查结果';
$lang->action->objectTypes['nc']              = '不符合项';
$lang->action->objectTypes['measurement']     = '度量';
$lang->action->objectTypes['cmcl']            = '配置清单';
$lang->action->objectTypes['cm']              = '基线';
$lang->action->objectTypes['reviewissue']     = '评审问题';
$lang->action->objectTypes['researchplan']    = '调研计划';
$lang->action->objectTypes['researchreport']  = '调研报告';
$lang->action->objectTypes['assetlib']        = '资产库';
$lang->action->objectTypes['pssp']            = '过程裁剪';
$lang->action->objectTypes['reviewcl']        = '检查项';
$lang->action->objectTypes['activity']        = '活动';
$lang->action->objectTypes['zoutput']         = '文档';
$lang->action->objectTypes['process']         = '过程';
$lang->action->objectTypes['basicmeas']       = '度量定义';

$lang->action->label->opportunity         = '机会|opportunity|view|opportunityID=%s';
$lang->action->label->trainplan           = '培训计划|trainplan|view|trainplanID=%s';
$lang->action->label->gapanalysis         = '能力差距分析|gapanalysis|view|gapanalysisID=%s';
$lang->action->label->auditcl             = 'QA检查项|auditcl|view|auditclID=%s';
$lang->action->label->review              = '评审|review|view|reviewID=%s';
$lang->action->label->reviewissue         = '评审问题|reviewissue|view|reviewissueID=%s';
$lang->action->label->researchplan        = '调研计划|researchplan|view|planID=%s';
$lang->action->label->researchreport      = '调研报告|researchreport|view|reportID=%s';
$lang->action->label->meeting             = '会议|meeting|view|meetingID=%s';
$lang->action->label->meetingroom         = '会议室|meetingroom|view|roomID=%s';
$lang->action->label->storylib            = '需求库|assetlib|story|libID=%s';
$lang->action->label->issuelib            = '问题库|assetlib|issue|libID=%s';
$lang->action->label->risklib             = '风险库|assetlib|risk|libID=%s';
$lang->action->label->opportunitylib      = '机会库|assetlib|opportunity|libID=%s';
$lang->action->label->practicelib         = '最佳实践库|assetlib|practice|libID=%s';
$lang->action->label->componentlib        = '组件库|assetlib|component|libID=%s';
$lang->action->label->storyassetlib       = '需求库|assetlib|storyLibView|libID=%s';
$lang->action->label->issueassetlib       = '问题库|assetlib|issueLibView|libID=%s';
$lang->action->label->riskassetlib        = '风险库|assetlib|riskLibView|libID=%s';
$lang->action->label->opportunityassetlib = '机会库|assetlib|opportunityLibView|libID=%s';
$lang->action->label->practiceassetlib    = '最佳实践库|assetlib|practiceLibView|libID=%s';
$lang->action->label->componentassetlib   = '组件库|assetlib|componentLibView|libID=%s';
$lang->action->label->nc                  = '不符合项|nc|view|ncID=%s';
$lang->action->label->measurement         = '度量|measurement|setSQL|ID=%s';
$lang->action->label->sqlview             = '视图|sqlbuilder|browsesqlview|';
$lang->action->label->process             = '过程|process|view|processID=%s';
$lang->action->label->activity            = '活动|activity|view|activityID=%s';
$lang->action->label->zoutput             = '文档|zoutput|view|zoutputID=%s';

$lang->action->dynamicAction->researchplan['opened']  = '创建调研计划';
$lang->action->dynamicAction->researchplan['edited']  = '编辑调研计划';
$lang->action->dynamicAction->researchplan['deleted'] = '删除调研计划';

$lang->action->dynamicAction->researchreport['opened']  = '创建调研报告';
$lang->action->dynamicAction->researchreport['edited']  = '编辑调研报告';
$lang->action->dynamicAction->researchreport['deleted'] = '删除调研报告';

$lang->action->dynamicAction->meetingroom['opened']  = '创建会议室';
$lang->action->dynamicAction->meetingroom['edited']  = '编辑会议室';
$lang->action->dynamicAction->meetingroom['deleted'] = '删除会议室';

$lang->action->dynamicAction->meeting['opened']  = '创建会议';
$lang->action->dynamicAction->meeting['edited']  = '编辑会议';
$lang->action->dynamicAction->meeting['deleted'] = '删除会议';
$lang->action->dynamicAction->meeting['minuted'] = '记录会议';

$lang->action->dynamicAction->reviewissue['deleted']  = '删除评审问题';

$lang->action->dynamicAction->story['import2storylib'] = '导入需求库';
$lang->action->dynamicAction->story['approved']        = '审批需求';
$lang->action->dynamicAction->story['removed']         = '移除需求';
$lang->action->dynamicAction->story['importfromlib']   = '从需求库中导入';

$lang->action->dynamicAction->issue['import2issuelib'] = '导入问题库';
$lang->action->dynamicAction->issue['opened']          = '创建问题';
$lang->action->dynamicAction->issue['approved']        = '审批问题';
$lang->action->dynamicAction->issue['edited']          = '编辑问题';
$lang->action->dynamicAction->issue['deleted']         = '删除问题';
$lang->action->dynamicAction->issue['removed']         = '移除问题';
$lang->action->dynamicAction->issue['importfromlib']   = '从问题库中导入';
$lang->action->dynamicAction->issue['minuted']         = '记录问题日志';
$lang->action->dynamicAction->issue['closed']          = '关闭问题';
$lang->action->dynamicAction->issue['canceled']        = '取消问题';
$lang->action->dynamicAction->issue['assigned']        = '指派问题';
$lang->action->dynamicAction->issue['resolved']        = '解决问题';
$lang->action->dynamicAction->issue['activated']       = '激活问题';

$lang->action->dynamicAction->risk['import2risklib'] = '导入风险库';
$lang->action->dynamicAction->risk['opened']         = '创建风险';
$lang->action->dynamicAction->risk['edited']         = '编辑风险';
$lang->action->dynamicAction->risk['closed']         = '关闭风险';
$lang->action->dynamicAction->risk['deleted']        = '删除风险';
$lang->action->dynamicAction->risk['activated']      = '激活风险';
$lang->action->dynamicAction->risk['canceled']       = '取消风险';
$lang->action->dynamicAction->risk['assigned']       = '指派风险';
$lang->action->dynamicAction->risk['hangup']         = '挂起风险';
$lang->action->dynamicAction->risk['tracked']        = '跟踪风险';
$lang->action->dynamicAction->risk['minuted']        = '记录风险日志';
$lang->action->dynamicAction->risk['importfromlib']  = '从风险库中导入';

$lang->action->dynamicAction->opportunity['import2opportunitylib'] = '导入机会库';
$lang->action->dynamicAction->opportunity['opened']                = '创建机会';
$lang->action->dynamicAction->opportunity['edited']                = '编辑机会';
$lang->action->dynamicAction->opportunity['closed']                = '关闭机会';
$lang->action->dynamicAction->opportunity['deleted']               = '删除机会';
$lang->action->dynamicAction->opportunity['canceled']              = '取消机会';
$lang->action->dynamicAction->opportunity['assigned']              = '指派机会';
$lang->action->dynamicAction->opportunity['hangup']                = '挂起机会';
$lang->action->dynamicAction->opportunity['tracked']               = '跟踪机会';
$lang->action->dynamicAction->opportunity['activated']             = '激活机会';
$lang->action->dynamicAction->opportunity['importfromlib']         = '从机会库中导入';

$lang->action->dynamicAction->auditplan['opened']   = '创建质量保证计划';
$lang->action->dynamicAction->auditplan['edited']   = '编辑质量保证计划';
$lang->action->dynamicAction->auditplan['deleted']  = '删除质量保证计划';
$lang->action->dynamicAction->auditplan['checked']  = '检查质量保证计划';
$lang->action->dynamicAction->auditplan['assigned'] = '指派质量保证计划';

$lang->action->dynamicAction->nc['opened']    = '创建不符合项';
$lang->action->dynamicAction->nc['deleted']   = '删除不符合项';
$lang->action->dynamicAction->nc['eidted']    = '编辑不符合项';
$lang->action->dynamicAction->nc['activated'] = '激活不符合项';

$lang->action->dynamicAction->pssp['opened']  = '过程裁剪';

$lang->action->dynamicAction->doc['import2practicelib']  = '导入最佳实践库';
$lang->action->dynamicAction->doc['import2componentlib'] = '导入组件库';
$lang->action->dynamicAction->doc['approved']            = '审批';
$lang->action->dynamicAction->doc['removed']             = '移除';
$lang->action->dynamicAction->doc['assigned']            = '指派给';

$lang->action->dynamicAction->assetlib['opened'] = '创建库';
$lang->action->dynamicAction->assetlib['edited'] = '编辑库';

if(!helper::hasFeature('meeting'))
{
    unset($lang->action->dynamicAction->meetingroom);
    unset($lang->action->dynamicAction->meeting);
}
if(!helper::hasFeature('process')) unset($lang->action->dynamicAction->pssp);
if(!helper::hasFeature('auditplan'))
{
    unset($lang->action->dynamicAction->auditplan);
    unset($lang->action->dynamicAction->nc);
}
if(!helper::hasFeature('opportunity')) unset($lang->action->dynamicAction->opportunity);
if(!helper::hasFeature('issue'))       unset($lang->action->dynamicAction->issue);
if(!helper::hasFeature('risk'))        unset($lang->action->dynamicAction->risk);
if(!helper::hasFeature('storylib'))
{
    unset($lang->action->dynamicAction->story['import2storylib']);
    unset($lang->action->dynamicAction->story['importfromlib']);
}
if(!helper::hasFeature('issuelib'))
{
    unset($lang->action->dynamicAction->issue['import2issuelib']);
    unset($lang->action->dynamicAction->issue['importfromlib']);
}
if(!helper::hasFeature('risklib'))
{
    unset($lang->action->dynamicAction->risk['import2risklib']);
    unset($lang->action->dynamicAction->risk['importfromlib']);
}
if(!helper::hasFeature('opportunitylib'))
{
    unset($lang->action->dynamicAction->opportunity['import2opportunitylib']);
    unset($lang->action->dynamicAction->opportunity['importfromlib']);
}
if(!helper::hasFeature('practicelib'))    unset($lang->action->dynamicAction->doc['import2practicelib']);
if(!helper::hasFeature('componentlib'))   unset($lang->action->dynamicAction->doc['import2componentlib']);

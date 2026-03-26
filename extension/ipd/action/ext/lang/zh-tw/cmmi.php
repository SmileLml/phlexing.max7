<?php
$lang->action->desc->createrequirements    = '$date, 由 <strong>$actor</strong> 分解' . $lang->SRCommon . '<strong>$extra</strong>。' . "\n";
$lang->action->desc->linked2process        = '$date 由 <strong>$actor</strong> 關聯。';
$lang->action->desc->linked2activity       = '$date 由 <strong>$actor</strong> 關聯。';
$lang->action->desc->linked2output         = '$date 由 <strong>$actor</strong> 關聯。';
$lang->action->desc->checked               = '$date 由 <strong>$actor</strong> 進行檢查。';
$lang->action->desc->communicate           = '$date 由 <strong>$actor</strong> 進行添加溝通記錄。';
$lang->action->desc->designconfirmed       = '$date, 由 <strong>$actor</strong> 確認設計變更，最新版本為<strong>#$extra</strong>。' . "\n";
$lang->action->desc->issueconfirmed        = '$date, 由 <strong>$actor</strong> 確認。' . "\n";
$lang->action->desc->minuted               = '$date, 由 <strong>$actor</strong> 記錄。' . "\n";
$lang->action->desc->import2storylib       = '$date, 由 <strong>$actor</strong> 導入。' . "\n";
$lang->action->desc->import2issuelib       = '$date, 由 <strong>$actor</strong> 導入。' . "\n";
$lang->action->desc->import2risklib        = '$date, 由 <strong>$actor</strong> 導入。' . "\n";
$lang->action->desc->import2opportunitylib = '$date, 由 <strong>$actor</strong> 導入。' . "\n";
$lang->action->desc->import2practicelib    = '$date, 由 <strong>$actor</strong> 導入。' . "\n";
$lang->action->desc->import2componentlib   = '$date, 由 <strong>$actor</strong> 導入。' . "\n";
$lang->action->desc->removed               = '$date, 由 <strong>$actor</strong> 移除。' . "\n";
$lang->action->desc->reverted              = '$date, 由 <strong>$actor</strong> 回退。' . "\n";
$lang->action->desc->forwarded             = '$date, 由 <strong>$actor</strong> 轉交。' . "\n";
$lang->action->desc->addnode               = '$date, 由 <strong>$actor</strong> 加簽。' . "\n";
$lang->action->desc->managedeliverable     = '$date, 由 <strong>$actor</strong> 維護交付物。' . "\n";
$lang->action->desc->changeDeliverable     = '變更了 <strong><i>%s</i></strong> 交付物，舊值為"%s"，新值為"%s"。<br />' . "\n";
$lang->action->desc->addDeliverable        = '新增了 <strong><i>%s</i></strong> 交付物，新值為"%s"。<br />' . "\n";
$lang->action->desc->removeDeliverable     = '刪除了 <strong><i>%s</i></strong> 交付物，舊值為"%s"。<br />' . "\n";

$lang->action->approve = new stdclass();
$lang->action->approve->pass   = '$date, 由 <strong>$actor</strong> 審批。' . '審批結果為<strong>通過</strong>。' ."\n";
$lang->action->approve->reject = '$date, 由 <strong>$actor</strong> 審批。' . '審批結果為<strong>拒絶</strong>。' ."\n";

$lang->action->label->toaudit                  = '提交審計';
$lang->action->label->issueconfirmed           = '確認了';
$lang->action->label->checked                  = '檢查了';
$lang->action->label->editbasic                = '編輯了';
$lang->action->label->minuted                  = '記錄了';
$lang->action->label->import2storylib          = "在需求庫中導入了";
$lang->action->label->import2issuelib          = "在問題庫中導入了";
$lang->action->label->import2risklib           = "在風險庫中導入了";
$lang->action->label->import2opportunitylib    = "在機會庫中導入了";
$lang->action->label->import2practicelib       = "在最佳實踐庫中導入了";
$lang->action->label->import2componentlib      = "在組件庫中導入了";
$lang->action->label->importfromstorylib       = '從需求庫導入';
$lang->action->label->importfromissuelib       = '從問題庫導入';
$lang->action->label->importfromrisklib        = '從風險庫導入';
$lang->action->label->importfromopportunitylib = '從機會庫導入';
$lang->action->label->approved                 = '審批了';
$lang->action->label->removed                  = '移除了';
$lang->action->label->recall                   = '撤回了';
$lang->action->label->audited                  = '審計了';
$lang->action->label->createbasic              = '創建了';
$lang->action->label->submit                   = '提交了';
$lang->action->label->designconfirmed          = '確認了設計';
$lang->action->label->reverted                 = '回退了';
$lang->action->label->forwarded                = '轉交了';
$lang->action->label->addnode                  = '加簽了';
$lang->action->label->enabled                  = '啟用了';
$lang->action->label->disabled                 = '停用了';
$lang->action->label->managedeliverable        = '維護了交付物';

$lang->action->objectTypes['projecttemplate'] = '項目模板';
$lang->action->objectTypes['review']          = '評審';
$lang->action->objectTypes['waterfall']       = '瀑布' . $lang->projectCommon . '審批';
$lang->action->objectTypes['approval']        = '審批流';
$lang->action->objectTypes['approvalflow']    = '審批流';
$lang->action->objectTypes['opportunity']     = '機會';
$lang->action->objectTypes['trainplan']       = '培訓計劃';
$lang->action->objectTypes['meeting']         = '會議';
$lang->action->objectTypes['meetingroom']     = '會議室';
$lang->action->objectTypes['gapanalysis']     = '能力差距分析';
$lang->action->objectTypes['auditcl']         = 'QA檢查項';
$lang->action->objectTypes['auditplan']       = '質量保證計劃';
$lang->action->objectTypes['auditresult']     = '檢查結果';
$lang->action->objectTypes['nc']              = '不符合項';
$lang->action->objectTypes['measurement']     = '度量';
$lang->action->objectTypes['cmcl']            = '配置清單';
$lang->action->objectTypes['cm']              = '基線';
$lang->action->objectTypes['reviewissue']     = '評審問題';
$lang->action->objectTypes['researchplan']    = '調研計劃';
$lang->action->objectTypes['researchreport']  = '調研報告';
$lang->action->objectTypes['assetlib']        = '資產庫';
$lang->action->objectTypes['pssp']            = '過程裁剪';
$lang->action->objectTypes['reviewcl']        = '檢查項';
$lang->action->objectTypes['activity']        = '活動';
$lang->action->objectTypes['zoutput']         = '文檔';
$lang->action->objectTypes['process']         = '過程';
$lang->action->objectTypes['basicmeas']       = '度量定義';

$lang->action->label->opportunity         = '機會|opportunity|view|opportunityID=%s';
$lang->action->label->trainplan           = '培訓計劃|trainplan|view|trainplanID=%s';
$lang->action->label->gapanalysis         = '能力差距分析|gapanalysis|view|gapanalysisID=%s';
$lang->action->label->auditcl             = 'QA檢查項|auditcl|view|auditclID=%s';
$lang->action->label->review              = '評審|review|view|reviewID=%s';
$lang->action->label->reviewissue         = '評審問題|reviewissue|view|reviewissueID=%s';
$lang->action->label->researchplan        = '調研計劃|researchplan|view|planID=%s';
$lang->action->label->researchreport      = '調研報告|researchreport|view|reportID=%s';
$lang->action->label->meeting             = '會議|meeting|view|meetingID=%s';
$lang->action->label->meetingroom         = '會議室|meetingroom|view|roomID=%s';
$lang->action->label->storylib            = '需求庫|assetlib|story|libID=%s';
$lang->action->label->issuelib            = '問題庫|assetlib|issue|libID=%s';
$lang->action->label->risklib             = '風險庫|assetlib|risk|libID=%s';
$lang->action->label->opportunitylib      = '機會庫|assetlib|opportunity|libID=%s';
$lang->action->label->practicelib         = '最佳實踐庫|assetlib|practice|libID=%s';
$lang->action->label->componentlib        = '組件庫|assetlib|component|libID=%s';
$lang->action->label->storyassetlib       = '需求庫|assetlib|storyLibView|libID=%s';
$lang->action->label->issueassetlib       = '問題庫|assetlib|issueLibView|libID=%s';
$lang->action->label->riskassetlib        = '風險庫|assetlib|riskLibView|libID=%s';
$lang->action->label->opportunityassetlib = '機會庫|assetlib|opportunityLibView|libID=%s';
$lang->action->label->practiceassetlib    = '最佳實踐庫|assetlib|practiceLibView|libID=%s';
$lang->action->label->componentassetlib   = '組件庫|assetlib|componentLibView|libID=%s';
$lang->action->label->nc                  = '不符合項|nc|view|ncID=%s';
$lang->action->label->measurement         = '度量|measurement|setSQL|ID=%s';
$lang->action->label->sqlview             = '視圖|sqlbuilder|browsesqlview|';
$lang->action->label->process             = '過程|process|view|processID=%s';
$lang->action->label->activity            = '活動|activity|view|activityID=%s';
$lang->action->label->zoutput             = '文檔|zoutput|view|zoutputID=%s';

$lang->action->dynamicAction->researchplan['opened']  = '創建調研計劃';
$lang->action->dynamicAction->researchplan['edited']  = '編輯調研計劃';
$lang->action->dynamicAction->researchplan['deleted'] = '刪除調研計劃';

$lang->action->dynamicAction->researchreport['opened']  = '創建調研報告';
$lang->action->dynamicAction->researchreport['edited']  = '編輯調研報告';
$lang->action->dynamicAction->researchreport['deleted'] = '刪除調研報告';

$lang->action->dynamicAction->meetingroom['opened']  = '創建會議室';
$lang->action->dynamicAction->meetingroom['edited']  = '編輯會議室';
$lang->action->dynamicAction->meetingroom['deleted'] = '刪除會議室';

$lang->action->dynamicAction->meeting['opened']  = '創建會議';
$lang->action->dynamicAction->meeting['edited']  = '編輯會議';
$lang->action->dynamicAction->meeting['deleted'] = '刪除會議';
$lang->action->dynamicAction->meeting['minuted'] = '記錄會議';

$lang->action->dynamicAction->reviewissue['deleted']  = '刪除評審問題';

$lang->action->dynamicAction->story['import2storylib'] = '導入需求庫';
$lang->action->dynamicAction->story['approved']        = '審批需求';
$lang->action->dynamicAction->story['removed']         = '移除需求';
$lang->action->dynamicAction->story['importfromlib']   = '從需求庫中導入';

$lang->action->dynamicAction->issue['import2issuelib'] = '導入問題庫';
$lang->action->dynamicAction->issue['opened']          = '創建問題';
$lang->action->dynamicAction->issue['approved']        = '審批問題';
$lang->action->dynamicAction->issue['edited']          = '編輯問題';
$lang->action->dynamicAction->issue['deleted']         = '刪除問題';
$lang->action->dynamicAction->issue['removed']         = '移除問題';
$lang->action->dynamicAction->issue['importfromlib']   = '從問題庫中導入';
$lang->action->dynamicAction->issue['minuted']         = '記錄問題日誌';
$lang->action->dynamicAction->issue['closed']          = '關閉問題';
$lang->action->dynamicAction->issue['canceled']        = '取消問題';
$lang->action->dynamicAction->issue['assigned']        = '指派問題';
$lang->action->dynamicAction->issue['resolved']        = '解決問題';
$lang->action->dynamicAction->issue['activated']       = '激活問題';

$lang->action->dynamicAction->risk['import2risklib'] = '導入風險庫';
$lang->action->dynamicAction->risk['opened']         = '創建風險';
$lang->action->dynamicAction->risk['edited']         = '編輯風險';
$lang->action->dynamicAction->risk['closed']         = '關閉風險';
$lang->action->dynamicAction->risk['deleted']        = '刪除風險';
$lang->action->dynamicAction->risk['activated']      = '激活風險';
$lang->action->dynamicAction->risk['canceled']       = '取消風險';
$lang->action->dynamicAction->risk['assigned']       = '指派風險';
$lang->action->dynamicAction->risk['hangup']         = '掛起風險';
$lang->action->dynamicAction->risk['tracked']        = '跟蹤風險';
$lang->action->dynamicAction->risk['minuted']        = '記錄風險日誌';
$lang->action->dynamicAction->risk['importfromlib']  = '從風險庫中導入';

$lang->action->dynamicAction->opportunity['import2opportunitylib'] = '導入機會庫';
$lang->action->dynamicAction->opportunity['opened']                = '創建機會';
$lang->action->dynamicAction->opportunity['edited']                = '編輯機會';
$lang->action->dynamicAction->opportunity['closed']                = '關閉機會';
$lang->action->dynamicAction->opportunity['deleted']               = '刪除機會';
$lang->action->dynamicAction->opportunity['canceled']              = '取消機會';
$lang->action->dynamicAction->opportunity['assigned']              = '指派機會';
$lang->action->dynamicAction->opportunity['hangup']                = '掛起機會';
$lang->action->dynamicAction->opportunity['tracked']               = '跟蹤機會';
$lang->action->dynamicAction->opportunity['activated']             = '激活機會';
$lang->action->dynamicAction->opportunity['importfromlib']         = '從機會庫中導入';

$lang->action->dynamicAction->auditplan['opened']   = '創建質量保證計劃';
$lang->action->dynamicAction->auditplan['edited']   = '編輯質量保證計劃';
$lang->action->dynamicAction->auditplan['deleted']  = '刪除質量保證計劃';
$lang->action->dynamicAction->auditplan['checked']  = '檢查質量保證計劃';
$lang->action->dynamicAction->auditplan['assigned'] = '指派質量保證計劃';

$lang->action->dynamicAction->nc['opened']    = '創建不符合項';
$lang->action->dynamicAction->nc['deleted']   = '刪除不符合項';
$lang->action->dynamicAction->nc['eidted']    = '編輯不符合項';
$lang->action->dynamicAction->nc['activated'] = '激活不符合項';

$lang->action->dynamicAction->pssp['opened']  = '過程裁剪';

$lang->action->dynamicAction->doc['import2practicelib']  = '導入最佳實踐庫';
$lang->action->dynamicAction->doc['import2componentlib'] = '導入組件庫';
$lang->action->dynamicAction->doc['approved']            = '審批';
$lang->action->dynamicAction->doc['removed']             = '移除';
$lang->action->dynamicAction->doc['assigned']            = '指派給';

$lang->action->dynamicAction->assetlib['opened'] = '創建庫';
$lang->action->dynamicAction->assetlib['edited'] = '編輯庫';

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

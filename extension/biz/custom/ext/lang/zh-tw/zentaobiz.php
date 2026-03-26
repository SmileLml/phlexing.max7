<?php
$lang->custom->libreOffice       = 'Office轉換設置';
$lang->custom->libreOfficeTurnon = '功能開關';
$lang->custom->type              = '類型';
$lang->custom->libreOfficePath   = 'soffice路徑';
$lang->custom->collaboraPath     = 'Collabora路徑';

$lang->custom->errorSofficePath   = 'soffice檔案不存在';
$lang->custom->errorRunSoffice    = "程序運行失敗。錯誤信息：%s";
$lang->custom->errorRunCollabora  = "連接Collabora失敗，請確認Collabora是否配置正確，或者網絡是否能成功訪問。";
$lang->custom->cannotUseCollabora = "要使用Collabora，必須配置禪道系統為靜態訪問。";

$lang->custom->turnonList[1] = '開啟';
$lang->custom->turnonList[0] = '關閉';

$lang->custom->typeList['libreoffice'] = 'LibreOffice';
$lang->custom->typeList['collabora']   = 'Collabora Online';

$lang->custom->sofficePlaceholder   = '填寫LibreOffice中的soffice檔案路徑。如 /opt/libreoffice/program/soffice';
$lang->custom->collaboraPlaceholder = '填寫Collabora的訪問URL，如 https://127.0.0.1:9980';

$lang->custom->feedback = new stdclass();
$lang->custom->feedback->fields['required']         = $lang->custom->required;
$lang->custom->feedback->fields['review']           = '評審流程';
$lang->custom->feedback->fields['closedReasonList'] = '關閉原因';
$lang->custom->feedback->fields['typeList']         = '反饋類型';
$lang->custom->feedback->fields['priList']          = '優先順序';

$lang->custom->ticket = new stdclass();
$lang->custom->ticket->fields['required']         = $lang->custom->required;
$lang->custom->ticket->fields['priList']          = '優先順序';
$lang->custom->ticket->fields['typeList']         = '工單類型';
$lang->custom->ticket->fields['closedReasonList'] = '關閉原因';

$lang->custom->browseRelation    = "關聯關係列表";
$lang->custom->createRelation    = "新增關聯關係";
$lang->custom->editRelation      = "編輯關聯關係";
$lang->custom->deleteRelation    = "刪除關聯關係";
$lang->custom->relativeRelation  = "對應關係";
$lang->custom->relationTip       = '用戶可以對需求、任務、Bug、用例、文檔、設計、問題、風險、提交、反饋、工單，以及工作流對象配置關聯關係。“關聯關係”與“對應關係”是雙向的，例如A依賴B，則B被依賴A。';
$lang->custom->hasRelationTip    = '"%s"關係在系統內已存在，是否仍要保存？';
$lang->custom->relation          = '關聯關係';
$lang->custom->relateObject      = '關聯對象';
$lang->custom->removeObjects     = '解除關聯關係';
$lang->custom->removeObjectTip   = '您確定解除該關聯關係嗎？';
$lang->custom->deleteRelationTip = '系統內已有對象配置了此關係，無法刪除。';
$lang->custom->defaultRelation   = '系統內置的關係跟隨用戶操作流程記錄，此處不能解除關聯。';
$lang->custom->relationGraph     = '關係圖譜';

$lang->custom->relationList = array();
$lang->custom->relationList['transferredto']   = '轉化為';
$lang->custom->relationList['transferredfrom'] = '轉化于';
$lang->custom->relationList['twin']            = '孿生';
$lang->custom->relationList['subdivideinto']   = '細分為';
$lang->custom->relationList['subdividefrom']   = '細分于';
$lang->custom->relationList['generated']       = '產生了';
$lang->custom->relationList['derivedfrom']     = '產生於';
$lang->custom->relationList['completedin']     = '被實現';
$lang->custom->relationList['completedfrom']   = '實現了';
$lang->custom->relationList['interrated']      = '關聯到';

$lang->custom->setCharterInfo   = '自定義立項配置';
$lang->custom->resetCharterInfo = '恢復預設立項配置';

$lang->custom->charter = new stdclass();
$lang->custom->charter->level            = '項目等級';
$lang->custom->charter->type             = '規劃方式';
$lang->custom->charter->projectApproval  = '立項資料';
$lang->custom->charter->completeApproval = '結項資料';
$lang->custom->charter->cancelApproval   = '取消立項資料';

$lang->custom->charter->tips = new stdclass();
$lang->custom->charter->tips->sameLevel = '已存在相同的項目等級。';
$lang->custom->charter->tips->leastOne  = '請設置資料。';

$lang->custom->charterFiles = array();
$lang->custom->charterFiles['1'] = array('key' => '1', 'type' => 'plan', 'level' => '1', 'projectApproval'  => array(array('index' => 'BP', 'name' => '業務計劃書'), array('index' => 'charter', 'name' => '項目任務書'), array('index' => 'other', 'name' => '其他')), 'completeApproval' => array(array('index' => 'summary', 'name' => '項目總結報告'), array('index' => 'finance', 'name' => '財務報告'), array('index' => 'assess', 'name' => '評估報告'), array('index' => 'other', 'name' => '其他')), 'cancelApproval' => array(array('index' => 'reason', 'name' => '取消原因分析'), array('index' => 'finance', 'name' => '財務報告'), array('index' => 'assess', 'name' => '風險和影響評估'), array('index' => 'other', 'name' => '其他')));
$lang->custom->charterFiles['2'] = array('key' => '2', 'type' => 'plan', 'level' => '2', 'projectApproval'  => array(array('index' => 'BP', 'name' => '業務計劃書'), array('index' => 'charter', 'name' => '項目任務書'), array('index' => 'other', 'name' => '其他')), 'completeApproval' => array(array('index' => 'summary', 'name' => '項目總結報告'), array('index' => 'finance', 'name' => '財務報告'), array('index' => 'assess', 'name' => '評估報告'), array('index' => 'other', 'name' => '其他')), 'cancelApproval' => array(array('index' => 'reason', 'name' => '取消原因分析'), array('index' => 'finance', 'name' => '財務報告'), array('index' => 'assess', 'name' => '風險和影響評估'), array('index' => 'other', 'name' => '其他')));
$lang->custom->charterFiles['3'] = array('key' => '3', 'type' => 'plan', 'level' => '3', 'projectApproval'  => array(array('index' => 'BP', 'name' => '業務計劃書'), array('index' => 'charter', 'name' => '項目任務書'), array('index' => 'other', 'name' => '其他')), 'completeApproval' => array(array('index' => 'summary', 'name' => '項目總結報告'), array('index' => 'finance', 'name' => '財務報告'), array('index' => 'other', 'name' => '其他')), 'cancelApproval' => array(array('index' => 'reason', 'name' => '取消原因分析'), array('index' => 'finance', 'name' => '財務報告'), array('index' => 'other', 'name' => '其他')));
$lang->custom->charterFiles['4'] = array('key' => '4', 'type' => 'plan', 'level' => '4', 'projectApproval'  => array(array('index' => 'BP', 'name' => '業務計劃書'), array('index' => 'charter', 'name' => '項目任務書'), array('index' => 'other', 'name' => '其他')), 'completeApproval' => array(array('index' => 'summary', 'name' => '項目總結報告'), array('index' => 'finance', 'name' => '財務報告'), array('index' => 'other', 'name' => '其他')), 'cancelApproval' => array(array('index' => 'reason', 'name' => '取消原因分析'), array('index' => 'finance', 'name' => '財務報告'), array('index' => 'other', 'name' => '其他')));

$lang->custom->approvalflow = new stdclass();
$lang->custom->approvalflow->fields['browse'] = '審批流';
$lang->custom->approvalflow->fields['role']   = '審批角色';


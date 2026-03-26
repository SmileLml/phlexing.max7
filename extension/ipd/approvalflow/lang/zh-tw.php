<?php
$lang->approvalflow->browse        = '流程列表';
$lang->approvalflow->create        = '創建審批流';
$lang->approvalflow->edit          = '編輯審批流';
$lang->approvalflow->view          = '審批流詳情';
$lang->approvalflow->delete        = '刪除審批流';
$lang->approvalflow->design        = '設計審批流';
$lang->approvalflow->roleList      = '審批角色';
$lang->approvalflow->createRole    = '創建角色';
$lang->approvalflow->editRole      = '編輯角色';
$lang->approvalflow->deleteRole    = '刪除角色';

$lang->approvalflow->common        = '審批流';
$lang->approvalflow->id            = 'ID';
$lang->approvalflow->name          = '名稱';
$lang->approvalflow->createdBy     = '創建人';
$lang->approvalflow->createdDate   = '創建日期';
$lang->approvalflow->noFlow        = '當前沒有審批流';
$lang->approvalflow->title         = '標題';
$lang->approvalflow->reviewer      = '審批人';
$lang->approvalflow->workflow      = '綁定工作流';
$lang->approvalflow->ccer          = '抄送人';
$lang->approvalflow->condition     = '條件分支';
$lang->approvalflow->parallel      = '並行分支';
$lang->approvalflow->priv          = '操作權限';
$lang->approvalflow->approval      = '審批流程';
$lang->approvalflow->desc          = '描述';
$lang->approvalflow->basicInfo     = '基本信息';
$lang->approvalflow->confirmDelete = '您確認要刪除嗎？';
$lang->approvalflow->setNode       = '節點設置';
$lang->approvalflow->select        = '選擇';
$lang->approvalflow->needAll       = '需所有人完成評審';
$lang->approvalflow->percent       = '百分比';

$lang->approvalflow->nameList = array();
$lang->approvalflow->nameList['stage']  = '階段審批';

$lang->approvalflow->nodeTypeList = array();
$lang->approvalflow->nodeTypeList['branch']    = '分支';
$lang->approvalflow->nodeTypeList['condition'] = '條件';
$lang->approvalflow->nodeTypeList['default']   = '預設';
$lang->approvalflow->nodeTypeList['other']     = '其他';
$lang->approvalflow->nodeTypeList['approval']  = '審批';
$lang->approvalflow->nodeTypeList['cc']        = '抄送';
$lang->approvalflow->nodeTypeList['start']     = '發起';
$lang->approvalflow->nodeTypeList['end']       = '結束';

$lang->approvalflow->userTypeList = array();
$lang->approvalflow->userTypeList['cc']        = '抄送人';
$lang->approvalflow->userTypeList['submitter'] = '發起人';
$lang->approvalflow->userTypeList['reviewer']  = '審批人';

$lang->approvalflow->noticeTypeList = array();
$lang->approvalflow->noticeTypeList['setReviewer']     = '設置審批人';
$lang->approvalflow->noticeTypeList['setCondition']    = '設置條件';
$lang->approvalflow->noticeTypeList['addCondition']    = '添加條件分支';
$lang->approvalflow->noticeTypeList['addParallel']     = '添加並行分支';
$lang->approvalflow->noticeTypeList['addCond']         = '添加條件';
$lang->approvalflow->noticeTypeList['addReviewer']     = '添加審批人';
$lang->approvalflow->noticeTypeList['addCC']           = '添加抄送人';
$lang->approvalflow->noticeTypeList['setCC']           = '設置抄送人';
$lang->approvalflow->noticeTypeList['setNode']         = '設置節點';
$lang->approvalflow->noticeTypeList['defaultBranch']   = '所有條件都會執行此流程';
$lang->approvalflow->noticeTypeList['otherBranch']     = '其他條件進入此流程';
$lang->approvalflow->noticeTypeList['conditionOr']     = '不設置條件或者滿足其中一個條件即可執行';
$lang->approvalflow->noticeTypeList['when']            = '當';
$lang->approvalflow->noticeTypeList['type']            = '類型';
$lang->approvalflow->noticeTypeList['confirm']         = '確定';
$lang->approvalflow->noticeTypeList['reviewType']      = '審批設置';
$lang->approvalflow->noticeTypeList['ccType']          = '抄送設置';
$lang->approvalflow->noticeTypeList['reviewRange']     = '審批範圍';
$lang->approvalflow->noticeTypeList['ccRange']         = '抄送範圍';
$lang->approvalflow->noticeTypeList['range']           = '範圍';
$lang->approvalflow->noticeTypeList['value']           = '值';
$lang->approvalflow->noticeTypeList['set']             = '設置';
$lang->approvalflow->noticeTypeList['node']            = '節點';
$lang->approvalflow->noticeTypeList['approvalTitle']   = '審批標題';
$lang->approvalflow->noticeTypeList['ccTitle']         = '抄送標題';
$lang->approvalflow->noticeTypeList['multipleType']    = '多人審批時採用的審批方式';
$lang->approvalflow->noticeTypeList['multipleAnd']     = '會簽(所有人通過則審批通過)';
$lang->approvalflow->noticeTypeList['multiplePercent'] = '會簽(百分比通過則審批通過)';
$lang->approvalflow->noticeTypeList['multipleOr']      = '或簽(僅一人通過則審批通過)';
$lang->approvalflow->noticeTypeList['multipleSolicit'] = '徵詢意見(結果為通過)';
$lang->approvalflow->noticeTypeList['commentType']     = '審批通過時審批意見';
$lang->approvalflow->noticeTypeList['required']        = '必填';
$lang->approvalflow->noticeTypeList['noRequired']      = '不必填';
$lang->approvalflow->noticeTypeList['agentType']       = '當審批人為空時';
$lang->approvalflow->noticeTypeList['agentPass']       = '自動通過';
$lang->approvalflow->noticeTypeList['agentReject']     = '自動拒絶';
$lang->approvalflow->noticeTypeList['agentUser']       = '指定人員';
$lang->approvalflow->noticeTypeList['agentAdmin']      = '管理員';
$lang->approvalflow->noticeTypeList['selfType']        = '當審批人與發起人為同一人時';
$lang->approvalflow->noticeTypeList['selfReview']      = '發起人評審';
$lang->approvalflow->noticeTypeList['selfPass']        = '自動通過';
$lang->approvalflow->noticeTypeList['selfNext']        = '轉交直屬上級';
$lang->approvalflow->noticeTypeList['selfManager']     = '轉交部門主管';
$lang->approvalflow->noticeTypeList['deletedType']     = '當審批人已被刪除時';
$lang->approvalflow->noticeTypeList['autoPass']        = '自動通過';
$lang->approvalflow->noticeTypeList['autoReject']      = '自動拒絶';
$lang->approvalflow->noticeTypeList['setUser']         = '指定人員';
$lang->approvalflow->noticeTypeList['setSuperior']     = '轉交直屬上級';
$lang->approvalflow->noticeTypeList['setManager']      = '轉交部門主管';
$lang->approvalflow->noticeTypeList['setAdmin']        = '轉交管理員';

$lang->approvalflow->warningList = array();
$lang->approvalflow->warningList['needReview']     = '請保留最少一個審批節點';
$lang->approvalflow->warningList['save']           = '您的修改內容還沒有保存，您確定離開嗎？';
$lang->approvalflow->warningList['selectUser']     = '請選擇人員';
$lang->approvalflow->warningList['selectDept']     = '請選擇部門';
$lang->approvalflow->warningList['selectRole']     = '請選擇角色';
$lang->approvalflow->warningList['selectPosition'] = '請選擇職位';
$lang->approvalflow->warningList['needReviewer']   = '審批人不能為空';
$lang->approvalflow->warningList['needValue']      = '值不能為空';
$lang->approvalflow->warningList['oneSelect']      = '"發起人自選"和"由上一節點審批人指定"只能存在一個';
$lang->approvalflow->warningList['percent']        = '百分比必須在1-100之間，且為整數。';
$lang->approvalflow->warningList['workflow']       = '綁定工作流後，您可以使用其欄位配置審批流條件，且僅限于綁定的工作流下使用。';

$lang->approvalflow->userRangeList = array();
$lang->approvalflow->userRangeList['all']      = '不限';
$lang->approvalflow->userRangeList['role']     = '角色';
$lang->approvalflow->userRangeList['dept']     = '部門';
$lang->approvalflow->userRangeList['position'] = '職位';

$lang->approvalflow->reviewTypeList = array();
$lang->approvalflow->reviewTypeList['manual'] = '人工審批';
$lang->approvalflow->reviewTypeList['pass']   = '自動同意';
$lang->approvalflow->reviewTypeList['reject'] = '自動拒絶';

$lang->approvalflow->errorList = array();
$lang->approvalflow->errorList['needReivewer'] = '請填寫全部審批人';
$lang->approvalflow->errorList['needCcer']     = '請填寫全部抄送人';
$lang->approvalflow->errorList['hasWorkflow']  = '該審批流已綁定工作流，請到工作流-設置審批界面解綁，否則無法刪除';

$lang->approvalflow->reviewerTypeList = array();
$lang->approvalflow->reviewerTypeList['select']        = array('name' => '發起人自選',           'options' => 'userRange',      'tips' => '選擇範圍');
$lang->approvalflow->reviewerTypeList['self']          = array('name' => '發起人本人',           'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['appointee']     = array('name' => '指定人員',             'options' => 'users',          'tips' => '選擇人員');
$lang->approvalflow->reviewerTypeList['role']          = array('name' => '角色',                 'options' => 'roles',          'tips' => '選擇角色');
$lang->approvalflow->reviewerTypeList['position']      = array('name' => '職位',                 'options' => 'positions',      'tips' => '選擇職位');
$lang->approvalflow->reviewerTypeList['upLevel']       = array('name' => '部門負責人',           'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['superior']      = array('name' => '直屬上級',             'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['superiorList']  = array('name' => '連續多級上級',         'options' => 'superiorList',   'tips' => '審批終點');
$lang->approvalflow->reviewerTypeList['setByPrev']     = array('name' => '由上一節點審批人指定', 'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['productRole']   = array('name' => '產品角色',             'options' => 'productRoles',   'tips' => '選擇角色');
$lang->approvalflow->reviewerTypeList['projectRole']   = array('name' => '項目角色',             'options' => 'projectRoles',   'tips' => '選擇角色');
$lang->approvalflow->reviewerTypeList['executionRole'] = array('name' => '執行角色',             'options' => 'executionRoles', 'tips' => '選擇角色');

$lang->approvalflow->conditionTypeList = array();
$lang->approvalflow->conditionTypeList['submitUsers']     = '發起人姓名';
$lang->approvalflow->conditionTypeList['submitDepts']     = '發起人從屬部門';
$lang->approvalflow->conditionTypeList['submitRoles']     = '發起人所屬角色';
$lang->approvalflow->conditionTypeList['submitPositions'] = '發起人所屬職位';

$lang->approvalflow->superiorList[0] = '最高上級';
$lang->approvalflow->superiorList[2] = '2級上級';
$lang->approvalflow->superiorList[3] = '3級上級';
$lang->approvalflow->superiorList[4] = '4級上級';
$lang->approvalflow->superiorList[5] = '5級上級';

$lang->approvalflow->productRoleList['PO']       = '產品負責人';
$lang->approvalflow->productRoleList['QD']       = '測試負責人';
$lang->approvalflow->productRoleList['RD']       = '發佈負責人';
$lang->approvalflow->productRoleList['feedback'] = '反饋負責人';
$lang->approvalflow->productRoleList['ticket']   = '工單負責人';
$lang->approvalflow->productRoleList['reviewer'] = '需求評審人';

$lang->approvalflow->projectRoleList['PM']          = '項目負責人';
$lang->approvalflow->projectRoleList['stakeholder'] = '項目干係人';

$lang->approvalflow->executionRoleList['PO'] = '產品負責人';
$lang->approvalflow->executionRoleList['PM'] = '執行負責人';
$lang->approvalflow->executionRoleList['QD'] = '測試負責人';
$lang->approvalflow->executionRoleList['RD'] = '發佈負責人';

$lang->approvalflow->privList['forward']   = '轉交';
$lang->approvalflow->privList['revert']    = '回退';
$lang->approvalflow->privList['addnode']   = '加簽';
$lang->approvalflow->privList['withdrawn'] = '評審時發起人撤回';

$lang->approvalflow->required['yes'] = '審批人必填';
$lang->approvalflow->required['no']  = '審批人不必填';

$lang->approvalflow->emptyName       = '名稱不能為空！';
$lang->approvalflow->passOverPercent = '系統判斷通過人數占比達到%d%%，審批結果為通過';
$lang->approvalflow->failOverPercent = '系統判斷通過人數占比未達到%d%%，審批結果為不通過';

$lang->approvalflow->role = new stdclass();
$lang->approvalflow->role->create = '創建角色';
$lang->approvalflow->role->browse = '角色列表';
$lang->approvalflow->role->edit   = '編輯角色';
$lang->approvalflow->role->member = '角色成員';
$lang->approvalflow->role->delete = '刪除角色';

$lang->approvalflow->role->name   = '角色名稱';
$lang->approvalflow->role->code   = '角色代號';
$lang->approvalflow->role->desc   = '角色描述';

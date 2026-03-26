<?php
$lang->approval->common         = '審批';
$lang->approval->startNode      = '開始節點';
$lang->approval->node           = '審批節點';
$lang->approval->nodeName       = '節點名稱';
$lang->approval->addNodeTitle   = '加簽節點';
$lang->approval->reviewer       = '審批人';
$lang->approval->cc             = '抄送';
$lang->approval->ccer           = '抄送人';
$lang->approval->progress       = '審批進度';
$lang->approval->noResult       = '未評審';
$lang->approval->setReviewer    = '下一節點審批人';
$lang->approval->toNodeID       = '回退到節點';
$lang->approval->revertType     = '重新提交評審後';
$lang->approval->revertOpinion  = '回退意見';
$lang->approval->addNodeMethod  = '加簽方式';
$lang->approval->addNodeOpinion = '加簽說明';
$lang->approval->multipleType   = '多人審批方式';

$lang->approval->start          = '發起審批';
$lang->approval->end            = '審批結束';
$lang->approval->cancel         = '撤銷審批';
$lang->approval->revert         = '回退';
$lang->approval->addNode        = '加簽';
$lang->approval->forward        = '轉交';
$lang->approval->forwardTo      = '轉交人';
$lang->approval->forwardOpinion = '轉交說明';

$lang->approval->otherReviewer = '其他審批人：';
$lang->approval->otherCcer     = '其他抄送人：';

$lang->approval->revertOpinionRequired  = '回退意見不能為空！';
$lang->approval->forwardOpinionRequired = '轉交說明不能為空！';

$lang->approval->reviewDesc = new stdclass();
$lang->approval->reviewDesc->start      = '由 <strong>$actor</strong> %s申請 <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->pass       = '由 <strong>$actor</strong> 審批，結果為<span class="result pass">已通過</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->fail       = '由 <strong>$actor</strong> 審批，結果為<span class="result fail">不通過</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->doing      = '由 <strong>$actor</strong> 審批，正在<span class="result reviewing">審批中</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->reverted   = '由 <strong>$actor</strong> 回退到節點 <strong>$node</strong> <span class="text-muted">$date</span>。' . "\n";
$lang->approval->reviewDesc->wait       = '<strong>$actor</strong> <span class="text-muted">待審批</span>' . "\n";
$lang->approval->reviewDesc->cc         = '抄送給 <strong>$actor</strong>' . "\n";
$lang->approval->reviewDesc->forwardBy  = '（由 <strong>$actor</strong> 轉交）';
$lang->approval->reviewDesc->addPrev    = '由 <strong>$actor</strong> 前加簽，加簽<span class="text-muted">$reviewer</span>，<span class="text-muted">$date</span>';
$lang->approval->reviewDesc->addNext    = '由 <strong>$actor</strong> 後加簽，加簽<span class="text-muted">$reviewer</span>，<span class="text-muted">$date</span>';
$lang->approval->reviewDesc->addCurrent = '由 <strong>$actor</strong> 增加審批人，加簽<span class="text-muted">$reviewer</span>，<span class="text-muted">$date</span>';

$lang->approval->reviewDesc->autoReject        = '已設置自動拒絶' . "\n";
$lang->approval->reviewDesc->autoPass          = '已設置自動同意' . "\n";
$lang->approval->reviewDesc->autoRejected      = '已經自動拒絶' . "\n";
$lang->approval->reviewDesc->autoPassed        = '已經自動同意' . "\n";
$lang->approval->reviewDesc->pass4NoReviewer   = '未選定評審人，根據配置自動通過' . "\n";
$lang->approval->reviewDesc->reject4NoReviewer = '未選定評審人，根據配置自動拒絶' . "\n";
$lang->approval->reviewDesc->setReviewer       = '未選定評審人，等待上級節點設置' . "\n";

$lang->approval->notice = new stdclass();
$lang->approval->notice->orSign       = '(一人通過即可)';
$lang->approval->notice->times        = '第%s次提交';
$lang->approval->notice->approvalTime = '已評審%s';
$lang->approval->notice->day          = '天';
$lang->approval->notice->hour         = '小時';

$lang->approval->currentResult = '當前節點審批結果';
$lang->approval->currentNode   = '當前節點';

$lang->approval->cannotOperate = '審批狀態已更新，請刷新頁面';

$lang->approval->revertTypeList = array();
$lang->approval->revertTypeList['order']  = '按審批流程順序審批';
$lang->approval->revertTypeList['revert'] = '直接從當前評審節點開始';

$lang->approval->addNodeMethodList = array();
$lang->approval->addNodeMethodList['prev']    = '前加審批節點';
$lang->approval->addNodeMethodList['next']    = '後加審批節點';
$lang->approval->addNodeMethodList['current'] = '在當前審批節點加人';

$lang->approval->statusList = array();
$lang->approval->statusList['wait']  = '未開始';
$lang->approval->statusList['doing'] = '進行中';
$lang->approval->statusList['done']  = '已完成';

$lang->approval->resultList = array();
$lang->approval->resultList['pass'] = '通過';
$lang->approval->resultList['fail'] = '拒絶';

$lang->approval->mailResultList = array();
$lang->approval->mailResultList['pass']   = '通過';
$lang->approval->mailResultList['fail']   = '拒絶';
$lang->approval->mailResultList['ignore'] = '撤回';

$lang->approval->nodeList = array();
$lang->approval->nodeList['cc']     = '抄送';
$lang->approval->nodeList['review'] = '審批';
$lang->approval->nodeList['doing']  = '審批中';

$lang->approval->mailContent = array();
$lang->approval->mailContent['mail'] = '
<p>尊敬的用戶，您好！</p>
<p>[<a href="%s">%s</a>] 當前需要您進行審批，請前往禪道進行審批。</p>
';
$lang->approval->mailContent['mailto'] = '
<p>尊敬的用戶，您好！</p>
<p>[<a href="%s">%s</a>] 正在審批中，請知悉。</p>
';
$lang->approval->mailContent['result'] = '
<p>尊敬的用戶，您好！</p>
<p>[<a href="%s">%s</a>] 最終審批結果為<span class="%s"><strong>%s</strong></span>，請知悉。</p>
';

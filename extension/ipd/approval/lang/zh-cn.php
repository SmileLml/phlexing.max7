<?php
$lang->approval->common         = '审批';
$lang->approval->startNode      = '开始节点';
$lang->approval->node           = '审批节点';
$lang->approval->nodeName       = '节点名称';
$lang->approval->addNodeTitle   = '加签节点';
$lang->approval->reviewer       = '审批人';
$lang->approval->cc             = '抄送';
$lang->approval->ccer           = '抄送人';
$lang->approval->progress       = '审批进度';
$lang->approval->noResult       = '未评审';
$lang->approval->setReviewer    = '下一节点审批人';
$lang->approval->toNodeID       = '回退到节点';
$lang->approval->revertType     = '重新提交评审后';
$lang->approval->revertOpinion  = '回退意见';
$lang->approval->addNodeMethod  = '加签方式';
$lang->approval->addNodeOpinion = '加签说明';
$lang->approval->multipleType   = '多人审批方式';

$lang->approval->start          = '发起审批';
$lang->approval->end            = '审批结束';
$lang->approval->cancel         = '撤销审批';
$lang->approval->revert         = '回退';
$lang->approval->addNode        = '加签';
$lang->approval->forward        = '转交';
$lang->approval->forwardTo      = '转交人';
$lang->approval->forwardOpinion = '转交说明';

$lang->approval->otherReviewer = '其他审批人：';
$lang->approval->otherCcer     = '其他抄送人：';

$lang->approval->revertOpinionRequired  = '回退意见不能为空！';
$lang->approval->forwardOpinionRequired = '转交说明不能为空！';

$lang->approval->reviewDesc = new stdclass();
$lang->approval->reviewDesc->start      = '由 <strong>$actor</strong> %s申请 <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->pass       = '由 <strong>$actor</strong> 审批，结果为<span class="result pass">已通过</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->fail       = '由 <strong>$actor</strong> 审批，结果为<span class="result fail">不通过</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->doing      = '由 <strong>$actor</strong> 审批，正在<span class="result reviewing">审批中</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->reverted   = '由 <strong>$actor</strong> 回退到节点 <strong>$node</strong> <span class="text-muted">$date</span>。' . "\n";
$lang->approval->reviewDesc->wait       = '<strong>$actor</strong> <span class="text-muted">待审批</span>' . "\n";
$lang->approval->reviewDesc->cc         = '抄送给 <strong>$actor</strong>' . "\n";
$lang->approval->reviewDesc->forwardBy  = '（由 <strong>$actor</strong> 转交）';
$lang->approval->reviewDesc->addPrev    = '由 <strong>$actor</strong> 前加签，加签<span class="text-muted">$reviewer</span>，<span class="text-muted">$date</span>';
$lang->approval->reviewDesc->addNext    = '由 <strong>$actor</strong> 后加签，加签<span class="text-muted">$reviewer</span>，<span class="text-muted">$date</span>';
$lang->approval->reviewDesc->addCurrent = '由 <strong>$actor</strong> 增加审批人，加签<span class="text-muted">$reviewer</span>，<span class="text-muted">$date</span>';

$lang->approval->reviewDesc->autoReject        = '已设置自动拒绝' . "\n";
$lang->approval->reviewDesc->autoPass          = '已设置自动同意' . "\n";
$lang->approval->reviewDesc->autoRejected      = '已经自动拒绝' . "\n";
$lang->approval->reviewDesc->autoPassed        = '已经自动同意' . "\n";
$lang->approval->reviewDesc->pass4NoReviewer   = '未选定评审人，根据配置自动通过' . "\n";
$lang->approval->reviewDesc->reject4NoReviewer = '未选定评审人，根据配置自动拒绝' . "\n";
$lang->approval->reviewDesc->setReviewer       = '未选定评审人，等待上级节点设置' . "\n";

$lang->approval->notice = new stdclass();
$lang->approval->notice->orSign       = '(一人通过即可)';
$lang->approval->notice->times        = '第%s次提交';
$lang->approval->notice->approvalTime = '已评审%s';
$lang->approval->notice->day          = '天';
$lang->approval->notice->hour         = '小时';

$lang->approval->currentResult = '当前节点审批结果';
$lang->approval->currentNode   = '当前节点';

$lang->approval->cannotOperate = '审批状态已更新，请刷新页面';

$lang->approval->revertTypeList = array();
$lang->approval->revertTypeList['order']  = '按审批流程顺序审批';
$lang->approval->revertTypeList['revert'] = '直接从当前评审节点开始';

$lang->approval->addNodeMethodList = array();
$lang->approval->addNodeMethodList['prev']    = '前加审批节点';
$lang->approval->addNodeMethodList['next']    = '后加审批节点';
$lang->approval->addNodeMethodList['current'] = '在当前审批节点加人';

$lang->approval->statusList = array();
$lang->approval->statusList['wait']  = '未开始';
$lang->approval->statusList['doing'] = '进行中';
$lang->approval->statusList['done']  = '已完成';

$lang->approval->resultList = array();
$lang->approval->resultList['pass'] = '通过';
$lang->approval->resultList['fail'] = '拒绝';

$lang->approval->mailResultList = array();
$lang->approval->mailResultList['pass']   = '通过';
$lang->approval->mailResultList['fail']   = '拒绝';
$lang->approval->mailResultList['ignore'] = '撤回';

$lang->approval->nodeList = array();
$lang->approval->nodeList['cc']     = '抄送';
$lang->approval->nodeList['review'] = '审批';
$lang->approval->nodeList['doing']  = '审批中';

$lang->approval->mailContent = array();
$lang->approval->mailContent['mail'] = '
<p>尊敬的用户，您好！</p>
<p>[<a href="%s">%s</a>] 当前需要您进行审批，请前往禅道进行审批。</p>
';
$lang->approval->mailContent['mailto'] = '
<p>尊敬的用户，您好！</p>
<p>[<a href="%s">%s</a>] 正在审批中，请知悉。</p>
';
$lang->approval->mailContent['result'] = '
<p>尊敬的用户，您好！</p>
<p>[<a href="%s">%s</a>] 最终审批结果为<span class="%s"><strong>%s</strong></span>，请知悉。</p>
';

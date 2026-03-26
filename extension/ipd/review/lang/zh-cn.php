<?php
$lang->review->id                = '编号';
$lang->review->delete            = '删除';
$lang->review->deleted           = '已删除';
$lang->review->common            = '评审';
$lang->review->assess            = '评审';
$lang->review->record            = '评审记录';
$lang->review->explain           = '评审说明';
$lang->review->resultExplain     = '评审结论说明';
$lang->review->reviewResult      = '评审结果';
$lang->review->conclusion        = '评审结论（是否通过）';
$lang->review->recall            = '撤回';
$lang->review->files             = '附件';
$lang->review->start             = '发起';
$lang->review->finish            = '结束';
$lang->review->submit            = '提交评审';
$lang->review->toAudit           = '提交审计';
$lang->review->create            = '发起评审';
$lang->review->edit              = '编辑评审';
$lang->review->browse            = '浏览评审';
$lang->review->view              = '评审详情';
$lang->review->viewFlow          = '预览评审流程';
$lang->review->title             = '评审标题';
$lang->review->object            = '评审对象';
$lang->review->content           = '评审内容来源';
$lang->review->doclib            = '相关文档库';
$lang->review->template          = '相关模板';
$lang->review->doc               = '相关文档';
$lang->review->version           = '对象版本号';
$lang->review->reviewedBy        = '由谁评审';
$lang->review->reviewer          = '评审人员';
$lang->review->reviewReport      = '评审报告';
$lang->review->exportReport      = '导出评审报告';
$lang->review->reviewerCount     = '评审人数';
$lang->review->deadline          = '计划完成时间';
$lang->review->comment           = '备注';
$lang->review->createdBy         = '由谁创建';
$lang->review->createdDate       = '创建时间';
$lang->review->reviewedHours     = '评审时长（小时）';
$lang->review->area              = '评审地点';
$lang->review->audit             = '审计';
$lang->review->auditedBy         = '由谁审计';
$lang->review->objectScale       = '文档规模';
$lang->review->issueCount        = '缺陷总数';
$lang->review->issueRate         = '缺陷率';
$lang->review->issueFoundRate    = '缺陷发现率';
$lang->review->issues            = '发现的问题';
$lang->review->isIssue           = '是否缺陷';
$lang->review->result            = '评审节点及结果';
$lang->review->nodeDetail        = '节点详情';
$lang->review->status            = '状态';
$lang->review->opinion           = '修改意见';
$lang->review->finalOpinion      = '评审意见';
$lang->review->reviewcl          = '检查清单';
$lang->review->reviewedDate      = '评审时间';
$lang->review->consumed          = '消耗工时';
$lang->review->basicInfo         = '基本信息';
$lang->review->product           = '所属' . $lang->productCommon;
$lang->review->auditResult       = '审计结果';
$lang->review->auditedDate       = '审计时间';
$lang->review->auditOpinion      = '审计意见';
$lang->review->issueList         = '问题清单';
$lang->review->lastIssue         = '上次遗留问题';
$lang->review->fullScreen        = '全屏';
$lang->review->auditedByEmpty    = '由谁审计不能为空！';
$lang->review->exporting         = '正在导出...';
$lang->review->lastReviewedDate  = '最后评审时间';
$lang->review->lastAuditedDate   = '最后审计时间';
$lang->review->createBaseline    = '打基线';
$lang->review->opinionDate       = '建议解决时间';
$lang->review->setReviewer       = '下一节点审批人';
$lang->review->objectScaleTip    = "{$lang->review->objectScale}统计的是软件需求或用户需求规模点数";
$lang->review->issueCountTip     = "{$lang->review->issueCount}统计的是{$lang->review->common}不符合项的问题总数";
$lang->review->issueRateTip      = "{$lang->review->issueRate}={$lang->review->issueCount}/评审规模";
$lang->review->issueFoundRateTip = "{$lang->review->issueFoundRate}={$lang->review->issueCount}/{$lang->review->reviewedHours}";
$lang->review->untitled          = '未命名';

$lang->review->browseAction = '基线评审列表';

$lang->review->pageAllSummary = '本页共 %total% 个评审，待评审 %wait%，评审中 %reviewing%，评审通过 %pass%，审计中 %auditing%，完成 %done%。';
$lang->review->pageSummary    = '本页共 %s 个评审。';

$lang->object = new stdclass();
$lang->object->product = $lang->review->product;

$lang->review->report = new stdclass();
$lang->review->report->common = '评审报告';

$lang->review->reportCreatedBy  = '报告提交人';
$lang->review->reportApprovedBy = '报告审批人';

$lang->review->listCategory = '分类';
$lang->review->listTitle    = '检查内容';
$lang->review->listItem     = '检查项';
$lang->review->listResult   = '是否符合';

$lang->review->contentList['template'] = '系统模板生成';
$lang->review->contentList['doc']      = $lang->projectCommon . '文档';

$lang->review->noBook        = '暂无相关说明书，请到文档维护说明书';
$lang->review->stopSubmit    = '检查清单中存在不符合项';
$lang->review->confirmDelete = '您确定删除该评审吗？删除后该评审下的问题也会删除！';

$lang->review->statusList['draft']     = '草稿';
$lang->review->statusList['wait']      = '待评审';
$lang->review->statusList['reverting'] = '回退中';
$lang->review->statusList['reviewing'] = '评审中';
$lang->review->statusList['pass']      = '评审通过';
$lang->review->statusList['fail']      = '评审失败';
$lang->review->statusList['auditing']  = '审计中';
$lang->review->statusList['done']      = '完成';

$lang->review->resultList['pass'] = '通过';
$lang->review->resultList['fail'] = '不通过';

$lang->review->auditResultList['pass']    = '通过';
$lang->review->auditResultList['needfix'] = '修改后再次审计';
$lang->review->auditResultList['fail']    = '重新走评审流程';

$lang->review->resultLable['pass']    = 'success';
$lang->review->resultLable['fail']    = 'danger';
$lang->review->resultLable['needfix'] = 'info';

$lang->review->reviewResultList['pass']    = '通过';
$lang->review->reviewResultList['needfix'] = '修改后通过';
$lang->review->reviewResultList['fail']    = '不通过';

$lang->review->checkList['1'] = '符合';
$lang->review->checkList['0'] = '不符合';

$lang->review->resolvedList['1'] = '已解决';
$lang->review->resolvedList['0'] = '未解决';

$lang->review->featureBar['browse']['all']          = '全部';
$lang->review->featureBar['browse']['reviewing']    = '评审中';
$lang->review->featureBar['browse']['done']         = '已结束';
$lang->review->featureBar['browse']['wait']         = '待我评审';
$lang->review->featureBar['browse']['reviewedbyme'] = '由我评审';
$lang->review->featureBar['browse']['createdbyme']  = '由我发起';

$lang->review->resultExplainList['pass']    = "通过：工作成果合格，“无需修改”或者“需要轻微修改但不必再审核”。";
//$lang->review->resultExplainList['needFix'] = '修改后通过：有条件通过：工作成果基本合格，需要作少量的修改，之后通过验证即可。';
$lang->review->resultExplainList['fail']    = '不通过：工作成果不合格，需要作比较大的修改，之后必须重新对其评审。';

$lang->review->issue = new stdclass();
$lang->review->issue->id           = '序号';
$lang->review->issue->summary      = '缺陷分析及跟踪';
$lang->review->issue->desc         = '缺陷描述';
$lang->review->issue->analyse      = '缺陷分析';
$lang->review->issue->introAnalyse = '引入分析';
$lang->review->issue->resolvedBy   = '修改人';
$lang->review->issue->deadline     = '修改期限';
$lang->review->issue->resolvedDate = '修改完成时间';
$lang->review->issue->severity     = '严重程度';
$lang->review->issue->verifiedBy   = '验证人';
$lang->review->issue->status       = '状态';

$lang->review->action = new stdclass();
$lang->review->action->reviewed  = array('main' => '$date, 由 <strong>$actor</strong> 评审，结果为 <strong>$extra</strong>。', 'extra' => 'resultList');
$lang->review->action->submit    = array('main' => '$date, 由 <strong>$actor</strong> 提交评审。');
$lang->review->action->recall    = array('main' => '$date, 由 <strong>$actor</strong> 撤回评审。');
$lang->review->action->toaudit   = array('main' => '$date, 由 <strong>$actor</strong> 提交审计， 指派给 <strong>$extra</strong>。');
$lang->review->action->audited   = array('main' => '$date, 由 <strong>$actor</strong> 审计，结果为 <strong>$extra</strong>。', 'extra' => 'auditResultList');

$lang->reviewresult = new stdclass();
$lang->reviewresult->consumed    = '消耗工时';
$lang->reviewresult->createdDate = '审计时间';

$lang->review->selectApprovalText = '评审：第%s次';

$lang->review->cannotReview = '抱歉！您没有该条数据的审批权限。';

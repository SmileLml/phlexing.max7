<?php
$lang->create      = '新建';
$lang->saveSuccess = '保存成功';
$lang->month       = '月';
$lang->day         = '日';
$lang->detail      = '详情';
$lang->logout      = '签退';
$lang->minus       = ' - ';

$lang->oa = new stdclass();
$lang->oa->common = '办公';

$lang->confirmDelete = '您确定要执行删除操作吗？';
$lang->deleteing     = '删除中';

$lang->navIcons['oa'] = "<i class='icon icon-oa'></i>";
$lang->mainNav->oa    = "{$lang->navIcons['oa']} 办公|attend|personal|";

$lang->oa->menu = new stdclass();
$lang->oa->menu->attend   = array('link' => '考勤|attend|personal', 'subModule' => 'attend');
$lang->oa->menu->leave    = array('link' => '请假|leave|personal', 'alias' => 'browse', 'subModule' => 'leave');
$lang->oa->menu->makeup   = array('link' => '补班|makeup|personal', 'alias' => 'create,edit,view,browse', 'subModule' => 'makeup');
$lang->oa->menu->overtime = array('link' => '加班|overtime|personal', 'subModule' => 'overtime');
$lang->oa->menu->lieu     = array('link' => '调休|lieu|personal', 'subModule' => 'lieu');
$lang->oa->menu->review   = array('link' => '审批|my|review|type=all&orderBy=status');

$lang->oa->menuOrder[5]  = 'attend';
$lang->oa->menuOrder[10] = 'leave';
$lang->oa->menuOrder[15] = 'makeup';
$lang->oa->menuOrder[20] = 'overtime';
$lang->oa->menuOrder[25] = 'lieu';
$lang->oa->menuOrder[30] = 'review';

$lang->attend   = new stdclass();
$lang->leave    = new stdclass();
$lang->makeup   = new stdclass();
$lang->overtime = new stdclass();
$lang->lieu     = new stdclass();

$lang->officeapproval = new stdclass();
$lang->officesetting  = new stdclass();
$lang->datapermission = new stdclass();
$lang->officeexport   = new stdclass();

$lang->officeapproval->common = '办公审批';
$lang->officesetting->common  = '办公设置';
$lang->datapermission->common = '数据权限';
$lang->officeexport->common   = '办公导出';

$lang->navGroup->officeapproval = 'oa';
$lang->navGroup->officesetting  = 'oa';
$lang->navGroup->datapermission = 'oa';
$lang->navGroup->officeexport   = 'oa';

$lang->navGroup->attend   = 'oa';
$lang->navGroup->leave    = 'oa';
$lang->navGroup->makeup   = 'oa';
$lang->navGroup->overtime = 'oa';
$lang->navGroup->lieu     = 'oa';

$lang->attend->featurebar = new stdclass();
$lang->attend->featurebar->personal     = '我的考勤|attend|personal|';
$lang->attend->featurebar->department   = '部门考勤|attend|department|';
$lang->attend->featurebar->company      = '公司考勤|attend|company|';
$lang->attend->featurebar->detail       = '考勤明细|attend|detail|';
$lang->attend->featurebar->browsereview = '补录审核|attend|browsereview|';
$lang->attend->featurebar->stat         = '统计|attend|stat|';
$lang->attend->featurebar->settings     = array('link' => '设置|attend|settings|');

$lang->leave->featurebar = new stdclass();
$lang->leave->featurebar->personal     = '我的请假|leave|personal|';
$lang->leave->featurebar->browseReview = '我的审核|leave|browsereview|';
$lang->leave->featurebar->company      = '全部请假|leave|company|';
$lang->leave->featurebar->setReviewer  = '设置|leave|setReviewer|';

$lang->makeup->featurebar = new stdclass();
$lang->makeup->featurebar->personal     = '我的补班|makeup|personal|';
$lang->makeup->featurebar->browseReview = '我的审核|makeup|browsereview|';
$lang->makeup->featurebar->company      = '全部补班|makeup|company|';
$lang->makeup->featurebar->setReviewer  = '设置|makeup|setReviewer|';

$lang->overtime->featurebar = new stdclass();
$lang->overtime->featurebar->personal     = '我的加班|overtime|personal|';
$lang->overtime->featurebar->browseReview = '我的审核|overtime|browsereview|';
$lang->overtime->featurebar->company      = '全部加班|overtime|company|';
$lang->overtime->featurebar->setReviewer  = '设置|overtime|setReviewer|';

$lang->lieu->featurebar = new stdclass();
$lang->lieu->featurebar->personal     = '我的调休|lieu|personal|';
$lang->lieu->featurebar->browseReview = '我的审核|lieu|browsereview|';
$lang->lieu->featurebar->company      = '全部调休|lieu|company|';
$lang->lieu->featurebar->setReviewer  = '设置|lieu|setReviewer|';

$lang->holiday->featurebar = new stdclass();
$lang->holiday->featurebar->browse = '所有|holiday|browse|';

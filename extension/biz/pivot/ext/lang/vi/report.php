<?php
$lang->pivot->testcase          = 'Báo cáo tình huống';
$lang->pivot->casesrun          = 'Báo cáo chạy tình huống';
$lang->pivot->build             = 'Báo cáo bản dựng';
$lang->pivot->workSummary       = 'Nhiệm vụ hoàn thành';
$lang->pivot->bugSummary        = 'Giải pháp Bug';
$lang->pivot->roadmap           = 'Lộ trình '.$lang->productCommon;
$lang->pivot->storyLinkedBug    = 'Liên kết Bugs - câu chuyện';
$lang->pivot->workAssignSummary = 'Bàn giao nhiệm vụ';
$lang->pivot->bugAssignSummary  = 'Bàn giao Bugs';
$lang->pivot->productInvest     = 'Product Investment';

$lang->pivot->taskFinishedDate = 'Ngày hoàn thành nhiệm vụ';
$lang->pivot->taskConsumed     = 'Tổng thời lượng nhiệm vụ';
$lang->pivot->projectConsumed  = 'Tổng thời lượng dự án';
$lang->pivot->userConsumed     = 'Tổng thời lượng người dùng';
$lang->pivot->bugResolvedDate  = 'Bug được giải quyết Date';
$lang->pivot->bugAssignedDate  = 'Bug Assigned Date';
$lang->pivot->taskAssignedDate = 'Task Assigned Date';
$lang->pivot->projects         = $lang->projectCommon;
$lang->pivot->storyConsumed    = $lang->SRCommon . ' Consumed';
$lang->pivot->taskConsumed     = 'Task Consumed';
$lang->pivot->bugConsumed      = 'Bug Consumed';
$lang->pivot->caseConsumed     = 'Case Consumed';
$lang->pivot->totalConsumed    = 'Total Consumed';

if(helper::hasFeature('product_roadmap')) $lang->pivotList->product->lists[20] = 'Lộ trình '.$lang->productCommon . '|pivot|roadmap';
$lang->pivotList->product->lists[25] = $lang->productCommon . ' Investment|pivot|productInvest';
$lang->pivotList->test->lists[20] = 'Báo cáo tình huống|pivot|testcase';
$lang->pivotList->test->lists[25] = 'Báo cáo chạy tình huống|pivot|casesrun';
$lang->pivotList->test->lists[30] = 'Báo cáo bản dựng|pivot|build';
$lang->pivotList->test->lists[35] = 'Câu chuyện Bugs liên kết|pivot|storylinkedbug';
$lang->pivotList->staff->lists[20]   = 'Nhiệm vụ hoàn thành|pivot|worksummary';
$lang->pivotList->staff->lists[25]   = 'Bàn giao nhiệm vụ|pivot|workAssignSummary';
$lang->pivotList->staff->lists[30]   = 'Giải pháp Bug|pivot|bugsummary';
$lang->pivotList->staff->lists[35]   = 'Bàn giao Bug|pivot|bugAssignSummary';

$lang->pivot->product    = 'Tên '.$lang->productCommon;
$lang->pivot->moduleName = 'Module';
$lang->pivot->buildTitle = 'Bản dựng';
$lang->pivot->severity   = 'Mức độ';
$lang->pivot->bugType    = 'Loại';
$lang->pivot->bugStatus  = 'Tình trạng';
$lang->pivot->delay      = 'Tạm ngưng';
$lang->pivot->day        = 'ngày';
$lang->pivot->plan       = 'Kế hoạch';
$lang->pivot->future     = 'TBD';

$lang->pivot->case     = new stdclass();
$lang->pivot->case->total = 'Tổng';
$lang->pivot->case->run   = 'Chạy';
$lang->pivot->case->passRate = 'Phần trăm';;
$lang->pivot->case->name  = 'Tên';

$lang->pivot->bugTypeList['codeerror']   = 'code';
$lang->pivot->bugTypeList['interface']   = 'interface';
$lang->pivot->bugTypeList['config']   = 'config';
$lang->pivot->bugTypeList['install']  = 'install';
$lang->pivot->bugTypeList['security'] = 'security';
$lang->pivot->bugTypeList['performance'] = 'performace';
$lang->pivot->bugTypeList['standard'] = 'standard';
$lang->pivot->bugTypeList['automation']  = 'automation';
$lang->pivot->bugTypeList['others']   = 'others';

$lang->pivot->bug     = new stdclass();
$lang->pivot->bug->total = 'Tổng';
$lang->pivot->bug->title = 'Tiêu đề Bug';
$lang->pivot->bug->story = 'Câu chuyện';
$lang->pivot->bug->status   = 'Tình trạng';

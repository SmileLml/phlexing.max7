<?php
$lang->create   = 'Tạo';
$lang->saveSuccess = 'Đã lưu';
$lang->month    = 'Tháng';
$lang->day   = 'Ngày';
$lang->detail   = 'Chi tiết';
$lang->logout   = 'Thoát';
$lang->minus    = ' - ';

$lang->confirmDelete = 'Bạn có muốn xóa nó?';
$lang->deleteing  = 'Deleting...';

$lang->my->menu->review = array('link' => 'Review|my|review|type=all', 'exclude' => 'review-assess');

$lang->menu->oa  = 'OA|attend|personal|';
$lang->menuOrder[23] = 'oa';

$lang->oa = new stdclass();
$lang->oa->menu = new stdclass();
$lang->oa->menu->attend   = array('link' => 'Attend|attend|personal', 'subModule' => 'attend');
$lang->oa->menu->leave = array('link' => 'Xin nghỉ phép|leave|personal', 'alias' => 'browse', 'subModule' => 'leave');
$lang->oa->menu->makeup   = array('link' => 'Makeup|makeup|personal', 'alias' => 'create,edit,view,browse', 'subModule' => 'makeup');
$lang->oa->menu->overtime = array('link' => 'Overtime|overtime|personal', 'subModule' => 'overtime');
$lang->oa->menu->lieu  = array('link' => 'Lieu|lieu|personal', 'subModule' => 'lieu');
$lang->oa->menu->holiday  = array('link' => 'Holiday|holiday|browse', 'subModule' => 'holiday');
$lang->oa->menu->review   = array('link' => 'Review|my|review|type=all&orderBy=status');

$lang->oa->menuOrder[5]  = 'attend';
$lang->oa->menuOrder[10] = 'leave';
$lang->oa->menuOrder[15] = 'makeup';
$lang->oa->menuOrder[20] = 'overtime';
$lang->oa->menuOrder[25] = 'lieu';
$lang->oa->menuOrder[30] = 'holiday';
$lang->oa->menuOrder[35] = 'review';

$lang->attend = new stdclass();
$lang->attend->menu   = $lang->oa->menu;
$lang->attend->menuOrder = $lang->oa->menuOrder;

$lang->leave = new stdclass();
$lang->leave->menu   = $lang->oa->menu;
$lang->leave->menuOrder = $lang->oa->menuOrder;

$lang->makeup = new stdclass();
$lang->makeup->menu   = $lang->oa->menu;
$lang->makeup->menuOrder = $lang->oa->menuOrder;

$lang->overtime = new stdclass();
$lang->overtime->menu   = $lang->oa->menu;
$lang->overtime->menuOrder = $lang->oa->menuOrder;

$lang->lieu = new stdclass();
$lang->lieu->menu   = $lang->oa->menu;
$lang->lieu->menuOrder = $lang->oa->menuOrder;

$lang->holiday = new stdclass();
$lang->holiday->menu   = $lang->oa->menu;
$lang->holiday->menuOrder = $lang->oa->menuOrder;


$lang->attend->featurebar = new stdclass();
$lang->attend->featurebar->personal  = 'My|attend|personal|';
$lang->attend->featurebar->department   = 'Phòng/Ban|attend|department|';
$lang->attend->featurebar->company   = 'Doanh nghiệp|attend|company|';
$lang->attend->featurebar->detail    = 'Detail|attend|detail|';
$lang->attend->featurebar->browsereview = 'Review|attend|browsereview|';
$lang->attend->featurebar->stat   = 'Báo cáo|attend|stat|';
$lang->attend->featurebar->settings  = array('link' => 'Settings|attend|settings|');

$lang->leave->featurebar = new stdclass();
$lang->leave->featurebar->personal  = 'My Leave|leave|personal|';
$lang->leave->featurebar->browseReview = 'Review|leave|browsereview|';
$lang->leave->featurebar->company   = 'All|leave|company|';
$lang->leave->featurebar->setReviewer  = 'Settings|leave|setReviewer|';

$lang->makeup->featurebar = new stdclass();
$lang->makeup->featurebar->personal  = 'My Makeup|makeup|personal|';
$lang->makeup->featurebar->browseReview = 'Review|makeup|browsereview|';
$lang->makeup->featurebar->company   = 'All|makeup|company|';
$lang->makeup->featurebar->setReviewer  = 'Settings|makeup|setReviewer|';

$lang->overtime->featurebar = new stdclass();
$lang->overtime->featurebar->personal  = 'My Overtime|overtime|personal|';
$lang->overtime->featurebar->browseReview = 'Review|overtime|browsereview|';
$lang->overtime->featurebar->company   = 'All|overtime|company|';
$lang->overtime->featurebar->setReviewer  = 'Settings|overtime|setReviewer|';

$lang->lieu->featurebar = new stdclass();
$lang->lieu->featurebar->personal  = 'My Lieu|lieu|personal|';
$lang->lieu->featurebar->browseReview = 'Review|lieu|browsereview|';
$lang->lieu->featurebar->company   = 'All|lieu|company|';
$lang->lieu->featurebar->setReviewer  = 'Settings|lieu|setReviewer|';

$lang->holiday->featurebar = new stdclass();
$lang->holiday->featurebar->browse = 'All|holiday|browse|';

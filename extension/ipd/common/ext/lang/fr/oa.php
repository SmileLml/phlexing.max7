<?php
$lang->create      = 'Create';
$lang->saveSuccess = 'Saved';
$lang->month       = 'Month';
$lang->day         = 'Day';
$lang->detail      = 'Detail';
$lang->logout      = 'Logout';
$lang->minus       = ' - ';

$lang->oa = new stdclass();
$lang->oa->common = 'OA';

$lang->confirmDelete = 'Do you want to delete it?';
$lang->deleteing     = 'Deleting...';

$lang->navIcons['oa'] = "<i class='icon icon-oa'></i>";
$lang->mainNav->oa    = "{$lang->navIcons['oa']} Attend|attend|personal|";

$lang->oa->menu = new stdclass();
$lang->oa->menu->attend   = array('link' => 'Attend|attend|personal', 'subModule' => 'attend');
$lang->oa->menu->leave    = array('link' => 'Leave|leave|personal', 'alias' => 'browse', 'subModule' => 'leave');
$lang->oa->menu->makeup   = array('link' => 'Makeup|makeup|personal', 'alias' => 'create,edit,view,browse', 'subModule' => 'makeup');
$lang->oa->menu->overtime = array('link' => 'Overtime|overtime|personal', 'subModule' => 'overtime');
$lang->oa->menu->lieu     = array('link' => 'Lieu|lieu|personal', 'subModule' => 'lieu');
$lang->oa->menu->review   = array('link' => 'Review|my|review|type=all&orderBy=status');

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

$lang->officeapproval->common = 'OA approval';
$lang->officesetting->common  = 'OA setting';
$lang->datapermission->common = 'Data permission';
$lang->officeexport->common   = 'OA export';

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
$lang->attend->featurebar->personal     = 'My|attend|personal|';
$lang->attend->featurebar->department   = 'Department|attend|department|';
$lang->attend->featurebar->company      = 'Company|attend|company|';
$lang->attend->featurebar->detail       = 'Detail|attend|detail|';
$lang->attend->featurebar->browsereview = 'Review|attend|browsereview|';
$lang->attend->featurebar->stat         = 'Report|attend|stat|';
$lang->attend->featurebar->settings     = array('link' => 'Settings|attend|settings|');

$lang->leave->featurebar = new stdclass();
$lang->leave->featurebar->personal     = 'My Leave|leave|personal|';
$lang->leave->featurebar->browseReview = 'Review|leave|browsereview|';
$lang->leave->featurebar->company      = 'All|leave|company|';
$lang->leave->featurebar->setReviewer  = 'Settings|leave|setReviewer|';

$lang->makeup->featurebar = new stdclass();
$lang->makeup->featurebar->personal     = 'My Makeup|makeup|personal|';
$lang->makeup->featurebar->browseReview = 'Review|makeup|browsereview|';
$lang->makeup->featurebar->company      = 'All|makeup|company|';
$lang->makeup->featurebar->setReviewer  = 'Settings|makeup|setReviewer|';

$lang->overtime->featurebar = new stdclass();
$lang->overtime->featurebar->personal     = 'My Overtime|overtime|personal|';
$lang->overtime->featurebar->browseReview = 'Review|overtime|browsereview|';
$lang->overtime->featurebar->company      = 'All|overtime|company|';
$lang->overtime->featurebar->setReviewer  = 'Settings|overtime|setReviewer|';

$lang->lieu->featurebar = new stdclass();
$lang->lieu->featurebar->personal     = 'My Lieu|lieu|personal|';
$lang->lieu->featurebar->browseReview = 'Review|lieu|browsereview|';
$lang->lieu->featurebar->company      = 'All|lieu|company|';
$lang->lieu->featurebar->setReviewer  = 'Settings|lieu|setReviewer|';

$lang->holiday->featurebar = new stdclass();
$lang->holiday->featurebar->browse = 'All|holiday|browse|';

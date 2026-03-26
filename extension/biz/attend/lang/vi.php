<?php
if(!isset($lang->attend)) $lang->attend = new stdclass();
$lang->attend->common    = 'Có mặt';
$lang->attend->personal  = 'Có mặt bạn';
$lang->attend->department   = 'Phòng/Ban';
$lang->attend->company   = 'Doanh nghiệp';
$lang->attend->detail    = 'Chi tiết';
$lang->attend->edit   = 'Sửa';
$lang->attend->edited    = 'Giờ vào';
$lang->attend->leave  = 'Nghỉ phép';
$lang->attend->leaved    = 'Nghỉ có phép';
$lang->attend->makeup    = 'Makeup lần';
$lang->attend->makeuped  = 'Made up';
$lang->attend->lieu   = 'Lieu';
$lang->attend->lieud  = 'Lieu đã đăng ký';
$lang->attend->trip   = 'Trip';
$lang->attend->triped    = 'Trip đã đăng ký';
$lang->attend->egress    = 'Ra ngoài';
$lang->attend->egressed  = 'Đăng ký ra ngoài';
$lang->attend->overtime  = 'Ngoài giờ';
$lang->attend->overtimed = 'Ngoài giờ đã đăng ký';
$lang->attend->review    = 'Duyệt có mặt';
$lang->attend->settings  = 'Thiết lập';
$lang->attend->export    = 'Xuất';
$lang->attend->stat   = 'Báo cáo';
$lang->attend->saveStat  = 'Lưu lại';
$lang->attend->exportStat   = 'Xuất báo cáo';
$lang->attend->exportDetail = 'Chi tiết xuất';
$lang->attend->browseReview = 'Danh sách xét duyệt';
$lang->attend->batchReview  = 'Duyệt hàng loạt';
$lang->attend->batchPass = 'Duyệt hàng loạt';

$lang->attend->id   = 'ID';
$lang->attend->date    = 'Ngày';
$lang->attend->account    = 'Người dùng';
$lang->attend->signIn  = 'Vào';
$lang->attend->signOut    = 'Ra';
$lang->attend->status  = 'Tình trạng';
$lang->attend->ip   = 'IP';
$lang->attend->device  = 'Thiết bị';
$lang->attend->desc    = 'Mô tả';
$lang->attend->dayName    = 'Ngày';
$lang->attend->report  = 'Báo cáo';
$lang->attend->AM   = 'SA';
$lang->attend->PM   = 'CH';
$lang->attend->ipList  = 'Danh sách IP';
$lang->attend->noAttendUsers = 'Không bắt buộc';
$lang->attend->signInClient  = 'Máy khách';
$lang->attend->rejectReason  = 'Lý do từ chối';

$lang->attend->user    = 'Người dùng';
$lang->attend->begin   = 'Bắt đầu';
$lang->attend->end     = 'Kết thúc';
$lang->attend->search  = 'Tìm kiếm';

$lang->attend->manualIn  = 'Vào';
$lang->attend->manualOut = 'Ra';
$lang->attend->reason    = 'Lý do';
$lang->attend->reviewStatus = 'Xét duyệt';
$lang->attend->reviewedBy   = 'Người duyệt';
$lang->attend->reviewedDate = 'Đã duyệt';
$lang->attend->deserveDays  = 'Ngày kỳ vọng';
$lang->attend->actualDays   = 'Ngày thực tế';

$lang->attend->clientList['all']  = 'Tất cả';
$lang->attend->clientList['desktop'] = 'Desktop';

$lang->attend->statusList['normal']   = 'Bình thường';
$lang->attend->statusList['late']  = 'Trễ';
$lang->attend->statusList['early'] = 'Về sớm';
$lang->attend->statusList['both']  = 'Trễ - Về sớm';
$lang->attend->statusList['absent']   = 'Nghỉ phép';
$lang->attend->statusList['leave'] = 'Xin nghỉ phép';
$lang->attend->statusList['makeup']   = 'Makeup lần';
$lang->attend->statusList['overtime'] = 'Ngoài giờ';
$lang->attend->statusList['lieu']  = 'Lieu';
$lang->attend->statusList['trip']  = 'Công tác';
$lang->attend->statusList['egress']   = 'Ra ngoài làm việc';
$lang->attend->statusList['rest']  = 'Off';

$lang->attend->abbrStatusList['normal']   = '√';
$lang->attend->abbrStatusList['late']  = 'Trễ';
$lang->attend->abbrStatusList['early'] = 'Sớm';
$lang->attend->abbrStatusList['both']  = 'T+S';
$lang->attend->abbrStatusList['absent']   = 'Nghỉ phép';
$lang->attend->abbrStatusList['leave'] = 'Nghỉ phép';
$lang->attend->abbrStatusList['makeup']   = 'Makeup';
$lang->attend->abbrStatusList['overtime'] = 'OT';
$lang->attend->abbrStatusList['lieu']  = 'Lieu';
$lang->attend->abbrStatusList['trip']  = 'Biz';
$lang->attend->abbrStatusList['egress']   = 'Out';
$lang->attend->abbrStatusList['rest']  = 'Off';

$lang->attend->markStatusList['normal']   = '√';
$lang->attend->markStatusList['late']  = '=';
$lang->attend->markStatusList['early'] = '>';
$lang->attend->markStatusList['both']  = '=>';
$lang->attend->markStatusList['absent']   = 'x';
$lang->attend->markStatusList['leave'] = '!';
$lang->attend->markStatusList['makeup']   = '↑';
$lang->attend->markStatusList['overtime'] = '+';
$lang->attend->markStatusList['lieu']  = '↓';
$lang->attend->markStatusList['trip']  = '$';
$lang->attend->markStatusList['egress']   = '#';
$lang->attend->markStatusList['rest']  = '~';

$lang->attend->reasonList['normal']   = 'Bình thường';
$lang->attend->reasonList['leave'] = 'Xin nghỉ phép';
$lang->attend->reasonList['makeup']   = 'Makeup lần';
$lang->attend->reasonList['overtime'] = 'Ngoài giờ';
$lang->attend->reasonList['lieu']  = 'Lieu';
$lang->attend->reasonList['trip']  = 'Công tác';
$lang->attend->reasonList['egress']   = 'Ra ngoài làm việc';

$lang->attend->reviewStatusList['wait']   = 'Đợi';
$lang->attend->reviewStatusList['pass']   = 'Đạt';
$lang->attend->reviewStatusList['reject'] = 'Từ chối';

$lang->attend->inSuccess  = 'Chấm công';
$lang->attend->inFail  = 'Clock-in thất bại';
$lang->attend->outSuccess = 'Clocked out.';
$lang->attend->outFail = 'Clock-out thất bại';

$lang->attend->signInLimit   = 'Giờ vào';
$lang->attend->signOutLimit  = 'Giờ ra';
$lang->attend->workingDays   = 'Ngày làm việc';
$lang->attend->workingHours  = 'Giờ làm việc';
$lang->attend->mustSignOut   = 'Bắt buộc';
$lang->attend->denied  = 'Truy cập bị từ chối.';
$lang->attend->nodata  = 'Không có dữ liệu được chọn.';
$lang->attend->reviewSuccess = 'Đã duyệt';

$lang->attend->workingDaysList['5']  = "Thứ hai ~ Thứ sáu";
$lang->attend->workingDaysList['6']  = "Thứ hai ~ Thứ bảy";
$lang->attend->workingDaysList['7']  = "Thứ hai ~ Chủ Nhật";
$lang->attend->workingDaysList['12'] = "Chủ nhật ~ Thứ năm";
$lang->attend->workingDaysList['13'] = "Chủ nhật ~ Thứ sáu";

$lang->attend->mustSignOutList['yes'] = 'Có';
$lang->attend->mustSignOutList['no']  = 'Không';

$lang->attend->weeks = array('Tuần một', 'Tuần hai', 'Tuần ba', 'Tuần bốn', 'Tuần năm', 'Tuần sáu');

$lang->attend->notice['today'] = "<p>Hôm qua bạn có mặt %s, <a href='%s' %s> Click để sửa</a></p>";
$lang->attend->notice['yestoday'] = "<p>Hôm nay bạn có mặt %s, <a href='%s' %s> Click để sửa</a></p>";
$lang->attend->notice['absent']   = "N/A";

$lang->attend->confirmReview['pass']   = 'Bạn có muốn duyệt nó?';
$lang->attend->confirmReview['reject'] = 'Bạn có muốn từ chối nó?';

$lang->attend->settings   = 'Doanh nghiệp';
$lang->attend->personalSettings = 'Cá nhân';
$lang->attend->setManager    = 'Trưởng phòng';
$lang->attend->setDept    = 'Phòng/ban';

$lang->attend->beginDate = new stdClass();
$lang->attend->beginDate->company  = 'Bắt đầu';
$lang->attend->beginDate->personal = 'Bắt đầu';

$lang->attend->note = new stdClass();
$lang->attend->note->ip     = "Sử dụng dấu phẩy để phân chia IP, và phân khúc IP là OK, ví dụ:  192.168.1.*";
$lang->attend->note->allip  = 'Tất cả IPs';
$lang->attend->note->IPDenied  = 'IP bị từ chối.';
$lang->attend->note->beginDate = 'Thiết lập một ngày để bắt đầu ghi nhận làm việc. Sự có mặt trước ngày này sẽ không được ghi nhận.';
$lang->attend->note->signInClient = 'Chọn tất cả để Giờ vào bằng mọi cách, mặt khác, người dùng phải chấm công bằng cách chọn một.';

$lang->attend->h = 'hours';
$lang->attend->m = 'minutes';
$lang->attend->s = 'seconds';

$lang->attend->signInClientError = 'Chấm công thất bại. Thiết lập chấm công chỉ thông qua %s.';
$lang->attend->waitReviews    = 'Có <strong>%s</strong> ghi nhận đang đợi duyệt.';

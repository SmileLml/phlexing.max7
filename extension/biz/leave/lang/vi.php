<?php
if(!isset($lang->leave)) $lang->leave = new stdclass();
$lang->leave->common  = 'Nghỉ phép';
$lang->leave->browse  = 'Nghỉ phép';
$lang->leave->view    = 'Xem';
$lang->leave->create  = 'Tạo';
$lang->leave->edit    = 'Sửa';
$lang->leave->delete  = 'Xóa';
$lang->leave->review  = 'Xét duyệt';
$lang->leave->cancel  = 'Hủy';
$lang->leave->commit  = 'Gửi';
$lang->leave->back    = 'Trở lại';
$lang->leave->export  = 'Xuất';
$lang->leave->reviewBack = 'Duyệt ngày quay lại';

$lang->leave->personal    = 'Nghỉ phép của bạn';
$lang->leave->browseReview   = 'Danh sách xét duyệt';
$lang->leave->company  = 'Tất cả';
$lang->leave->setReviewer = 'Thiết lập người duyệt';
$lang->leave->personalAnnual = 'Nghỉ phép năm';
$lang->leave->batchReview = 'Duyệt hàng loạt';
$lang->leave->batchPass   = 'Duyệt hàng loạt';

$lang->leave->id     = 'ID';
$lang->leave->year   = 'Năm';
$lang->leave->begin  = 'Bắt đầu';
$lang->leave->end    = 'Kết thúc';
$lang->leave->start  = 'Bắt đầu';
$lang->leave->finish    = 'Kết thúc';
$lang->leave->hours  = 'giờ';
$lang->leave->backDate  = 'Quay lại';
$lang->leave->type   = 'Loại';
$lang->leave->desc   = 'Mô tả';
$lang->leave->status    = 'Tình trạng';
$lang->leave->createdBy = 'Người lập';
$lang->leave->createdDate  = 'Ngày tạo';
$lang->leave->reviewedBy   = 'Người duyệt';
$lang->leave->reviewedDate = 'Ngày duyệt';
$lang->leave->date   = 'Ngày';
$lang->leave->time   = 'Thời gian';
$lang->leave->rejectReason = 'Lý do từ chối';
$lang->leave->account   = 'Tài khoản';
$lang->leave->dateRange = 'Thời gian';
$lang->leave->totalAnnual  = 'Thiết lập nghỉ phép năm';

$lang->leave->typeList['affairs']   = 'Riêng tư';
$lang->leave->typeList['sick']   = 'Bệnh tật';
$lang->leave->typeList['annual'] = 'Phép năm';
$lang->leave->typeList['lieu']   = 'Lieu';
$lang->leave->typeList['home']   = 'Việc nhà';
$lang->leave->typeList['marry']  = 'Cưới hỏi';
$lang->leave->typeList['maternity'] = 'Chăm sóc';

$lang->leave->paid   = 'Paid nghỉ phép';
$lang->leave->unpaid = 'Unpaid nghỉ phép';

$lang->leave->statusList['draft']  = 'Nháp';
$lang->leave->statusList['wait']   = 'Đợi';
$lang->leave->statusList['doing']  = 'Đang làm';
$lang->leave->statusList['pass']   = 'Đạt';
$lang->leave->statusList['reject'] = 'Từ chối';
$lang->leave->statusList['back']   = 'Trở lại';

$lang->leave->notExist   = 'Mục này không tồn tại';
$lang->leave->denied  = 'Truy cập bị từ chối.';
$lang->leave->unique  = 'Có một xin nghỉ phép trong %s.';
$lang->leave->sameMonth  = 'Xin nghỉ phép phải trong cùng tháng.';
$lang->leave->wrongEnd   = 'Thời gian kết thúc phải > thời gian bắt đầu.';
$lang->leave->wrongBackDate = 'Thời gian quay lại phải > thời gian bắt đầu.';
$lang->leave->nodata  = 'Không có dữ liệu được chọn.';
$lang->leave->reviewSuccess = 'Đã duyệt';

$lang->leave->confirmReview['pass']   = 'Bạn có muốn duyệt nó?';
$lang->leave->confirmReview['reject'] = 'Bạn có muốn từ chối nó?';

$lang->leave->hoursTip  = 'giờ';
$lang->leave->annualTip = 'Bạn còn %s ngày nghỉ phép năm.';

$lang->leave->reviewStatusList['pass']   = 'Đạt';
$lang->leave->reviewStatusList['reject'] = 'Từ chối';

$lang->leave->settings = new stdclass();
$lang->leave->settings->setReviewer = "Người duyệt|leave|setreviewer";
$lang->leave->settings->personalAnnual = "Nghỉ phép năm|leave|personalannual";

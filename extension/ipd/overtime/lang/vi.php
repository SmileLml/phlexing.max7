<?php
if(!isset($lang->overtime)) $lang->overtime = new stdclass();
$lang->overtime->common = 'Ngoài giờ';
$lang->overtime->browse = 'Ngoài giờ';
$lang->overtime->create = 'Tạo';
$lang->overtime->edit   = 'Sửa';
$lang->overtime->view   = 'Xem';
$lang->overtime->delete = 'Xóa';
$lang->overtime->review = 'Xét duyệt';
$lang->overtime->cancel = 'Hủy';
$lang->overtime->commit = 'Giao phó';
$lang->overtime->export = 'Xuất';

$lang->overtime->personal  = 'Ngoài giờ của bạn';
$lang->overtime->browseReview = 'Danh sách xét duyệt';
$lang->overtime->company   = 'Tất cả';
$lang->overtime->setReviewer  = 'Thiết lập';
$lang->overtime->batchReview  = 'Duyệt hàng loạt';
$lang->overtime->batchPass = 'Duyệt hàng loạt';

$lang->overtime->id     = 'ID';
$lang->overtime->year   = 'Năm';
$lang->overtime->begin  = 'Bắt đầu';
$lang->overtime->end    = 'Kết thúc';
$lang->overtime->start  = 'Bắt đầu';
$lang->overtime->finish    = 'Kết thúc';
$lang->overtime->hours  = 'giờ';
$lang->overtime->leave  = 'Nghỉ phép';
$lang->overtime->type   = 'Loại';
$lang->overtime->desc   = 'Mô tả';
$lang->overtime->status    = 'Tình trạng';
$lang->overtime->createdBy = 'Người tạo';
$lang->overtime->createdDate  = 'Ngày tạo';
$lang->overtime->reviewedBy   = 'Người duyệt';
$lang->overtime->reviewedDate = 'Đã duyệt';
$lang->overtime->date   = 'Ngày';
$lang->overtime->time   = 'Thời gian';
$lang->overtime->rejectReason = 'Lý do từ chối';

$lang->overtime->typeList['time'] = 'Sau giờ làm';
$lang->overtime->typeList['rest'] = 'Ngày cuối tuần';
$lang->overtime->typeList['holiday'] = 'Ngày lễ';

$lang->overtime->statusList['draft']  = 'Nháp';
$lang->overtime->statusList['wait']   = 'Đợi';
$lang->overtime->statusList['doing']  = 'Đang làm';
$lang->overtime->statusList['pass']   = 'Đạt';
$lang->overtime->statusList['reject'] = 'Từ chối';

$lang->overtime->notExist   = 'Ghi nhận này không tồn tại';
$lang->overtime->denied  = 'Truy cập bị từ chối';
$lang->overtime->unique  = 'Có một ghi nhận ngoài giờ trong %s.';
$lang->overtime->sameMonth  = 'Ngoài giờ phải cùng tháng.';
$lang->overtime->wrongEnd   = 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu.';
$lang->overtime->nodata  = 'Không có dữ liệu được chọn.';
$lang->overtime->reviewSuccess = 'Đã duyệt';

$lang->overtime->confirmReview['pass']   = 'Bạn có chắc duyệt nó?';
$lang->overtime->confirmReview['reject'] = 'Bạn có chắc từ chối nó?';

$lang->overtime->hoursTip = 'giờ';

$lang->overtime->reviewStatusList['pass']   = 'Đạt';
$lang->overtime->reviewStatusList['reject'] = 'Từ chối';

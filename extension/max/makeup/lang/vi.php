<?php
if(!isset($lang->makeup)) $lang->makeup = new stdclass();
$lang->makeup->common = 'Makeup';
$lang->makeup->browse = 'Browse Makeup';
$lang->makeup->create = 'Tạo';
$lang->makeup->edit   = 'Sửa';
$lang->makeup->view   = 'Xem';
$lang->makeup->delete = 'Xóa';
$lang->makeup->review = 'Xét duyệt';
$lang->makeup->cancel = 'Hủy';
$lang->makeup->commit = 'Giao phó';
$lang->makeup->export = 'Xuất';

$lang->makeup->personal  = 'Makeup của bạn';
$lang->makeup->browseReview = 'Danh sách xét duyệt';
$lang->makeup->company   = 'Tất cả';
$lang->makeup->setReviewer  = 'Thiết lập';
$lang->makeup->batchReview  = 'Duyệt hàng loạt';
$lang->makeup->batchPass = 'Duyệt hàng loạt';

$lang->makeup->id     = 'ID';
$lang->makeup->year   = 'Năm';
$lang->makeup->begin  = 'Bắt đầu';
$lang->makeup->end    = 'Kết thúc';
$lang->makeup->start  = 'Bắt đầu';
$lang->makeup->finish    = 'Kết thúc';
$lang->makeup->hours  = 'giờ';
$lang->makeup->leave  = 'Nghỉ phép';
$lang->makeup->type   = 'Loại';
$lang->makeup->desc   = 'Mô tả';
$lang->makeup->status    = 'Tình trạng';
$lang->makeup->createdBy = 'Người tạo';
$lang->makeup->createdDate  = 'Ngày tạo';
$lang->makeup->reviewedBy   = 'Người duyệt';
$lang->makeup->reviewedDate = 'Ngày duyệt';
$lang->makeup->date   = 'Ngày';
$lang->makeup->time   = 'Thời gian';
$lang->makeup->rejectReason = 'Lý do từ chối';

$lang->makeup->typeList['compensate'] = 'Nghỉ bù';

$lang->makeup->statusList['draft']  = 'Nháp';
$lang->makeup->statusList['wait']   = 'Đợi';
$lang->makeup->statusList['doing']  = 'Đang làm';
$lang->makeup->statusList['pass']   = 'Đạt';
$lang->makeup->statusList['reject'] = 'Từ chối';

$lang->makeup->notExist   = 'Ghi nhận này không tồn tại';
$lang->makeup->denied  = 'Truy cập bị từ chối';
$lang->makeup->unique  = 'Đã có a record of makeup in %s.';
$lang->makeup->sameMonth  = 'Makeup phải cùng tháng.';
$lang->makeup->wrongEnd   = 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu.';
$lang->makeup->nodata  = 'Không có dữ liệu được chọn.';
$lang->makeup->reviewSuccess = 'Đã duyệt';

$lang->makeup->confirmReview['pass']   = 'Bạn có chắc duyệt nó?';
$lang->makeup->confirmReview['reject'] = 'Bạn có chắc từ chối nó?';

$lang->makeup->hoursTip = 'giờ';

$lang->makeup->reviewStatusList['pass']   = 'Đạt';
$lang->makeup->reviewStatusList['reject'] = 'Từ chối';

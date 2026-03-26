<?php
if(!isset($lang->lieu)) $lang->lieu = new stdclass();
$lang->lieu->common = 'Lieu';
$lang->lieu->browse = 'Lieu';
$lang->lieu->create = 'Tạo';
$lang->lieu->edit   = 'Sửa';
$lang->lieu->view   = 'Xem';
$lang->lieu->delete = 'Xóa';
$lang->lieu->review = 'Xét duyệt';
$lang->lieu->cancel = 'Hủy';
$lang->lieu->commit = 'Gửi';

$lang->lieu->personal  = 'My Lieu';
$lang->lieu->browseReview = 'Xét duyệt';
$lang->lieu->company   = 'Tất cả';
$lang->lieu->setReviewer  = 'Thiết lập';
$lang->lieu->batchReview  = 'Duyệt hàng loạt';
$lang->lieu->batchPass = 'Duyệt hàng loạt';

$lang->lieu->id     = 'ID';
$lang->lieu->year   = 'Năm';
$lang->lieu->begin  = 'Bắt đầu';
$lang->lieu->end    = 'Kết thúc';
$lang->lieu->start  = 'Bắt đầu';
$lang->lieu->finish    = 'Kết thúc';
$lang->lieu->hours  = 'giờ';
$lang->lieu->overtime  = 'Ngoài giờ';
$lang->lieu->trip   = 'Trip';
$lang->lieu->status    = 'Tình trạng';
$lang->lieu->desc   = 'Mô tả';
$lang->lieu->createdBy = 'Người tạo';
$lang->lieu->createdDate  = 'Ngày tạo';
$lang->lieu->reviewedBy   = 'Người duyệt';
$lang->lieu->reviewedDate = 'Đã duyệt';
$lang->lieu->date   = 'Ngày';
$lang->lieu->time   = 'Thời gian';
$lang->lieu->rejectReason = 'Lý do từ chối';

$lang->lieu->statusList['draft']  = 'Nháp';
$lang->lieu->statusList['wait']   = 'Đợi';
$lang->lieu->statusList['doing']  = 'Đang làm';
$lang->lieu->statusList['pass']   = 'Đạt';
$lang->lieu->statusList['reject'] = 'Từ chối';

$lang->lieu->confirmReview['pass']   = 'Bạn có muốn duyệt nó?';
$lang->lieu->confirmReview['reject'] = 'Bạn có muốn từ chối nó?';

$lang->lieu->notExist   = 'Ghi nhận này không tồn tại.';
$lang->lieu->checkHours = 'Kiểm tra Hours';
$lang->lieu->denied  = 'Truy cập bị từ chối.';
$lang->lieu->unique  = 'Đã có %s Lieu in %s.';
$lang->lieu->sameMonth  = 'Lieus phải cùng tháng.';
$lang->lieu->wrongEnd   = 'Thời gian kết thúc phải > thời gian bắt đầu.';
$lang->lieu->nodata  = 'Không có dữ liệu được chọn.';
$lang->lieu->reviewSuccess = 'Đã duyệt';
$lang->lieu->wrongHours = 'Tổng số giờ ngoài giờ và chuyến đi là <strong>%s</strong> giờ. Lieu không thể > tổng thời gian.';
$lang->lieu->nobccomp   = 'Vui lòng install the extension php-bcmath.';
$lang->lieu->bothEmpty  = 'Ghi nhận <strong>Ngoài giờ</strong> và ghi nhận <strong>trip</strong> không thể trống cùng một lúc.';

$lang->lieu->hoursTip = 'giờ';

$lang->lieu->checkHoursList['0'] = 'Chưa kiểm tra';
$lang->lieu->checkHoursList['1'] = 'Giờ Lieu không thể > giờ làm thêm (%s)';

$lang->lieu->reviewStatusList['pass']   = 'Đạt';
$lang->lieu->reviewStatusList['reject'] = 'Từ chối';

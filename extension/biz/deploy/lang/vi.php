<?php
$lang->deploy->common     = 'Kế hoạch triển khai';
$lang->deploy->create     = 'Tạo Kế hoạch triển khai';
$lang->deploy->view    = 'Chi tiết triển khai';
$lang->deploy->finish     = 'Kết thúc';
$lang->deploy->finishAction  = 'Kết thúc triển khai';
$lang->deploy->edit    = 'Sửa';
$lang->deploy->editAction    = 'Sửa triển khai';
$lang->deploy->delete     = 'Xóa';
$lang->deploy->deleteAction  = 'Xóa triển khai';
$lang->deploy->activate   = 'Kích hoạt';
$lang->deploy->activateAction   = 'Kích hoạt triển khai';
$lang->deploy->browse     = 'Triển khai';
$lang->deploy->scope   = 'Phạm vi triển khai';
$lang->deploy->manageScope   = 'Quản lý phạm vi';
$lang->deploy->cases   = 'Tình huống';
$lang->deploy->notify     = 'Thông báo';
$lang->deploy->casesAction   = 'Triển khai tình huống';
$lang->deploy->linkCases  = 'Liên kết tình huống';
$lang->deploy->unlinkCase    = 'Hủy liên kết tình huống';
$lang->deploy->steps   = 'Triển khai bước';
$lang->deploy->manageStep    = 'Quản lý bước';
$lang->deploy->finishStep    = 'Kết thúc bước';
$lang->deploy->activateStep  = 'Kích hoạt bước';
$lang->deploy->assignTo   = 'Giao cho';
$lang->deploy->assignAction  = 'Bàn giao bước';
$lang->deploy->editStep   = 'Sửa bước';
$lang->deploy->deleteStep    = 'Xóa bước';
$lang->deploy->viewStep   = 'Chi tiết bước';
$lang->deploy->batchUnlinkCases = 'Hủy liên kết tình huống hàng loạt';
$lang->deploy->createdDate   = 'Ngày tạo';

$lang->deploy->name    = 'Tên kết hoạch';
$lang->deploy->desc    = 'Mô tả';
$lang->deploy->members = 'Thành viên';
$lang->deploy->hosts   = 'Hosts';
$lang->deploy->service = 'Dịch vụ';
$lang->deploy->product = $lang->productCommon;
$lang->deploy->release = 'Phát hành';
$lang->deploy->package = 'Đường dẫn Gói';
$lang->deploy->begin   = 'Bắt đầu';
$lang->deploy->end  = 'Kết thúc';
$lang->deploy->status  = 'Tình trạng';
$lang->deploy->owner   = 'Sở hữu';
$lang->deploy->stage   = 'Giai đoạn';
$lang->deploy->ditto   = 'Như trên';
$lang->deploy->manageAB   = 'Quản lý';
$lang->deploy->title   = 'Tiêu đề';
$lang->deploy->content = 'Nội dung';
$lang->deploy->assignedTo = 'Giao cho';
$lang->deploy->finishedBy = 'Người kết thúc';
$lang->deploy->createdBy  = 'Người tạo';
$lang->deploy->result  = 'Kết quả';
$lang->deploy->updateHost = 'Cập nhật Host';
$lang->deploy->removeHost = 'Hosts bị xóa';
$lang->deploy->addHost = 'Host mới';
$lang->deploy->hadHost = 'Hosted';

$lang->deploy->lblBeginEnd = 'Thời gian';
$lang->deploy->lblBasic = 'Thông tin cơ bản';
$lang->deploy->lblProduct  = 'Liên kết';
$lang->deploy->lblMonth = 'Hiện tại';
$lang->deploy->toggle   = 'Menu';

$lang->deploy->monthFormat = 'M Y';

$lang->deploy->statusList['wait'] = 'Đang đợi';
$lang->deploy->statusList['done'] = 'Hoàn thành';

$lang->deploy->dateList['today']  = 'Hôm nay';
$lang->deploy->dateList['tomorrow']  = 'Hôm qua';
$lang->deploy->dateList['thisweek']  = 'Tuần này';
$lang->deploy->dateList['thismonth'] = 'Tháng này';
$lang->deploy->dateList['done']   = $lang->deploy->statusList['done'];

$lang->deploy->stageList['wait'] = 'Trước khi triển khai';
$lang->deploy->stageList['doing']   = 'Đang triển khai';
$lang->deploy->stageList['testing'] = 'Đang kiểm tra';
$lang->deploy->stageList['done'] = 'Sau khi triển khai';

$lang->deploy->resultList['']  = '';
$lang->deploy->resultList['success'] = 'Hoàn thành';
$lang->deploy->resultList['fail'] = 'Thất bại';

$lang->deploy->confirmDelete  = 'Bạn có muốn xóa triển khai này?';
$lang->deploy->confirmDeleteStep = 'Bạn có muốn xóa bước này?';
$lang->deploy->errorTime   = 'Thời gian kết thúc phải > thời gian bắt đầu!';
$lang->deploy->errorStatusWait   = 'Nếu tình trạng này là Đang đợi, Người kết thúc phải là rỗng';
$lang->deploy->errorStatusDone   = 'Nếu tình trạng này là Hoàn thành, Người kết thúc không nên rỗng';
$lang->deploy->errorOffline   = 'Hosts trong Thêm/Xóa cho một dịch vụ không thể cùng thời điểm.';
$lang->deploy->resultNotEmpty = 'Kết quả không thể trống';

$lang->deploystep = new stdClass();
$lang->deploystep->status       = $lang->deploy->status;
$lang->deploystep->assignedTo   = $lang->deploy->assignedTo;
$lang->deploystep->finishedBy   = $lang->deploy->finishedBy;
$lang->deploystep->finishedDate = 'Finished Date';
$lang->deploystep->begin        = $lang->deploy->begin;
$lang->deploystep->end          = $lang->deploy->end;

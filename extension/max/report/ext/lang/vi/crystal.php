<?php
$lang->report->crystalExport = 'Xuất Crystal';
$lang->report->null          = 'NULL';

$lang->crystal = new stdclass();
$lang->crystal->common  = 'Crystal báo cáo';
$lang->crystal->setVar  = 'Thêm biến';
$lang->crystal->browse  = 'Báo cáo';
$lang->crystal->use     = 'Thiết kế';
$lang->crystal->addVar  = 'Thêm biến';
$lang->crystal->addLang = 'Thiết lập trường';
$lang->crystal->custom  = 'Thêm báo cáo';
$lang->crystal->saveAs  = 'Lưu thành';

$lang->crystal->sql         = 'SQL truy vấn';
$lang->crystal->query       = 'Truy vấn';
$lang->crystal->condition   = 'Thiết kế';
$lang->crystal->params      = 'Thông số';
$lang->crystal->result      = 'Query kết quả';
$lang->crystal->group       = 'Nhóm theo trường';
$lang->crystal->statistics  = 'Báo cáo trường';
$lang->crystal->group1      = 'Trường 1';
$lang->crystal->group2      = 'Trường 2';
$lang->crystal->reportField = 'Trường';
$lang->crystal->reportType  = 'Phương thức';
$lang->crystal->reportTotal = 'Tổng';
$lang->crystal->percent     = 'Phần trăm';;
$lang->crystal->contrast    = 'So sánh';
$lang->crystal->showAlone   = 'trong một cột đã chia';
$lang->crystal->percentAB   = '%';
$lang->crystal->isUser      = 'Họ & Tên';
$lang->crystal->total       = 'Tổng';
$lang->crystal->requestType = 'Đầu vào';
$lang->crystal->varName     = 'Tên biến';
$lang->crystal->showName    = 'Tên hiển thị';
$lang->crystal->name        = 'Báo cáo';
$lang->crystal->module      = 'Group';
$lang->crystal->id          = 'ID';
$lang->crystal->code        = 'Mã';
$lang->crystal->all         = 'Tất cả';
$lang->crystal->fieldName   = 'Tên trường';
$lang->crystal->fieldValue  = 'Tên hiển thị';
$lang->crystal->lang        = 'Ngôn ngữ';
$lang->crystal->desc        = 'Mô tả';
$lang->crystal->default     = 'Giá trị mặc định';
$lang->crystal->project     = $lang->projectCommon;

$lang->crystal->reportTypeList['count'] = 'Đếm';
$lang->crystal->reportTypeList['sum']   = 'Tổng';

$lang->crystal->requestTypeList['input']   = 'Text';
$lang->crystal->requestTypeList['date'] = 'Ngày';
$lang->crystal->requestTypeList['select']  = 'Dropdown';

$lang->crystal->selectList['user']           = 'Người dùng';
$lang->crystal->selectList['product']        = $lang->productCommon;
$lang->crystal->selectList['project']        = $lang->projectCommon . 'List';
$lang->crystal->selectList['dept']           = 'Departments';
$lang->crystal->selectList['project.status'] = $lang->projectCommon . 'Status List';

$lang->crystal->moduleList['']          = '';
$lang->crystal->moduleList['product']   = $lang->productCommon;
$lang->crystal->moduleList['execution'] = 'Execution';
$lang->crystal->moduleList['test']      = 'QA';
$lang->crystal->moduleList['staff']     = 'Văn phòng';

$lang->crystal->sqlPlaceholder = 'Một truy vấn SQL chỉ có thể truy vấn và SQL khác là ngăn cấm.';
$lang->crystal->sumPlaceholder = 'Chọn trường để tổng hợp';
$lang->crystal->codePlaceholder   = 'Mã duy nhất cho báo cáo này';
$lang->crystal->noticeSelect   = 'Chỉ truy xuất SQL';
$lang->crystal->noticeBlack    = 'SQL chứa từ khóa không truy vấn %s';
$lang->crystal->notResult   = 'Không có truy vấn dữ liệu.';
$lang->crystal->noticeResult   = 'Tổng %s. Hiện %s';
$lang->crystal->noticeVarName  = 'Tên biến chưa được thiết lập';
$lang->crystal->noticeRequestType = 'Đầu vào của %s chưa được thiết lập.';
$lang->crystal->noticeShowName = 'Tên biến %s chưa được thiết lập.';
$lang->crystal->errorSave   = 'Biến SQL không thể để trống!';
$lang->crystal->errorNoReport  = 'Báo cáo này không tồn tại!';
$lang->crystal->confirmDelete  = 'Bạn có muốn xóa báo cáo này?';
$lang->crystal->errorSql    = 'SQL sai! Lỗi: ';
$lang->crystal->noSumAppend    = 'Không có trường được chọn để tổng hợp trong  %s.';
$lang->crystal->noStep   = 'Vui lòng tìm dữ liệu, sau đó lưu báo cáo!';
$lang->crystal->emptyName   = 'Tên báo cáo không nên trống.';

$lang->report->custom           = $lang->crystal->custom;
$lang->report->useReport        = $lang->crystal->use;
$lang->report->useReportAction  = 'Thiết kế báo cáo';
$lang->report->browseReport     = 'Browse báo cáo';
$lang->report->deleteReport     = 'Xóa báo cáo';
$lang->report->editReport       = 'Sửa';
$lang->report->editReportAction = 'Sửa báo cáo';
$lang->report->saveReport       = 'Lưu báo cáo';
$lang->report->show             = 'Hiện data';

$lang->datepicker->dpText->TEXT_WEEK_MONDAY = 'Thứ hai';
$lang->datepicker->dpText->TEXT_WEEK_SUNDAY = 'Chủ nhật';
$lang->datepicker->dpText->TEXT_MONTH_BEGIN = 'Đầu tháng';
$lang->datepicker->dpText->TEXT_MONTH_END   = 'Cuối tháng';

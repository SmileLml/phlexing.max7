<?php
/**
 * The effort module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  effort
 * @version  $Id: vi.php 2605 2012-02-21 07:22:58Z wwccss $
 * @link  http://www.zentao.net
 */
$lang->effort->common          = 'Chấm công';
$lang->effort->index           = "Danh sách chấm công";
$lang->effort->create          = "Nhật ký chấm công";
$lang->effort->batchCreate     = "Nhật ký chấm công";
$lang->effort->createForObject = "Nhật ký chấm công cho hạng mục";
$lang->effort->edit            = "Sửa chấm công";
$lang->effort->batchEdit       = "Sửa hàng loạt";
$lang->effort->view            = "Chi tiết chấm công";
$lang->effort->viewAB          = "Thông tin";
$lang->effort->comment         = 'Nhận xét';
$lang->effort->export          = "Xuất";
$lang->effort->exportAction    = "Xuất chấm công";
$lang->effort->delete          = "Xóa chấm công";
$lang->effort->browse          = "Xem";
$lang->effort->history         = "Lịch sử";
$lang->effort->hour            = "giờ";
$lang->effort->clean           = "Xóa";
$lang->effort->workHour        = "giờ làm việc";
$lang->effort->handleTask      = "Handle Task";

$lang->effort->id         = 'ID';
$lang->effort->account    = 'Người dùng';
$lang->effort->date       = 'Ngày';
$lang->effort->dept       = 'Phòng/Ban';
$lang->effort->left       = 'Còn lại';
$lang->effort->leftAB     = 'Còn';
$lang->effort->consumed   = 'Đã làm';
$lang->effort->consumedAB = 'Đã làm';
$lang->effort->objectType = 'Hạng mục';
$lang->effort->objectID   = 'Item ID';
$lang->effort->product    = $lang->productCommon;
$lang->effort->execution  = $lang->execution->common;
$lang->effort->work       = 'Nhật ký công việc';
$lang->effort->deal       = 'Handle';
$lang->effort->allDept    = 'Tất cả';
$lang->effort->begin      = 'Bắt đầu';
$lang->effort->end        = 'Kết thúc';

$lang->effort->week         = '(l)';  // date function's param.
$lang->effort->today        = 'Hôm nay';
$lang->effort->weekDateList = '';

$lang->effort->objectTypeList['custom']      = '';
$lang->effort->objectTypeList['story']       = 'Câu chuyện';
$lang->effort->objectTypeList['productplan'] = 'Kế hoạch sản phẩm';
$lang->effort->objectTypeList['release']     = 'Phát hành';
$lang->effort->objectTypeList['task']        = 'Nhiệm vụ';
$lang->effort->objectTypeList['build']       = 'Bản dựng';
$lang->effort->objectTypeList['bug']         = 'Bug';
$lang->effort->objectTypeList['case']        = 'Tình huống';
$lang->effort->objectTypeList['testcase']    = 'tình huống';
$lang->effort->objectTypeList['testtask']    = 'Test';
$lang->effort->objectTypeList['doc']         = 'Tài liệu';
$lang->effort->objectTypeList['todo']        = 'Việc làm';

$lang->effort->todayEfforts     = 'Hôm nay';
$lang->effort->yesterdayEfforts = 'Hôm qua';
$lang->effort->thisWeekEfforts  = 'Tuần này';
$lang->effort->lastWeekEfforts  = 'Tuần trước';
$lang->effort->thisMonthEfforts = 'Tháng này';
$lang->effort->lastMonthEfforts = 'Tháng trước';
$lang->effort->allDaysEfforts   = 'Tất cả';

$lang->effort->periods['all']       = $lang->effort->allDaysEfforts;
$lang->effort->periods['today']     = $lang->effort->todayEfforts;
$lang->effort->periods['yesterday'] = $lang->effort->yesterdayEfforts;
$lang->effort->periods['thisweek']  = $lang->effort->thisWeekEfforts;
$lang->effort->periods['lastweek']  = $lang->effort->lastWeekEfforts;
$lang->effort->periods['thismonth'] = $lang->effort->thisMonthEfforts;
$lang->effort->periods['lastmonth'] = $lang->effort->lastMonthEfforts;

$lang->effort->deleted          = "Đã xóa";
$lang->effort->notEmpty         = 'Nó không nên trống.';
$lang->effort->notNegative      = "Không thể là số âm.";
$lang->effort->isNumber         = 'Nó phải là số.';
$lang->effort->tooLang          = 'ID%s là quá dài.';
$lang->effort->nowork           = "ID%s  không nên trống!";
$lang->effort->notice           = '(Ghi chú: 1. Nếu %s này trống, nó không hợp lệ; 2. Nếu %s này không bằng %s và %s trống, nó không hợp lệ)';
$lang->effort->notFuture        = 'Tính lương cho ngày trong tương lai không thể được thêm.';
$lang->effort->confirmDelete    = "Bạn có muốn xóa tính lương này?";
$lang->effort->noticeClean      = "Chỉ có tính lương từ xa được tạo tự động.";
$lang->effort->noticeFinish     = "Số giờ còn lại là 0, và tình trạng sẽ là Hoàn thành. Bạn có muốn tiếp tục?";
$lang->effort->noticeSaveRecord = 'Vui lòng lưu dự tính này.';
$lang->effort->remindSubject    = 'Nhắc nhở Tính lương';
$lang->effort->remindContent    = "Bạn đã không đăng nhập hôm qua, <a href='%s' target='_blank'> Nhật ký tính lương </a>";
$lang->effort->leftTip          = "Only tasks can enter remaining work";

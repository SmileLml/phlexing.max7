<?php
$lang->action->dynamicAction->bug['fromfeedback']   = 'Tạo bug  from feedback';
$lang->action->dynamicAction->story['fromfeedback'] = "Tạo {$lang->SRCommon} from feedback";
$lang->action->dynamicAction->task['fromfeedback']  = 'Tạo task from feedback';
$lang->action->dynamicAction->todo['fromfeedback']  = 'Tạo todo from feedback';

$lang->action->dynamicAction->deploy['created']    = 'Tạo deploy';
$lang->action->dynamicAction->deploy['edited']  = 'Sửa deploy';
$lang->action->dynamicAction->deploy["activated"]  = 'Kích hoạt deploy';
$lang->action->dynamicAction->deploy['commented']  = 'Comment deploy';
$lang->action->dynamicAction->deploy["finished"]   = 'Kết thúc deploy';
$lang->action->dynamicAction->deploy['deleted']    = 'Xóa deploy';
$lang->action->dynamicAction->deploy['undeleted']  = 'Khôi phục deploy';
$lang->action->dynamicAction->deploy['hidden']  = 'Ẩn deploy';
$lang->action->dynamicAction->deploystep['created']   = 'Tạo step';
$lang->action->dynamicAction->deploystep['edited'] = 'Sửa step';
$lang->action->dynamicAction->deploystep["finished"]  = 'Kết thúc step';
$lang->action->dynamicAction->deploystep["assigned"]  = 'Bàn giao step';
$lang->action->dynamicAction->deploystep['deleted']   = 'Xóa step';
$lang->action->dynamicAction->deploystep['undeleted'] = 'Khôi phục step';
$lang->action->dynamicAction->deploystep['hidden'] = 'Ẩn step';

$lang->action->dynamicAction->host['created']   = 'Tạo host';
$lang->action->dynamicAction->host['edited'] = 'Sửa host';
$lang->action->dynamicAction->host['online'] = 'Online host';
$lang->action->dynamicAction->host['offline']   = 'Offline host';
$lang->action->dynamicAction->host['deleted']   = 'Xóa host';
$lang->action->dynamicAction->host['undeleted'] = 'Khôi phục host';
$lang->action->dynamicAction->host['hidden'] = 'Ẩn host';

$lang->action->dynamicAction->serverroom['created']   = 'Tạo server room';
$lang->action->dynamicAction->serverroom['edited'] = 'Sửa server room';
$lang->action->dynamicAction->serverroom['deleted']   = 'Xóa server room';
$lang->action->dynamicAction->serverroom['undeleted'] = 'Khôi phục server room';
$lang->action->dynamicAction->serverroom['hidden'] = 'Ẩn server room';

$lang->action->dynamicAction->service['created']   = 'Tạo service';
$lang->action->dynamicAction->service['edited'] = 'Sửa service';
$lang->action->dynamicAction->service['deleted']   = 'Xóa service';
$lang->action->dynamicAction->service['undeleted'] = 'Khôi phục service';
$lang->action->dynamicAction->service['hidden'] = 'Ẩn service';

$lang->action->dynamicAction->attend['commited'] = 'Commit attend';
$lang->action->dynamicAction->attend['reviewed'] = 'Duyệt attend';

$lang->action->dynamicAction->leave['created']  = 'Tạo leave';
$lang->action->dynamicAction->leave['edited']   = 'Sửa leave';
$lang->action->dynamicAction->leave['reviewed'] = 'Duyệt leave';
$lang->action->dynamicAction->leave['reported'] = 'Sales leave';
$lang->action->dynamicAction->leave['revoked']  = 'Revoke leave';
$lang->action->dynamicAction->leave['commited'] = 'Commit leave';

$lang->action->dynamicAction->makeup['created']  = 'Tạo makeup';
$lang->action->dynamicAction->makeup['edited']   = 'Sửa makeup';
$lang->action->dynamicAction->makeup['reviewed'] = 'Duyệt makeup';
$lang->action->dynamicAction->makeup['revoked']  = 'Revoke makeup';
$lang->action->dynamicAction->makeup['commited'] = 'Commit makeup';

$lang->action->dynamicAction->overtime['created']  = 'Tạo overtime';
$lang->action->dynamicAction->overtime['edited']   = 'Sửa overtime';
$lang->action->dynamicAction->overtime['reviewed'] = 'Duyệt overtime';
$lang->action->dynamicAction->overtime['revoked']  = 'Revoke overtime';
$lang->action->dynamicAction->overtime['commited'] = 'Commit overtime';

$lang->action->dynamicAction->lieu['created']  = 'Tạo lieu';
$lang->action->dynamicAction->lieu['edited']   = 'Sửa lieu';
$lang->action->dynamicAction->lieu['reviewed'] = 'Duyệt lieu';
$lang->action->dynamicAction->lieu['revoked']  = 'Revoke lieu';
$lang->action->dynamicAction->lieu['commited'] = 'Commit lieu';

$lang->action->dynamicAction->holiday['created'] = 'Tạo holiday';

$lang->action->dynamicAction->feedback['opened']      = 'Tạo feedback';
$lang->action->dynamicAction->feedback['edited']      = 'Sửa feedback';
$lang->action->dynamicAction->feedback['reviewed']    = 'Duyệt feedback';
$lang->action->dynamicAction->feedback['asked']       = 'Ask feedback';
$lang->action->dynamicAction->feedback['replied']     = 'Trả lời feedback';
$lang->action->dynamicAction->feedback['commented']   = 'Commit feedback';
$lang->action->dynamicAction->feedback['assigned']    = 'Bàn giao feedback';
$lang->action->dynamicAction->feedback['tostory']     = 'Tới ' . $lang->SRCommon;
$lang->action->dynamicAction->feedback['touserstory'] = 'Tới ' . $lang->URCommon;
$lang->action->dynamicAction->feedback['tobug']       = 'Tới bug';
$lang->action->dynamicAction->feedback['totask']      = 'Tới nhiệm vụ';
$lang->action->dynamicAction->feedback['totodo']      = 'Tới todo';
$lang->action->dynamicAction->feedback['closed']      = 'Đóng feedback';

if(!$config->URAndSR) unset($lang->action->dynamicAction->feedback['touserstory']);

<?php

$lang->log             = new stdClass();
$lang->log->common     = '日誌';
$lang->log->browse     = '瀏覽日誌';
$lang->log->admin      = '系統管理員和安全管理員日誌';
$lang->log->name       = '操作日誌';
$lang->log->objectType = '操作對象類型';
$lang->log->objectId   = '操作對象 ID';
$lang->log->actor      = '操作用戶';
$lang->log->action     = '操作';
$lang->log->result     = '結果';
$lang->log->ip         = 'IP';
$lang->log->date       = '日期';
$lang->log->reset      = '重置';
$lang->log->comment    = '備註';
$lang->log->view       = '查看';
$lang->log->guest      = '遊客';
$lang->log->datePlaceholder = '請選擇一個日期';

$lang->log->typeArray = array(
  'all'   => '全部',
  'user'  => '用戶',
  'chat'  => '會話',
  'entry' => '應用',
  'role'  => '角色',
  'group' => '權限分組',
  'deptcategory' => '部門',
  'paramprofile' => '參數',
);

$lang->log->actionName = array(
  'loginxuanxuan'      => '登錄喧喧',
  'disconnectxuanxuan' => '斷開喧喧',
  'reconnectxuanxuan'  => '重連喧喧',
  'logoutxuanxuan'     => '退出喧喧',
  'login'              => '登錄',
  'logout'             => '登出',
  'created'            => '創建',
  'create'             => '創建',
  'edit'               => '編輯',
  'edited'             => '編輯',
  'forbidden'          => '禁用',
  'forbid'             => '禁用',
  'activated'          => '激活',
  'active'             => '激活',
  'kick'               => '踢出',
  'invite'             => '邀請',
  'rename'             => '重命名',
  'delete'             => '刪除',
  'deleted'            => '刪除',
  'managechildren'     => '修改部門',
  'managemember'       => '修改成員',
  'managepriv'         => '修改權限',
  'threeroles'         => '三員設置',
  'dismiss'            => '解散',
  'updatestatus'       => '更新狀態',
  'setmoderators'      => '設置審批員',
  'loginsecurity'      => '登錄安全',
  'archive'            => '歸檔',
  'unarchive'          => '取消歸檔',
  'changeownership'    => '轉讓',
  'recover'            => '還原',
  'leave'              => '退出',
  'merge'              => '合併',
  'join'               => '加入'
);


$lang->log->resultName = array(
  'success' => '成功',
  'fail' => '失敗'
);

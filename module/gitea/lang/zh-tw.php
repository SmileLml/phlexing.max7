<?php
$lang->gitea->common        = 'Gitea';
$lang->gitea->browse        = '瀏覽Gitea';
$lang->gitea->search        = '搜索';
$lang->gitea->create        = '添加Gitea';
$lang->gitea->edit          = '編輯Gitea';
$lang->gitea->view          = 'Gitea詳情';
$lang->gitea->delete        = '刪除Gitea';
$lang->gitea->confirmDelete = '確認刪除該Gitea嗎？';
$lang->gitea->bindUser      = '綁定用戶';
$lang->gitea->giteaAvatar   = '頭像';
$lang->gitea->giteaAccount  = 'Gitea用戶';
$lang->gitea->giteaEmail    = '郵箱';
$lang->gitea->zentaoAccount = '禪道用戶';
$lang->gitea->bindingStatus = '綁定狀態';
$lang->gitea->all           = '全部';
$lang->gitea->notBind       = '未綁定';
$lang->gitea->binded        = '已綁定';
$lang->gitea->bindDynamic   = '%s與禪道用戶%s';
$lang->gitea->bindedError   = '綁定的用戶已刪除或者已修改，請重新綁定';
$lang->gitea->zentaoEmail   = '禪道用戶郵箱';
$lang->gitea->accountDesc   = '(系統會將相同郵箱地址的用戶自動匹配)';

$lang->gitea->bindStatus['binded']      = $lang->gitea->binded;
$lang->gitea->bindStatus['notBind']     = "<span class='text-danger'>{$lang->gitea->notBind}</span>";
$lang->gitea->bindStatus['bindedError'] = "<span class='text-danger'>{$lang->gitea->bindedError}</span>";

$lang->gitea->browseAction = 'Gitea列表';
$lang->gitea->deleteAction = '刪除Gitea';

$lang->gitea->id    = 'ID';
$lang->gitea->name  = "應用名稱";
$lang->gitea->url   = '伺服器地址';
$lang->gitea->token = 'Token';

$lang->gitea->tokenLimit    = "Gitea Token權限不足。";
$lang->gitea->hostError     = "當前Gitea伺服器地址無效，請確認當前伺服器可被訪問";
$lang->gitea->bindUserError = "不能重複綁定用戶 %s";

$lang->gitea->server        = "伺服器列表";
$lang->gitea->lblCreate     = '添加Gitea伺服器';
$lang->gitea->emptyError    = "不能為空";
$lang->gitea->createSuccess = "創建成功";

$lang->gitea->apiError = array();
$lang->gitea->apiError[0] = 'The repository with the same name already exists.';

$lang->gitea->errorLang = array();
$lang->gitea->errorLang[0] = '名稱已存在。';

$lang->gitea->errorKey = array();
$lang->gitea->errorKey[0] = 'name';

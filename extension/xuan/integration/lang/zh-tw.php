<?php
$lang->integration->common     = '整合';
$lang->integration->switch     = '功能開關';
$lang->integration->switchList = array('禁用', '啟用');

$lang->integration->type = '類型';

$lang->integration->office        = 'Office 整合';
$lang->integration->collabora     = 'Collabora Online';
$lang->integration->collaboraPath = 'Collabora 地址';

$lang->integration->error = new stdclass();
$lang->integration->error->cannotConnectToCollabora = '無法連接到 Collabora Online，請檢查 Collabora Online 是否正確配置，或者網絡是否暢通。';
$lang->integration->error->officeNotEnabled         = '服務端尚未開啟 Office 整合。';
$lang->integration->error->userNotFoundForRequest   = '無法找到該請求對應的用戶。';
$lang->integration->error->fileNotFoundForRequest   = '無法找到該請求對應的檔案。';
$lang->integration->error->filePreviewNotSupported  = '不支持預覽該檔案。';
$lang->integration->error->buildIdentifierFail      = '參數有誤，無法預覽。';

$lang->integration->placeholder = new stdclass();
$lang->integration->placeholder->collabora = '填寫 Collabora Online 的 URL，如 https://192.168.1.2:9980';

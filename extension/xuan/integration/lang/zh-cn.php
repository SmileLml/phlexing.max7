<?php
$lang->integration->common     = '集成';
$lang->integration->switch     = '功能开关';
$lang->integration->switchList = array('禁用', '启用');

$lang->integration->type = '类型';

$lang->integration->office        = 'Office 集成';
$lang->integration->collabora     = 'Collabora Online';
$lang->integration->collaboraPath = 'Collabora 地址';

$lang->integration->error = new stdclass();
$lang->integration->error->cannotConnectToCollabora = '无法连接到 Collabora Online，请检查 Collabora Online 是否正确配置，或者网络是否畅通。';
$lang->integration->error->officeNotEnabled         = '服务端尚未开启 Office 集成。';
$lang->integration->error->userNotFoundForRequest   = '无法找到该请求对应的用户。';
$lang->integration->error->fileNotFoundForRequest   = '无法找到该请求对应的文件。';
$lang->integration->error->filePreviewNotSupported  = '不支持预览该文件。';
$lang->integration->error->buildIdentifierFail      = '参数有误，无法预览。';

$lang->integration->placeholder = new stdclass();
$lang->integration->placeholder->collabora = '填写 Collabora Online 的 URL，如 https://192.168.1.2:9980';

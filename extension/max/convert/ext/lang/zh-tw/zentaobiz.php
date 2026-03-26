<?php
$lang->convert->jira->zentaoObjectList['feedback']   = '反饋';
$lang->convert->jira->zentaoObjectList['ticket']     = '工單';
$lang->convert->jira->zentaoObjectList['add_custom'] = '新增成工作流';

$lang->convert->jira->zentaoLinkTypeList['add_relation'] = '新增關係';

$lang->convert->confluence = new stdclass();
$lang->convert->confluence->import       = '導入Confluence';
$lang->convert->confluence->notice       = 'Confluence導入提示';
$lang->convert->confluence->domain       = 'Confluence域名';
$lang->convert->confluence->admin        = 'Confluence管理員帳號';
$lang->convert->confluence->token        = 'Confluence密碼/Token';
$lang->convert->confluence->apiError     = '無法連接到ConfluenceAPI介面，請檢查您的Confluence域名和帳號、密碼/Token信息。';
$lang->convert->confluence->importDesc   = '使用REST API介面導入數據，請保證與Confluencee伺服器的網絡連接暢通。';
$lang->convert->confluence->importSpace  = '產品空間或項目空間必須選擇一個產品或項目。';
$lang->convert->confluence->mapSpaceDesc = '"只對我可見"的空間預設映射到禪道"我的空間"下，並自動創建同空間名稱的庫，其他空間預設不映射。';
$lang->convert->confluence->mapToZentao  = '設置Jira空間與禪道數據對應關係';
$lang->convert->confluence->importUser   = '導入Confluence用戶';
$lang->convert->confluence->importData   = '導入Confluence數據';
$lang->convert->confluence->successfully = 'Confluence導入完成！';
$lang->convert->confluence->defaultSpace = 'Confluence空間';
$lang->convert->confluence->space        = 'Confluence空間';
$lang->convert->confluence->zentaoSpace  = '禪道空間';
$lang->convert->confluence->spaceKey     = '空間標識';
$lang->convert->confluence->archived     = '已歸檔';
$lang->convert->confluence->undefined    = '未命名';
$lang->convert->confluence->spaceMap     = 'Confluence空間映射';
$lang->convert->confluence->userNotice   = '系統將根據郵箱地址自動合併重複用戶，保證每個郵箱僅對應一個賬號。';

$lang->convert->confluence->importSteps[1] = '備份禪道資料庫，備份Confluence資料庫。';
$lang->convert->confluence->importSteps[2] = '導入數據時使用禪道會給伺服器造成性能壓力，請儘量保證導入數據時無人使用禪道。';
$lang->convert->confluence->importSteps[3] = "將Confluence附件目錄<strong class='text-danger'> attachments</strong> 放到 <strong class='text-danger'>%s</strong> 下，確保禪道伺服器磁碟空間足夠。";
$lang->convert->confluence->importSteps[4] = "填寫當前Confluence環境的域名、管理員帳號、密碼/Token。";
$lang->convert->confluence->importSteps[5] = "上述步驟完成後，點擊下一步。";

$lang->convert->confluence->objectList['user']       = '用戶';
$lang->convert->confluence->objectList['space']      = '空間';
$lang->convert->confluence->objectList['folder']     = '檔案夾';
$lang->convert->confluence->objectList['page']       = '文檔';
$lang->convert->confluence->objectList['embed']      = '智能連結';
$lang->convert->confluence->objectList['whiteboard'] = '白板';
$lang->convert->confluence->objectList['database']   = '資料庫';
$lang->convert->confluence->objectList['blogpost']   = '博文';
$lang->convert->confluence->objectList['draft']      = '草稿文檔';
$lang->convert->confluence->objectList['archived']   = '已歸檔文檔';
$lang->convert->confluence->objectList['version']    = '文檔歷史版本';
$lang->convert->confluence->objectList['comment']    = '文檔評論';
$lang->convert->confluence->objectList['attachment'] = '附件';

$lang->convert->confluence->zentaoSpaceList = array();
$lang->convert->confluence->zentaoSpaceList['product'] = '產品空間';
$lang->convert->confluence->zentaoSpaceList['project'] = '項目空間';
$lang->convert->confluence->zentaoSpaceList['custom']  = '團隊空間';
$lang->convert->confluence->zentaoSpaceList['mine']    = '我的空間';

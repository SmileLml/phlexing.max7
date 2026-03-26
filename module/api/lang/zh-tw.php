<?php
/**
 * The api module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     api
 * @version     $Id: zh-tw.php 5129 2013-07-15 00:16:07Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->api->common   = 'API介面';
$lang->api->getModel = '超級model調用介面';
$lang->api->sql      = 'SQL查詢介面';
$lang->api->manage   = '介面管理';

$lang->api->index               = '介面空間';
$lang->api->view                = '介面詳情';
$lang->api->editLib             = '編輯庫';
$lang->api->releases            = '版本管理';
$lang->api->deleteRelease       = '刪除版本';
$lang->api->deleteLib           = '刪除庫';
$lang->api->createRelease       = '發佈介面';
$lang->api->createLib           = '創建庫';
$lang->api->createApi           = '創建介面';
$lang->api->createAB            = '創建';
$lang->api->createDemo          = '導入禪道API';
$lang->api->edit                = '編輯介面';
$lang->api->delete              = '刪除介面';
$lang->api->position            = '位置';
$lang->api->startLine           = "%s,%s行";
$lang->api->desc                = '描述';
$lang->api->debug               = '調試';
$lang->api->submit              = '提交';
$lang->api->url                 = '請求地址';
$lang->api->result              = '返回結果';
$lang->api->status              = '狀態';
$lang->api->data                = '內容';
$lang->api->noParam             = 'GET方式調試不需要輸入參數，';
$lang->api->noModule            = '庫下沒有目錄，請先維護目錄';
$lang->api->post                = 'POST方式調試請參照頁面表單';
$lang->api->noUniqueName        = '庫名已存在。';
$lang->api->noUniqueVersion     = '版本已存在。';
$lang->api->createStruct        = '創建資料結構';
$lang->api->editStruct          = '編輯資料結構';
$lang->api->deleteStruct        = '刪除資料結構';
$lang->api->create              = '創建介面';
$lang->api->title               = '介面名稱';
$lang->api->pageTitle           = '庫';
$lang->api->module              = '目錄';
$lang->api->apiDoc              = '介面';
$lang->api->manageType          = '維護目錄';
$lang->api->managePublish       = '版本管理';
$lang->api->doing               = '開發中';
$lang->api->done                = '開發完成';
$lang->api->basicInfo           = '基本信息';
$lang->api->apiDesc             = '介面說明';
$lang->api->confirmDelete       = "您確定刪除該介面嗎？";
$lang->api->confirmDeleteLib    = "您確定刪除該庫嗎？";
$lang->api->confirmDeleteStruct = "您確定刪除該資料結構嗎？";
$lang->api->filterStruct        = "使用資料結構填充";
$lang->api->defaultVersion      = "當前版本";
$lang->api->latestVersion       = '最新版本';
$lang->api->zentaoAPI           = "禪道API文檔v1";
$lang->api->search              = "搜索";
$lang->api->allLibs             = "全部庫";
$lang->api->noLinked            = "獨立介面";
$lang->api->apiCatalog          = '介面目錄';
$lang->api->addCatalog          = '添加目錄';
$lang->api->editCatalog         = '編輯目錄';
$lang->api->sortCatalog         = '目錄排序';
$lang->api->deleteCatalog       = '刪除目錄';

/* Common access control lang. */
$lang->api->whiteList          = '白名單';
$lang->api->aclList['open']    = "公開 （有文檔視圖權限即可訪問）";
$lang->api->aclList['default'] = "預設 （有所選%s訪問權限用戶可以訪問）";
$lang->api->aclList['private'] = "私有 （僅創建者和白名單用戶可訪問）";
$lang->api->group              = '分組';
$lang->api->user               = '用戶';

$lang->api->noticeAcl = array(
    'open'    => '所有人都可以訪問',
    'custom'  => '白名單的用戶可以訪問',
    'private' => '只有創建者自己可以訪問',
);

/* fields of struct */
$lang->struct = new stdClass();

$lang->struct->add             = '添加';
$lang->struct->field           = '欄位';
$lang->struct->paramsType      = '類型';
$lang->struct->required        = '必填';
$lang->struct->desc            = '描述';
$lang->struct->descPlaceholder = '參數說明';
$lang->struct->action          = '操作';
$lang->struct->addSubField     = '添加子欄位';
$lang->struct->list            = '資料結構列表';
$lang->struct->type            = 'Body類型';

$lang->struct->typeOptions = array(
    'formData' => 'FormData',
    'json'     => 'JSON',
    'array'    => 'Array',
    'object'   => 'Object',
);

/* fields of form */
$lang->api->struct             = '資料結構';
$lang->api->structName         = '結構名';
$lang->api->structType         = '類型';
$lang->api->structAttr         = '屬性';
$lang->api->structAddedBy      = '創建人';
$lang->api->structAddedDate    = '創建時間';
$lang->api->name               = '庫名稱';
$lang->api->baseUrl            = '請求基礎路徑';
$lang->api->baseUrlDesc        = '網址或者路徑，比如 http://test.zentao.net 或者 /v1';
$lang->api->desc               = '描述';
$lang->api->control            = '訪問控制';
$lang->api->noLib              = '暫時沒有庫。';
$lang->api->noApi              = '暫時沒有介面。';
$lang->api->noStruct           = '暫時沒有資料結構。';
$lang->api->noRelease          = '暫時沒有版本。';
$lang->api->lib                = '所屬庫';
$lang->api->apiList            = '介面列表';
$lang->api->formTitle          = '介面名稱';
$lang->api->path               = '請求路徑';
$lang->api->protocol           = '請求協議';
$lang->api->method             = '請求方式';
$lang->api->requestType        = '請求格式';
$lang->api->status             = '開髮狀態';
$lang->api->owner              = '負責人';
$lang->api->paramsExample      = '請求示例';
$lang->api->header             = '請求頭';
$lang->api->query              = '請求參數';
$lang->api->params             = '請求體';
$lang->api->response           = '請求響應';
$lang->api->responseExample    = '響應示例';
$lang->api->id                 = 'ID';
$lang->api->addedBy            = '創建者';
$lang->api->addedDate          = '創建時間';
$lang->api->editedBy           = '修改者';
$lang->api->editedDate         = '修改時間';
$lang->api->version            = '版本號';
$lang->api->res                = new stdClass();
$lang->api->res->name          = '名稱';
$lang->api->res->desc          = '描述';
$lang->api->res->type          = '類型';
$lang->api->req                = new stdClass();
$lang->api->req->name          = '名稱';
$lang->api->req->desc          = '描述';
$lang->api->req->type          = '類型';
$lang->api->req->required      = '必填';
$lang->api->field              = '欄位';
$lang->api->scope              = '位置';
$lang->api->paramsType         = '類型';
$lang->api->required           = '是否必填';
$lang->api->default            = '預設值';
$lang->api->desc               = '描述';
$lang->api->customType         = '自定義結構';
$lang->api->format             = '格式化';
$lang->api->libType            = '介面庫類型';
$lang->api->product            = '所屬' . $lang->productCommon;
$lang->api->project            = '所屬' . $lang->projectCommon;
$lang->api->apiTotalInfo       = '共 %d 個 API';
$lang->api->showNotEmpty       = '僅顯示有介面的';
$lang->api->showClosed         = '顯示包含已關閉的';

$lang->api->methodOptions      = array(
    'GET'     => 'GET',
    'POST'    => 'POST',
    'PUT'     => 'PUT',
    'DELETE'  => 'DELETE',
    'PATCH'   => 'PATCH',
    'OPTIONS' => 'OPTIONS',
    'HEAD'    => 'HEAD'
);

$lang->api->protocalOptions = array();
$lang->api->protocalOptions['HTTP']  = 'HTTP';
$lang->api->protocalOptions['HTTPS'] = 'HTTPS';
$lang->api->protocalOptions['WS']    = 'WS';
$lang->api->protocalOptions['WSS']   = 'WSS';

$lang->api->requestTypeOptions = array();
$lang->api->requestTypeOptions['application/json']                  = 'application/json';
$lang->api->requestTypeOptions['application/x-www-form-urlencoded'] = 'application/x-www-form-urlencoded';
$lang->api->requestTypeOptions['multipart/form-data']               = 'multipart/form-data';

$lang->api->libTypeList = array();
$lang->api->libTypeList['product'] = $lang->productCommon . '介面';
$lang->api->libTypeList['project'] = $lang->projectCommon . '介面';
$lang->api->libTypeList['nolink']  = '獨立介面';

$lang->api->statusOptions      = array(
    'done'   => '開發完成',
    'doing'  => '開發中',
    'hidden' => '不顯示'
);
$lang->api->paramsScopeOptions = array(
    'formData' => 'formData',
    'path'     => 'path',
    'query'    => 'query',
    'body'     => 'body',
    'header'   => 'header',
    'cookie'   => 'cookie',
);
/* Api global common params */
$lang->api->paramsTypeOptions = array(
    'object'   => 'object',
    'array'    => 'array',
    'string'   => 'string',
    'date'     => 'date',
    'datetime' => 'datetime',
    'boolean'  => 'boolean',
    'int'      => 'int',
    'long'     => 'long',
    'float'    => 'float',
    'double'   => 'double',
    'decimal'  => 'decimal'
);

$lang->api->boolList = array(false => '否', true => '是', '' => '否');

/* Api params */
$lang->api->paramsTypeCustomOptions = array('file' => 'file', 'ref' => 'ref');

$lang->api->structParamsOptons   = array_merge($lang->api->paramsTypeOptions, array('file' => 'file', 'ref' => 'ref'));
$lang->api->allParamsTypeOptions = array_merge($lang->api->paramsTypeOptions, $lang->api->paramsTypeCustomOptions);
$lang->api->requiredOptions      = array(0 => '否', 1 => '是');

$lang->apistruct = new stdClass();
$lang->apistruct->name = '結構名';

$lang->api_lib_release = new stdClass();
$lang->api_lib_release->version = '版本號';
$lang->api_lib_release->desc    = '描述';

$lang->api->error             = new stdclass();
$lang->api->error->onlySelect = 'SQL查詢介面只允許SELECT查詢';
$lang->api->error->disabled   = '因為安全原因，該功能被禁用。可以到config目錄，修改配置項 %s，打開此功能。';
$lang->api->error->notInput   = '因欄位參數類型限制，暫不支持調試';

$lang->api->filterTypes[] = array('all', '全部');
$lang->api->filterTypes[] = array('createdByMe', '我創建的');
$lang->api->filterTypes[] = array('editedByMe', '我編輯的');

$lang->api->homeFilterTypes['nolink']  = $lang->api->libTypeList['nolink'];
$lang->api->homeFilterTypes['product'] = $lang->api->libTypeList['product'];
$lang->api->homeFilterTypes['project'] = $lang->api->libTypeList['project'];

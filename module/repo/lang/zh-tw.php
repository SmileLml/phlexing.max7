<?php
global $config;

$lang->repo->common          = '代碼庫';
$lang->repo->codeRepo        = '倉庫名稱';
$lang->repo->browse          = '瀏覽';
$lang->repo->viewRevision    = '查看修訂';
$lang->repo->product         = '關聯' . $lang->productCommon;
$lang->repo->projects        = '相關' . $lang->projectCommon;
$lang->repo->execution       = '所屬' . $lang->execution->common;
$lang->repo->create          = '創建';
$lang->repo->maintain        = '代碼庫列表';
$lang->repo->edit            = '編輯';
$lang->repo->delete          = '解除代碼庫';
$lang->repo->showSyncCommit  = '顯示同步進度';
$lang->repo->ajaxSyncCommit  = '介面：AJAX同步註釋';
$lang->repo->setRules        = '指令配置';
$lang->repo->download        = '下載';
$lang->repo->downloadDiff    = '下載Diff';
$lang->repo->addBug          = '添加評審';
$lang->repo->editBug         = '編輯評審';
$lang->repo->deleteBug       = '刪除評審';
$lang->repo->addComment      = '添加備註';
$lang->repo->editComment     = '編輯備註';
$lang->repo->deleteComment   = '刪除備註';
$lang->repo->encrypt         = '加密方式';
$lang->repo->repo            = '代碼庫';
$lang->repo->parent          = '父檔案夾';
$lang->repo->branch          = '分支';
$lang->repo->tag             = '標籤';
$lang->repo->addWebHook      = '添加Webhook';
$lang->repo->apiGetRepoByUrl = '介面：通過URL獲取代碼庫';
$lang->repo->blameTmpl       = '第 <strong>%line</strong> 行代碼相關信息： %name 于 %time 提交 %version %comment';
$lang->repo->notRelated      = '暫時沒有關聯禪道對象';
$lang->repo->source          = '基準';
$lang->repo->target          = '對比';
$lang->repo->descPlaceholder = '一句話描述';
$lang->repo->namespace       = '命名空間';
$lang->repo->branchName      = '分支名稱';
$lang->repo->branchFrom      = '創建自';
$lang->repo->codeBranch      = '代碼分支';
$lang->repo->createdBranch   = '已創建分支';
$lang->repo->unlink          = '解除關聯';
$lang->repo->visit           = '訪問';

$lang->repo->createBranchAction = '創建分支';
$lang->repo->createTagAction    = '創建標籤';
$lang->repo->browseAction       = '瀏覽代碼庫';
$lang->repo->createAction       = '導入代碼庫';
$lang->repo->editAction         = '編輯代碼庫';
$lang->repo->diffAction         = '代碼對比';
$lang->repo->downloadAction     = '下載代碼庫檔案';
$lang->repo->revisionAction     = '提交詳情';
$lang->repo->blameAction        = '追溯';
$lang->repo->reviewAction       = '評審列表';
$lang->repo->downloadCode       = '下載代碼';
$lang->repo->downloadZip        = '下載壓縮包';
$lang->repo->sshClone           = '使用SSH克隆';
$lang->repo->httpClone          = '使用HTTP克隆';
$lang->repo->cloneUrl           = '克隆地址';
$lang->repo->linkTask           = '關聯任務';
$lang->repo->unlinkedTasks      = '未關聯任務';
$lang->repo->importAction       = '批量導入代碼庫';
$lang->repo->import             = '導入';
$lang->repo->importName         = '導入後的名稱';
$lang->repo->importServer       = '請選擇伺服器';
$lang->repo->hide               = '隱藏';
$lang->repo->show               = '顯示';
$lang->repo->showHidden         = '顯示隱藏的代碼庫';
$lang->repo->gitlabList         = 'Gitlab代碼庫';
$lang->repo->batchCreate        = '批量導入代碼庫';
$lang->repo->browseTag          = '瀏覽標籤';
$lang->repo->browseBranch       = '瀏覽分支';

$lang->repo->createRepoAction = '創建遠程代碼庫';

$lang->repo->submit     = '提交';
$lang->repo->cancel     = '取消';
$lang->repo->addComment = '添加評論';
$lang->repo->addIssue   = '提問題';
$lang->repo->compare    = '比較';

$lang->repo->copy     = '點擊複製';
$lang->repo->copied   = '複製成功';
$lang->repo->module   = '模組';
$lang->repo->type     = '類型';
$lang->repo->assign   = '指派';
$lang->repo->title    = '標題';
$lang->repo->detile   = '詳情';
$lang->repo->lines    = '代碼行';
$lang->repo->line     = '行';
$lang->repo->expand   = '點擊展開';
$lang->repo->collapse = '點擊摺疊';

$lang->repo->id                 = 'ID';
$lang->repo->SCM                = '類型';
$lang->repo->name               = '名稱';
$lang->repo->path               = '地址';
$lang->repo->prefix             = '地址擴展';
$lang->repo->config             = '配置目錄';
$lang->repo->desc               = '描述';
$lang->repo->account            = '用戶名';
$lang->repo->password           = '密碼';
$lang->repo->encoding           = '編碼';
$lang->repo->client             = '客戶端';
$lang->repo->size               = '大小';
$lang->repo->revision           = '提交';
$lang->repo->revisionA          = '提交';
$lang->repo->revisions          = '提交';
$lang->repo->time               = '提交時間';
$lang->repo->committer          = '提交人';
$lang->repo->commits            = '提交數';
$lang->repo->synced             = '初始化同步';
$lang->repo->lastSync           = '最後同步時間';
$lang->repo->deleted            = '已刪除';
$lang->repo->commit             = '提交';
$lang->repo->comment            = '註釋';
$lang->repo->view               = '查看檔案';
$lang->repo->viewA              = '查看';
$lang->repo->log                = '提交歷史';
$lang->repo->blame              = '追溯';
$lang->repo->date               = '日期';
$lang->repo->diff               = '比較差異';
$lang->repo->diffAB             = '比較';
$lang->repo->diffAll            = '全部比較';
$lang->repo->viewDiff           = '查看差異';
$lang->repo->allLog             = '提交記錄';
$lang->repo->codeLocation       = '代碼位置';
$lang->repo->action             = '操作';
$lang->repo->code               = '代碼';
$lang->repo->review             = '評審';
$lang->repo->acl                = '瀏覽';
$lang->repo->group              = '分組';
$lang->repo->user               = '用戶';
$lang->repo->info               = '提交信息';
$lang->repo->job                = '構建任務';
$lang->repo->fileServerUrl      = '預合併後上傳伺服器目錄';
$lang->repo->fileServerAccount  = '檔案伺服器登錄用戶名';
$lang->repo->fileServerPassword = '檔案伺服器登錄密碼';
$lang->repo->linkStory          = '關聯' . $lang->SRCommon;
$lang->repo->linkBug            = '關聯Bug';
$lang->repo->linkTask           = '關聯任務';
$lang->repo->unlink             = '取消關聯';
$lang->repo->viewBugs           = '查看Bug';
$lang->repo->lastSubmitTime     = '最後提交時間';
$lang->repo->lastCommitter      = '提交人';
$lang->repo->lastUpdateTime     = '最後修改時間';
$lang->repo->createdBy          = '創建人';
$lang->repo->sourceCommit       = '來源提交';
$lang->repo->relations          = '相關';
$lang->repo->story              = '需求';
$lang->repo->searchTips         = '按%s搜索';
$lang->repo->design             = '設計';
$lang->repo->bug                = 'Bug';
$lang->repo->task               = '任務';

$lang->repo->title      = '標題';
$lang->repo->status     = '狀態';
$lang->repo->openedBy   = '創建者';
$lang->repo->assignedTo = '指派給';
$lang->repo->openedDate = '創建日期';

$lang->repo->actionInfo     = "由%s在%s添加";
$lang->repo->changes        = "修改記錄";
$lang->repo->reviewLocation = "%s@%s，%s行 - %s行";
$lang->repo->commentEdit    = '<i class="icon-pencil"></i>';
$lang->repo->commentDelete  = '<i class="icon-remove"></i>';
$lang->repo->allChanges     = "其他改動";
$lang->repo->commitTitle    = "第%s次提交";
$lang->repo->mark           = "開始標記";
$lang->repo->split          = "多ID間隔";

$lang->repo->objectRule   = '對象匹配規則';
$lang->repo->objectIdRule = '對象ID匹配規則';
$lang->repo->actionRule   = '動作匹配規則';
$lang->repo->manHourRule  = '工時匹配規則';
$lang->repo->ruleUnit     = "單位";
$lang->repo->ruleSplit    = "多關鍵字用';'分割，如：任務多關鍵字： Task;任務";

$lang->repo->viewDiffList['inline'] = '直列';
$lang->repo->viewDiffList['appose'] = '並排';

$lang->repo->encryptList['plain']  = '不加密';
$lang->repo->encryptList['base64'] = 'BASE64';

$lang->repo->logStyles['A'] = '添加';
$lang->repo->logStyles['M'] = '修改';
$lang->repo->logStyles['D'] = '刪除';

$lang->repo->encodingList['utf_8'] = 'UTF-8';
$lang->repo->encodingList['gbk']   = 'GBK';

$lang->repo->scmList['Gitlab'] = 'GitLab';
if(!$config->inQuickon)
{
    $lang->repo->scmList['Gitea']      = 'Gitea';
    $lang->repo->scmList['Gogs']       = 'Gogs';
    $lang->repo->scmList['Git']        = '本地 Git';
    $lang->repo->scmList['Subversion'] = 'Subversion';
}

$lang->repo->aclList['private'] = '私有 (所屬產品人員可訪問)';
$lang->repo->aclList['open']    = '公開 (有DevOps視圖權限即可訪問)';
$lang->repo->aclList['custom']  = '自定義';

$lang->repo->gitlabHost    = 'GitLab Server';
$lang->repo->gitlabToken   = 'GitLab Token';
$lang->repo->gitlabProject = 'GitLab 項目';

$lang->repo->serviceHost    = '伺服器';
$lang->repo->serviceProject = '倉庫';

$lang->repo->placeholder = new stdclass;
$lang->repo->placeholder->gitlabHost = '請填寫GitLab訪問地址';

$lang->repo->notice                 = new stdclass();
$lang->repo->notice->syncing        = '正在同步中, 請稍等...';
$lang->repo->notice->syncComplete   = '同步完成，正在跳轉...';
$lang->repo->notice->syncFailed     = '同步失敗';
$lang->repo->notice->syncedCount    = '已經同步記錄條數';
$lang->repo->notice->delete         = '是否解除關聯代碼庫？';
$lang->repo->notice->deleteConfirm  = '是否刪除代碼庫？此操作將永久移除該倉庫及其所有內容和歷史記錄，且無法恢復。';
$lang->repo->notice->successDelete  = '已經成功解除代碼庫。';
$lang->repo->notice->commentContent = '輸入評論內容';
$lang->repo->notice->deleteReview   = '確認刪除該評審？';
$lang->repo->notice->deleteBug      = '確認刪除該Bug？';
$lang->repo->notice->deleteComment  = '確認刪除該回覆？';
$lang->repo->notice->lastSyncTime   = '最後更新于：';
$lang->repo->notice->unlinkBranch   = '確認解除分支與%s的關聯關係嗎？';
$lang->repo->notice->noRepoLeft     = '該伺服器下的所有代碼庫都已經關聯到禪道了，請選擇其他伺服器。';
$lang->repo->notice->noChanges      = '沒有代碼差異';
$lang->repo->notice->storyNotActive = '需求不是激活狀態，不能創建分支。';
$lang->repo->notice->taskNotActive  = '任務不是未開始或進行中狀態，不能創建分支。';
$lang->repo->notice->bugNotActive   = 'Bug不是激活狀態，不能創建分支。';

$lang->repo->rules = new stdclass();
$lang->repo->rules->exampleLabel = "註釋示例";
$lang->repo->rules->example['task']['start']  = "%start% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['task']['finish'] = "%finish% %task% %id%1%split%2 %cost%%consumedmark%10%cunit%";
$lang->repo->rules->example['task']['effort'] = "%effort% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['bug']['resolve'] = "%resolve% %bug% %id%1%split%2";

$lang->repo->error = new stdclass();
$lang->repo->error->useless           = '你的伺服器禁用了exec,shell_exec方法，無法使用該功能';
$lang->repo->error->connect           = '連接代碼庫失敗，請填寫正確的用戶名、密碼和代碼庫地址！';
$lang->repo->error->version           = "https和svn協議需要1.8及以上版本的客戶端，請升級到最新版本！詳情訪問:http://subversion.apache.org/";
$lang->repo->error->path              = '代碼庫地址直接填寫檔案路徑，如：/home/test。';
$lang->repo->error->cmd               = '客戶端錯誤！';
$lang->repo->error->diff              = '必須選擇兩個提交';
$lang->repo->error->safe              = "因為安全原因，需要檢測客戶端版本，請將版本號寫入檔案 %s \n 可以執行命令：%s";
$lang->repo->error->product           = "請選擇{$lang->productCommon}！";
$lang->repo->error->commentText       = '請填寫評審內容';
$lang->repo->error->comment           = '請填寫內容';
$lang->repo->error->title             = '請填寫標題';
$lang->repo->error->accessDenied      = '你沒有權限訪問該代碼庫';
$lang->repo->error->noFound           = '你訪問的代碼庫不存在';
$lang->repo->error->empty             = '代碼庫內容為空，無法同步';
$lang->repo->error->noFile            = '目錄 %s 不存在';
$lang->repo->error->noPriv            = '程序沒有權限切換到目錄 %s';
$lang->repo->error->output            = "執行命令：%s\n錯誤結果(%s)： %s\n";
$lang->repo->error->clientVersion     = "客戶端版本過低，請升級或更換SVN客戶端";
$lang->repo->error->encoding          = "編碼可能錯誤，請更換編碼重試。";
$lang->repo->error->deleted           = "解除失敗，提交記錄與設計( %s )關聯。<br/>";
$lang->repo->error->linkedBranch      = "解除失敗，代碼庫與%s分支( %s )關聯。<br/>";
$lang->repo->error->linkedJob         = "解除失敗，代碼庫與構建( %s )關聯。<br/>";
$lang->repo->error->clientPath        = "客戶端安裝目錄不能有空格和特殊字元！";
$lang->repo->error->notFound          = "代碼庫『%s』路徑 %s 不存在，請確認此代碼庫是否已在本地伺服器被刪除";
$lang->repo->error->noWritable        = '%s 不可寫！請檢查該目錄權限，否則無法下載。';
$lang->repo->error->noCloneAddr       = '該項目克隆地址未找到';
$lang->repo->error->differentVersions = '基準和對比不能一樣';
$lang->repo->error->needTwoVersion    = '必須選擇兩個分支/標籤';
$lang->repo->error->projectUnique     = $lang->repo->serviceProject . '已經有這條記錄了。如果您確定該記錄已刪除，請到後台-系統設置-資源回收筒還原。';
$lang->repo->error->repoNameInvalid   = '名稱必須以字母或 _ 開頭，只包含字母數字，破折號，下劃線和點。';
$lang->repo->error->createdFail       = '創建失敗';
$lang->repo->error->noProduct         = '在開始導入代碼庫之前，請先關聯產品。';

$lang->repo->syncTips          = '請參照<a target="_blank" href="https://www.zentao.net/book/zentaopmshelp/207.html">這裡</a>，設置代碼庫定時同步。';
$lang->repo->encodingsTips     = "提交日誌的編碼，可以用逗號連接起來的多個，比如utf-8。";
$lang->repo->pathTipsForGitlab = "GitLab 項目URL";

$lang->repo->example              = new stdclass();
$lang->repo->example->client      = new stdclass();
$lang->repo->example->path        = new stdclass();
$lang->repo->example->client->git = "例如：/usr/bin/git";
$lang->repo->example->client->svn = "例如：/usr/bin/svn";
$lang->repo->example->path->git   = "例如：/home/user/myproject";
$lang->repo->example->path->svn   = "例如：http://example.googlecode.com/svn/trunk/myproject";
$lang->repo->example->config      = "https需要填寫配置目錄的位置，通過config-dir選項生成配置目錄";
$lang->repo->example->encoding    = "填寫代碼庫中檔案的編碼";

$lang->repo->typeList['standard']    = '規範';
$lang->repo->typeList['performance'] = '性能';
$lang->repo->typeList['security']    = '安全';
$lang->repo->typeList['redundancy']  = '冗餘';
$lang->repo->typeList['logicError']  = '邏輯錯誤';

$lang->repo->featureBar['maintain']['all'] = '全部';

$lang->repo->errorLang[0] = "只能包含字母、數字、'.'-'和'.'。不能以'-'開頭、以'.git'結尾或以'.atom'結尾。";
$lang->repo->errorLang[1] = '分支名已存在。';
$lang->repo->errorLang[2] = '分支名已存在。';
$lang->repo->errorLang[3] = '權限不足。';
$lang->repo->errorLang[4] = "分支名不能包含 ' ', '~', '^'或':'。";
$lang->repo->errorLang[5] = '分支創建失敗';
$lang->repo->errorLang[6] = '權限不足。';

$lang->repo->apiError[0] = "can contain only letters, digits, '_', '-' and '.'. Cannot start with '-', end in '.git' or end in '.atom'";
$lang->repo->apiError[1] = 'Branch is exists';
$lang->repo->apiError[2] = 'branch.* already exists';
$lang->repo->apiError[3] = 'Forbidden';
$lang->repo->apiError[4] = 'cannot have ASCII control characters';
$lang->repo->apiError[5] = 'Created fail';
$lang->repo->apiError[6] = 'Project Not Found';

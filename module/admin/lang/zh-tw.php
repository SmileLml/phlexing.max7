<?php
/**
 * The admin module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: zh-tw.php 4767 2013-05-05 06:10:13Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->admin->index           = '後台管理首頁';
$lang->admin->sso             = 'ZDOO整合';
$lang->admin->ssoAction       = 'ZDOO整合';
$lang->admin->safeIndex       = '密碼安全設置';
$lang->admin->checkWeak       = '弱口令檢查';
$lang->admin->certifyMobile   = '認證手機';
$lang->admin->certifyEmail    = '認證郵箱';
$lang->admin->ztCompany       = '認證公司';
$lang->admin->captcha         = '驗證碼';
$lang->admin->getCaptcha      = '獲取驗證碼';
$lang->admin->register        = '登記';
$lang->admin->resetPWDSetting = '重置密碼設置';
$lang->admin->tableEngine     = '表引擎';
$lang->admin->setModuleIndex  = '系統功能配置';

$lang->admin->mon              = '月';
$lang->admin->day              = '天';
$lang->admin->updateDynamics   = '更新動態';
$lang->admin->updatePatch      = '補丁更新';
$lang->admin->upgradeRecommend = '推薦升級';
$lang->admin->zentaoUsed       = '您已使用禪道';

$lang->admin->api                  = '介面';
$lang->admin->log                  = '日誌';
$lang->admin->setting              = '設置';
$lang->admin->pluginRecommendation = '插件推薦';
$lang->admin->zentaoInfo           = '禪道信息';
$lang->admin->officialAccount      = '官方公眾號';
$lang->admin->publicClass          = '公開課';
$lang->admin->days                 = '日誌保存天數';
$lang->admin->resetPWDByMail       = '通過郵箱重置密碼';
$lang->admin->followUs             = '掃碼關注公眾號';
$lang->admin->followUsContent      = '隨時查看禪道動態、活動信息、也可獲取幫助支持';

$lang->admin->changeEngine               = "更換到InnoDB";
$lang->admin->changingTable              = '正在更換數據表%s引擎...';
$lang->admin->changeSuccess              = '已經更換數據表%s引擎為InnoDB。';
$lang->admin->changeFail                 = "更換數據表%s引擎失敗，原因：<span class='text-red'>%s</span>。";
$lang->admin->errorInnodb                = '您當前的資料庫不支持使用InnoDB數據表引擎。';
$lang->admin->changeFinished             = "更換資料庫引擎完畢。";
$lang->admin->engineInfo                 = "表<strong>%s</strong>的引擎是<strong>%s</strong>。";
$lang->admin->engineSummary['hasMyISAM'] = "有%s個表不是InnoDB引擎";
$lang->admin->engineSummary['allInnoDB'] = "所有的表都是InnoDB引擎了";

$lang->admin->info = new stdclass();
$lang->admin->info->version = '當前系統的版本是%s，';
$lang->admin->info->links   = '您可以訪問以下連結：';
$lang->admin->info->account = "您的禪道社區賬戶為%s。";
$lang->admin->info->log     = '超出存天數的日誌會被刪除，需要開啟計劃任務。';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "可%s禪道社區 www.zentao.net，及時獲得禪道最新信息。";
$lang->admin->notice->ignore   = "不再提示";
$lang->admin->notice->int      = "『%s』應當是正整數。";
$lang->admin->notice->openUR   = "請先開啟{$lang->URCommon}，才能使用{$lang->ERCommon}。";

$lang->admin->registerNotice = new stdclass();
$lang->admin->registerNotice->common     = '註冊新帳號';
$lang->admin->registerNotice->caption    = '禪道社區登記';
$lang->admin->registerNotice->click      = '點擊此處';
$lang->admin->registerNotice->lblAccount = '請設置您的用戶名，英文字母和數字的組合，三位以上。';
$lang->admin->registerNotice->lblPasswd  = '請設置您的密碼。數字和字母的組合，六位以上。';
$lang->admin->registerNotice->submit     = '登記';
$lang->admin->registerNotice->submitHere = '在此登記';
$lang->admin->registerNotice->bind       = "綁定已有帳號";
$lang->admin->registerNotice->success    = "登記賬戶成功";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = '關聯社區帳號';
$lang->admin->bind->success = "關聯賬戶成功";
$lang->admin->bind->submit  = "綁定";

$lang->admin->setModule = new stdclass();
$lang->admin->setModule->module         = '功能點';
$lang->admin->setModule->optional       = '可選功能';
$lang->admin->setModule->opened         = '已開啟';
$lang->admin->setModule->closed         = '已關閉';

$lang->admin->setModule->my             = '地盤';
$lang->admin->setModule->product        = $lang->productCommon;
$lang->admin->setModule->scrum          = '敏捷' . $lang->projectCommon;
$lang->admin->setModule->waterfall      = '瀑布' . $lang->projectCommon;
$lang->admin->setModule->agileplus      = '融合敏捷' . $lang->projectCommon;
$lang->admin->setModule->waterfallplus  = '融合瀑布' . $lang->projectCommon;
$lang->admin->setModule->assetlib       = '資產庫';
$lang->admin->setModule->other          = '通用功能';

$lang->admin->setModule->score          = '積分';
$lang->admin->setModule->repo           = '代碼';
$lang->admin->setModule->issue          = '問題';
$lang->admin->setModule->risk           = '風險';
$lang->admin->setModule->opportunity    = '機會';
$lang->admin->setModule->process        = '過程';
$lang->admin->setModule->auditplan      = 'QA';
$lang->admin->setModule->meeting        = '會議';
$lang->admin->setModule->roadmap        = '路線圖';
$lang->admin->setModule->track          = '矩陣';
$lang->admin->setModule->ER             = $lang->ERCommon;
$lang->admin->setModule->UR             = $lang->URCommon;
$lang->admin->setModule->researchplan   = '調研';
$lang->admin->setModule->gapanalysis    = '培訓';
$lang->admin->setModule->storylib       = '需求庫';
$lang->admin->setModule->caselib        = '用例庫';
$lang->admin->setModule->issuelib       = '問題庫';
$lang->admin->setModule->risklib        = '風險庫';
$lang->admin->setModule->opportunitylib = '機會庫';
$lang->admin->setModule->practicelib    = '最佳實踐庫';
$lang->admin->setModule->componentlib   = '組件庫';
$lang->admin->setModule->devops         = 'DevOps';
$lang->admin->setModule->deliverable    = '交付物';
$lang->admin->setModule->kanban         = '通用看板';
$lang->admin->setModule->OA             = '辦公';
$lang->admin->setModule->deploy         = '運維';
$lang->admin->setModule->traincourse    = '學堂';
$lang->admin->setModule->setCode        = '代號';
$lang->admin->setModule->measrecord     = '度量';

$lang->admin->safe = new stdclass();
$lang->admin->safe->common                   = '安全策略';
$lang->admin->safe->set                      = '密碼安全設置';
$lang->admin->safe->password                 = '密碼安全';
$lang->admin->safe->weak                     = '常用弱口令';
$lang->admin->safe->reason                   = '類型';
$lang->admin->safe->checkWeak                = '弱口令掃瞄';
$lang->admin->safe->changeWeak               = '修改弱口令密碼';
$lang->admin->safe->loginCaptcha             = '登錄使用驗證碼';
$lang->admin->safe->modifyPasswordFirstLogin = '首次登錄修改密碼';
$lang->admin->safe->passwordStrengthWeak     = '密碼強度小於系統設置';

$lang->admin->safe->modeList[0] = '不檢查';
$lang->admin->safe->modeList[1] = '中';
$lang->admin->safe->modeList[2] = '強';

$lang->admin->safe->modeRuleList[1] = '6位及以上，包含大小寫字母，數字。';
$lang->admin->safe->modeRuleList[2] = '10位及以上，包含大小寫字母，數字，特殊字元。';

$lang->admin->safe->reasonList['weak']     = '常用弱口令';
$lang->admin->safe->reasonList['account']  = '與帳號相同';
$lang->admin->safe->reasonList['mobile']   = '與手機相同';
$lang->admin->safe->reasonList['phone']    = '與電話相同';
$lang->admin->safe->reasonList['birthday'] = '與生日相同';

$lang->admin->safe->modifyPasswordList[1] = '必須修改';
$lang->admin->safe->modifyPasswordList[0] = '不強制';

$lang->admin->safe->loginCaptchaList[1] = '是';
$lang->admin->safe->loginCaptchaList[0] = '否';

$lang->admin->safe->resetPWDList[1] = '開啟';
$lang->admin->safe->resetPWDList[0] = '關閉';

$lang->admin->safe->noticeMode     = '系統會在創建和修改用戶、修改密碼的時候檢查用戶口令。';
$lang->admin->safe->noticeWeakMode = '系統會在登錄、創建和修改用戶、修改密碼的時候檢查用戶口令。';
$lang->admin->safe->noticeStrong   = '密碼長度越長，含有大寫字母或數字或特殊符號越多，密碼字母越不重複，安全度越強！';
$lang->admin->safe->noticeGd       = '系統檢測到您的伺服器未安裝GD模組或未啟用FreeType支持，無法使用驗證碼功能，請安裝後使用。';

$lang->admin->menuSetting['system']['name']        = '系統設置';
$lang->admin->menuSetting['system']['desc']        = '備份、聊天、安全等系統各要素配置。';
$lang->admin->menuSetting['user']['name']          = '人員管理';
$lang->admin->menuSetting['user']['desc']          = '維護部門、添加人員、分組配置權限。';
$lang->admin->menuSetting['switch']['name']        = '功能開關';
$lang->admin->menuSetting['switch']['desc']        = '打開、關閉系統部分功能。';
$lang->admin->menuSetting['model']['name']         = '項目模型配置';
$lang->admin->menuSetting['model']['desc']         = '不同項目管理模型和項目通用要素配置。';
$lang->admin->menuSetting['feature']['name']       = '功能配置';
$lang->admin->menuSetting['feature']['desc']       = '按照功能菜單進行系統的要素配置。';
$lang->admin->menuSetting['template']['name']      = '文檔模板';
$lang->admin->menuSetting['template']['desc']      = '配置文檔的模板類型和模板內容。';
$lang->admin->menuSetting['message']['name']       = '通知設置';
$lang->admin->menuSetting['message']['desc']       = '配置通知路徑，自定義需要通知的動作。';
$lang->admin->menuSetting['extension']['name']     = '插件管理';
$lang->admin->menuSetting['extension']['desc']     = '瀏覽、安裝插件。';
$lang->admin->menuSetting['dev']['name']           = '二次開發';
$lang->admin->menuSetting['dev']['desc']           = '支持對系統進行二次開發。';
$lang->admin->menuSetting['convert']['name']       = '數據導入';
$lang->admin->menuSetting['convert']['desc']       = '第三方系統的數據導入。';
$lang->admin->menuSetting['ai']['name']            = 'AI 配置';
$lang->admin->menuSetting['ai']['desc']            = '支持配置與管理AI提詞、AI小程序及大語言模型。';
$lang->admin->menuSetting['adminregister']['name'] = '加入禪道社區';
$lang->admin->menuSetting['adminregister']['desc'] = '獲取項目管理大禮包、技術支持服務、體驗各版本Demo。';

$lang->admin->updateDynamics   = '更新動態';
$lang->admin->updatePatch      = '補丁更新';
$lang->admin->upgradeRecommend = '推薦升級';
$lang->admin->zentaoUsed       = '您已使用禪道';
$lang->admin->noPriv           = '您沒有訪問該區塊的權限。';

$lang->admin->openTag = '禪道';
$lang->admin->bizTag  = '禪道企業版';
$lang->admin->maxTag  = '禪道旗艦版';
$lang->admin->ipdTag  = '禪道IPD版';

$lang->admin->bizInfoURL    = 'https://www.zentao.net/page/enterprise.html';
$lang->admin->maxInfoURL    = 'https://www.zentao.net/page/max.html';
$lang->admin->productDetail = '查看詳情';
$lang->admin->productFeature['biz'][] = '工時管理、甘特圖、導入導出';
$lang->admin->productFeature['biz'][] = '40+內置統計報表、自定義報表功能';
$lang->admin->productFeature['biz'][] = '強大的自定義工作流、反饋管理功能';
$lang->admin->productFeature['biz'][] = '價格厚道，專屬技術支持服務';
$lang->admin->productFeature['max'][] = '120+概念，全面覆蓋瀑布管理模型';
$lang->admin->productFeature['max'][] = '項目管理可視化，精準掌控項目進度';
$lang->admin->productFeature['max'][] = '資產庫管理，為項目提供數據支撐';
$lang->admin->productFeature['max'][] = '嚴格權限控制，方式靈活安全';
$lang->admin->productFeature['ipd'][] = '內置需求池管理，用於需求收集分發';
$lang->admin->productFeature['ipd'][] = '完整支持產品路標規劃和立項流程';
$lang->admin->productFeature['ipd'][] = '提供完整的市場管理、調研管理和報告管理';
$lang->admin->productFeature['ipd'][] = '提供完整的IPD研發流程，內置TR和DCP評審';

$lang->admin->ai = new stdclass();
$lang->admin->ai->model        = '語言模型';
$lang->admin->ai->conversation = 'AI 會話';
$lang->admin->ai->miniPrograms = 'AI 小程序';
$lang->admin->ai->prompt       = 'AI 提詞';
$lang->admin->ai->assistant    = 'AI 助手';

$lang->admin->community = new stdclass();
$lang->admin->community->registerTitle       = '加入禪道社區';
$lang->admin->community->skip                = '跳過';
$lang->admin->community->uxPlanTitle         = '禪道用戶體驗改進計劃';
$lang->admin->community->loginFailed         = '登錄失敗';
$lang->admin->community->loginFailedMobile   = '請填寫手機號';
$lang->admin->community->loginFailedCode     = '請填寫驗證碼';
$lang->admin->community->officialWebsite     = '禪道官網 ';
$lang->admin->community->uxPlanWithBookTitle = '《禪道用戶體驗改進計劃》';
$lang->admin->community->uxPlanStatusTitle   = '幫助我們瞭解產品使用情況。';
$lang->admin->community->mobile              = '手機號';
$lang->admin->community->smsCode             = '驗證碼';
$lang->admin->community->sendCode            = '獲取驗證碼';
$lang->admin->community->join                = '加入';
$lang->admin->community->joinDesc            = '幫助我們瞭解產品使用情況。';
$lang->admin->community->captchaTip          = '請輸入驗證碼';
$lang->admin->community->sure                = '<span style="font-size: 15px;">&nbsp;&nbsp;確定</span>';
$lang->admin->community->unBindText          = '解綁';
$lang->admin->community->welcome             = '加入禪道社區';
$lang->admin->community->welcomeForBound     = '您已加入禪道社區，您的賬號為：';
$lang->admin->community->advantage1          = '項目管理大禮包';
$lang->admin->community->advantage2          = '技術支持服務';
$lang->admin->community->advantage3          = '體驗各版本Demo';
$lang->admin->community->advantage4          = '禪道軟件手冊';
$lang->admin->community->goCommunity         = '前往社區';
$lang->admin->community->giftPackage         = '填信息領禮包';
$lang->admin->community->enterMobile         = '請輸入手機號';
$lang->admin->community->enterCode           = '請輸入驗證碼';
$lang->admin->community->goBack              = '返回';
$lang->admin->community->reSend              = '重新發送';
$lang->admin->community->unbindTitle         = '確認與禪道解綁嗎';
$lang->admin->community->unbindContent       = '解綁後將無法通過禪道軟件直接跳轉禪道官網';
$lang->admin->community->cancelButton        = '取消';
$lang->admin->community->unbindButton        = '解綁';
$lang->admin->community->joinSuccess         = '加入禪道社區成功';
$lang->admin->community->receiveGiftPackage  = '領取項目禮包';
$lang->admin->community->giftPackageSuccess  = '提交成功';

$lang->admin->community->positionList['項目經理']    = '項目經理';
$lang->admin->community->positionList['研發主管']    = '研發主管';
$lang->admin->community->positionList['運營']       = '運營';
$lang->admin->community->positionList['採購']       = '採購';
$lang->admin->community->positionList['產品經理']    = '產品經理';
$lang->admin->community->positionList['UI/UX設計師'] = 'UI/UX設計師';
$lang->admin->community->positionList['前端開發']    = '前端開發';
$lang->admin->community->positionList['後端開發']    = '後端開發';
$lang->admin->community->positionList['全棧開發']    = '全棧開發';
$lang->admin->community->positionList['測試 / QA']  = '測試 / QA';
$lang->admin->community->positionList['架構師']      = '架構師';

$lang->admin->community->solvedProblems['產品管理']   = '產品管理';
$lang->admin->community->solvedProblems['項目管理']   = '項目管理';
$lang->admin->community->solvedProblems['BUG管理']   = 'BUG管理';
$lang->admin->community->solvedProblems['工作流管理'] = '工作流管理';
$lang->admin->community->solvedProblems['效能管理']   = '效能管理';
$lang->admin->community->solvedProblems['文檔管理']   = '文檔管理';
$lang->admin->community->solvedProblems['反饋管理']   = '反饋管理';
$lang->admin->community->solvedProblems['其他']      = '其他';

$lang->admin->community->giftPackageFormNickname = '如何稱呼您';
$lang->admin->community->giftPackageFormPosition = '您的職位';
$lang->admin->community->giftPackageFormCompany  = '公司名稱';
$lang->admin->community->giftPackageFormQuestion = '您想使用禪道解決哪些項目管理問題';

$lang->admin->community->giftPackageFailed         = '提交失敗';
$lang->admin->community->giftPackageFailedNickname = '請填寫稱呼';
$lang->admin->community->giftPackageFailedPosition = '請填寫職位';
$lang->admin->community->giftPackageFailedCompany  = '請填寫公司名稱';

$lang->admin->community->uxPlan = new stdclass();
$lang->admin->community->uxPlan->agree  = '已同意';
$lang->admin->community->uxPlan->cancel = '已取消';

$lang->admin->community->unBind = new stdclass();
$lang->admin->community->unBind->success = '已解綁';

$lang->admin->nickname       = '稱呼';
$lang->admin->position       = '職位';
$lang->admin->company        = '公司名稱';
$lang->admin->solvedProblems = '項目管理問題';

$lang->admin->mobile  = '手機號';
$lang->admin->code    = '短信驗證碼';
$lang->admin->agreeUX = '用戶體驗計劃';

include dirname(__FILE__) . '/menu.php';

<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-tw.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        https://www.zentao.net
 */
$lang->tutorial = new stdclass();
$lang->tutorial->common           = '使用教程';
$lang->tutorial->desc             = '通過完成一系列任務，快速瞭解禪道的基本使用方法，你可以隨時退出任務。';
$lang->tutorial->start            = '開始';
$lang->tutorial->continue         = '繼續';
$lang->tutorial->exit             = '退出教程';
$lang->tutorial->exitStep         = '退出';
$lang->tutorial->finish           = '完成';
$lang->tutorial->congratulation   = '恭喜，你已完成了所有任務！';
$lang->tutorial->restart          = '重新開始';
$lang->tutorial->currentTask      = '當前任務';
$lang->tutorial->allTasks         = '所有任務';
$lang->tutorial->previous         = '上一個';
$lang->tutorial->nextTask         = '下一個任務';
$lang->tutorial->nextGuide        = '下一個教程';
$lang->tutorial->nextStep         = '下一步';
$lang->tutorial->openTargetPage   = '打開 <strong class="task-page-name">目標</strong> 頁面';
$lang->tutorial->atTargetPage     = '已在 <strong class="task-page-name">目標</strong> 頁面';
$lang->tutorial->reloadTargetPage = '重新載入';
$lang->tutorial->target           = '目標';
$lang->tutorial->targetPageTip    = '按此指示打開【%s】頁面';
$lang->tutorial->targetAppTip     = '按此指示打開【%s】應用';
$lang->tutorial->requiredTip      = '【%s】為必填項';
$lang->tutorial->congratulateTask = '恭喜，你完成了任務【<span class="task-name-current"></span>】';
$lang->tutorial->serverErrorTip   = '發生了一些錯誤。';
$lang->tutorial->ajaxSetError     = '必須指定已完成的任務，如果要重置任務，請設置值為空。';
$lang->tutorial->novice           = "你可能初次使用禪道，是否進入新手教程";
$lang->tutorial->dataNotSave      = "教程任務中，數據不會保存。";
$lang->tutorial->clickTipFormat   = "點擊%s";
$lang->tutorial->clickAndOpenIt   = "點擊%s打開%s。";

$lang->tutorial->guideTypes        = array();
$lang->tutorial->guideTypes['starter'] = '快速上手';
$lang->tutorial->guideTypes['basic']   = '基礎教程';
$lang->tutorial->guideTypes['advance'] = '進階教程';

$lang->tutorial->tasks = new stdclass();
$lang->tutorial->tasks->createAccount = new stdclass();

$lang->tutorial->tasks->createAccount->title          = '創建帳號';
$lang->tutorial->tasks->createAccount->targetPageName = '添加用戶';
$lang->tutorial->tasks->createAccount->desc           = "<p>在系統創建一個新的用戶帳號：</p><ul><li data-target='nav'>打開 <span class='task-nav'>後台 <i class='icon icon-angle-right'></i> 人員管理 <i class='icon icon-angle-right'></i> 用戶 <i class='icon icon-angle-right'></i> 添加用戶</span> 頁面；</li><li data-target='form'>在添加用戶表單中填寫新用戶信息；</li><li data-target='submit'>保存用戶信息。</li></ul>";

$lang->tutorial->tasks->createProgram = new stdClass();
$lang->tutorial->tasks->createProgram->title          = '創建項目集';
$lang->tutorial->tasks->createProgram->targetPageName = '添加項目集';
$lang->tutorial->tasks->createProgram->desc           = "<p>在系統創建一個新的項目集：</p><ul><li data-target='nav'>打開 <span class='task-nav'>項目集 <i class='icon icon-angle-right'></i> 項目集列表 <i class='icon icon-angle-right'></i> 添加項目集</span> 頁面；</li><li data-target='form'>在添加項目集表單中填寫項目集信息；</li><li data-target='submit'>保存項目集信息。</li></ul>";

$lang->tutorial->tasks->createProduct = new stdClass();
$lang->tutorial->tasks->createProduct->title          = '創建產品';
$lang->tutorial->tasks->createProduct->targetPageName = '添加產品';
$lang->tutorial->tasks->createProduct->desc           = "<p>在系統創建一個新的{$lang->productCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->productCommon}列表 <i class='icon icon-angle-right'></i> 添加{$lang->productCommon}</span> 頁面；</li><li data-target='form'>在添加{$lang->productCommon}表單中填寫要創建的{$lang->productCommon}信息；</li><li data-target='submit'>保存{$lang->productCommon}信息。</li></ul>";

$lang->tutorial->tasks->createStory = new stdClass();
$lang->tutorial->tasks->createStory->title          = "創建{$lang->SRCommon}";
$lang->tutorial->tasks->createStory->targetPageName = "提{$lang->SRCommon}";
$lang->tutorial->tasks->createStory->desc           = "<p>在系統創建一個新的{$lang->SRCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 提{$lang->SRCommon}</span> 頁面；</li><li data-target='form'>在{$lang->productCommon}表單中填寫要創建的{$lang->SRCommon}信息；</li><li data-target='submit'>保存{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks->createProject = new stdClass();
$lang->tutorial->tasks->createProject->title          = '創建項目';
$lang->tutorial->tasks->createProject->targetPageName = '添加項目';
$lang->tutorial->tasks->createProject->desc           = "<p>在系統創建一個新的{$lang->projectCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->projectCommon}列表 <i class='icon icon-angle-right'></i> 創建{$lang->projectCommon}</span> 頁面；</li><li data-target='form'>在{$lang->projectCommon}表單中填寫要創建的{$lang->projectCommon}信息；</li><li data-target='submit'>保存{$lang->projectCommon}信息。</li></ul>";

$lang->tutorial->tasks->manageTeam = new stdClass();
$lang->tutorial->tasks->manageTeam->title          = "管理{$lang->projectCommon}團隊";
$lang->tutorial->tasks->manageTeam->targetPageName = '團隊管理';
$lang->tutorial->tasks->manageTeam->desc           = "<p>管理{$lang->projectCommon}團隊成員：</p><ul><li data-target='nav'>打開 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> 設置 <i class='icon icon-angle-right'></i> 團隊 <i class='icon icon-angle-right'></i> 團隊管理</span> 頁面；</li><li data-target='form'>選擇要加入{$lang->projectCommon}團隊的成員；</li><li data-target='submit'>保存團隊成員信息。</li></ul>";

$lang->tutorial->tasks->createProjectExecution = new stdClass();
$lang->tutorial->tasks->createProjectExecution->title             = '創建執行';
$lang->tutorial->tasks->createProjectExecution->targetPageName = "添加{$lang->executionCommon}";
$lang->tutorial->tasks->createProjectExecution->desc              = "<p>在系統創建一個新的{$lang->executionCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->executionCommon} <i class='icon icon-angle-right'></i> 添加{$lang->executionCommon}</span> 頁面；</li><li data-target='form'>在{$lang->executionCommon}表單中填寫要創建的{$lang->executionCommon}信息；</li><li data-target='submit'>保存{$lang->executionCommon}信息。</li></ul>";

$lang->tutorial->tasks->linkStory = new stdClass();
$lang->tutorial->tasks->linkStory->title          = "關聯{$lang->SRCommon}";
$lang->tutorial->tasks->linkStory->targetPageName = "關聯{$lang->SRCommon}";
$lang->tutorial->tasks->linkStory->desc           = "<p>將{$lang->SRCommon}關聯到執行：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 執行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 關聯{$lang->SRCommon}</span> 頁面；</li><li data-target='form'>在{$lang->SRCommon}列表中勾選要關聯的{$lang->SRCommon}；</li><li data-target='submit'>保存關聯的{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks->createTask = new stdClass();
$lang->tutorial->tasks->createTask->title          = '分解任務';
$lang->tutorial->tasks->createTask->targetPageName = '建任務';
$lang->tutorial->tasks->createTask->desc           = "<p>將執行{$lang->SRCommon}分解為任務：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 執行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 分解任務</span> 頁面；</li><li data-target='form'>在表單中填寫任務信息；</li><li data-target='submit'>保存任務信息。</li></ul>";

$lang->tutorial->tasks->createBug = new stdClass();
$lang->tutorial->tasks->createBug->title          = '提Bug';
$lang->tutorial->tasks->createBug->targetPageName = '提Bug';
$lang->tutorial->tasks->createBug->desc           = "<p>在系統中提交一個Bug：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 測試 <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> 提Bug</span>；</li><li data-target='form'>在表單中填寫Bug信息；</li><li data-target='submit'>保存Bug信息。</li></ul>";

$lang->tutorial->starter = new stdClass();
$lang->tutorial->starter->title = '快速上手教程';

$lang->tutorial->starter->createAccount = new stdClass();
$lang->tutorial->starter->createAccount->title = '創建賬號';

$lang->tutorial->starter->createAccount->step1 = new stdClass();
$lang->tutorial->starter->createAccount->step1->name = '點擊後台';
$lang->tutorial->starter->createAccount->step1->desc = '您可以在這裡維護管理賬號，進行各類配置項的設置。';

$lang->tutorial->starter->createAccount->step2 = new stdClass();
$lang->tutorial->starter->createAccount->step2->name = '點擊人員管理';
$lang->tutorial->starter->createAccount->step2->desc = '您可以在這裡維護部門、添加人員和分組配置權限';

$lang->tutorial->starter->createAccount->step3 = new stdClass();
$lang->tutorial->starter->createAccount->step3->name = '點擊用戶';
$lang->tutorial->starter->createAccount->step3->desc = '您可以在這裡維護公司人員';

$lang->tutorial->starter->createAccount->step4 = new stdClass();
$lang->tutorial->starter->createAccount->step4->name = '點擊添加人員按鈕';
$lang->tutorial->starter->createAccount->step4->desc = '點擊添加公司人員';

$lang->tutorial->starter->createAccount->step5 = new stdClass();
$lang->tutorial->starter->createAccount->step5->name = '填寫表單';

$lang->tutorial->starter->createAccount->step6 = new stdClass();
$lang->tutorial->starter->createAccount->step6->name = '保存表單';
$lang->tutorial->starter->createAccount->step6->desc = '保存後可以在人員列表中查看';

$lang->tutorial->starter->createProgram = new stdClass();
$lang->tutorial->starter->createProgram->title = '創建項目集';

$lang->tutorial->starter->createProgram->step1 = new stdClass();
$lang->tutorial->starter->createProgram->step1->name = '點擊項目集';
$lang->tutorial->starter->createProgram->step1->desc = '您可以在這裡維護管理項目集';

$lang->tutorial->starter->createProgram->step2 = new stdClass();
$lang->tutorial->starter->createProgram->step2->name = '點擊添加項目集';
$lang->tutorial->starter->createProgram->step2->desc = '點擊添加項目集';

$lang->tutorial->starter->createProgram->step3 = new stdClass();
$lang->tutorial->starter->createProgram->step3->name = '填寫表單';

$lang->tutorial->starter->createProgram->step4 = new stdClass();
$lang->tutorial->starter->createProgram->step4->name = '保存表單';
$lang->tutorial->starter->createProgram->step4->desc = '保存後在項目視角和產品視角列表中均可查看';

$lang->tutorial->starter->createProduct = new stdClass();
$lang->tutorial->starter->createProduct->title = '創建產品';

$lang->tutorial->starter->createProduct->step1 = new stdClass();
$lang->tutorial->starter->createProduct->step1->name = '點擊產品';
$lang->tutorial->starter->createProduct->step1->desc = '您可以在這裡維護管理產品';

$lang->tutorial->starter->createProduct->step2 = new stdClass();
$lang->tutorial->starter->createProduct->step2->name = '點擊添加產品';
$lang->tutorial->starter->createProduct->step2->desc = '您可以在這裡添加產品';

$lang->tutorial->starter->createProduct->step3 = new stdClass();
$lang->tutorial->starter->createProduct->step3->name = '填寫表單';

$lang->tutorial->starter->createProduct->step4 = new stdClass();
$lang->tutorial->starter->createProduct->step4->name = '保存表單';
$lang->tutorial->starter->createProduct->step4->desc = '保存後可以在產品列表中查看';

$lang->tutorial->starter->createStory = new stdClass();
$lang->tutorial->starter->createStory->title = '創建研發需求';

$lang->tutorial->starter->createStory->step1 = new stdClass();
$lang->tutorial->starter->createStory->step1->name = '點擊產品';
$lang->tutorial->starter->createStory->step1->desc = '您可以在這裡維護管理產品';

$lang->tutorial->starter->createStory->step2 = new stdClass();
$lang->tutorial->starter->createStory->step2->name = '點擊產品名稱';
$lang->tutorial->starter->createStory->step2->desc = '點擊進入產品，查看產品的詳細信息。';

$lang->tutorial->starter->createStory->step3 = new stdClass();
$lang->tutorial->starter->createStory->step3->name = '點擊提研發需求';
$lang->tutorial->starter->createStory->step3->desc = '您可以在這裡創建研發需求';

$lang->tutorial->starter->createStory->step4 = new stdClass();
$lang->tutorial->starter->createStory->step4->name = '填寫表單';

$lang->tutorial->starter->createStory->step5 = new stdClass();
$lang->tutorial->starter->createStory->step5->name = '保存表單';
$lang->tutorial->starter->createStory->step5->desc = '保存後可以在產品需求列表中查看';

$lang->tutorial->starter->createProject = new stdClass();
$lang->tutorial->starter->createProject->title = '創建項目';

$lang->tutorial->starter->createProject->step1 = new stdClass();
$lang->tutorial->starter->createProject->step1->name = '點擊項目';
$lang->tutorial->starter->createProject->step1->desc = '您可以在這裡創建項目';

$lang->tutorial->starter->createProject->step2 = new stdClass();
$lang->tutorial->starter->createProject->step2->name = '點擊創建項目';
$lang->tutorial->starter->createProject->step2->desc = '您可以選擇不同項目管理方式來創建不同類型的項目';

$lang->tutorial->starter->createProject->step3 = new stdClass();
$lang->tutorial->starter->createProject->step3->name = '點擊Scrum項目';
$lang->tutorial->starter->createProject->step3->desc = '請點擊Scrum創建Scrum項目';

$lang->tutorial->starter->createProject->step4 = new stdClass();
$lang->tutorial->starter->createProject->step4->name = '填寫表單';

$lang->tutorial->starter->createProject->step5 = new stdClass();
$lang->tutorial->starter->createProject->step5->name = '保存表單';
$lang->tutorial->starter->createProject->step5->desc = '保存後會顯示在項目列表中';

$lang->tutorial->starter->manageTeam = new stdClass();
$lang->tutorial->starter->manageTeam->title = '管理項目團隊';

$lang->tutorial->starter->manageTeam->step1 = new stdClass();
$lang->tutorial->starter->manageTeam->step1->name = '點擊項目';
$lang->tutorial->starter->manageTeam->step1->desc = '您可以在這裡維護管理項目';

$lang->tutorial->starter->manageTeam->step2 = new stdClass();
$lang->tutorial->starter->manageTeam->step2->name = '點擊項目名稱';
$lang->tutorial->starter->manageTeam->step2->desc = '點擊項目名稱進入項目';

$lang->tutorial->starter->manageTeam->step3 = new stdClass();
$lang->tutorial->starter->manageTeam->step3->name = '點擊設置';
$lang->tutorial->starter->manageTeam->step3->desc = '點擊設置開始維護團隊';

$lang->tutorial->starter->manageTeam->step4 = new stdClass();
$lang->tutorial->starter->manageTeam->step4->name = '點擊團隊';
$lang->tutorial->starter->manageTeam->step4->desc = '點擊團隊可以查看該項目中的團隊成員';

$lang->tutorial->starter->manageTeam->step5 = new stdClass();
$lang->tutorial->starter->manageTeam->step5->name = '點擊團隊管理';
$lang->tutorial->starter->manageTeam->step5->desc = '點擊團隊管理可以對當前項目的團隊成員進行維護';

$lang->tutorial->starter->manageTeam->step6 = new stdClass();
$lang->tutorial->starter->manageTeam->step6->name = '填寫表單';

$lang->tutorial->starter->manageTeam->step7 = new stdClass();
$lang->tutorial->starter->manageTeam->step7->name = '保存表單';
$lang->tutorial->starter->manageTeam->step7->desc = '保存後可以在團隊中查看團隊成員';

$lang->tutorial->starter->createProjectExecution = new stdClass();
$lang->tutorial->starter->createProjectExecution->title = '創建執行';

$lang->tutorial->starter->createProjectExecution->step1 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step1->name = '點擊項目';
$lang->tutorial->starter->createProjectExecution->step1->desc = '您可以在這裡維護管理項目';

$lang->tutorial->starter->createProjectExecution->step2 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step2->name = '點擊項目名稱';
$lang->tutorial->starter->createProjectExecution->step2->desc = '點擊項目名稱進入項目';

$lang->tutorial->starter->createProjectExecution->step3 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step3->name = '點擊迭代';
$lang->tutorial->starter->createProjectExecution->step3->desc = '點擊迭代開始添加新迭代';

$lang->tutorial->starter->createProjectExecution->step4 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step4->name = '點擊添加迭代';
$lang->tutorial->starter->createProjectExecution->step4->desc = '您可以在這裡添加迭代';

$lang->tutorial->starter->createProjectExecution->step5 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step5->name = '填寫表單';

$lang->tutorial->starter->createProjectExecution->step6 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step6->name = '保存表單';
$lang->tutorial->starter->createProjectExecution->step6->desc = '保存後可以選擇設置團隊、關聯需求、創建任務、返回任務列表和返回執行列表';

$lang->tutorial->starter->linkStory = new stdClass();
$lang->tutorial->starter->linkStory->title = "關聯{$lang->SRCommon}";

$lang->tutorial->starter->linkStory->step1 = new stdClass();
$lang->tutorial->starter->linkStory->step1->name = '點擊迭代';
$lang->tutorial->starter->linkStory->step1->desc = '您可以在這裡維護管理迭代';

$lang->tutorial->starter->linkStory->step2 = new stdClass();
$lang->tutorial->starter->linkStory->step2->name = '點擊需求';
$lang->tutorial->starter->linkStory->step2->desc = '點擊需求查看已關聯的需求';

$lang->tutorial->starter->linkStory->step3 = new stdClass();
$lang->tutorial->starter->linkStory->step3->name = '點擊關聯需求';
$lang->tutorial->starter->linkStory->step3->desc = '點擊關聯需求進入關聯需求列表';

$lang->tutorial->starter->linkStory->step4 = new stdClass();
$lang->tutorial->starter->linkStory->step4->name = '選擇需求';

$lang->tutorial->starter->linkStory->step5 = new stdClass();
$lang->tutorial->starter->linkStory->step5->name = '點擊保存';
$lang->tutorial->starter->linkStory->step5->desc = '點擊保存可以將需求關聯到需求列表中，返回到需求列表';

$lang->tutorial->starter->createTask = new stdClass();
$lang->tutorial->starter->createTask->title = '分解任務';

$lang->tutorial->starter->createTask->step1 = new stdClass();
$lang->tutorial->starter->createTask->step1->name = '點擊迭代';
$lang->tutorial->starter->createTask->step1->desc = '您可以在這裡維護管理迭代';

$lang->tutorial->starter->createTask->step2 = new stdClass();
$lang->tutorial->starter->createTask->step2->name = '點擊需求';
$lang->tutorial->starter->createTask->step2->desc = '進入需求列表，您可以在這裡看到之前關聯的需求';

$lang->tutorial->starter->createTask->step3 = new stdClass();
$lang->tutorial->starter->createTask->step3->name = '分解任務';
$lang->tutorial->starter->createTask->step3->desc = '您可以在這裡將需求分解為任務，支持批量分解';

$lang->tutorial->starter->createTask->step4 = new stdClass();
$lang->tutorial->starter->createTask->step4->name = '填寫表單';

$lang->tutorial->starter->createTask->step5 = new stdClass();
$lang->tutorial->starter->createTask->step5->name = '保存表單';
$lang->tutorial->starter->createTask->step5->desc = '保存後可以在任務列表中查看分解的任務';

$lang->tutorial->starter->createBug = new stdClass();
$lang->tutorial->starter->createBug->title = '提Bug';

$lang->tutorial->starter->createBug->step1 = new stdClass();
$lang->tutorial->starter->createBug->step1->name = '點擊測試';
$lang->tutorial->starter->createBug->step1->desc = '您可以在這裡進行測試管理';

$lang->tutorial->starter->createBug->step2 = new stdClass();
$lang->tutorial->starter->createBug->step2->name = '點擊Bug';
$lang->tutorial->starter->createBug->step2->desc = '可以在這裡進行Bug管理';

$lang->tutorial->starter->createBug->step3 = new stdClass();
$lang->tutorial->starter->createBug->step3->name = '點擊提Bug';
$lang->tutorial->starter->createBug->step3->desc = '可以在這裡創建Bug';

$lang->tutorial->starter->createBug->step4 = new stdClass();
$lang->tutorial->starter->createBug->step4->name = '填寫表單';

$lang->tutorial->starter->createBug->step5 = new stdClass();
$lang->tutorial->starter->createBug->step5->name = '保存表單';
$lang->tutorial->starter->createBug->step5->desc = '保存後進入Bug列表';

$lang->tutorial->scrumProjectManage = new stdClass();
$lang->tutorial->scrumProjectManage->title = 'Scrum項目管理教程';

$lang->tutorial->scrumProjectManage->manageProject = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->title = '項目維護';

$lang->tutorial->scrumProjectManage->manageProject->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step1->name = '點擊項目';
$lang->tutorial->scrumProjectManage->manageProject->step1->desc = '您可以在這裡創建項目';

$lang->tutorial->scrumProjectManage->manageProject->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step2->name = '點擊創建項目';
$lang->tutorial->scrumProjectManage->manageProject->step2->desc = '您可以選擇不同項目管理方式來創建不同類型的項目';

$lang->tutorial->scrumProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step3->name = '點擊Scrum項目';
$lang->tutorial->scrumProjectManage->manageProject->step3->desc = '請點擊Scrum創建Scrum項目';

$lang->tutorial->scrumProjectManage->manageProject->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step4->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageProject->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step5->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageProject->step5->desc = '保存後會顯示在項目列表中';

$lang->tutorial->scrumProjectManage->manageProject->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step6->name = '點擊項目名稱';
$lang->tutorial->scrumProjectManage->manageProject->step6->desc = '點擊項目名稱進入項目';

$lang->tutorial->scrumProjectManage->manageProject->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step7->name = '點擊設置';
$lang->tutorial->scrumProjectManage->manageProject->step7->desc = '點擊設置開始維護團隊';

$lang->tutorial->scrumProjectManage->manageProject->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step8->name = '點擊團隊';
$lang->tutorial->scrumProjectManage->manageProject->step8->desc = '點擊團隊可以查看該項目中的團隊成員';

$lang->tutorial->scrumProjectManage->manageProject->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step9->name = '點擊團隊管理';
$lang->tutorial->scrumProjectManage->manageProject->step9->desc = '點擊團隊管理可以對當前項目的團隊成員進行維護';

$lang->tutorial->scrumProjectManage->manageProject->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step10->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageProject->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step11->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageProject->step11->desc = '保存後可以在團隊中查看團隊成員';

$lang->tutorial->scrumProjectManage->manageExecution = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->title = '迭代管理';

$lang->tutorial->scrumProjectManage->manageExecution->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step1->name = '點擊迭代';
$lang->tutorial->scrumProjectManage->manageExecution->step1->desc = '點擊迭代開始添加新迭代';

$lang->tutorial->scrumProjectManage->manageExecution->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step2->name = '點擊添加迭代';
$lang->tutorial->scrumProjectManage->manageExecution->step2->desc = '您可以在這裡添加迭代';

$lang->tutorial->scrumProjectManage->manageExecution->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step3->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageExecution->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step4->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageExecution->step4->desc = '保存後可以選擇設置團隊、關聯需求、創建任務、返回任務列表和返回執行列表';

$lang->tutorial->scrumProjectManage->manageExecution->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step5->name = '點擊迭代';
$lang->tutorial->scrumProjectManage->manageExecution->step5->desc = '點擊迭代名稱進入迭代';

$lang->tutorial->scrumProjectManage->manageExecution->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step6->name = '點擊需求';
$lang->tutorial->scrumProjectManage->manageExecution->step6->desc = '可以在這裡完成需求的維護';

$lang->tutorial->scrumProjectManage->manageExecution->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step7->name = '點擊關聯需求';
$lang->tutorial->scrumProjectManage->manageExecution->step7->desc = '可以將需求關聯進迭代中';

$lang->tutorial->scrumProjectManage->manageExecution->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step8->name = '選擇需求';

$lang->tutorial->scrumProjectManage->manageExecution->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step9->name = '點擊保存';
$lang->tutorial->scrumProjectManage->manageExecution->step9->desc = '點擊保存可以將需求關聯到需求列表中，返回到需求列表';

$lang->tutorial->scrumProjectManage->manageExecution->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step10->name = '點擊燃盡圖';
$lang->tutorial->scrumProjectManage->manageExecution->step10->desc = '點擊燃盡圖可以查看迭代燃盡圖';

$lang->tutorial->scrumProjectManage->manageTask = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->title = '任務管理';

$lang->tutorial->scrumProjectManage->manageTask->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step1->name = '點擊需求';
$lang->tutorial->scrumProjectManage->manageTask->step1->desc = '進入需求列表，您可以在這裡看到之前關聯的需求';

$lang->tutorial->scrumProjectManage->manageTask->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step3->name = '分解任務';
$lang->tutorial->scrumProjectManage->manageTask->step3->desc = '您可以在這裡將需求分解為任務，支持批量分解';

$lang->tutorial->scrumProjectManage->manageTask->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step4->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTask->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step5->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTask->step5->desc = '保存後可以在任務列表中查看分解的任務';

$lang->tutorial->scrumProjectManage->manageTask->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step6->name = '點擊指派給';
$lang->tutorial->scrumProjectManage->manageTask->step6->desc = '您可以在這裡將任務指派給對應的用戶';

$lang->tutorial->scrumProjectManage->manageTask->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step7->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTask->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step8->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTask->step8->desc = '保存後在任務列表中指派給欄位會顯示被指派的用戶';

$lang->tutorial->scrumProjectManage->manageTask->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step9->name = '點擊開始任務';
$lang->tutorial->scrumProjectManage->manageTask->step9->desc = '您可以在這裡開始任務，並記錄消耗和剩餘工時';

$lang->tutorial->scrumProjectManage->manageTask->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step10->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTask->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step11->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTask->step11->desc = '保存後返回任務列表';

$lang->tutorial->scrumProjectManage->manageTask->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step12->name = '點擊記錄工時';
$lang->tutorial->scrumProjectManage->manageTask->step12->desc = '您可以在這裡記錄消耗和剩餘工時，當剩餘工時為0後，任務會自動完成';

$lang->tutorial->scrumProjectManage->manageTask->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step13->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTask->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step14->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTask->step14->desc = '保存後返回任務列表';

$lang->tutorial->scrumProjectManage->manageTask->step15 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step15->name = '點擊完成任務';
$lang->tutorial->scrumProjectManage->manageTask->step15->desc = '您可以在這裡完成任務';

$lang->tutorial->scrumProjectManage->manageTask->step16 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step16->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTask->step17 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step17->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTask->step17->desc = '保存後返回任務列表';

$lang->tutorial->scrumProjectManage->manageTask->step18 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step18->name = '點擊構建';
$lang->tutorial->scrumProjectManage->manageTask->step18->desc = '進入構建模組中可以創建構建';

$lang->tutorial->scrumProjectManage->manageTask->step19 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step19->name = '點擊創建構建';
$lang->tutorial->scrumProjectManage->manageTask->step19->desc = '可以在這裡創建新的構建';

$lang->tutorial->scrumProjectManage->manageTask->step20 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step20->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTask->step21 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step21->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTask->step21->desc = '保存後進入構建詳情';

$lang->tutorial->scrumProjectManage->manageTask->step22 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step22->name = '關聯需求';
$lang->tutorial->scrumProjectManage->manageTask->step22->desc = '可以將完成的研發需求關聯在構建中';

$lang->tutorial->scrumProjectManage->manageTask->step23 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step23->name = '選擇需求';
$lang->tutorial->scrumProjectManage->manageTask->step23->desc = '在這裡可以選擇勾選需要關聯的需求';

$lang->tutorial->scrumProjectManage->manageTask->step24 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step24->name = '保存關聯的需求';
$lang->tutorial->scrumProjectManage->manageTask->step24->desc = '您可以將完成的需求關聯在當前構建中';

$lang->tutorial->scrumProjectManage->manageTest = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->title = '測試管理';

$lang->tutorial->scrumProjectManage->manageTest->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step1->name = '點擊測試';
$lang->tutorial->scrumProjectManage->manageTest->step1->desc = '可以在這裡進行測試管理';

$lang->tutorial->scrumProjectManage->manageTest->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step2->name = '點擊用例';
$lang->tutorial->scrumProjectManage->manageTest->step2->desc = '在這裡可以查看用例';

$lang->tutorial->scrumProjectManage->manageTest->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step3->name = '點擊創建用例';
$lang->tutorial->scrumProjectManage->manageTest->step3->desc = '在這裡可以創建用例';

$lang->tutorial->scrumProjectManage->manageTest->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step4->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTest->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step5->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTest->step5->desc = '保存後進入用例列表';

$lang->tutorial->scrumProjectManage->manageTest->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step6->name = '點擊執行';
$lang->tutorial->scrumProjectManage->manageTest->step6->desc = '點擊執行可以執行用例';

$lang->tutorial->scrumProjectManage->manageTest->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step7->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTest->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step8->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTest->step8->desc = '保存後返回用例列表';

$lang->tutorial->scrumProjectManage->manageTest->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step9->name = '點擊結果';
$lang->tutorial->scrumProjectManage->manageTest->step9->desc = '點擊這裡可以查看用例執行結果';

$lang->tutorial->scrumProjectManage->manageTest->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step10->name = '選擇步驟';

$lang->tutorial->scrumProjectManage->manageTest->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step11->name = '點擊轉Bug';
$lang->tutorial->scrumProjectManage->manageTest->step11->desc = '可以將未通過的執行結果轉Bug處理';

$lang->tutorial->scrumProjectManage->manageTest->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step12->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTest->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step13->name = '保存表單';

$lang->tutorial->scrumProjectManage->manageTest->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step14->name = '點擊測試單';
$lang->tutorial->scrumProjectManage->manageTest->step14->desc = '點擊維護測試單';

$lang->tutorial->scrumProjectManage->manageTest->step15 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step15->name = '點擊提交測試';
$lang->tutorial->scrumProjectManage->manageTest->step15->desc = '可以在這裡創建測試單';

$lang->tutorial->scrumProjectManage->manageTest->step16 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step16->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTest->step17 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step17->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTest->step17->desc = '保存後回到測試單列表中';

$lang->tutorial->scrumProjectManage->manageTest->step18 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step18->name = '點擊測試單名稱';
$lang->tutorial->scrumProjectManage->manageTest->step18->desc = '可以在這裡查看測試單詳情列表';

$lang->tutorial->scrumProjectManage->manageTest->step19 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step19->name = '點擊關聯用例';
$lang->tutorial->scrumProjectManage->manageTest->step19->desc = '可以在這裡關聯用例';

$lang->tutorial->scrumProjectManage->manageTest->step20 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step20->name = '選擇要關聯的用例';

$lang->tutorial->scrumProjectManage->manageTest->step21 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step21->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTest->step21->desc = '您可以將用例關聯在測試單中，在這裡可以查看到可關聯的用例';

$lang->tutorial->scrumProjectManage->manageTest->step22 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step22->name = '點擊測試單';
$lang->tutorial->scrumProjectManage->manageTest->step22->desc = '點擊這裡返回測試單列表';

$lang->tutorial->scrumProjectManage->manageTest->step23 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step23->name = '選擇測試單';

$lang->tutorial->scrumProjectManage->manageTest->step24 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step24->name = '點擊測試報告';
$lang->tutorial->scrumProjectManage->manageTest->step24->desc = '可以在這裡生成測試報告';

$lang->tutorial->scrumProjectManage->manageTest->step25 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step25->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageTest->step26 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step26->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageTest->step26->desc = '保存後可以生成測試報告';

$lang->tutorial->scrumProjectManage->manageTest->step27 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step27->name = '點擊測試報告';
$lang->tutorial->scrumProjectManage->manageTest->step27->desc = '可以在這裡查看測試報告列表';

$lang->tutorial->scrumProjectManage->manageBug = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->title = 'Bug管理';

$lang->tutorial->scrumProjectManage->manageBug->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step1->name = '點擊測試';
$lang->tutorial->scrumProjectManage->manageBug->step1->desc = '可以在這裡進行Bug管理';

$lang->tutorial->scrumProjectManage->manageBug->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step2->name = '點擊提Bug';
$lang->tutorial->scrumProjectManage->manageBug->step2->desc = '可以在這裡創建Bug';

$lang->tutorial->scrumProjectManage->manageBug->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step3->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageBug->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step4->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageBug->step4->desc = '保存後進入Bug列表';

$lang->tutorial->scrumProjectManage->manageBug->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step5->name = '確認Bug';
$lang->tutorial->scrumProjectManage->manageBug->step5->desc = '可以在這裡確認Bug';

$lang->tutorial->scrumProjectManage->manageBug->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step6->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageBug->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step7->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageBug->step7->desc = '保存後進入Bug列表';

$lang->tutorial->scrumProjectManage->manageBug->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step8->name = '解決Bug';
$lang->tutorial->scrumProjectManage->manageBug->step8->desc = '可以在這裡解決Bug';

$lang->tutorial->scrumProjectManage->manageBug->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step9->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageBug->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step10->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageBug->step10->desc = '保存後可以將解決完的Bug進行驗證';

$lang->tutorial->scrumProjectManage->manageBug->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step11->name = '關閉Bug';
$lang->tutorial->scrumProjectManage->manageBug->step11->desc = '可以在這裡關閉Bug';

$lang->tutorial->scrumProjectManage->manageBug->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step12->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageBug->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step13->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageBug->step13->desc = '保存後可以將驗證完的Bug關閉';

$lang->tutorial->scrumProjectManage->manageIssue = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->title = '問題管理';

$lang->tutorial->scrumProjectManage->manageIssue->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step1->name = '點擊其他';

$lang->tutorial->scrumProjectManage->manageIssue->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step2->name = '點擊問題';
$lang->tutorial->scrumProjectManage->manageIssue->step2->desc = '可以在這裡進行問題管理';

$lang->tutorial->scrumProjectManage->manageIssue->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step3->name = '點擊新建問題';
$lang->tutorial->scrumProjectManage->manageIssue->step3->desc = '在這裡新建問題，支持批量創建';

$lang->tutorial->scrumProjectManage->manageIssue->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step4->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageIssue->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step5->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageIssue->step5->desc = '保存後進入問題列表';

$lang->tutorial->scrumProjectManage->manageIssue->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step6->name = '確認問題';
$lang->tutorial->scrumProjectManage->manageIssue->step6->desc = '可以在這裡確認當前項目的問題';

$lang->tutorial->scrumProjectManage->manageIssue->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step7->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageIssue->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step8->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageIssue->step8->desc = '確認後回到問題列表';

$lang->tutorial->scrumProjectManage->manageIssue->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step9->name = '解決問題';
$lang->tutorial->scrumProjectManage->manageIssue->step9->desc = '可以在這裡解決問題';

$lang->tutorial->scrumProjectManage->manageIssue->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step10->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageIssue->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step11->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageIssue->step11->desc = '保存後返回問題列表';

$lang->tutorial->scrumProjectManage->manageIssue->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step12->name = '關閉問題';
$lang->tutorial->scrumProjectManage->manageIssue->step12->desc = '可以將已經處理的問題關閉';

$lang->tutorial->scrumProjectManage->manageIssue->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step13->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageIssue->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step14->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageIssue->step14->desc = '可以在這裡關閉問題';

$lang->tutorial->scrumProjectManage->manageRisk = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->title = '風險管理';

$lang->tutorial->scrumProjectManage->manageRisk->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step1->name = '點擊其他';

$lang->tutorial->scrumProjectManage->manageRisk->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step2->name = '點擊風險';
$lang->tutorial->scrumProjectManage->manageRisk->step2->desc = '可以在這裡進行風險管理';

$lang->tutorial->scrumProjectManage->manageRisk->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step3->name = '點擊添加風險';
$lang->tutorial->scrumProjectManage->manageRisk->step3->desc = '在這裡可以添加當前項目的風險，支持批量創建';

$lang->tutorial->scrumProjectManage->manageRisk->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step4->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageRisk->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step5->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageRisk->step5->desc = '可以在這裡將風險添加到風險列表中';

$lang->tutorial->scrumProjectManage->manageRisk->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step6->name = '跟蹤風險';
$lang->tutorial->scrumProjectManage->manageRisk->step6->desc = '可以在這裡跟蹤風險';

$lang->tutorial->scrumProjectManage->manageRisk->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step7->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageRisk->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step8->name = '保存表單';
$lang->tutorial->scrumProjectManage->manageRisk->step8->desc = '保存後返迴風險列表';

$lang->tutorial->scrumProjectManage->manageRisk->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step9->name = '關閉風險';
$lang->tutorial->scrumProjectManage->manageRisk->step9->desc = '可以在這裡將風險關閉';

$lang->tutorial->scrumProjectManage->manageRisk->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step10->name = '填寫表單';

$lang->tutorial->scrumProjectManage->manageRisk->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step11->name = '保存表單';

$lang->tutorial->waterfallProjectManage = new stdClass();
$lang->tutorial->waterfallProjectManage->title = '瀑布項目管理教程';

$lang->tutorial->waterfallProjectManage->manageProject = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->title = '項目維護';

$lang->tutorial->waterfallProjectManage->manageProject->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step1->name = '點擊項目';
$lang->tutorial->waterfallProjectManage->manageProject->step1->desc = '您可以在這裡創建項目';

$lang->tutorial->waterfallProjectManage->manageProject->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step2->name = '點擊創建項目';
$lang->tutorial->waterfallProjectManage->manageProject->step2->desc = '您可以選擇不同項目管理方式來創建不同類型的項目';

$lang->tutorial->waterfallProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step3->name = '點擊瀑布項目';
$lang->tutorial->waterfallProjectManage->manageProject->step3->desc = '可以在這裡創建瀑布項目';

$lang->tutorial->waterfallProjectManage->manageProject->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step4->name = '填寫表單';

$lang->tutorial->waterfallProjectManage->manageProject->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step5->name = '保存表單';
$lang->tutorial->waterfallProjectManage->manageProject->step5->desc = '保存後會顯示在項目列表中';

$lang->tutorial->waterfallProjectManage->manageProject->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step6->name = '點擊項目名稱';
$lang->tutorial->waterfallProjectManage->manageProject->step6->desc = '點擊項目名稱進入瀑布項目';

$lang->tutorial->waterfallProjectManage->manageProject->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step7->name = '點擊設置';
$lang->tutorial->waterfallProjectManage->manageProject->step7->desc = '點擊設置開始維護團隊';

$lang->tutorial->waterfallProjectManage->manageProject->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step8->name = '點擊團隊';
$lang->tutorial->waterfallProjectManage->manageProject->step8->desc = '點擊團隊可以查看該項目中的團隊成員';

$lang->tutorial->waterfallProjectManage->manageProject->step9 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step9->name = '點擊團隊管理';
$lang->tutorial->waterfallProjectManage->manageProject->step9->desc = '點擊團隊管理可以對當前項目的團隊成員進行維護';

$lang->tutorial->waterfallProjectManage->manageProject->step10 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step10->name = '填寫表單';

$lang->tutorial->waterfallProjectManage->manageProject->step11 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step11->name = '保存表單';
$lang->tutorial->waterfallProjectManage->manageProject->step11->desc = '保存後可以在團隊中查看團隊成員';

$lang->tutorial->waterfallProjectManage->setStage = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->title = '階段設置';

$lang->tutorial->waterfallProjectManage->setStage->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step1->name = '點擊階段';
$lang->tutorial->waterfallProjectManage->setStage->step1->desc = '可以在這裡維護階段';

$lang->tutorial->waterfallProjectManage->setStage->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step2->name = '點擊設置階段';
$lang->tutorial->waterfallProjectManage->setStage->step2->desc = '點擊設置階段可以確定項目的各個階段，將階段設置為里程碑，可以查看相關里程碑報告。';

$lang->tutorial->waterfallProjectManage->setStage->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step3->name = '填寫表單';

$lang->tutorial->waterfallProjectManage->setStage->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step4->name = '保存表單';
$lang->tutorial->waterfallProjectManage->setStage->step4->desc = '可以為每個階段設置起止日期，保存在階段列表中查看所有階段';

$lang->tutorial->waterfallProjectManage->setStage->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step5->name = '切換視圖';
$lang->tutorial->waterfallProjectManage->setStage->step5->desc = '在這裡可以切換為列表視圖查看階段';

$lang->tutorial->waterfallProjectManage->setStage->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step6->name = '點擊開發階段';
$lang->tutorial->waterfallProjectManage->setStage->step6->desc = '可以在每個階段中分配相應的資源和任務';

$lang->tutorial->waterfallProjectManage->setStage->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step7->name = '點擊燃盡圖';
$lang->tutorial->waterfallProjectManage->setStage->step7->desc = '查看燃盡圖可以跟進階段';

$lang->tutorial->waterfallProjectManage->manageTask = new stdClass();
$lang->tutorial->waterfallProjectManage->manageTask = $lang->tutorial->scrumProjectManage->manageTask;

$lang->tutorial->waterfallProjectManage->manageTest = new stdClass();
$lang->tutorial->waterfallProjectManage->manageTest = $lang->tutorial->scrumProjectManage->manageTest;

$lang->tutorial->waterfallProjectManage->manageBug = new stdClass();
$lang->tutorial->waterfallProjectManage->manageBug = $lang->tutorial->scrumProjectManage->manageBug;

$lang->tutorial->waterfallProjectManage->design = new stdClass();
$lang->tutorial->waterfallProjectManage->design->title = '設計管理';

$lang->tutorial->waterfallProjectManage->design->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step1->name = '點擊設計';
$lang->tutorial->waterfallProjectManage->design->step1->desc = '可以在這裡進行設計管理';

$lang->tutorial->waterfallProjectManage->design->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step2->name = '點擊創建設計';
$lang->tutorial->waterfallProjectManage->design->step2->desc = '您可以在這裡創建版本';

$lang->tutorial->waterfallProjectManage->design->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step3->name = '填寫表單';

$lang->tutorial->waterfallProjectManage->design->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step4->name = '保存表單';
$lang->tutorial->waterfallProjectManage->design->step4->desc = '保存後進入設計列表中查看全部設計';

$lang->tutorial->waterfallProjectManage->design->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step5->name = '點擊設計名稱';
$lang->tutorial->waterfallProjectManage->design->step5->desc = '可以在這裡進入設計詳情';

$lang->tutorial->waterfallProjectManage->design->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step6->name = '點擊關聯提交';
$lang->tutorial->waterfallProjectManage->design->step6->desc = '您可以在這裡關聯提交';

$lang->tutorial->waterfallProjectManage->design->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step7->name = '選擇提交';

$lang->tutorial->waterfallProjectManage->design->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step8->name = '保存表單';
$lang->tutorial->waterfallProjectManage->design->step8->desc = '保存後可以在設計詳情查看已經關聯的提交';

$lang->tutorial->waterfallProjectManage->review = new stdClass();
$lang->tutorial->waterfallProjectManage->review->title = '評審和配置管理';

$lang->tutorial->waterfallProjectManage->review->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step1->name = '點擊評審';
$lang->tutorial->waterfallProjectManage->review->step1->desc = '可以在這裡進行評審管理';

$lang->tutorial->waterfallProjectManage->review->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step2->name = '點擊基線評審列表';
$lang->tutorial->waterfallProjectManage->review->step2->desc = '可以在這裡查看所有評審項';

$lang->tutorial->waterfallProjectManage->review->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step3->name = '點擊發起評審';
$lang->tutorial->waterfallProjectManage->review->step3->desc = '可以在這裡發起評審';

$lang->tutorial->waterfallProjectManage->review->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step4->name = '填寫表單';

$lang->tutorial->waterfallProjectManage->review->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step5->name = '保存表單';
$lang->tutorial->waterfallProjectManage->review->step5->desc = '保存後可以在基線評審列表中查看，在後台可配置創建模板，在相關模板欄位下引用';

$lang->tutorial->waterfallProjectManage->review->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step6->name = '點擊提交審計';
$lang->tutorial->waterfallProjectManage->review->step6->desc = '可以在這裡提交審計，評審未通過的可以在問題列表中查看問題和添加問題';

$lang->tutorial->waterfallProjectManage->review->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step7->name = '填寫表單';

$lang->tutorial->waterfallProjectManage->review->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step8->name = '保存表單';
$lang->tutorial->waterfallProjectManage->review->step8->desc = '保存後回到基線評審列表';

$lang->tutorial->waterfallProjectManage->manageIssue = new stdClass();
$lang->tutorial->waterfallProjectManage->manageIssue = $lang->tutorial->scrumProjectManage->manageIssue;

$lang->tutorial->waterfallProjectManage->manageRisk = new stdClass();
$lang->tutorial->waterfallProjectManage->manageRisk = $lang->tutorial->scrumProjectManage->manageRisk;

$lang->tutorial->kanbanProjectManage = new stdClass();
$lang->tutorial->kanbanProjectManage->title = '看板項目管理教程';

$lang->tutorial->kanbanProjectManage->manageProject = new stdClass();
$lang->tutorial->kanbanProjectManage->manageProject = clone $lang->tutorial->scrumProjectManage->manageProject;

$lang->tutorial->kanbanProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageProject->step3->name = '點擊項目看板';
$lang->tutorial->kanbanProjectManage->manageProject->step3->desc = '可以在這裡創建看板項目';

$lang->tutorial->kanbanProjectManage->manageKanban = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->title = '看板管理';

$lang->tutorial->kanbanProjectManage->manageKanban->step1 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step1->name = '點擊添加看板';
$lang->tutorial->kanbanProjectManage->manageKanban->step1->desc = '您可以在這裡添加看板';

$lang->tutorial->kanbanProjectManage->manageKanban->step2 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step2->name = '填寫表單';

$lang->tutorial->kanbanProjectManage->manageKanban->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step3->name = '保存表單';
$lang->tutorial->kanbanProjectManage->manageKanban->step3->desc = '可以在這裡完成看板的創建';

$lang->tutorial->kanbanProjectManage->manageKanban->step4 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step4->name = '點擊更多';

$lang->tutorial->kanbanProjectManage->manageKanban->step5 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step5->name = '點擊新增區域';
$lang->tutorial->kanbanProjectManage->manageKanban->step5->desc = '您可以在這裡添加新的區域';

$lang->tutorial->kanbanProjectManage->manageKanban->step6 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step6->name = '填寫表單';

$lang->tutorial->kanbanProjectManage->manageKanban->step7 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step7->name = '保存表單';
$lang->tutorial->kanbanProjectManage->manageKanban->step7->desc = '可以新增區域到看板項目中';

$lang->tutorial->kanbanProjectManage->manageKanban->step8 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step8->name = '點擊新建';
$lang->tutorial->kanbanProjectManage->manageKanban->step8->desc = '可以選擇關聯/新建需求';

$lang->tutorial->kanbanProjectManage->manageKanban->step9 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step9->name = '點擊關聯需求';
$lang->tutorial->kanbanProjectManage->manageKanban->step9->desc = '您可以在需求泳道中關聯/創建需求';

$lang->tutorial->kanbanProjectManage->manageKanban->step10 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step10->name = '填寫表單';

$lang->tutorial->kanbanProjectManage->manageKanban->step11 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step11->name = '保存表單';
$lang->tutorial->kanbanProjectManage->manageKanban->step11->desc = '可以將需求關聯到需求泳道中';

$lang->tutorial->kanbanProjectManage->manageKanban->step12 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step12->name = '點擊更多';

$lang->tutorial->kanbanProjectManage->manageKanban->step13 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step13->name = '點擊分解任務';
$lang->tutorial->kanbanProjectManage->manageKanban->step13->desc = '可以將需求分解為任務';

$lang->tutorial->kanbanProjectManage->manageKanban->step14 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step14->name = '填寫表單';

$lang->tutorial->kanbanProjectManage->manageKanban->step15 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step15->name = '保存表單';
$lang->tutorial->kanbanProjectManage->manageKanban->step15->desc = '可以將任務添加到任務泳道中';

$lang->tutorial->kanbanProjectManage->manageKanban->step16 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step16->name = '點擊新建';

$lang->tutorial->kanbanProjectManage->manageKanban->step17 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step17->name = '點擊新建Bug';
$lang->tutorial->kanbanProjectManage->manageKanban->step17->desc = '可以在這裡提Bug';

$lang->tutorial->kanbanProjectManage->manageKanban->step18 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step18->name = '填寫表單';

$lang->tutorial->kanbanProjectManage->manageKanban->step19 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step19->name = '保存表單';
$lang->tutorial->kanbanProjectManage->manageKanban->step19->desc = '可以將Bug添加到Bug泳道中';

$lang->tutorial->kanbanProjectManage->manageKanban->step20 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step20->name = '點擊更多';

$lang->tutorial->kanbanProjectManage->manageKanban->step21 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step21->name = '點擊在製品設置';
$lang->tutorial->kanbanProjectManage->manageKanban->step21->desc = '可以靈活設置在製品數量';

$lang->tutorial->kanbanProjectManage->manageKanban->step22 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step22->name = '填寫表單';

$lang->tutorial->kanbanProjectManage->manageKanban->step23 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step23->name = '保存表單';

$lang->tutorial->kanbanProjectManage->manageBuild = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->title = '構建管理';

$lang->tutorial->kanbanProjectManage->manageBuild->step1 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step1->name = '點擊構建';
$lang->tutorial->kanbanProjectManage->manageBuild->step1->desc = '可以在這裡進行構建管理';

$lang->tutorial->kanbanProjectManage->manageBuild->step2 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step2->name = '點擊創建構建';
$lang->tutorial->kanbanProjectManage->manageBuild->step2->desc = '可以在這裡創建新的構建';

$lang->tutorial->kanbanProjectManage->manageBuild->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step3->name = '填寫表單';

$lang->tutorial->kanbanProjectManage->manageBuild->step4 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step4->name = '保存表單';
$lang->tutorial->kanbanProjectManage->manageBuild->step4->desc = '保存後在構建列表中顯示';

$lang->tutorial->kanbanProjectManage->manageBuild->step5 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step5->name = '點擊累積流圖';
$lang->tutorial->kanbanProjectManage->manageBuild->step5->desc = '可以在這裡查看累積流圖進行看板跟蹤';

$lang->tutorial->taskManage = new stdClass();
$lang->tutorial->taskManage->title = '任務管理教程';

$lang->tutorial->taskManage->step1 = new stdClass();
$lang->tutorial->taskManage->step1->name = '點擊項目';
$lang->tutorial->taskManage->step1->desc = '點擊進入項目，對項目及其任務進行管理';

$lang->tutorial->taskManage->step2 = new stdClass();
$lang->tutorial->taskManage->step2->name = '點擊創建項目';
$lang->tutorial->taskManage->step2->desc = '點擊創建一個無迭代的項目管理任務';

$lang->tutorial->taskManage->step3 = new stdClass();
$lang->tutorial->taskManage->step3->name = '點擊Scrum項目';
$lang->tutorial->taskManage->step3->desc = '點擊創建一個無迭代的項目';

$lang->tutorial->taskManage->step4 = new stdClass();
$lang->tutorial->taskManage->step4->name = '填寫表單';
$lang->tutorial->taskManage->step4->desc = '“項目類型”選擇項目型，“啟用迭代”取消勾選，以此來創建無迭代項目';

$lang->tutorial->taskManage->step5 = new stdClass();
$lang->tutorial->taskManage->step5->name = '保存表單';
$lang->tutorial->taskManage->step5->desc = '保存後在項目列表查看';

$lang->tutorial->taskManage->step6 = new stdClass();
$lang->tutorial->taskManage->step6->name = '點擊項目名稱';
$lang->tutorial->taskManage->step6->desc = '點擊項目名稱，進入項目';

$lang->tutorial->taskManage->step7 = new stdClass();
$lang->tutorial->taskManage->step7->name = '點擊新建任務';
$lang->tutorial->taskManage->step7->desc = '點擊創建項目的任務';

$lang->tutorial->taskManage->step8 = new stdClass();
$lang->tutorial->taskManage->step8->name = '填寫表單';

$lang->tutorial->taskManage->step9 = new stdClass();
$lang->tutorial->taskManage->step9->name = '保存表單';
$lang->tutorial->taskManage->step9->desc = '保存後可以在任務列表中查看任務';

$lang->tutorial->taskManage->step10 = new stdClass();
$lang->tutorial->taskManage->step10->name = '點擊指派給';
$lang->tutorial->taskManage->step10->desc = '點擊領取、分配任務到人';

$lang->tutorial->taskManage->step11 = new stdClass();
$lang->tutorial->taskManage->step11->name = '填寫表單';

$lang->tutorial->taskManage->step12 = new stdClass();
$lang->tutorial->taskManage->step12->name = '保存表單';
$lang->tutorial->taskManage->step12->desc = '保存後在任務列表中指派給欄位會顯示被指派的用戶';

$lang->tutorial->taskManage->step13 = new stdClass();
$lang->tutorial->taskManage->step13->name = '點擊開始任務';
$lang->tutorial->taskManage->step13->desc = '您可以在這裡開始任務，並記錄消耗和剩餘工時';

$lang->tutorial->taskManage->step14 = new stdClass();
$lang->tutorial->taskManage->step14->name = '填寫表單';

$lang->tutorial->taskManage->step15 = new stdClass();
$lang->tutorial->taskManage->step15->name = '保存表單';
$lang->tutorial->taskManage->step15->desc = '保存後任務狀態變為進行中';

$lang->tutorial->taskManage->step16 = new stdClass();
$lang->tutorial->taskManage->step16->name = '點擊記錄工時';
$lang->tutorial->taskManage->step16->desc = '您可以在這裡記錄消耗和剩餘工時，當剩餘工時為0後，任務會自動完成';

$lang->tutorial->taskManage->step17 = new stdClass();
$lang->tutorial->taskManage->step17->name = '填寫表單';

$lang->tutorial->taskManage->step18 = new stdClass();
$lang->tutorial->taskManage->step18->name = '保存表單';
$lang->tutorial->taskManage->step18->desc = '保存後返回任務列表';

$lang->tutorial->taskManage->step19 = new stdClass();
$lang->tutorial->taskManage->step19->name = '點擊完成任務';
$lang->tutorial->taskManage->step19->desc = '您可以在這裡完成任務';

$lang->tutorial->taskManage->step20 = new stdClass();
$lang->tutorial->taskManage->step20->name = '填寫表單';

$lang->tutorial->taskManage->step21 = new stdClass();
$lang->tutorial->taskManage->step21->name = '保存表單';
$lang->tutorial->taskManage->step21->desc = '保存後任務狀態變為已完成';

$lang->tutorial->taskManage->step22 = new stdClass();
$lang->tutorial->taskManage->step22->name = '點擊關閉任務';
$lang->tutorial->taskManage->step22->desc = '確認任務完成後點擊關閉任務';

$lang->tutorial->taskManage->step23 = new stdClass();
$lang->tutorial->taskManage->step23->name = '填寫表單';

$lang->tutorial->taskManage->step24 = new stdClass();
$lang->tutorial->taskManage->step24->name = '保存表單';
$lang->tutorial->taskManage->step24->desc = '保存後任務狀態變為已關閉';

$lang->tutorial->testManage = new stdClass();
$lang->tutorial->testManage->title = '測試管理教程';

$lang->tutorial->testManage->step1 = new stdClass();
$lang->tutorial->testManage->step1->name = '點擊測試';
$lang->tutorial->testManage->step1->desc = '點擊測試進行測試管理';

$lang->tutorial->testManage->step2 = new stdClass();
$lang->tutorial->testManage->step2->name = '點擊用例';
$lang->tutorial->testManage->step2->desc = '點擊用例進行用例管理';

$lang->tutorial->testManage->step3 = new stdClass();
$lang->tutorial->testManage->step3->name = '點擊創建用例';
$lang->tutorial->testManage->step3->desc = '可以在這裡創建用例';

$lang->tutorial->testManage->step4 = new stdClass();
$lang->tutorial->testManage->step4->name = '填寫表單';

$lang->tutorial->testManage->step5 = new stdClass();
$lang->tutorial->testManage->step5->name = '保存表單';
$lang->tutorial->testManage->step5->desc = '保存後在用例列表中查看';

$lang->tutorial->testManage->step6 = new stdClass();
$lang->tutorial->testManage->step6->name = '點擊測試單';
$lang->tutorial->testManage->step6->desc = '點擊維護測試單信息';

$lang->tutorial->testManage->step7 = new stdClass();
$lang->tutorial->testManage->step7->name = '點擊提交測試';
$lang->tutorial->testManage->step7->desc = '點擊提交測試單會生成測試單';

$lang->tutorial->testManage->step8 = new stdClass();
$lang->tutorial->testManage->step8->name = '填寫表單';

$lang->tutorial->testManage->step9 = new stdClass();
$lang->tutorial->testManage->step9->name = '保存表單';
$lang->tutorial->testManage->step9->desc = '保存後在測試單列表查看';

$lang->tutorial->testManage->step10 = new stdClass();
$lang->tutorial->testManage->step10->name = '點擊測試單名稱';
$lang->tutorial->testManage->step10->desc = '點擊查看測試單詳情';

$lang->tutorial->testManage->step11 = new stdClass();
$lang->tutorial->testManage->step11->name = '點擊關聯用例';
$lang->tutorial->testManage->step11->desc = '點擊將用例關聯進測試單';

$lang->tutorial->testManage->step12 = new stdClass();
$lang->tutorial->testManage->step12->name = '勾選用例';
$lang->tutorial->testManage->step12->desc = '您可以將用例關聯在測試單中';

$lang->tutorial->testManage->step13 = new stdClass();
$lang->tutorial->testManage->step13->name = '點擊保存';
$lang->tutorial->testManage->step13->desc = '保存後用例成功關聯進測試單';

$lang->tutorial->testManage->step14 = new stdClass();
$lang->tutorial->testManage->step14->name = '點擊執行';
$lang->tutorial->testManage->step14->desc = '點擊執行用例';

$lang->tutorial->testManage->step15 = new stdClass();
$lang->tutorial->testManage->step15->name = '填寫表單';

$lang->tutorial->testManage->step16 = new stdClass();
$lang->tutorial->testManage->step16->name = '保存表單';
$lang->tutorial->testManage->step16->desc = '保存後可以完成用例的執行';

$lang->tutorial->testManage->step17 = new stdClass();
$lang->tutorial->testManage->step17->name = '點擊執行結果';
$lang->tutorial->testManage->step17->desc = '可以在這裡執行用例';

$lang->tutorial->testManage->step18 = new stdClass();
$lang->tutorial->testManage->step18->name = '選擇用例步驟';

$lang->tutorial->testManage->step19 = new stdClass();
$lang->tutorial->testManage->step19->name = '點擊轉Bug';
$lang->tutorial->testManage->step19->desc = '可以將執行失敗的用例步驟轉為Bug';

$lang->tutorial->testManage->step20 = new stdClass();
$lang->tutorial->testManage->step20->name = '填寫表單';

$lang->tutorial->testManage->step21 = new stdClass();
$lang->tutorial->testManage->step21->name = '保存表單';

$lang->tutorial->testManage->step22 = new stdClass();
$lang->tutorial->testManage->step22->name = '點擊測試單';

$lang->tutorial->testManage->step23 = new stdClass();
$lang->tutorial->testManage->step23->name = '生成測試報告';
$lang->tutorial->testManage->step23->desc = '可以在這裡生成測試報告';

$lang->tutorial->testManage->step24 = new stdClass();
$lang->tutorial->testManage->step24->name = '填寫表單';

$lang->tutorial->testManage->step25 = new stdClass();
$lang->tutorial->testManage->step25->name = '保存表單';
$lang->tutorial->testManage->step25->desc = '保存後可以生成測試報告';

$lang->tutorial->accountManage = new stdClass();
$lang->tutorial->accountManage->title = '賬號管理教程';

$lang->tutorial->accountManage->deptManage = new stdClass();
$lang->tutorial->accountManage->deptManage->title = '維護部門';

$lang->tutorial->accountManage->deptManage->step1 = new stdClass();
$lang->tutorial->accountManage->deptManage->step1->name = '點擊後台';
$lang->tutorial->accountManage->deptManage->step1->desc = '您可以在這裡維護管理賬號，進行各類配置項的設置。';

$lang->tutorial->accountManage->deptManage->step2 = new stdClass();
$lang->tutorial->accountManage->deptManage->step2->name = '點擊人員管理';
$lang->tutorial->accountManage->deptManage->step2->desc = '您可以在這裡維護部門、添加人員和分組配置權限';

$lang->tutorial->accountManage->deptManage->step3 = new stdClass();
$lang->tutorial->accountManage->deptManage->step3->name = '點擊部門';
$lang->tutorial->accountManage->deptManage->step3->desc = '您可以點擊這裡進行部門維護';

$lang->tutorial->accountManage->deptManage->step4 = new stdClass();
$lang->tutorial->accountManage->deptManage->step4->name = '填寫表單';

$lang->tutorial->accountManage->deptManage->step5 = new stdClass();
$lang->tutorial->accountManage->deptManage->step5->name = '保存表單';
$lang->tutorial->accountManage->deptManage->step5->desc = '保存後可以在左側目錄中看到';

$lang->tutorial->accountManage->addUser = new stdClass();
$lang->tutorial->accountManage->addUser->title = '添加人員';

$lang->tutorial->accountManage->addUser->step1 = new stdClass();
$lang->tutorial->accountManage->addUser->step1->name = '點擊用戶';
$lang->tutorial->accountManage->addUser->step1->desc = '您可以在這裡維護公司人員';

$lang->tutorial->accountManage->addUser->step2 = new stdClass();
$lang->tutorial->accountManage->addUser->step2->name = '點擊添加人員按鈕';
$lang->tutorial->accountManage->addUser->step2->desc = '點擊添加公司人員';

$lang->tutorial->accountManage->addUser->step3 = new stdClass();
$lang->tutorial->accountManage->addUser->step3->name = '填寫表單';

$lang->tutorial->accountManage->addUser->step4 = new stdClass();
$lang->tutorial->accountManage->addUser->step4->name = '保存表單';
$lang->tutorial->accountManage->addUser->step4->desc = '保存後可以在人員列表中查看';

$lang->tutorial->accountManage->privManage = new stdClass();
$lang->tutorial->accountManage->privManage->title = '維護權限';

$lang->tutorial->accountManage->privManage->step1 = new stdClass();
$lang->tutorial->accountManage->privManage->step1->name = '點擊權限';
$lang->tutorial->accountManage->privManage->step1->desc = '您可以在這裡查看人員分組、維護人員權限。';

$lang->tutorial->accountManage->privManage->step2 = new stdClass();
$lang->tutorial->accountManage->privManage->step2->name = '點擊新增分組';
$lang->tutorial->accountManage->privManage->step2->desc = '點擊增加人員分組';

$lang->tutorial->accountManage->privManage->step3 = new stdClass();
$lang->tutorial->accountManage->privManage->step3->name = '填寫表單';

$lang->tutorial->accountManage->privManage->step4 = new stdClass();
$lang->tutorial->accountManage->privManage->step4->name = '保存表單';
$lang->tutorial->accountManage->privManage->step4->desc = '保存後可以在人員列表中查看';

$lang->tutorial->accountManage->privManage->step5 = new stdClass();
$lang->tutorial->accountManage->privManage->step5->name = '點擊成員維護';
$lang->tutorial->accountManage->privManage->step5->desc = '您可以為權限組添加公司人員以便後面分組授權。';

$lang->tutorial->accountManage->privManage->step6 = new stdClass();
$lang->tutorial->accountManage->privManage->step6->name = '填寫表單';

$lang->tutorial->accountManage->privManage->step7 = new stdClass();
$lang->tutorial->accountManage->privManage->step7->name = '保存表單';
$lang->tutorial->accountManage->privManage->step7->desc = '保存後可以在人員列表中查看';

$lang->tutorial->accountManage->privManage->step8 = new stdClass();
$lang->tutorial->accountManage->privManage->step8->name = '點擊分配權限';
$lang->tutorial->accountManage->privManage->step8->desc = '點擊為用戶組維護權限';

$lang->tutorial->accountManage->privManage->step9 = new stdClass();
$lang->tutorial->accountManage->privManage->step9->name = '點擊權限包的展開按鈕';
$lang->tutorial->accountManage->privManage->step9->desc = '點擊查看權限包下的權限';

$lang->tutorial->accountManage->privManage->step10 = new stdClass();
$lang->tutorial->accountManage->privManage->step10->name = '保存表單';
$lang->tutorial->accountManage->privManage->step10->desc = '保存後該分組的人員擁有分配到的權限';

$lang->tutorial->productManage = new stdClass();
$lang->tutorial->productManage->title = '產品管理教程';

$lang->tutorial->productManage->addProduct = new stdClass();
$lang->tutorial->productManage->addProduct->title = '產品維護';

$lang->tutorial->productManage->addProduct->step1 = new stdClass();
$lang->tutorial->productManage->addProduct->step1->name = '點擊添加產品';
$lang->tutorial->productManage->addProduct->step1->desc = '點擊添加產品';

$lang->tutorial->productManage->addProduct->step2 = new stdClass();
$lang->tutorial->productManage->addProduct->step2->name = '填寫表單';

$lang->tutorial->productManage->addProduct->step3 = new stdClass();
$lang->tutorial->productManage->addProduct->step3->name = '保存表單';
$lang->tutorial->productManage->addProduct->step3->desc = '保存後可以在產品列表中查看';

$lang->tutorial->productManage->moduleManage = new stdClass();
$lang->tutorial->productManage->moduleManage->title = '產品模組維護';

$lang->tutorial->productManage->moduleManage->step1 = new stdClass();
$lang->tutorial->productManage->moduleManage->step1->name = '點擊產品名稱';
$lang->tutorial->productManage->moduleManage->step1->desc = '點擊進入產品，查看產品的詳細信息.';

$lang->tutorial->productManage->moduleManage->step2 = new stdClass();
$lang->tutorial->productManage->moduleManage->step2->name = '點擊模組設置';
$lang->tutorial->productManage->moduleManage->step2->desc = '點擊去維護產品的模組';

$lang->tutorial->productManage->moduleManage->step3 = new stdClass();
$lang->tutorial->productManage->moduleManage->step3->name = '填寫表單';

$lang->tutorial->productManage->moduleManage->step4 = new stdClass();
$lang->tutorial->productManage->moduleManage->step4->name = '保存表單';
$lang->tutorial->productManage->moduleManage->step4->desc = '保存後可以在創建需求時選擇模組進行分類';

$lang->tutorial->productManage->storyManage = new stdClass();
$lang->tutorial->productManage->storyManage->title = '需求管理';

$lang->tutorial->productManage->storyManage->step1 = new stdClass();
$lang->tutorial->productManage->storyManage->step1->name = '點擊業務需求';
$lang->tutorial->productManage->storyManage->step1->desc = '您可以在這裡管理產品的業務需求';

$lang->tutorial->productManage->storyManage->step2 = new stdClass();
$lang->tutorial->productManage->storyManage->step2->name = '點擊提業務需求';
$lang->tutorial->productManage->storyManage->step2->desc = '點擊提業務需求';

$lang->tutorial->productManage->storyManage->step3 = new stdClass();
$lang->tutorial->productManage->storyManage->step3->name = '填寫表單';

$lang->tutorial->productManage->storyManage->step4 = new stdClass();
$lang->tutorial->productManage->storyManage->step4->name = '保存表單';
$lang->tutorial->productManage->storyManage->step4->desc = '保存後在業務需求列表查看';

$lang->tutorial->productManage->storyManage->step5 = new stdClass();
$lang->tutorial->productManage->storyManage->step5->name = '點擊拆分業務需求';
$lang->tutorial->productManage->storyManage->step5->desc = '點擊將業務需拆分成用戶需求';

$lang->tutorial->productManage->storyManage->step6 = new stdClass();
$lang->tutorial->productManage->storyManage->step6->name = '填寫表單';

$lang->tutorial->productManage->storyManage->step7 = new stdClass();
$lang->tutorial->productManage->storyManage->step7->name = '保存表單';
$lang->tutorial->productManage->storyManage->step7->desc = '保存後可以在需求列表中查看';

$lang->tutorial->productManage->storyManage->step8 = new stdClass();
$lang->tutorial->productManage->storyManage->step8->name = '點拆分用戶需求';
$lang->tutorial->productManage->storyManage->step8->desc = '點擊將用戶需求拆分成研發需求';

$lang->tutorial->productManage->storyManage->step9 = new stdClass();
$lang->tutorial->productManage->storyManage->step9->name = '填寫表單';

$lang->tutorial->productManage->storyManage->step10 = new stdClass();
$lang->tutorial->productManage->storyManage->step10->name = '保存表單';
$lang->tutorial->productManage->storyManage->step10->desc = '保存後在需求列表中查看';

$lang->tutorial->productManage->storyManage->step11 = new stdClass();
$lang->tutorial->productManage->storyManage->step11->name = '點擊評審按鈕';
$lang->tutorial->productManage->storyManage->step11->desc = '點擊對需求進行評審';

$lang->tutorial->productManage->storyManage->step12 = new stdClass();
$lang->tutorial->productManage->storyManage->step12->name = '填寫表單';

$lang->tutorial->productManage->storyManage->step13 = new stdClass();
$lang->tutorial->productManage->storyManage->step13->name = '保存表單';
$lang->tutorial->productManage->storyManage->step13->desc = '保存後需求的狀態根據評審結果變動';

$lang->tutorial->productManage->storyManage->step14 = new stdClass();
$lang->tutorial->productManage->storyManage->step14->name = '點擊變更按鈕';
$lang->tutorial->productManage->storyManage->step14->desc = '點擊對需求進行變更';

$lang->tutorial->productManage->storyManage->step15 = new stdClass();
$lang->tutorial->productManage->storyManage->step15->name = '填寫表單';

$lang->tutorial->productManage->storyManage->step16 = new stdClass();
$lang->tutorial->productManage->storyManage->step16->name = '保存表單';
$lang->tutorial->productManage->storyManage->step16->desc = '保存後，需求變更完成';

$lang->tutorial->productManage->storyManage->step17 = new stdClass();
$lang->tutorial->productManage->storyManage->step17->name = '點擊矩陣';
$lang->tutorial->productManage->storyManage->step17->desc = '您可以在這裡跟進需求的進展情況';

$lang->tutorial->productManage->planManage = new stdClass();
$lang->tutorial->productManage->planManage->title = '計劃管理';

$lang->tutorial->productManage->planManage->step1 = new stdClass();
$lang->tutorial->productManage->planManage->step1->name = '點擊計劃';
$lang->tutorial->productManage->planManage->step1->desc = '您可以在這裡維護管理產品計劃';

$lang->tutorial->productManage->planManage->step2 = new stdClass();
$lang->tutorial->productManage->planManage->step2->name = '點擊創建計劃';
$lang->tutorial->productManage->planManage->step2->desc = '點擊為產品創建計劃';

$lang->tutorial->productManage->planManage->step3 = new stdClass();
$lang->tutorial->productManage->planManage->step3->name = '填寫表單';

$lang->tutorial->productManage->planManage->step4 = new stdClass();
$lang->tutorial->productManage->planManage->step4->name = '保存表單';
$lang->tutorial->productManage->planManage->step4->desc = '保存後可以在計劃列表中查看';

$lang->tutorial->productManage->planManage->step5 = new stdClass();
$lang->tutorial->productManage->planManage->step5->name = '點擊計劃名稱';
$lang->tutorial->productManage->planManage->step5->desc = '點擊進入計劃的詳情，管理計劃詳細信息';

$lang->tutorial->productManage->planManage->step6 = new stdClass();
$lang->tutorial->productManage->planManage->step6->name = '點擊關聯需求';
$lang->tutorial->productManage->planManage->step6->desc = '將該計劃要完成的需求關聯進計劃中';

$lang->tutorial->productManage->planManage->step7 = new stdClass();
$lang->tutorial->productManage->planManage->step7->name = '勾選需求';

$lang->tutorial->productManage->planManage->step8 = new stdClass();
$lang->tutorial->productManage->planManage->step8->name = '點擊保存';
$lang->tutorial->productManage->planManage->step8->desc = '保存後，需求成功關聯進計劃中';

$lang->tutorial->productManage->planManage->step9 = new stdClass();
$lang->tutorial->productManage->planManage->step9->name = '點擊Bug';
$lang->tutorial->productManage->planManage->step9->desc = '將該計劃要解決的Bug關聯進計劃中';

$lang->tutorial->productManage->planManage->step10 = new stdClass();
$lang->tutorial->productManage->planManage->step10->name = '點擊關聯Bug';
$lang->tutorial->productManage->planManage->step10->desc = '點擊將該計劃要解決的Bug關聯進計劃中';

$lang->tutorial->productManage->planManage->step11 = new stdClass();
$lang->tutorial->productManage->planManage->step11->name = '勾選Bug';

$lang->tutorial->productManage->planManage->step12 = new stdClass();
$lang->tutorial->productManage->planManage->step12->name = '點擊保存';
$lang->tutorial->productManage->planManage->step12->desc = '保存後，Bug成功關聯進計劃中';

$lang->tutorial->productManage->releaseManage = new stdClass();
$lang->tutorial->productManage->releaseManage->title = '發佈管理';

$lang->tutorial->productManage->releaseManage->step1 = new stdClass();
$lang->tutorial->productManage->releaseManage->step1->name = '點擊發佈';
$lang->tutorial->productManage->releaseManage->step1->desc = '您可以在這裡維護管理產品的發佈信息';

$lang->tutorial->productManage->releaseManage->step2 = new stdClass();
$lang->tutorial->productManage->releaseManage->step2->name = '點擊創建發佈';
$lang->tutorial->productManage->releaseManage->step2->desc = '點擊為產品創建發佈';

$lang->tutorial->productManage->releaseManage->step3 = new stdClass();
$lang->tutorial->productManage->releaseManage->step3->name = '填寫表單';

$lang->tutorial->productManage->releaseManage->step4 = new stdClass();
$lang->tutorial->productManage->releaseManage->step4->name = '保存表單';
$lang->tutorial->productManage->releaseManage->step4->desc = '保存後，在發佈列表中查看';

$lang->tutorial->productManage->releaseManage->step5 = new stdClass();
$lang->tutorial->productManage->releaseManage->step5->name = '點擊發佈名稱';
$lang->tutorial->productManage->releaseManage->step5->desc = '點擊進入發佈，查看管理髮布詳細信息';

$lang->tutorial->productManage->releaseManage->step6 = new stdClass();
$lang->tutorial->productManage->releaseManage->step6->name = '點擊關聯需求';
$lang->tutorial->productManage->releaseManage->step6->desc = '點擊將本次要發佈的研發需求關聯進發佈';

$lang->tutorial->productManage->releaseManage->step7 = new stdClass();
$lang->tutorial->productManage->releaseManage->step7->name = '勾選需求';

$lang->tutorial->productManage->releaseManage->step8 = new stdClass();
$lang->tutorial->productManage->releaseManage->step8->name = '點擊保存';
$lang->tutorial->productManage->releaseManage->step8->desc = '保存後需求成功關聯進發佈';

$lang->tutorial->productManage->releaseManage->step9 = new stdClass();
$lang->tutorial->productManage->releaseManage->step9->name = '點擊解決的Bug';
$lang->tutorial->productManage->releaseManage->step9->desc = '點擊查看管理本次發佈解決的Bug';

$lang->tutorial->productManage->releaseManage->step10 = new stdClass();
$lang->tutorial->productManage->releaseManage->step10->name = '點擊關聯Bug';
$lang->tutorial->productManage->releaseManage->step10->desc = '點擊將本次發佈解決的Bug關聯進發佈';

$lang->tutorial->productManage->releaseManage->step11 = new stdClass();
$lang->tutorial->productManage->releaseManage->step11->name = '勾選Bug';

$lang->tutorial->productManage->releaseManage->step12 = new stdClass();
$lang->tutorial->productManage->releaseManage->step12->name = '點擊保存';
$lang->tutorial->productManage->releaseManage->step12->desc = '保存後，Bug成功關聯進發佈中';

$lang->tutorial->productManage->releaseManage->step13 = new stdClass();
$lang->tutorial->productManage->releaseManage->step13->name = '點擊遺留的Bug';
$lang->tutorial->productManage->releaseManage->step13->desc = '點擊查看管理本次發佈解決的Bug';

$lang->tutorial->productManage->releaseManage->step14 = new stdClass();
$lang->tutorial->productManage->releaseManage->step14->name = '點擊關聯Bug';
$lang->tutorial->productManage->releaseManage->step14->desc = '點擊將本次發佈遺留未解決的Bug關聯進發佈';

$lang->tutorial->productManage->releaseManage->step15 = new stdClass();
$lang->tutorial->productManage->releaseManage->step15->name = '勾選Bug';

$lang->tutorial->productManage->releaseManage->step16 = new stdClass();
$lang->tutorial->productManage->releaseManage->step16->name = '點擊保存';
$lang->tutorial->productManage->releaseManage->step16->desc = '保存後，Bug成功關聯進發佈中';

$lang->tutorial->productManage->releaseManage->step17 = new stdClass();
$lang->tutorial->productManage->releaseManage->step17->name = '點擊發佈按鈕';
$lang->tutorial->productManage->releaseManage->step17->desc = '點擊進行發佈';

$lang->tutorial->productManage->releaseManage->step18 = new stdClass();
$lang->tutorial->productManage->releaseManage->step18->name = '填寫表單';

$lang->tutorial->productManage->releaseManage->step19 = new stdClass();
$lang->tutorial->productManage->releaseManage->step19->name = '保存表單';
$lang->tutorial->productManage->releaseManage->step19->desc = '保存後，需求會根據發佈狀態改變階段';

$lang->tutorial->productManage->releaseManage->step20 = new stdClass();
$lang->tutorial->productManage->releaseManage->step20->name = '點擊管理應用';
$lang->tutorial->productManage->releaseManage->step20->desc = '您可以在這裡維護管理產品的應用信息';

$lang->tutorial->productManage->releaseManage->step21 = new stdClass();
$lang->tutorial->productManage->releaseManage->step21->name = '點擊創建應用';
$lang->tutorial->productManage->releaseManage->step21->desc = '點擊為產品創建應用';

$lang->tutorial->productManage->releaseManage->step22 = new stdClass();
$lang->tutorial->productManage->releaseManage->step22->name = '填寫表單';

$lang->tutorial->productManage->releaseManage->step23 = new stdClass();
$lang->tutorial->productManage->releaseManage->step23->name = '保存表單';
$lang->tutorial->productManage->releaseManage->step23->desc = '保存後，在應用列表中查看';

$lang->tutorial->productManage->releaseManage->step24 = new stdClass();
$lang->tutorial->productManage->releaseManage->step24->name = '點擊返回';
$lang->tutorial->productManage->releaseManage->step24->desc = '您可以在這裡維護管理產品的發佈信息';

$lang->tutorial->productManage->lineManage = new stdClass();
$lang->tutorial->productManage->lineManage->title = '產品線管理';

$lang->tutorial->productManage->lineManage->step1 = new stdClass();
$lang->tutorial->productManage->lineManage->step1->name = '點擊產品';
$lang->tutorial->productManage->lineManage->step1->desc = '您可以在這裡對產品進行維護管理';

$lang->tutorial->productManage->lineManage->step2 = new stdClass();
$lang->tutorial->productManage->lineManage->step2->name = '點擊產品綫按鈕';
$lang->tutorial->productManage->lineManage->step2->desc = '點擊維護產品綫';

$lang->tutorial->productManage->lineManage->step3 = new stdClass();
$lang->tutorial->productManage->lineManage->step3->name = '填寫表單';

$lang->tutorial->productManage->lineManage->step4 = new stdClass();
$lang->tutorial->productManage->lineManage->step4->name = '保存表單';
$lang->tutorial->productManage->lineManage->step4->desc = '保存後在維護產品時可以選擇對應的產品綫';

$lang->tutorial->productManage->branchManage = new stdClass();
$lang->tutorial->productManage->branchManage->title = '多分支/平台管理';

$lang->tutorial->productManage->branchManage->step1 = new stdClass();
$lang->tutorial->productManage->branchManage->step1->name = '點擊產品';
$lang->tutorial->productManage->branchManage->step1->desc = '您可以在這裡對產品進行維護管理';

$lang->tutorial->productManage->branchManage->step2 = new stdClass();
$lang->tutorial->productManage->branchManage->step2->name = '點擊添加產品';
$lang->tutorial->productManage->branchManage->step2->desc = '點擊添加產品';

$lang->tutorial->productManage->branchManage->step3 = new stdClass();
$lang->tutorial->productManage->branchManage->step3->name = '填寫表單';

$lang->tutorial->productManage->branchManage->step4 = new stdClass();
$lang->tutorial->productManage->branchManage->step4->name = '保存表單';

$lang->tutorial->productManage->branchManage->step5 = new stdClass();
$lang->tutorial->productManage->branchManage->step5->name = '點擊設置';
$lang->tutorial->productManage->branchManage->step5->desc = '點擊維護產品的信息';

$lang->tutorial->productManage->branchManage->step6 = new stdClass();
$lang->tutorial->productManage->branchManage->step6->name = '點擊分支';
$lang->tutorial->productManage->branchManage->step6->desc = '點擊維護產品分支';

$lang->tutorial->productManage->branchManage->step7 = new stdClass();
$lang->tutorial->productManage->branchManage->step7->name = '點擊新建分支';
$lang->tutorial->productManage->branchManage->step7->desc = '點擊為產品添加新的分支';

$lang->tutorial->productManage->branchManage->step8 = new stdClass();
$lang->tutorial->productManage->branchManage->step8->name = '填寫表單';

$lang->tutorial->productManage->branchManage->step9 = new stdClass();
$lang->tutorial->productManage->branchManage->step9->name = '保存表單';
$lang->tutorial->productManage->branchManage->step9->desc = '保存後在分支列表查看分支';

$lang->tutorial->productManage->branchManage->step10 = new stdClass();
$lang->tutorial->productManage->branchManage->step10->name = '勾選分支';

$lang->tutorial->productManage->branchManage->step11 = new stdClass();
$lang->tutorial->productManage->branchManage->step11->name = '點擊合併';

$lang->tutorial->productManage->branchManage->step12 = new stdClass();
$lang->tutorial->productManage->branchManage->step12->name = '選擇分支';

$lang->tutorial->productManage->branchManage->step13 = new stdClass();
$lang->tutorial->productManage->branchManage->step13->name = '保存表單';
$lang->tutorial->productManage->branchManage->step13->desc = '保存後分支下面對應的發佈、計劃、構建、模組、需求、Bug、用例都合併到新的分支下';

$lang->tutorial->productManage->branchManage->step14 = new stdClass();
$lang->tutorial->productManage->branchManage->step14->name = '點擊研發需求';
$lang->tutorial->productManage->branchManage->step14->desc = '您可以在這裡管理產品的研發需求';

$lang->tutorial->productManage->branchManage->step15 = new stdClass();
$lang->tutorial->productManage->branchManage->step15->name = '點擊提研發需求';
$lang->tutorial->productManage->branchManage->step15->desc = '點擊創建孿生需求';

$lang->tutorial->productManage->branchManage->step16 = new stdClass();
$lang->tutorial->productManage->branchManage->step16->name = '填寫表單';

$lang->tutorial->productManage->branchManage->step17 = new stdClass();
$lang->tutorial->productManage->branchManage->step17->name = '保存表單';
$lang->tutorial->productManage->branchManage->step17->desc = '保存後每個分支會建立一個需求，需求間互為孿生關係。孿生需求間除產品、分支、模組、計劃、階段欄位外均保持同步，在需求詳情頁可以解除孿生關係。';

$lang->tutorial->programManage = new stdClass();
$lang->tutorial->programManage->title = '項目集管理教程';

$lang->tutorial->programManage->addProgram = new stdClass();
$lang->tutorial->programManage->addProgram->title = '項目集維護';

$lang->tutorial->programManage->addProgram->step1 = new stdClass();
$lang->tutorial->programManage->addProgram->step1->name = '點擊項目集';
$lang->tutorial->programManage->addProgram->step1->desc = '您可以在這裡維護管理項目集';

$lang->tutorial->programManage->addProgram->step2 = new stdClass();
$lang->tutorial->programManage->addProgram->step2->name = '點擊添加項目集';
$lang->tutorial->programManage->addProgram->step2->desc = '點擊添加項目集';

$lang->tutorial->programManage->addProgram->step3 = new stdClass();
$lang->tutorial->programManage->addProgram->step3->name = '填寫表單';

$lang->tutorial->programManage->addProgram->step4 = new stdClass();
$lang->tutorial->programManage->addProgram->step4->name = '保存表單';
$lang->tutorial->programManage->addProgram->step4->desc = '保存後在項目視角和產品視角列表中均可查看';

$lang->tutorial->programManage->addProgram->step5 = new stdClass();
$lang->tutorial->programManage->addProgram->step5->name = '點擊添加項目';
$lang->tutorial->programManage->addProgram->step5->desc = '點擊維護項目集下的項目';

$lang->tutorial->programManage->addProgram->step6 = new stdClass();
$lang->tutorial->programManage->addProgram->step6->name = '點擊Scrum';
$lang->tutorial->programManage->addProgram->step6->desc = '可以在這裡為該項目集添加項目';

$lang->tutorial->programManage->addProgram->step7 = new stdClass();
$lang->tutorial->programManage->addProgram->step7->name = '填寫表單';

$lang->tutorial->programManage->addProgram->step8 = new stdClass();
$lang->tutorial->programManage->addProgram->step8->name = '保存表單';
$lang->tutorial->programManage->addProgram->step8->desc = '保存後可以在項目視角列表中查看';

$lang->tutorial->programManage->addProgram->step9 = new stdClass();
$lang->tutorial->programManage->addProgram->step9->name = '點擊產品視角';
$lang->tutorial->programManage->addProgram->step9->desc = '在這裡您可以查看維護項目集和產品的關係';

$lang->tutorial->programManage->addProgram->step10 = new stdClass();
$lang->tutorial->programManage->addProgram->step10->name = '點擊展開';

$lang->tutorial->programManage->addProgram->step11 = new stdClass();
$lang->tutorial->programManage->addProgram->step11->name = '點擊添加產品';
$lang->tutorial->programManage->addProgram->step11->desc = '點擊維護項目集下的產品';

$lang->tutorial->programManage->addProgram->step12 = new stdClass();
$lang->tutorial->programManage->addProgram->step12->name = '填寫表單';

$lang->tutorial->programManage->addProgram->step13 = new stdClass();
$lang->tutorial->programManage->addProgram->step13->name = '保存表單';
$lang->tutorial->programManage->addProgram->step13->desc = '保存後在產品視角列表查看';

$lang->tutorial->programManage->whitelistManage = new stdClass();
$lang->tutorial->programManage->whitelistManage->title = '維護白名單';

$lang->tutorial->programManage->whitelistManage->step1 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step1->name = '點擊項目集名稱';
$lang->tutorial->programManage->whitelistManage->step1->desc = '點擊進入項目集，查看項目集的詳細信息.';

$lang->tutorial->programManage->whitelistManage->step2 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step2->name = '點擊人員';
$lang->tutorial->programManage->whitelistManage->step2->desc = '點擊查看項目集投入人員、可訪問人員及白名單信息';

$lang->tutorial->programManage->whitelistManage->step3 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step3->name = '點擊白名單';
$lang->tutorial->programManage->whitelistManage->step3->desc = '點擊管理項目集白名單';

$lang->tutorial->programManage->whitelistManage->step4 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step4->name = '點擊添加白名單';
$lang->tutorial->programManage->whitelistManage->step4->desc = '點擊維護白名單人員';

$lang->tutorial->programManage->whitelistManage->step5 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step5->name = '填寫表單';

$lang->tutorial->programManage->whitelistManage->step6 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step6->name = '保存表單';
$lang->tutorial->programManage->whitelistManage->step6->desc = '保存後白名單人員可以查看項目集';

$lang->tutorial->programManage->addStakeholder = new stdClass();
$lang->tutorial->programManage->addStakeholder->title = '創建干係人';

$lang->tutorial->programManage->addStakeholder->step1 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step1->name = '點擊干係人';
$lang->tutorial->programManage->addStakeholder->step1->desc = '點擊管理項目集的干係人';

$lang->tutorial->programManage->addStakeholder->step2 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step2->name = '點擊添加干係人';
$lang->tutorial->programManage->addStakeholder->step2->desc = '點擊添加項目集內外部干係人';

$lang->tutorial->programManage->addStakeholder->step3 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step3->name = '填寫表單';

$lang->tutorial->programManage->addStakeholder->step4 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step4->name = '保存表單';
$lang->tutorial->programManage->addStakeholder->step4->desc = '保存後干係人可以查看項目集';

$lang->tutorial->feedbackManage = new stdClass();
$lang->tutorial->feedbackManage->title = '反饋管理教程';

$lang->tutorial->feedbackManage->feedback = new stdClass();
$lang->tutorial->feedbackManage->feedback->title = '反饋管理';

$lang->tutorial->feedbackManage->feedback->step1 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step1->name = '點擊反饋';
$lang->tutorial->feedbackManage->feedback->step1->desc = '您在這裡可以添加、處理反饋';

$lang->tutorial->feedbackManage->feedback->step2 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step2->name = '點擊創建反饋';
$lang->tutorial->feedbackManage->feedback->step2->desc = '點擊給某產品提反饋';

$lang->tutorial->feedbackManage->feedback->step3 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step3->name = '填寫表單';

$lang->tutorial->feedbackManage->feedback->step4 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step4->name = '保存表單';
$lang->tutorial->feedbackManage->feedback->step4->desc = '保存後在反饋列表跟進處理進度';

$lang->tutorial->feedbackManage->feedback->step5 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step5->name = '點擊評審';
$lang->tutorial->feedbackManage->feedback->step5->desc = '點擊對該條反饋進行評審';

$lang->tutorial->feedbackManage->feedback->step6 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step6->name = '填寫表單';

$lang->tutorial->feedbackManage->feedback->step7 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step7->name = '保存表單';
$lang->tutorial->feedbackManage->feedback->step7->desc = '保存後反饋狀態隨之改變';

$lang->tutorial->feedbackManage->feedback->step8 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step8->name = '點擊轉Bug';
$lang->tutorial->feedbackManage->feedback->step8->desc = '點擊選擇反饋的處理方式';

$lang->tutorial->feedbackManage->feedback->step9 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step9->name = '填寫表單';

$lang->tutorial->feedbackManage->feedback->step10 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step10->name = '保存表單';
$lang->tutorial->feedbackManage->feedback->step10->desc = '保存後反饋的狀態變為處理中，當轉化的需求、任務等完成後，反饋的狀態才會變為已處理';

$lang->tutorial->feedbackManage->feedback->step11 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step11->name = '關閉反饋';
$lang->tutorial->feedbackManage->feedback->step11->desc = '點擊關閉處理完的反饋，反饋處理完成';

$lang->tutorial->feedbackManage->feedback->step12 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step12->name = '填寫表單';

$lang->tutorial->feedbackManage->feedback->step13 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step13->name = '保存表單';

$lang->tutorial->docManage = new stdClass();
$lang->tutorial->docManage->title = '文檔管理教程';

$lang->tutorial->docManage->step1 = new stdClass();
$lang->tutorial->docManage->step1->name = '點擊文檔';
$lang->tutorial->docManage->step1->desc = '您在這裡可以對產品、項目、團隊和個人的文檔進行管理';

$lang->tutorial->docManage->step2 = new stdClass();
$lang->tutorial->docManage->step2->name = '點擊空間';
$lang->tutorial->docManage->step2->desc = '產品空間管理各產品下的文檔，項目空間管理各項目下的文檔，團隊空間管理組織團隊文檔，介面空間專門管理介面文檔，請點擊團隊空間進入。';

$lang->tutorial->docManage->step3 = new stdClass();
$lang->tutorial->docManage->step3->name = '點擊更多';

$lang->tutorial->docManage->step4 = new stdClass();
$lang->tutorial->docManage->step4->name = '點擊創建空間';

$lang->tutorial->docManage->step5 = new stdClass();
$lang->tutorial->docManage->step5->name = '填寫表單';

$lang->tutorial->docManage->step6 = new stdClass();
$lang->tutorial->docManage->step6->name = '保存表單';
$lang->tutorial->docManage->step6->desc = '保存後，可以在空間下管理庫和文檔。';

$lang->tutorial->docManage->step7 = new stdClass();
$lang->tutorial->docManage->step7->name = '點擊創建庫';
$lang->tutorial->docManage->step7->desc = '點擊創建文檔庫';

$lang->tutorial->docManage->step8 = new stdClass();
$lang->tutorial->docManage->step8->name = '填寫表單';

$lang->tutorial->docManage->step9 = new stdClass();
$lang->tutorial->docManage->step9->name = '保存表單';
$lang->tutorial->docManage->step9->desc = '保存後在左側目錄樹中查看';

$lang->tutorial->docManage->step10 = new stdClass();
$lang->tutorial->docManage->step10->name = '滑鼠移入，點擊更多按鈕';

$lang->tutorial->docManage->step11 = new stdClass();
$lang->tutorial->docManage->step11->name = '點擊添加目錄';
$lang->tutorial->docManage->step11->desc = '點擊給文檔庫添加目錄';

$lang->tutorial->docManage->step12 = new stdClass();
$lang->tutorial->docManage->step12->name = '填寫目錄名稱';

$lang->tutorial->docManage->step13 = new stdClass();
$lang->tutorial->docManage->step13->name = '點擊創建文檔';
$lang->tutorial->docManage->step13->desc = '點擊創建文檔';

$lang->tutorial->docManage->step14 = new stdClass();
$lang->tutorial->docManage->step14->name = '填寫表單';

$lang->tutorial->docManage->step15 = new stdClass();
$lang->tutorial->docManage->step15->name = '點擊發佈';

$lang->tutorial->docManage->step16 = new stdClass();
$lang->tutorial->docManage->step16->name = '填寫表單';

$lang->tutorial->docManage->step17 = new stdClass();
$lang->tutorial->docManage->step17->name = '保存發佈';
$lang->tutorial->docManage->step17->desc = '保存後在文檔列表中查看';

$lang->tutorial->docManage->step18 = new stdClass();
$lang->tutorial->docManage->step18->name = '點擊文檔標題';
$lang->tutorial->docManage->step18->desc = '點擊查看文檔詳情，支持收藏、編輯、導出文檔，支持查看文檔的歷史記錄、更新信息。';

$lang->tutorial->docManage->step19 = new stdClass();
$lang->tutorial->docManage->step19->name = '點擊編輯按鈕';
$lang->tutorial->docManage->step19->desc = '點擊修改文檔內容';

$lang->tutorial->docManage->step20 = new stdClass();
$lang->tutorial->docManage->step20->name = '修改文檔';

$lang->tutorial->docManage->step21 = new stdClass();
$lang->tutorial->docManage->step21->name = '點擊發佈';
$lang->tutorial->docManage->step21->desc = '點擊保存修改的內容';

$lang->tutorial->docManage->step22 = new stdClass();
$lang->tutorial->docManage->step22->name = '點擊版本';
$lang->tutorial->docManage->step22->desc = '可以在這裡切換文檔版本查看歷史版本記錄';

$lang->tutorial->docManage->step23 = new stdClass();
$lang->tutorial->docManage->step23->name = '點擊版本#1';
$lang->tutorial->docManage->step23->desc = '查看版本#1的文檔內容';

$lang->tutorial->orTutorial = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->title = '需求池管理教程';

$lang->tutorial->orTutorial->demandpoolManage->demandManage = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->title = '需求管理';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1->name = '點擊創建需求';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1->desc = '點擊創建需求';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step2 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step2->name = '填寫表單';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3->name = '保存表單';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3->desc = '保存後在需求列表查看';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4->name = '點擊評審按鈕';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4->desc = '點擊對需求進行評審';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step5 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step5->name = '填寫表單';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6->name = '保存表單';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6->desc = '保存後需求的狀態根據評審結果變動';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7->name = '點擊變更按鈕';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7->desc = '點擊對需求進行變更';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step8 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step8->name = '填寫表單';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9->name = '保存表單';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9->desc = '保存後，需求變更完成';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10->name = '點擊矩陣';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10->desc = '您可以在這裡跟進需求的進展情況';

$lang->tutorial->orTutorial->marketManage = new stdClass();
$lang->tutorial->orTutorial->marketManage->title = '市場管理教程';

$lang->tutorial->orTutorial->marketManage->researchManage = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->title = '調研管理';

$lang->tutorial->orTutorial->marketManage->researchManage->step1 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step1->name = '點擊市場';
$lang->tutorial->orTutorial->marketManage->researchManage->step1->desc = '您在這裡可以管理調研活動';

$lang->tutorial->orTutorial->marketManage->researchManage->step2 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step2->name = '點擊調研';
$lang->tutorial->orTutorial->marketManage->researchManage->step2->desc = '您在這裡可以管理調研活動';

$lang->tutorial->orTutorial->marketManage->researchManage->step3 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step3->name = '點擊發起調研';
$lang->tutorial->orTutorial->marketManage->researchManage->step3->desc = '點擊發起調研活動';

$lang->tutorial->orTutorial->marketManage->researchManage->step4 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step4->name = '填寫表單';

$lang->tutorial->orTutorial->marketManage->researchManage->step5 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step5->name = '保存表單';
$lang->tutorial->orTutorial->marketManage->researchManage->step5->desc = '保存後在調研列表查看';

$lang->tutorial->orTutorial->marketManage->researchManage->step6 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step6->name = '點擊調研名稱';
$lang->tutorial->orTutorial->marketManage->researchManage->step6->desc = '點擊管理調研活動';

$lang->tutorial->orTutorial->marketManage->researchManage->step7 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step7->name = '點擊設置階段';
$lang->tutorial->orTutorial->marketManage->researchManage->step7->desc = '點擊設置調研活動的階段';

$lang->tutorial->orTutorial->marketManage->researchManage->step8 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step8->name = '填寫表單';

$lang->tutorial->orTutorial->marketManage->researchManage->step9 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step9->name = '保存表單';
$lang->tutorial->orTutorial->marketManage->researchManage->step9->desc = '保存後在調研任務列表查看';

$lang->tutorial->orTutorial->marketManage->researchManage->step10 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step10->name = '點擊建任務';
$lang->tutorial->orTutorial->marketManage->researchManage->step10->desc = '點擊創建調研活動的任務';

$lang->tutorial->orTutorial->marketManage->researchManage->step11 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step11->name = '填寫表單';

$lang->tutorial->orTutorial->marketManage->researchManage->step12 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step12->name = '保存表單';
$lang->tutorial->orTutorial->marketManage->researchManage->step12->desc = '保存後在調研任務列表查看';

$lang->tutorial->orTutorial->marketManage->researchManage->step13 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step13->name = '開始任務';
$lang->tutorial->orTutorial->marketManage->researchManage->step13->desc = '您可以在這裡開始任務，並記錄消耗和剩餘工時';

$lang->tutorial->orTutorial->marketManage->researchManage->step14 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step14->name = '填寫表單';

$lang->tutorial->orTutorial->marketManage->researchManage->step15 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step15->name = '保存表單';
$lang->tutorial->orTutorial->marketManage->researchManage->step15->desc = '保存後任務狀態變為進行中';

$lang->tutorial->orTutorial->marketManage->researchManage->step16 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step16->name = '點擊日誌';
$lang->tutorial->orTutorial->marketManage->researchManage->step16->desc = '點擊為任務記錄工時日誌';

$lang->tutorial->orTutorial->marketManage->researchManage->step17 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step17->name = '填寫表單';

$lang->tutorial->orTutorial->marketManage->researchManage->step18 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step18->name = '保存表單';
$lang->tutorial->orTutorial->marketManage->researchManage->step18->desc = '保存後任務工時會根據日誌更新';

$lang->tutorial->orTutorial->marketManage->researchManage->step19 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step19->name = '完成任務';
$lang->tutorial->orTutorial->marketManage->researchManage->step19->desc = '點擊完成任務';

$lang->tutorial->orTutorial->marketManage->researchManage->step20 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step20->name = '填寫表單';

$lang->tutorial->orTutorial->marketManage->researchManage->step21 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step21->name = '保存表單';
$lang->tutorial->orTutorial->marketManage->researchManage->step21->desc = '保存後任務狀態更改為已完成';

$lang->tutorial->orTutorial->marketManage->researchManage->step22 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step22->name = '關閉任務';
$lang->tutorial->orTutorial->marketManage->researchManage->step22->desc = '點擊將完成的任務關閉';

$lang->tutorial->orTutorial->marketManage->researchManage->step23 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step23->name = '填寫表單';

$lang->tutorial->orTutorial->marketManage->researchManage->step24 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step24->name = '保存表單';
$lang->tutorial->orTutorial->marketManage->researchManage->step24->desc = '保存後任務狀態更改為已關閉';

$lang->tutorial->orTutorial->roadmapManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->title = '產品規劃管理教程';

$lang->tutorial->orTutorial->roadmapManage->lineManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->lineManage = clone $lang->tutorial->productManage->lineManage;

$lang->tutorial->orTutorial->roadmapManage->addProduct = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->addProduct = clone $lang->tutorial->productManage->addProduct;

$lang->tutorial->orTutorial->roadmapManage->moduleManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->moduleManage = clone $lang->tutorial->productManage->moduleManage;

$lang->tutorial->orTutorial->roadmapManage->storyManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->storyManage = clone $lang->tutorial->productManage->storyManage;

$lang->tutorial->orTutorial->roadmapManage->branchManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->branchManage = clone $lang->tutorial->productManage->branchManage;

$lang->tutorial->orTutorial->charterManage = new stdClass();
$lang->tutorial->orTutorial->charterManage->title = 'Charter立項教程';

$lang->tutorial->orTutorial->charterManage->step1 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step1->name = "點擊立項";
$lang->tutorial->orTutorial->charterManage->step1->desc = "您可以在這裡管理Charter立項";

$lang->tutorial->orTutorial->charterManage->step2 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step2->name = "點擊提交立項";
$lang->tutorial->orTutorial->charterManage->step2->desc = "點擊提交Charter立項的申請";

$lang->tutorial->orTutorial->charterManage->step3 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step3->name = "填寫表單";

$lang->tutorial->orTutorial->charterManage->step4 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step4->name = "保存表單";
$lang->tutorial->orTutorial->charterManage->step4->desc = "保存後在立項列表中跟進申請進度";

$lang->tutorial->orTutorial->charterManage->step5 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step5->name = "點擊評審結果";
$lang->tutorial->orTutorial->charterManage->step5->desc = "點擊評審立項申請";

$lang->tutorial->orTutorial->charterManage->step6 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step6->name = "填寫表單";

$lang->tutorial->orTutorial->charterManage->step7 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step7->name = "保存表單";
$lang->tutorial->orTutorial->charterManage->step7->desc = "保存後根據評審結果，立項狀態修改";

$lang->tutorial->orTutorial->charterManage->step8 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step8->name = "點擊關閉";
$lang->tutorial->orTutorial->charterManage->step8->desc = "Charter完成後點擊關閉按鈕進行關閉";

$lang->tutorial->orTutorial->charterManage->step9 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step9->name = "填寫表單";

$lang->tutorial->orTutorial->charterManage->step10 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step10->name = "保存表單";
$lang->tutorial->orTutorial->charterManage->step10->desc = "保存後，立項狀態變為已關閉";

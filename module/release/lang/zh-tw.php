<?php
/**
 * The release module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: zh-tw.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->release->create           = '創建發佈';
$lang->release->edit             = '編輯發佈';
$lang->release->linkStory        = "關聯{$lang->SRCommon}";
$lang->release->linkBug          = '關聯Bug';
$lang->release->delete           = '刪除發佈';
$lang->release->deleted          = '已刪除';
$lang->release->view             = '發佈詳情';
$lang->release->browse           = '發佈列表';
$lang->release->publish          = '發佈';
$lang->release->changeStatus     = '修改狀態';
$lang->release->batchUnlink      = '批量移除';
$lang->release->batchUnlinkStory = "批量移除{$lang->SRCommon}";
$lang->release->batchUnlinkBug   = '批量移除Bug';
$lang->release->manageSystem     = '管理' . $lang->product->system;
$lang->release->addSystem        = '新建' . $lang->product->system;
$lang->release->consumed         = '耗時';

$lang->release->confirmDelete      = '您確認刪除該發佈嗎？';
$lang->release->syncFromBuilds     = "將構建中完成的{$lang->SRCommon}和已解決的Bug關聯到發佈下";
$lang->release->confirmUnlinkStory = "您確認移除該{$lang->SRCommon}嗎？";
$lang->release->confirmUnlinkBug   = '您確認移除該Bug嗎？';
$lang->release->existBuild         = '『構建』已經有『%s』這條記錄了。您可以更改『發佈名稱』或者選擇一個『構建』。';
$lang->release->noRelease          = '暫時沒有發佈。';
$lang->release->errorDate          = '發佈日期不能大於今天。';
$lang->release->confirmActivate    = '您確認激活該發佈嗎？';
$lang->release->confirmTerminate   = '您確認停止維護該發佈嗎？';
$lang->release->confirmPublish     = '您確認發佈該發佈嗎？';

$lang->release->basicInfo = '基本信息';

$lang->release->id             = 'ID';
$lang->release->product        = "所屬{$lang->productCommon}";
$lang->release->branch         = '平台/分支';
$lang->release->project        = '所屬' . $lang->projectCommon;
$lang->release->build          = '構建';
$lang->release->includedBuild  = '包含構建';
$lang->release->includedSystem = '包含' . $lang->product->system;
$lang->release->releases       = $lang->release->includedSystem;
$lang->release->includedApp    = '被包含' . $lang->product->system;
$lang->release->relatedProject = '對應' . $lang->projectCommon;
$lang->release->system         = $lang->product->system;
$lang->release->selectSystem   = '選擇' . $lang->product->system;
$lang->release->name           = $lang->product->system . '版本號';
$lang->release->marker         = '里程碑';
$lang->release->date           = '計劃發佈日期';
$lang->release->releasedDate   = '實際發佈日期';
$lang->release->desc           = '描述';
$lang->release->files          = '附件';
$lang->release->status         = '發佈狀態';
$lang->release->subStatus      = '子狀態';
$lang->release->last           = '最新版本號';
$lang->release->unlinkStory    = "移除{$lang->SRCommon}";
$lang->release->unlinkBug      = '移除Bug';
$lang->release->stories        = "完成的{$lang->SRCommon}";
$lang->release->bugs           = '解決的Bug';
$lang->release->leftBugs       = '遺留的Bug';
$lang->release->generatedBugs  = '遺留的Bug';
$lang->release->createdBy      = '由誰創建';
$lang->release->createdDate    = '創建時間';
$lang->release->finishStories  = "本次共完成 %s 個{$lang->SRCommon}";
$lang->release->resolvedBugs   = '本次共解決 %s 個Bug';
$lang->release->createdBugs    = '本次共遺留 %s 個Bug';
$lang->release->export         = '導出HTML';
$lang->release->yesterday      = '昨日發佈';
$lang->release->all            = '所有';
$lang->release->allProject     = '所有項目';
$lang->release->notify         = '發送通知';
$lang->release->notifyUsers    = '通知人員';
$lang->release->mailto         = '抄送給';
$lang->release->mailContent    = '<p>尊敬的用戶，您好！</p><p style="margin-left: 30px;">您反饋的如下需求和Bug已經在 %s版本中發佈，請聯繫客戶經理查看最新版本。</p>';
$lang->release->storyList      = '<p style="margin-left: 30px;">需求列表：%s。</p>';
$lang->release->bugList        = '<p style="margin-left: 30px;">Bug列表：%s。</p>';
$lang->release->pageAllSummary = '本頁共 <strong>%s</strong> 個發佈，已發佈 <strong>%s</strong>，停止維護 <strong>%s</strong>。';
$lang->release->pageSummary    = "本頁共 <strong>%s</strong> 個發佈。";
$lang->release->fileName       = '檔案名';
$lang->release->exportRange    = '要導出的數據';

$lang->release->storyTitle = '需求名稱';
$lang->release->bugTitle   = 'Bug名稱';

$lang->release->filePath = '下載地址：';
$lang->release->scmPath  = '版本庫地址：';

$lang->release->exportTypeList['all']     = '所有';
$lang->release->exportTypeList['story']   = $lang->release->stories;
$lang->release->exportTypeList['bug']     = $lang->release->bugs;
$lang->release->exportTypeList['leftbug'] = $lang->release->leftBugs;

$lang->release->resultList['normal'] = '發佈成功';
$lang->release->resultList['fail']   = '發佈失敗';

$lang->release->statusList['wait']      = '未開始';
$lang->release->statusList['normal']    = '已發佈';
$lang->release->statusList['fail']      = '發佈失敗';
$lang->release->statusList['terminate'] = '停止維護';

$lang->release->changeStatusList['wait']      = '發佈';
$lang->release->changeStatusList['normal']    = '激活';
$lang->release->changeStatusList['terminate'] = '停止維護';
$lang->release->changeStatusList['publish']   = '發佈';
$lang->release->changeStatusList['active']    = '激活';
$lang->release->changeStatusList['pause']     = '停止維護';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, 由 <strong>$actor</strong> $extra。', 'extra' => 'changeStatusList');
$lang->release->action->notified     = array('main' => '$date, 由 <strong>$actor</strong> 發送通知。');
$lang->release->action->published    = array('main' => '$date, 由 <strong>$actor</strong> 發佈，結果為<strong>$extra</strong>。', 'extra' => 'resultList');

$lang->release->notifyList['FB'] = "反饋者";
$lang->release->notifyList['PO'] = "{$lang->productCommon}負責人";
$lang->release->notifyList['QD'] = '測試負責人';
$lang->release->notifyList['SC'] = '需求提交人';
$lang->release->notifyList['ET'] = "所在{$lang->execution->common}團隊成員";
$lang->release->notifyList['PT'] = "所在{$lang->projectCommon}團隊成員";
$lang->release->notifyList['CT'] = "抄送給";

$lang->release->featureBar['browse']['all']       = '全部';
$lang->release->featureBar['browse']['wait']      = $lang->release->statusList['wait'];
$lang->release->featureBar['browse']['normal']    = $lang->release->statusList['normal'];
$lang->release->featureBar['browse']['fail']      = $lang->release->statusList['fail'];
$lang->release->featureBar['browse']['terminate'] = $lang->release->statusList['terminate'];

$lang->release->markerList[1] = '是';
$lang->release->markerList[0] = '否';

$lang->release->failTips        = '部署/上線失敗';
$lang->release->versionErrorTip = "版本號只能包含大小寫英文字母、數字、減號（-）、點（.） 、下劃線（_）";

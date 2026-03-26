<?php
/**
 * The view method view file of marketresearch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Deqing Chai <chaideqing@easycorp.ltd>
 * @package     marketresearch
 * @version     $Id: view.html.php 4769 2023-09-14 09:24:21Z
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php if(!common::checkNotCN()):?>
<style> table.data-stats > tbody > tr.statsTr > td:first-child {width: 70px;}</style>
<?php endif;?>
<div id='mainContent' class="main-row">
  <div class="col-8 main-col">
    <div class="row">
      <div class="col-sm-6">
        <div class="panel block-dynamic" style="height: 280px">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->execution->latestDynamic;?></div>
          </div>
          <div class="panel-body scrollbar-hover">
            <ul class="timeline timeline-tag-left no-margin">
              <?php foreach($dynamics as $action):?>
              <li <?php if($action->major) echo "class='active'";?>>
                <div>
                  <span class="timeline-tag"><?php echo $action->date;?></span>
                  <span class="timeline-text"><?php echo zget($users, $action->actor) . ' ' . "<span class='label-action'>{$action->actionLabel}</span>" . $action->objectLabel . ' ' . html::a($action->objectLink, $action->objectName, '', "title='$action->objectName'");?></span>
                </div>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="panel block-team" style="height: 280px">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->execution->relatedMember;?></div>
            <?php if(common::hasPriv('marketresearch', 'team')):?>
            <nav class="panel-actions nav nav-default">
              <li><?php common::printLink('marketresearch', 'team', "researchID=$research->id", strtoupper($lang->more), '', "title=$lang->more");?></li>
            </nav>
            <?php endif;?>
          </div>
          <div class="panel-body">
            <div class="row row-grid">
              <?php $i = 9; $j = 0;?>
              <?php if($research->PM):?>
              <?php $i--;?>
              <?php unset($teamMembers[$research->PM]);?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $research->PM);?> <span class="text-muted">（<?php echo $lang->project->PM;?>）</span></div>
              <?php endif;?>
              <?php if($research->PO):?>
              <?php $i--;?>
              <?php unset($teamMembers[$research->PO]);?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $research->PO);?> <span class="text-muted">（<?php echo $lang->project->PO;?>）</span></div>
              <?php endif;?>
              <?php if($research->QD):?>
              <?php $i--;?>
              <?php unset($teamMembers[$research->QD]);?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $research->QD);?> <span class="text-muted">（<?php echo $lang->project->QD;?>）</span></div>
              <?php endif;?>
              <?php if($research->RD):?>
              <?php $i--;?>
              <?php unset($teamMembers[$research->RD]);?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $research->RD);?> <span class="text-muted">（<?php echo $lang->project->RD;?>）</span></div>
              <?php endif;?>

              <?php foreach($teamMembers as $teamMember):?>
              <?php if($j > $i) break;?>
              <div class="col-xs-6"><i class="icon icon-person icon-sm text-muted"></i> <?php echo zget($users, $teamMember->account);?></div>
              <?php $j++;?>
              <?php endforeach;?>
              <div class="col-xs-6">
                <?php common::printLink('marketresearch', 'manageMembers', "researchID=$research->id", "<i class='icon icon-plus hl-primary text-primary'></i> &nbsp;" . $lang->project->manageMembers, '', "class='text-muted'");?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php if(!empty($reports)): ?>
      <div class="col-sm-12">
        <div class="panel block-team" style="max-height: 430px; overflow-y: scroll;">
          <div class="panel-heading">
            <div class="panel-title"><?php echo $lang->marketresearch->common . $lang->marketreport->report;?></div>
          </div>
          <div class="panel-body">
            <?php $canView = common::hasPriv('marketreport', 'view');?>
            <div class="card-container cards cards-borderless">
              <?php foreach($reports as $report):?>
              <div class="item">
                <a href="<?php echo $canView ? $this->createLink('marketreport', 'view', "id=$report->id&fromMarket=$report->market") : 'javascript:void(0)';?>" title="<?php echo $report->name;?>">
                  <div class="card bg-primary">
                    <div class="con">
                    <h2 class="title"><?php echo $report->name;?></h2>
                    </div>
                  </div>
                </a>
                <div>
                  <span class="right-icon">
                    <?php common::printIcon('marketreport', 'edit', "id=$report->id", $report, 'list', 'pencil-alt', '', 'btn-link text-primary');?>
                    <?php common::printIcon('marketreport', 'delete', "id=$report->id", $report, 'list', 'trash', 'hiddenwin', 'btn-link text-primary');?>
                  <span>
                </div>
              </div>
              <?php endforeach;?>
            </div>
          </div>
        </div>
      </div>
      <?php endif;?>
      <?php if($isExtended):?>
      <div class="col-sm-12">
        <div class='panel'>
          <?php $this->printExtendFields($research, 'div', "position=left&inForm=0");?>
        </div>
      </div>
      <?php endif;?>
      <div class="col-sm-12">
        <?php $blockHistory = true;?>
        <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=marketresearch&objectID=$research->id");?>
        <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
      </div>
    </div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php $browseLink = $this->session->marketstageList ? $this->session->marketstageList : $this->inlink('stage', "researchID=$researchID");?>
        <?php common::printBack($browseLink);?>
        <?php echo $this->marketresearch->buildOperateMenu($research, 'view');?>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class="row">
      <div class="col-sm-12">
        <div class="cell">
          <div class="detail">
            <?php $hiddenCode = (!isset($config->setCode) or $config->setCode == 0) ? 'hidden' : '';?>
            <h2 class="detail-title"><span class="label-id"><?php echo $research->id;?></span> <span class="label label-light label-outline <?php echo $hiddenCode;?>"><?php echo $research->code;?></span> <?php echo $research->name;?></h2>
            <div class="detail-content article-content">
              <div><span class="text-limit hidden" data-limit-size="40"><?php echo $research->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></div>
              <p>
                <?php if($research->deleted):?>
                <span class='label label-danger label-outline'><?php echo $lang->project->deleted;?></span>
                <?php endif; ?>
                <span class="label label-primary label-outline"><?php echo zget($lang->execution->lifeTimeList, $research->lifetime, '');?></span>
                <?php if(isset($research->delay)):?>
                <span class="label label-danger label-outline"><?php echo $lang->project->delayed;?></span>
                <?php else:?>
                <span class="label status-<?php echo $research->status;?> label-outline"><?php echo $this->processStatus('project', $research);?></span>
                <?php endif;?>
              </p>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><strong><?php echo $lang->execution->lblStats;?></strong></div>
            <div class="detail-content">
              <table class='table table-data data-stats'>
                <tbody>
                  <tr class='statsTr'><td></td><td></td><td></td><td></td></tr>
                  <tr>
                    <td colspan="4">
                      <?php $progress = $research->progress;?>
                      <?php echo $lang->project->progress;?> <?php echo $progress . $lang->percent;?> &nbsp;
                      <div class="progress inline-block">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress . $lang->percent;?>"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->begin;?></th>
                    <td><?php echo $research->begin;?></td>
                    <th><?php echo $lang->project->realBeganAB;?></th>
                    <td><?php echo $research->realBegan == '0000-00-00' ? '' : $research->realBegan;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->project->end;?></th>
                    <td><?php echo $research->end = $research->end == LONG_TIME ? $this->lang->project->longTime : $research->end;;?></td>
                    <th><?php echo $lang->project->realEndAB;?></th>
                    <td><?php echo $research->realEnd == '0000-00-00' ? '' : $research->realEnd;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->execution->totalEstimate;?></th>
                    <td><?php echo (float)$workhour->totalEstimate . $lang->execution->workHour;?></td>
                    <th><?php echo $lang->execution->totalConsumed;?></th>
                    <td><?php echo (float)$workhour->totalConsumed . $lang->execution->workHour;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->execution->totalHours;?></th>
                    <td><?php echo (float)$workhour->totalHours . $lang->execution->workHour;?></td>
                    <th><?php echo $lang->execution->totalLeft;?></th>
                    <td><?php echo (float)$workhour->totalLeft . $lang->execution->workHour;?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->project->acl;?></strong></div>
            <div class="detail-content">
              <?php $aclList = $lang->marketresearch->aclList;?>
              <p><?php echo $aclList[$research->acl];?></p>
            </div>
          </div>
        </div>
        <?php $this->printExtendFields($research, 'div', "position=right&inForm=0&inCell=1");?>
      </div>
    </div>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

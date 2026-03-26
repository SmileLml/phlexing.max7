<?php
/**
 * The edit of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     opportunity
 * @version     $Id: edit.html.php 4903 2021-05-27 10:32:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $opportunity->id;?></span>
        <?php echo html::a($this->createLink('opportunity', 'view', "opportunityID=$opportunity->id" . "&from=$from"), $opportunity->name, '', 'class="opportunity-title"');?>
        <small><?php echo $lang->arrow . ' ' . $lang->opportunity->edit;?></small>
      </h2>
    </div>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->opportunity->name;?></div>
            <div class='form-group'>
              <div class="input-control has-icon-right">
                <?php echo html::input('name', $opportunity->name, 'class="form-control"');?>
              </div>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->opportunity->desc;?></div>
            <div class='form-group'>
              <?php echo html::textarea('desc', $opportunity->desc, "rows='5' class='form-control'");?>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->opportunity->prevention;?></div>
            <div class='form-group'>
              <?php echo html::textarea('prevention', $opportunity->prevention, "rows='5' class='form-control'");?>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->opportunity->resolution;?></div>
            <div class='form-group'>
              <?php echo html::textarea('resolution', $opportunity->resolution, "rows='5' class='form-control'");?>
            </div>
          </div>
          <div class='detail text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton($lang->goback, "data-app='{$app->tab}'");?>
          </div>
          <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->opportunity->legendBasicInfo;?></div>
              <table class="table table-form">
                <tr>
                  <th><?php echo $lang->opportunity->source;?></th>
                  <td><?php echo html::select('source', $lang->opportunity->sourceList, $opportunity->source, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->type;?></th>
                  <td><?php echo html::select('type', $lang->opportunity->typeList, $opportunity->type, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->strategy;?></th>
                  <td><?php echo html::select('strategy', $lang->opportunity->strategyList, $opportunity->strategy, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->status;?></th>
                  <td><?php echo html::select('status', $lang->opportunity->statusList, $opportunity->status, "class='form-control chosen'");?></td>
                </tr>
                <tr <?php if(empty($project->multiple)) echo 'class="hidden"';?>>
                  <th><?php echo $lang->opportunity->execution;?></th>
                  <td><?php echo html::select('execution', $executions, $opportunity->execution, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->impact;?></th>
                  <td><?php echo html::select('impact', $lang->opportunity->impactList, $opportunity->impact, "class='form-control'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->chance;?></th>
                  <td><?php echo html::select('chance', $lang->opportunity->chanceList, $opportunity->chance, "class='form-control'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->ratio;?></th>
                  <td><?php echo html::input('ratio', $opportunity->ratio, "class='form-control' readonly");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->pri;?></th>
                  <td id='priValue'><?php echo html::select('pri', $lang->opportunity->priList, $opportunity->pri, "class='form-control' disabled");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->identifiedDate;?></th>
                  <td><?php echo html::input('identifiedDate', helper::isZeroDate($opportunity->identifiedDate) ? '' : $opportunity->identifiedDate, "class='form-control form-date'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->plannedClosedDate;?></th>
                  <td><?php echo html::input('plannedClosedDate', helper::isZeroDate($opportunity->plannedClosedDate) ? '' : $opportunity->plannedClosedDate, "class='form-control form-date'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->actualClosedDate;?></th>
                  <td><?php echo html::input('actualClosedDate', helper::isZeroDate($opportunity->actualClosedDate) ? '' : $opportunity->actualClosedDate, "class='form-control form-date'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->assignedTo;?></th>
                  <td><?php echo html::select('assignedTo', $users, empty($opportunity->assignedTo) ? '' : $opportunity->assignedTo, "class='form-control chosen'");?></td>
                </tr>
              </table>
            </div>
            <div class='detail'>
              <div class='detail-title'><?php echo $lang->opportunity->legendLifeTime;?></div>
              <table class='table table-form'>
                <tr>
                  <th><?php echo $lang->opportunity->lastCheckedBy;?></th>
                  <td><?php echo html::select('lastCheckedBy', $users, empty($opportunity->lastCheckedBy) ? '' : $opportunity->lastCheckedBy, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->lastCheckedDate;?></th>
                  <td><?php echo html::input('lastCheckedDate', helper::isZeroDate($opportunity->lastCheckedDate) ? '' : $opportunity->lastCheckedDate, "class='form-control form-datetime'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->hangupedBy;?></th>
                  <td><?php echo html::select('hangupedBy', $users, empty($opportunity->hangupedBy) ? '' : $opportunity->hangupedBy, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->hangupedDate;?></th>
                  <td><?php echo html::input('hangupedDate', helper::isZeroDate($opportunity->hangupedDate) ? '' : $opportunity->hangupedDate, "class='form-control form-datetime'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->canceledBy;?></th>
                  <td><?php echo html::select('canceledBy', $users, empty($opportunity->canceledBy) ? '' : $opportunity->canceledBy, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th class='w-100px'><?php echo $lang->opportunity->cancelReason;?></th>
                  <td><?php echo html::select('cancelReason', $lang->opportunity->cancelReasonList, $opportunity->cancelReason, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->canceledDate;?></th>
                  <td><?php echo html::input('canceledDate', helper::isZeroDate($opportunity->canceledDate) ? '' : $opportunity->canceledDate, "class='form-control form-datetime'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->closedBy;?></th>
                  <td><?php echo html::select('closedBy', $users, empty($opportunity->closedBy) ? '' : $opportunity->closedBy, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->closedDate;?></th>
                  <td><?php echo html::input('closedDate', helper::isZeroDate($opportunity->closedDate) ? '' : $opportunity->closedDate, "class='form-control form-datetime'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->activatedBy;?></th>
                  <td><?php echo html::select('activatedBy', $users, empty($opportunity->activatedBy) ? '' : $opportunity->activatedBy, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->activatedDate;?></th>
                  <td><?php echo html::input('activatedDate', helper::isZeroDate($opportunity->activatedDate) ? '' : $opportunity->activatedDate, "class='form-control form-datetime'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->resolvedBy;?></th>
                  <td><?php echo html::select('resolvedBy', $users, empty($opportunity->resolvedBy) ? '' : $opportunity->resolvedBy, "class='form-control chosen'");?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

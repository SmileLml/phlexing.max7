<?php
/**
 * The create view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: create.html.php 5090 2013-07-10 05:49:24Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->task->edit;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->marketresearch->execution;?></th>
          <td><?php echo html::select('execution', $stages, $task->execution, "class='form-control chosen' onchange='loadAll(this.value)' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->assignedTo;?></th>
          <td>
            <div class="input-group" id="dataPlanGroup">
              <?php echo html::select('assignedTo', $members, $task->assignedTo, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <?php if($task->parent >= 0):?>
        <tr>
          <th><?php echo $lang->task->parent;?></th>
          <td><?php echo html::select('parent', $tasks, $task->parent, "class='form-control chosen'");?></td>
        </tr>
        <?php else:?>
        <?php echo html::hidden('parent', $task->parent);?>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->task->name;?></th>
          <td colspan='3'>
            <div class='keep-row-height'>
              <div class="input-group title-group">
                <div class="input-control has-icon-right">
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->task->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#name"  data-provide="colorpicker">
                  </div>
                  <?php echo html::input('name', $task->name, "class='form-control' required");?>
                </div>
                <span class="input-group-addon fix-border br-0 priBox"><?php echo $lang->task->pri;?></span>
                <?php
                $hasCustomPri = false;
                foreach($lang->task->priList as $priKey => $priValue)
                {
                    if(!empty($priKey) and (string)$priKey != (string)$priValue)
                    {
                        $hasCustomPri = true;
                        break;
                    }
                }
                $priList = $lang->task->priList;
                if(end($priList)) unset($priList[0]);
                if(!isset($priList[$task->pri]))
                {
                    reset($priList);
                    $task->pri = key($priList);
                }
                ?>
                <?php if($hasCustomPri):?>
                <?php echo html::select('pri', (array)$priList, $task->pri, "class='form-control'");?>
                <?php else: ?>
                <div class="input-group-btn pri-selector priBox" data-type="pri">
                  <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                    <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($task->pri) ? '0' : $task->pri?>" title="<?php echo $task->pri?>"><?php echo $task->pri?></span></span> &nbsp;<span class="caret"></span>
                  </button>
                  <div class='dropdown-menu pull-right'>
                    <?php echo html::select('pri', (array)$priList, $task->pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                  </div>
                </div>
                <?php endif; ?>
                <div class="table-col w-120px estimateBox">
                  <div class="input-group">
                    <span class="input-group-addon fix-border br-0"><?php echo $lang->task->estimateAB;?></span>
                    <input type="text" name="estimate" id="estimate" value="<?php echo $task->estimate;?>" class="form-control" autocomplete="off">
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->task->desc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=task&link=desc');?>
            <?php echo html::textarea('desc', htmlSpecialString($task->desc), "rows='10' class='form-control kindeditor'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='3'><?php echo $this->fetch('file', 'buildoldform');?></td>
        </tr>
        <tr class="datePlanBox">
          <th><?php echo $lang->task->datePlan;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::input('estStarted', $task->estStarted, "class='form-control form-date estStartedBox' placeholder='{$lang->task->estStarted}'");?>
              <span class="input-group-addon fix-border borderBox">~</span>
              <?php echo html::input('deadline', $task->deadline, "class='form-control form-date deadlineBox' placeholder='{$lang->task->deadline}'");?>
            </div>
          </td>
        </tr>
        <tr class="mailtoBox">
          <th><?php echo $lang->task->mailto;?></th>
          <td colspan='3'>
            <div class="input-group">
              <?php echo html::select('mailto[]', $users, str_replace(' ', '', $task->mailto), "class='form-control picker-select' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::hidden('type', 'research');?>
            <?php echo html::hidden('status', $task->status);?>
            <?php echo html::hidden('closedReason', $task->closedReason);?>
            <?php echo html::hidden('mode', $task->mode);?>
            <?php echo html::hidden('consumed', $task->consumed);?>
            <?php echo html::hidden('finishedBy', $task->finishedBy);?>
            <?php echo html::hidden('finishedDate', $task->finishedDate);?>
            <?php echo html::hidden('canceledBy', $task->canceledBy);?>
            <?php echo html::hidden('canceledDate', $task->canceledDate);?>
            <?php echo html::hidden('assignedTo', $task->assignedTo);?>
            <?php echo html::hidden('assignedDate', $task->assignedDate);?>
            <?php echo html::hidden('closedBy', $task->closedBy);?>
            <?php echo html::hidden('closedDate', $task->closedDate);?>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

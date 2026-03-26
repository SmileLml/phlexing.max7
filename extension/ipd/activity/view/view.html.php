<?php
/**
 * The view of activity module of ZenTaoQC.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     activity
 * @version     $Id: view.html.php 4903 2020-09-10 10:27:59Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('activity', 'browse', "model={$this->session->model}"), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $activity->id?></span>
      <span class="text" title="<?php echo $activity->name;?>"><?php echo $activity->name;?></span>
      <?php if($activity->deleted):?>
      <span class='label label-danger'><?php echo $lang->activity->deleted;?></span>
      <?php endif;?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-9">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->activity->content;?></div>
        <div class="detail-content article-content"><?php echo $activity->content;?></div>
      </div>
    </div>
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($this->session->activityList);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$activity->deleted):?>
        <?php
        common::printIcon('zoutput', 'batchCreate', '', $activity, 'button', 'treemap-alt', '', '', '', '', $lang->activity->output);
        common::printIcon('activity', 'outputList', "activityID=$activity->id", $activity, 'button', 'list-alt', '', 'iframe showinonlybody', 'yes', '', $lang->activity->outputList);
        echo "<div class='divider'></div>";
        common::printIcon('activity', 'edit', "activityID=$activity->id", $activity);
        common::printIcon('activity', 'delete', "activityID=$activity->id", $activity, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class='cell'>
      <div class="detail">
        <div class='detail-title'><?php echo $lang->activity->basicInfo;?></div>
        <div class='detail-content'>
          <table class='table table-data'>
            <tr>
              <th><?php echo $lang->activity->process;?></th>
              <td><?php echo zget($processes, $activity->process, '')?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->optional;?></th>
              <td><?php echo zget($lang->activity->optionalOptions, $activity->optional, '')?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->tailorNorm;?></th>
              <td title="<?php echo $activity->tailorNorm;?>"><?php echo $activity->tailorNorm;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->assignedTo;?></th>
              <td><?php echo zget($users, $activity->assignedTo);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->createdBy;?></th>
              <td><?php echo zget($users, $activity->createdBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->createdDate;?></th>
              <td><?php echo substr($activity->createdDate, 0, 11);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->editedBy;?></th>
              <td><?php echo zget($users, $activity->editedBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->editedDate;?></th>
              <td><?php echo substr($activity->editedDate, 0, 11);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->assignedBy;?></th>
              <td><?php echo $activity->assignedBy;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->activity->assignedDate;?></th>
              <td><?php echo substr($activity->assignedDate, 0, 11);?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

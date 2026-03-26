<?php
/**
 * The view step view file of deploy module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     deploy
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/chosen.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $step->id;?></span>
      <?php echo $step->title;?>
      <span class='label label-info'><?php echo substr($step->begin, 0, 16) . ' ~ ' . substr($step->end, 0, 16)?></span>

      <?php if($step->deleted):?>
      <span class='label label-danger label-deleted'><?php echo $lang->deploy->deleted;?></span>
      <?php endif; ?>
    </h2>
  </div>
  <div class='main-row'>
    <div class='main-col col-8'>
      <div class='cell'>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->deploy->content;?></div>
          <div class='detail-content article-content'><?php echo $step->content;?></div>
        </div>
        <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
      </div>
      <div class='main-actions'>
        <div class='btn-toolbar'>
          <?php
          $browseLink = inlink('steps', "deploy=$step->deploy");
          $params     = "stepID=$step->id";
          common::printBack($browseLink);

          echo "<div class='divider'></div>";
          common::printIcon('deploy', 'assignTo',     $params, $step, 'button', '', '', 'showinonlybody', true, 'data-toggle=""');
          if($step->status != 'done') common::printIcon('deploy', 'finishStep',   $params, $step, 'button', 'checked', '', 'showinonlybody', true, 'data-toggle=""');

          if(!isonlybody())
          {
              echo "<div class='divider'></div>";
              common::printIcon('deploy', 'editStep', $params, $step, 'button', 'edit');
              common::printIcon('deploy', 'delete', $params, $step, 'button', '', 'hiddenwin');
          }
          ?>
        </div>
      </div>
    </div>
    <div class='side-col col-4'>
      <div class='cell'>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->deploy->lblBasic;?></div>
          <table class='table table-data table-condensed table-borderless table-fixed'>
            <tr>
              <th class='w-60px'><?php echo $lang->deploy->lblBeginEnd;?></th>
              <td><?php echo substr($step->begin, 0, 16) . ' ~ ' . substr($step->end, 0, 16);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->deploy->assignedTo;?></th>
              <td><?php if($step->assignedTo) echo zget($users, $step->assignedTo) . $lang->at . $step->assignedDate;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->deploy->status;?></th>
              <td><?php echo zget($lang->deploy->statusList, $step->status);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->deploy->createdBy;?></th>
              <td><?php echo zget($users, $step->createdBy) . $lang->at . $step->createdDate;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->deploy->finishedBy;?></th>
              <td><?php if($step->finishedBy) echo zget($users, $step->finishedBy) . $lang->at . $step->finishedDate;?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div id='mainActions' class='main-actions'>
  <nav class='container'></nav>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

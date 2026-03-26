<?php
/**
 * The browse of trainplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou <hufangzhou@easycorp.ltd>
 * @package     trainplan
 * @version     $Id: browse.html.php 4903 2020-09-04 09:32:59Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php"?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toobar pull-left">
    <?php
    $menus = customModel::getFeatureMenu($this->moduleName, $this->methodName);
    foreach($menus as $menuItem)
    {
        $active = $menuItem->name == $browseType ? ' btn-active-text' : '';
        echo html::a($this->createLink('trainplan', 'browse', "projectID=$projectID&browseType=$menuItem->name"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->trainplan->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group dropdown'>
      <?php
      $createTrainPlanLink = $this->createLink('trainplan', 'create', "projectID=$projectID");
      $batchCreateLink     = $this->createLink('trainplan', 'batchCreate', "project=$projectID");

      $buttonLink  = '';
      $buttonTitle = '';
      $canModify   = common::canModify('project', $project);
      if($canModify && common::hasPriv('trainplan', 'batchCreate'))
      {
          $buttonLink  = $batchCreateLink;
          $buttonTitle = $lang->trainplan->batchCreate;
      }
      if($canModify && common::hasPriv('trainplan', 'create'))
      {
          $buttonLink  = $createTrainPlanLink;
          $buttonTitle = $lang->trainplan->create;
      }

      $hidden = empty($buttonLink) ? 'hidden' : '';
      echo html::a($buttonLink, "<i class='icon icon-plus'></i> $buttonTitle", '', "class='btn btn-primary $hidden'");
      ?>
      <?php if($canModify && common::hasPriv('trainplan', 'batchCreate') and common::hasPriv('trainplan', 'create')): ?>
      <button type='button' class="btn btn-primary dropdown-toggle" data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($createTrainPlanLink, $lang->trainplan->create);?></li>
        <li><?php echo html::a($batchCreateLink, $lang->trainplan->batchCreate);?></li>
      </ul>
      <?php endif;?>
    </div>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=trainplan');?>" data-module='trainplan'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($trainplans)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
      <?php if($canModify && common::hasPriv('trainplan', 'create')):?>
      <?php echo html::a($this->createLink('trainplan', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->trainplan->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form  class='main-table' id='trainplanForm' method='post' data-ride="table">
    <table class="table has-sort-head" id='trainplanList'>
      <?php
      $vars = "projectID=$projectID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      $canBatchEdit   = common::hasPriv('trainplan', 'batchEdit');
      $canBatchFinish = common::hasPriv('trainplan', 'batchFinish');

      $canBatchAction = $canBatchEdit or $canBatchFinish;
      ?>
      <thead>
        <tr>
          <th class='text-left w-80px'>
            <?php
            if($canBatchAction) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";
            common::printOrderLink('id', $orderBy, $vars, $lang->trainplan->id);
            ?>
          </th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->trainplan->name);?></th>
          <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->trainplan->begin);?></th>
          <th class='w-100px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->trainplan->end);?></th>
          <th class='w-150px'><?php common::printOrderLink('place', $orderBy, $vars, $lang->trainplan->place);?></th>
          <th class='w-150px'><?php common::printOrderLink('trainee', $orderBy, $vars, $lang->trainplan->trainee);?></th>
          <th class='w-80px'><?php common::printOrderLink('lecturer', $orderBy, $vars, $lang->trainplan->lecturer);?></th>
          <th class='w-120px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->trainplan->type);?></th>
          <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->trainplan->status);?></th>
          <th class='c-actions-3'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($trainplans as $trainplanID => $trainplan):?>
        <tr>
          <td>
            <?php $viewLink = $this->createLink('trainplan', 'view', "trainplanID=$trainplan->id");?>
            <?php
            if($canBatchAction)
            {
                echo html::checkbox('trainplanIDList', array($trainplan->id => '')) . html::a($viewLink, sprintf('%03d', $trainplan->id));
            }
            else
            {
                echo html::a($viewLink, sprintf('%03d', $trainplan->id));
            }
            ?>
          </td>
          <td class="c-name" title="<?php echo $trainplan->name;?>"><?php echo html::a($viewLink, $trainplan->name);?></td>
          <td><?php echo $trainplan->begin == '0000-00-00' ? '' : $trainplan->begin;?></td>
          <td><?php echo $trainplan->end   == '0000-00-00' ? '' : $trainplan->end;?></td>
          <td class="c-name" title="<?php echo $trainplan->place;?>"><?php echo $trainplan->place;?></td>
          <td class="c-name" title="<?php echo $trainees[$trainplanID];?>"><?php echo $trainees[$trainplanID];?></td>
          <td class="c-name" title="<?php echo $trainplan->lecturer;?>"><?php echo $trainplan->lecturer;?></td>
          <td><?php echo zget($lang->trainplan->typeList, $trainplan->type);?></td>
          <td class='status-trainplan status-<?php echo $trainplan->status;?>'><?php echo zget($lang->trainplan->statusList, $trainplan->status);?></td>
          <td class='c-actions'>
            <?php
            $params = "trainplanID=$trainplan->id";
            common::printIcon('trainplan', 'edit', $params, $trainplan, "list");
            common::printIcon('trainplan', 'finish', $params, $trainplan, 'list', '', '', 'iframe', true);
            common::printIcon('trainplan', 'summary', $params, $trainplan, 'list', '', '', 'iframe', true);
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($canBatchAction):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        $batchEditActionLink = $this->createLink('trainplan', 'batchEdit', "projectID=$projectID");
        $batchEditDisabled   = $canBatchEdit ? '' : "disabled='disabled'";

        $batchFinishActionLink = $this->createLink('trainplan', 'batchFinish', "projectID=$projectID");
        $batchFinishDisabled   = $canBatchFinish ? '' : "disabled='disabled'";

        echo html::commonButton($lang->edit, "data-form-action='$batchEditActionLink' $batchEditDisabled");
        echo html::a('#', $lang->trainplan->finish, '', "class='btn btn-primary' onclick=\"setFormAction('$batchFinishActionLink', 'hiddenwin', '#trainplanList')\" $batchFinishDisabled");
        ?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>

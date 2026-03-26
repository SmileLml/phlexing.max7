<?php
/**
 * The browse of gapanalysis module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou <hufangzhou@easycorp.ltd>
 * @package     gapanalysis
 * @version     $Id: browse.html.php 4903 2021-05-28 13:55:59Z hfz $
 * @link        https://www.zentao.net
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
        echo html::a($this->createLink('gapanalysis', 'browse', "projectID=$projectID&browseType=$menuItem->name"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->gapanalysis->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group dropdown'>
      <?php
      $createGapanalysisLink = $this->createLink('gapanalysis', 'create', "projectID=$projectID");
      $batchCreateLink       = $this->createLink('gapanalysis', 'batchCreate', "project=$projectID");

      $buttonLink  = '';
      $buttonTitle = '';
      $canModify   = common::canModify('project', $project);
      if($canModify && common::hasPriv('gapanalysis', 'batchCreate'))
      {
          $buttonLink  = $batchCreateLink;
          $buttonTitle = $lang->gapanalysis->batchCreate;
      }
      if($canModify && common::hasPriv('gapanalysis', 'create'))
      {
          $buttonLink  = $createGapanalysisLink;
          $buttonTitle = $lang->gapanalysis->create;
      }

      $hidden = empty($buttonLink) ? 'hidden' : '';
      echo html::a($buttonLink, "<i class='icon icon-plus'></i> $buttonTitle", '', "class='btn btn-primary $hidden'");
      ?>
      <?php if($canModify && common::hasPriv('gapanalysis', 'batchCreate') and common::hasPriv('gapanalysis', 'create')): ?>
      <button type='button' class="btn btn-primary dropdown-toggle" data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($createGapanalysisLink, $lang->gapanalysis->create);?></li>
        <li><?php echo html::a($batchCreateLink, $lang->gapanalysis->batchCreate);?></li>
      </ul>
      <?php endif;?>
    </div>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=gapanalysis');?>" data-module='gapanalysis'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($gapanalysises)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
      <?php if($canModify && common::hasPriv('gapanalysis', 'create')):?>
      <?php echo html::a($this->createLink('gapanalysis', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->gapanalysis->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form  class='main-table' id='gapanalysisForm' method='post' data-ride="table">
    <table class="table has-sort-head" id='gapanalysisList'>
      <?php
      $vars         = "projectID=$projectID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      $canBatchEdit = common::hasPriv('gapanalysis', 'batchEdit');
      ?>
      <thead>
        <tr>
          <th class='text-left w-120px'>
            <?php
            if($canBatchEdit) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";
            common::printOrderLink('id', $orderBy, $vars, $lang->gapanalysis->id);
            ?>
          </th>
          <th class='text-left'><?php common::printOrderLink('account', $orderBy, $vars, $lang->gapanalysis->account);?></th>
          <th class='c-role'><?php common::printOrderLink('role', $orderBy, $vars, $lang->gapanalysis->role);?></th>
          <th class='c-needTrain'><?php common::printOrderLink('needTrain', $orderBy, $vars, $lang->gapanalysis->needTrain);?></th>
          <th class='c-actions-1'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($gapanalysises as $gapanalysisID => $gapanalysis):?>
        <tr>
          <?php $viewLink = $this->createLink('gapanalysis', 'view', "gapanalysisID=$gapanalysis->id");?>
          <td>
          <?php
          if($canBatchEdit)
          {
            echo html::checkbox('gapanalysisIDList', array($gapanalysis->id => '')) . html::a($viewLink, sprintf('%03d', $gapanalysis->id));
          }
          else
          {
            echo html::a($viewLink, sprintf('%03d', $gapanalysis->id));
          }
          ?>
          </td>
          <td><?php echo html::a($viewLink, zget($users, $gapanalysis->account));?></td>
          <td class="c-name" title="<?php echo $gapanalysis->role?>"><?php echo $gapanalysis->role?></td>
          <td><?php echo zget($lang->gapanalysis->needTrainList, $gapanalysis->needTrain);?></td>
          <td class='c-actions'><?php common::printIcon('gapanalysis', 'edit', "gapanalysisID=$gapanalysis->id", $gapanalysis, "list");?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        $batchEditActionLink = $this->createLink('gapanalysis', 'batchEdit', "projectID=$projectID");
        $batchEditDisabled   = $canBatchEdit ? '' : "disabled='disabled'";

        echo html::commonButton($lang->edit, "data-form-action='$batchEditActionLink' $batchEditDisabled");
        ?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>

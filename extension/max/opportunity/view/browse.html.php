<?php
/**
 * The browse of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     opportunity
 * @version     $Id: browse.html.php 4903 2021-05-26 09:32:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php"?>
<?php $hasOpportunitylib = helper::hasFeature('opportunitylib');?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toobar pull-left">
    <?php
    $menus = customModel::getFeatureMenu($this->moduleName, $this->methodName);
    foreach($menus as $menuItem)
    {
        $active = $menuItem->name == $browseType ? ' btn-active-text' : '';
        echo html::a($this->createLink('opportunity', 'browse', "projectID=$projectID&from=$from&browseType=$menuItem->name"), "<span class='text'>{$menuItem->text}</span> " . ($browseType == $menuItem->name ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active' data-app='{$app->tab}'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->opportunity->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
      <ul class='dropdown-menu pull-right' id='exportActionMenu'>
        <?php
        $class = common::hasPriv('opportunity', 'export') ? "" : "class='disabled'";
        $misc  = common::hasPriv('opportunity', 'export') ? "class='export'" : "class='disabled'";
        $link  = common::hasPriv('opportunity', 'export') ? $this->createLink('opportunity', 'export', "objectID=$projectID&browseType=$browseType&orderBy=$orderBy") : '#';
        echo "<li $class>" . html::a($link, $lang->opportunity->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <div class='btn-group'>
      <button class="btn btn-link" data-toggle='dropdown' id='importAction'><i class='icon icon-import muted'></i> <?php echo $lang->import;?><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right' id='importActionMenu'>
      <?php
      $canChangeObject = common::canModify($from, $object);
      $class = ($canChangeObject && common::hasPriv('opportunity', 'importFromLib') && $hasOpportunitylib) ? '' : "class=disabled";
      $misc  = ($canChangeObject && common::hasPriv('opportunity', 'importFromLib') && $hasOpportunitylib) ? "data-app='{$app->tab}'" : "class=disabled";
      $link  = ($canChangeObject && common::hasPriv('opportunity', 'importFromLib') && $hasOpportunitylib) ? $this->createLink('opportunity', 'importFromLib', "projectID=$projectID&from=$from") : '#';
      echo "<li $class>" . html::a($link, $lang->opportunity->importFromLib, '', $misc) . "</li>";
      ?>
      </ul>
    </div>
    <?php if($canChangeObject && (common::hasPriv('opportunity', 'create') || common::hasPriv('opportunity', 'batchCreate'))):?>
    <div class='btn-group dropdown'>
      <?php
      if(common::hasPriv('opportunity', 'create'))
      {
          $actionLink = $this->createLink('opportunity', 'create', "projectID=$projectID&from=$from");
          echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->opportunity->create}", '', "class='btn btn-primary' data-app='{$app->tab}'");
      }
      elseif(common::hasPriv('opportunity', 'batchCreate'))
      {
          $actionLink = $this->createLink('opportunity', 'batchCreate', "projectID=$projectID&from=$from");
          echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->opportunity->batchCreate}", '', "class='btn btn-primary' data-app='{$app->tab}'");
      }
      ?>
      <?php if((common::hasPriv('opportunity', 'create')) and (common::hasPriv('opportunity', 'batchCreate'))):?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($actionLink, $lang->opportunity->create, '' ,"data-app=$app->tab");?></li>
        <li><?php echo html::a($this->createLink('opportunity', 'batchCreate', "projectID=$projectID&from=$from"), $lang->opportunity->batchCreate, '', "data-app='{$app->tab}'");?></li>
      </ul>
      <?php endif;?>
    </div>
    <?php endif;?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=opportunity');?>"data-module='opportunity'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($opportunities)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
      <?php if(common::hasPriv('opportunity', 'create')):?>
      <?php echo html::a($this->createLink('opportunity', 'create', "projectID=$projectID&from=$from"), "<i class='icon icon-plus'></i> " . $lang->opportunity->create, '', "class='btn btn-info' data-app='{$app->tab}'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form  class='main-table' id='opportunityForm' method='post' data-ride="table">
    <table class="table has-sort-head" id='opportunityList'>
      <?php
      $vars = "projectID=$projectID&from=$from&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      $canBatchEdit        = common::hasPriv('opportunity', 'batchEdit');
      $canBatchAssignTo    = common::hasPriv('opportunity', 'batchAssignTo');
      $canBatchClose       = common::hasPriv('opportunity', 'batchClose');
      $canBatchCancel      = common::hasPriv('opportunity', 'batchCancel');
      $canBatchHangup      = common::hasPriv('opportunity', 'batchHangup');
      $canBatchActivate    = common::hasPriv('opportunity', 'batchActivate');
      $canBatchImportToLib = (common::hasPriv('opportunity', 'batchImportToLib') and $hasOpportunitylib);
      $canBatchAction      = $canChangeObject && ($canBatchEdit or $canBatchAssignTo or $canBatchClose or $canBatchCancel or $canBatchHangup or $canBatchActivate or $canBatchImportToLib);
      ?>
      <thead>
        <tr>
          <th class='text-left w-80px'>
            <?php
            if($canBatchAction) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";
            common::printOrderLink('id', $orderBy, $vars, $lang->opportunity->id);
            ?>
          </th>
          <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->opportunity->name);?></th>
          <th class='c-pri'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
          <th class='c-ratio'><?php common::printOrderLink('ratio', $orderBy, $vars, $lang->opportunity->ratio);?></th>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->opportunity->status);?></th>
          <th class='c-type'><?php common::printOrderLink('type', $orderBy, $vars, $lang->opportunity->type);?></th>
          <th class='c-identifiedDate'><?php common::printOrderLink('identifiedDate', $orderBy, $vars, $lang->opportunity->identifiedDate);?></th>
          <th class='c-assignedTo'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->opportunity->assignedTo);?></th>
          <th class='c-strategy'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->opportunity->strategy);?></th>
          <th class='c-actions'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($opportunities as $opportunity):?>
        <tr>
          <td>
            <?php echo $canBatchAction ? html::checkbox('opportunityIDList', array($opportunity->id => '')) . sprintf('%03d',$opportunity->id) : sprintf('%03d',$opportunity->id);?>
          </td>
          <td class='c-name' title=<?php echo $opportunity->name;?>>
            <?php
            if(commonModel::hasPriv('opportunity', 'view'))
            {
                echo html::a($this->createLink('opportunity', 'view', "opportunityID=$opportunity->id&from=$from"), $opportunity->name, '', "data-app='{$app->tab}'");
            }
            else
            {
                echo $opportunity->name;
            }
            ?>
          </td>
          <td><?php echo "<span class='pri-{$opportunity->pri}'>" . zget($lang->opportunity->priList, $opportunity->pri) . "</span>";?></td>
          <td><?php echo $opportunity->ratio;?></td>
          <td><?php echo zget($lang->opportunity->statusList, $opportunity->status);?></td>
          <td><?php echo zget($lang->opportunity->typeList, $opportunity->type);?></td>
          <td><?php echo helper::isZeroDate($opportunity->identifiedDate) ? '' : $opportunity->identifiedDate;?></td>
          <td><?php echo $this->opportunity->printAssignedHtml($opportunity, $users);?></td>
          <td><?php echo zget($lang->opportunity->strategyList, $opportunity->strategy);?></td>
          <td class='c-actions'>
            <?php
            if($canChangeObject)
            {
                $params = "opportunityID=$opportunity->id";
                common::printIcon('opportunity', 'track', $params, $opportunity, "list", 'checked', '', 'iframe', true);
                common::printIcon('opportunity', 'close', $params, $opportunity, "list", '', '', 'iframe', true);
                common::printIcon('opportunity', 'cancel', $params, $opportunity, "list", '', '', 'iframe', true);
                common::printIcon('opportunity', 'hangup', $params, $opportunity, "list", 'arrow-up', '', 'iframe', true);
                common::printIcon('opportunity', 'activate', $params, $opportunity, "list", '', '', 'iframe', true);
                common::printIcon('opportunity', 'edit', $params . "&from=$from", $opportunity, "list");
            }
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
        <div class='btn-group dropup'>
          <?php
          $actionLink = $this->createLink('opportunity', 'batchEdit', "projectID=$projectID&from=$from");
          $disabled   = $canBatchEdit ? '' : "disabled='disabled'";

          echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");
          echo "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
          echo "<ul class='dropdown-menu'>";

          $class      = $canBatchClose ? '' : "class=disabled";
          $actionLink = $this->createLink('opportunity', 'batchClose');
          $misc       = $canBatchClose ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#opportunityList')\"" : '';
          echo "<li $class>" . html::a('#', $lang->close, '', $misc) . "</li>";

          if($canBatchCancel)
          {
              echo "<li class='dropdown-submenu'>";
              echo html::a('javascript:;', $lang->opportunity->cancel, '', "id='reasonItem'");
              echo "<ul class='dropdown-menu'>";
              foreach($lang->opportunity->cancelReasonList as $key => $cancelReason)
              {
                  if(empty($key)) continue;
                  $actionLink = $this->createLink('opportunity', 'batchCancel', "cancelReason=$key");
                  echo "<li>" . html::a('#', $cancelReason, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#opportunityList')\"") . "</li>";
              }
              echo '</ul></li>';
          }
          else
          {
              $class = "class='disabled'";
              echo "<li $class>" . html::a('javascript:;', $lang->opportunity->cancel, '', $class) . '</li>';
          }

          $class      = $canBatchHangup ? '' : "class=disabled";
          $actionLink = $this->createLink('opportunity', 'batchHangup');
          $misc       = $canBatchHangup ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#opportunityList')\"" : '';
          echo "<li $class>" . html::a('#', $lang->opportunity->hangup, '', $misc) . "</li>";

          $class      = $canBatchActivate ? '' : "class=disabled";
          $actionLink = $this->createLink('opportunity', 'batchActivate');
          $misc       = $canBatchActivate ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#opportunityList')\"" : '';
          echo "<li $class>" . html::a('#', $lang->opportunity->activate, '', $misc) . "</li>";

          echo "</ul>";
          ?>
        </div>
        <?php if($canBatchAssignTo):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->opportunity->assignedTo;?> <span class="caret"></span></button>
          <?php
          $withSearch = count($members) > 10;
          $actionLink = $this->createLink('opportunity', 'batchAssignTo', "projectID=$projectID");
          echo html::select('assignedTo', $members, '', 'class="hidden"');
          if($withSearch):
          ?>
          <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
            <div class="input-control search-box has-icon-left has-icon-right search-example">
              <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
              <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
              <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
            </div>
          <?php $membersPinYin = common::convert2Pinyin($members);?>
          <?php else:?>
          <div class="dropdown-menu search-list">
          <?php endif;?>
            <div class="list-group">
              <?php
              foreach($members as $key => $value)
              {
                  if(empty($key)) continue;
                  $searchKey = $withSearch ? ('data-key="' . zget($membersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
                  echo html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#opportunityList\")", $value, '', $searchKey);
              }
              ?>
            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if($canBatchImportToLib):?>
        <?php echo html::a('#batchImportToLib', $lang->opportunity->importToLib, '', 'class="btn" data-toggle="modal" id="importToLib"');?>
        <?php endif;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>

<div class="modal fade" id="batchImportToLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->opportunity->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('opportunity', 'batchImportToLib');?>'>
          <table class='table table-form'>
            <tr>
              <th class='lib-th'><?php echo $lang->opportunity->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveOpportunity') and !common::hasPriv('assetlib', 'batchApproveOpportunity')):?>
            <tr>
              <th><?php echo $lang->opportunity->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $approvers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::hidden('opportunityIDList', '');?>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>

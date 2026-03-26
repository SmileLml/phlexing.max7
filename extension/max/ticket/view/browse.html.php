<?php
/**
 * The admin view file of ticket module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     ticket
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php $this->loadModel('datatable');?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';?>
<?php
js::set('browseType', isset($browseType) ? $browseType : '');
js::set('sessionBrowseType', $this->session->ticketBrowseType);
js::set('sessionObjectID', $this->session->ticketObjectID);
js::set('productID', $productID);
?>
<div id='mainMenu' class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php
      echo $moduleName;
      if($moduleID != 'all' and $browseType != 'bysearch')
      {
          $removeLink = inlink('browse', "browseType=byProduct&param=all&orderBy=$orderBy&recTotal=0");
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'>
    <?php foreach($lang->ticket->featureBar['browse'] as $type => $name):?>
    <?php if($browseType == 'byProduct' or $browseType == 'byModule') $browseType = 'all';?>
    <?php $active = (isset($browseType) and $type == $browseType) ? "btn-active-text" : ''?>
    <?php echo html::a(inlink('browse', "browseType=$type"), "<span class='text'>{$name}</span>", '', "id='{$type}Tab' class='btn btn-link $active'")?>
    <?php endforeach?>
  </div>
  <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->ticket->search;?></a>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('ticket', 'create')) echo html::a($this->createLink('ticket', 'create', "productID=$productID"), "<i class='icon-plus'></i> {$lang->ticket->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='queryBox' data-module='ticket' class='cell <?php if($browseType == 'bysearch') echo 'show';?>' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=ticket');?>"></div>
<div id='mainContent' class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->feedback->noModule;?></div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php if($productID != 'all'):?>
        <?php $productID = $this->session->ticketProduct;?>
        <?php echo html::a($this->createLink('tree', 'browse', "productID=$productID&view=ticket"), $lang->feedback->manageCate, '', "data-app='feedback' class='btn btn-info btn-wide'");?>
        <?php endif;?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <?php
  $datatableID  = $this->moduleName . ucfirst($this->methodName);
  $useDatatable = (isset($config->datatable->$datatableID->mode) and $config->datatable->$datatableID->mode == 'datatable');
  ?>
  <?php if(empty($tickets)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->ticket->noTicket;?></span>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='ticketForm' method='post' <?php if(!$useDatatable) echo "data-ride='table'"?>>
    <div class="table-header fixed-right">
      <nav class="btn-toolbar pull-right setting"></nav>
    </div>
    <?php
    $vars = "browseType=$browseType&param=0&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
    if($useDatatable) include $app->getModuleRoot() . 'common/view/datatable.html.php';

    $setting = $this->datatable->getoldSetting('ticket');
    $widths  = $this->datatable->setFixedFieldWidth($setting);
    $columns = 0;

    $canBatchEdit     = common::hasPriv('ticket', 'batchEdit');
    $canBatchActivate = common::hasPriv('ticket', 'batchActivate');
    $canBatchFinish   = common::hasPriv('ticket', 'batchFinish');
    $canBatchAssignTo = common::hasPriv('ticket', 'batchAssignTo');
    $canBatchAction   = ($canBatchEdit or $canBatchActivate or $canBatchFinish or $canBatchAssignTo);
    ?>
    <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
    <table class='table has-sort-head <?php if($useDatatable) echo 'datatable';?>' id='ticketList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
      <thead>
        <tr>
        <?php
        foreach($setting as $key => $value)
        {
            if($value->show) $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
        }
        ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tickets as $ticket):?>
        <tr data-id='<?php echo $ticket->id?>'>
          <?php
          foreach($setting as $key => $value)
          {
              if($value->show) $this->ticket->printCell($value, $ticket, $users, $products);
          }
          ?>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if(!$useDatatable) echo '</div>';?>

    <div class='table-footer'>
      <?php if($canBatchAction and $this->config->vision != 'lite'):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
      <div class="table-actions btn-toolbar">
      <?php if($canBatchEdit):?>
      <?php
      $actionLink = $this->createLink('ticket', 'batchEdit');
      $misc       = "onclick=\"setFormAction('$actionLink')\"";
      echo html::commonButton($lang->edit, $misc);
      ?>
      <?php endif;?>

      <?php if($canBatchAssignTo):?>
      <div class="btn-group dropup">
        <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->ticket->assign;?> <span class="caret"></span></button>
        <?php $withSearch = count($users) > 10;?>
        <?php if($withSearch):?>
        <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
          <div class="input-control search-box has-icon-left has-icon-right search-example">
            <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input" />
            <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
            <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
          </div>
          <?php $usersPinYin = common::convert2Pinyin($users);?>
        <?php else:?>
        <div class="dropdown-menu search-list">
        <?php endif;?>
          <div class="list-group">
          <?php
          $actionLink = $this->createLink('ticket', 'batchAssignTo');
          echo html::select('assignedTo', $users, '', 'class="hidden"');
          foreach($users as $key => $value)
          {
              if(empty($key)) continue;
              $searchKey = $withSearch ? ('data-key="' . zget($usersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
              echo html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\",\"hiddenwin\")", $value, '', $searchKey);
          }
          ?>
          </div>
        </div>
      </div>
      <?php endif;?>

      <?php if($canBatchFinish):?>
      <?php
      $actionLink = $this->createLink('ticket', 'batchFinish');
      $misc       = "onclick=\"setFormAction('$actionLink')\"";
      echo html::commonButton($lang->ticket->finish, $misc);
      ?>
      <?php endif;?>

      <?php if($canBatchActivate):?>
      <?php
      $actionLink = $this->createLink('ticket', 'batchActivate');
      $misc       = "onclick=\"setFormAction('$actionLink')\"";
      echo html::commonButton($lang->ticket->activate, $misc);
      ?>
      <?php endif;?>

      </div>
    </div>
  </form>
<?php endif;?>
</div>
<script>
<?php if($useDatatable):?>
$(function(){$('#ticketForm').table();})
<?php endif;?>
$(function()
{
    if(browseType != 'bysearch' && sessionObjectID && productID)
    {
      if(sessionBrowseType == 'byProduct')
      {
          $('#product' + sessionObjectID).closest('li').addClass('active');
      }
      else if(sessionBrowseType == 'byModule')
      {
          $('#module' + sessionObjectID).closest('li').addClass('active');
      }
    }
});

if(browseType == 'byProduct' || browseType == 'byModule') browseType = 'all';
$("#" + browseType + 'Tab').find('.text').after(" <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

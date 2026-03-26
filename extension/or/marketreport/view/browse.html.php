<?php
/**
 * The browse view file of marketreport module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hu Fangzhou <hufangzhou@easycorp.ltd>
 * @package     marketreport
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->marketreport->featureBar['browse'] as $key => $label):?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php
    $active = '';
    if($browseType == $key)
    {
        $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        $active = 'btn-active-text';
    }
    ?>
    <?php $marketParam = $this->app->rawMethod == 'all' ? '' : "marketID=$marketID&";?>
    <?php echo html::a($this->createLink('marketreport', ($this->app->rawModule == 'marketresearch' && $this->app->rawMethod == 'reports') ? 'browse' : $this->app->rawMethod, $marketParam . "browseType=$key&orderBy=$orderBy"), $label, '', "class='btn btn-link $active' id='{$key}Tab'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('involvedReport', array('1' => $lang->marketreport->mine), '', $this->cookie->involvedReport ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('marketreport', 'create')) echo html::a($this->createLink('marketreport', 'create', "marketID=$marketID"), "<i class='icon-plus'></i> {$lang->marketreport->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <?php if(empty($reports)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php if(common::hasPriv('marketreport', 'create')):?>
        <?php echo html::a($this->createLink('marketreport', 'create', "marketID=$marketID"), "<i class='icon icon-plus'></i> " . $lang->marketreport->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='marketreportForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right setting"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars         = $marketParam . "browseType=$browseType&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";

      if($useDatatable) include $app->getModuleRoot() . 'common/view/datatable.html.php';
      $setting = $this->datatable->getOldSetting('marketreport');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head' id='marketreportList'>
        <thead>
          <tr>
            <?php
            foreach($setting as $key => $value)
            {
                if($value->show)
                {
                    $this->datatable->printHead($value, $orderBy, $vars, false);
                    $columns ++;
                }
            }
            ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($reports as $report):?>
          <tr data-id='<?php echo $report->id?>'>
            <?php
            foreach($setting as $value) $this->marketreport->printCell($value, $report, $users, $markets, $researches, $marketID);
            ?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
       <?php if(!$useDatatable) echo '</div>';?>
      <div class="table-footer">
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

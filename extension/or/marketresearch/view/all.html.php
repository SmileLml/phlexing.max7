<?php
/**
 * The all view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hu Fangzhou <hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->marketresearch->featureBar['all'] as $key => $label):?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php
    $active = '';
    if($browseType == $key)
    {
        $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        $active = 'btn-active-text';
    }
    ?>
    <?php echo html::a($this->createLink('marketresearch', $this->app->rawMethod, "marketID=$marketID&browseType=$key&param=0&orderBy=$orderBy"), $label, '', "class='btn btn-link $active' id='{$key}Tab'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('involvedResearch', array('1' => $lang->marketresearch->mine), '', $this->cookie->involvedResearch ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('marketresearch', 'create')) echo html::a($this->createLink('marketresearch', 'create', "marketID=$marketID"), "<i class='icon-plus'></i> {$lang->marketresearch->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <?php if(empty($researches)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php if(common::hasPriv('marketresearch', 'create')):?>
        <?php echo html::a($this->createLink('marketresearch', 'create', "marketID=$marketID"), "<i class='icon icon-plus'></i> " . $lang->marketresearch->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='marketresearchForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right setting"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars         = "marketID=$marketID&browseType=$browseType&param=0&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";

      if($useDatatable) include $app->getModuleRoot() . 'common/view/datatable.html.php';
      $setting = $this->datatable->getOldSetting('marketresearch');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head' id='marketresearchList'>
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
          <?php foreach($researches as $research):?>
          <tr data-id='<?php echo $research->id?>'>
            <?php
            foreach($setting as $value) $this->marketresearch->printCell($value, $research, $users, $markets);
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

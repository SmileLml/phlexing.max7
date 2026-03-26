<?php
/**
 * The design view file of chart of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $biPath = $this->app->getModuleExtPath('bi', 'view');?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js');?>
<?php js::import($jsRoot . 'echarts/echarts-liquidfill.min.js');?>
<?php js::set('chart', $chart);?>
<?php js::set('langSettings', $chart->langs)?>
<?php js::set('objectFields', isset($objectFields) ? $objectFields : array());?>
<?php js::set('saveLink', $saveLink);?>
<?php js::set('fields', $chart->fields);?>
<?php js::set('fieldSettings', zget($chart, 'fieldSettings', ''));?>
<?php js::set('checkForm', $this->config->chart->checkForm);?>
<?php js::set('chartSettings', $this->config->chart->settings);?>
<?php js::set('multiColumns', $this->config->chart->multiColumn);?>
<?php js::set('canLabelRotate', $this->config->chart->canLabelRotate);?>
<?php js::set('labelMaxLength', $this->config->chart->labelMaxLength);?>
<?php js::set('chartLang', $lang->chart);?>
<?php js::set('notemptyLang', $lang->error->notempty);?>
<?php js::set('resetSettingsLang', $lang->chart->resetSettings);?>
<?php js::set('clearSettingsLang', $lang->chart->clearSettings);?>
<?php js::set('nameEmpty', $lang->chart->nameEmpty);?>
<?php js::set('clentLang', $this->app->getClientLang());?>
<?php js::set('confirmPublish', $lang->chart->confirm->publish);?>
<?php js::set('confirmDraft',   $lang->chart->confirm->draft);?>
<?php js::set('WIDTH_INPUT',   $config->chart->widthInput);?>
<?php js::set('WIDTH_DATE',    $config->chart->widthDate);?>
<?php js::set('pickerHeight',  $config->bi->pickerHeight);?>
<?php $queryDom = "<div class='queryButton query-inside hidden'> <button type='submit' id='submit' onclick='queryFilter()' class='btn btn-secondary btn-query' data-loading='Loading...'>{$lang->chart->query}</button></div>";?>
<?php js::set('queryDom', $queryDom);?>


<script><?php include $this->app->getModuleRoot() . 'dataview/js/datastorage.js';?></script>
<script>
window.DataStorage = initStorage(
{
    fields: {},
    columns: {},
    rows: {},
    langs: langSettings ? langSettings : {},
    relatedObject: {},
    fieldSettings: Array.isArray(fieldSettings) || typeof fieldSettings != 'object' ? {} : fieldSettings,
    objectFields: objectFields,
    fieldList: [],
    step: 1,
    saving: false,
    chart: chart,
    driver: chart.driver,
    mode: chart.mode
}, false);
</script>

<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='page-title'>
      <?php echo html::a($backLink, '<i class="icon icon-angle-left"></i> <span class="text">' . $lang->goback . '</span>', '', "class='btn btn-link' title={$lang->goback}");?>
    </div>
    <div class='divider'></div>
    <div class='page-title ptitle'>
      <span title='<?php echo $title;?>'><?php echo $title;?></span>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <div class="draft pull-right">
      <?php echo '<button type="button" class="btn btn-link btn-draft" onclick="publish(\'draft\')"><i class="icon icon-save" style="margin-right: 5px;"></i>' . $lang->chart->draft . '</button>';?>
    </div>
  </div>
  <div id="chartNav">
    <nav id='designSteps' class='steps editor-steps-advanced'>
      <ul class='nav nav-primary'>
        <?php
        $step = zget($chart, 'step', 1);
        foreach($lang->chart->designStepNav as $index => $designNav)
        {
            $active = $step >= $index ? 'active' : '';
            $margin = $index != 1 ? 'step-margin' : '';
            echo "<li id='step$index' class='step-tab $active $margin' onclick='tabClick(this, $index)'><a>$designNav</a></li>";
        }
        ?>
      </ul>
    </nav>
  </div>
</div>

<?php foreach($lang->chart->designStepNav as $index => $designNav):?>
<div id="step<?php echo $index;?>Content" class="step-content" class='hide'>
<?php include "./block/step{$index}.html.php"?>
</div>
<?php endforeach;?>

<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

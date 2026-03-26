<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js');?>

<?php js::set('pivot', $pivot);?>
<?php js::set('saveLink', $saveLink);?>
<?php js::set('fields', $pivot->fields);?>
<?php js::set('fieldSettings', zget($pivot, 'fieldSettings', ''));?>
<?php js::set('langSettings', $pivot->langs)?>
<?php js::set('objectFields', isset($objectFields) ? $objectFields : array());?>
<?php js::set('checkForm', $this->config->pivot->checkForm);?>
<?php js::set('pivotSettings', $this->config->pivot->settings);?>
<?php js::set('multiColumns', $this->config->pivot->multiColumn);?>
<?php js::set('pivotLang', $lang->pivot);?>
<?php js::set('notemptyLang', $lang->error->notempty);?>
<?php js::set('moreThanOneLang', $lang->pivot->step2->moreThanOne);?>
<?php js::set('resetSettingsLang', $lang->pivot->resetSettings);?>
<?php js::set('clearSettingsLang', $lang->pivot->clearSettings);?>
<?php js::set('nameEmpty', $lang->pivot->nameEmpty);?>
<?php js::set('cannotAddQuery', $lang->pivot->cannotAddQuery);?>
<?php js::set('cannotAddResult', $lang->pivot->cannotAddResult);?>
<?php js::set('confirmPublish', $lang->pivot->confirm->publish);?>
<?php js::set('confirmDraft',   $lang->pivot->confirm->draft);?>
<?php js::set('WIDTH_INPUT',  $config->pivot->widthInput);?>
<?php js::set('WIDTH_DATE',   $config->pivot->widthDate);?>
<?php js::set('pickerHeight', $config->bi->pickerHeight);?>
<?php js::set('datepickerText', $this->lang->datepicker->dpText);?>
<?php js::set('from',   $from);?>
<?php js::set('params', $params);?>
<?php $queryDom = "<div class='queryButton query-inside hidden'> <button type='submit' id='submit' onclick='queryFilter()' class='btn btn-secondary btn-query' data-loading='Loading...'>{$lang->pivot->query}</button></div>";?>
<?php js::set('queryDom', $queryDom);?>

<script><?php include $this->app->getModuleRoot() . 'dataview/js/datastorage.js';?></script>
<script>
window.DataStorage = initStorage(
{
    fields: {},
    columns: {},
    rows: {},
    langs: langSettings ? JSON.parse(langSettings) : {},
    relatedObject: {},
    fieldSettings: Array.isArray(fieldSettings) || typeof fieldSettings != 'object' ? {} : fieldSettings,
    objectFields: objectFields,
    fieldList: [],
    step: 1,
    saving: false,
    pivot: JSON.parse(JSON.stringify(pivot)),
    driver: pivot.driver
}, false);


window.DataStorage.isQueryFilter = function()
{
    var filters = this._pivot.filters;
    return filters && filters.length != 0 && filters[0].from == 'query';
}

window.DataStorage.updateFilter = function (index, object)
{
    var pivot = this.clone('pivot');
    var filter = pivot.filters[index];
    for(var key in object)
    {
        filter[key] = object[key];
    }
    pivot.filters[index] = filter;
    this.pivot = pivot;
    return filter;
}

window.methods =
{
    step1: {query: {}, result: {}},
    step2: {},
    step3: {query: {}, result: {}},
    step4: {}
}

</script>

<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='page-title'>
      <?php echo html::a(inlink('browse'), '<i class="icon icon-angle-left"></i> <span class="text">' . $lang->goback . '</span>', '', "class='btn btn-link' title={$lang->goback}");?>
    </div>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='ptitle' title='<?php echo $title;?>'><?php echo $title;?></span>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <div class="draft pull-right">
      <?php echo '<button type="button" class="btn btn-link btn-draft ptitle" onclick="publish(\'draft\')"><i class="icon icon-save" style="margin-right: 5px;"></i>' . $lang->pivot->draft . '</button>';?>
    </div>
  </div>
  <div id="pivotNav">
    <nav id='designSteps' class='steps editor-steps-advanced'>
      <ul class='nav nav-primary'>
        <?php
        $lastStep = isset($pivot->settings['lastStep']) ? $pivot->settings['lastStep'] : 1;

        foreach($lang->pivot->designStepNav as $index => $designNav)
        {
            $active = $lastStep >= $index ? 'active' : '';
            $margin = $index != 1 ? 'step-margin' : '';
            echo "<li id='step$index' class='step-tab $active $margin' onclick='tabClick(this, $index)'><a>$designNav</a></li>";
        }
        ?>
      </ul>
    </nav>
  </div>
</div>

<?php foreach($lang->pivot->designStepNav as $index => $designNav):?>
<div id="step<?php echo $index;?>Content" class="step-content hide">
  <?php include "./block/step{$index}.html.php"?>
</div>
<?php endforeach;?>

<?php $exportMode = 'design';?>
<?php include './exportdata.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<div id='mainContent' class='main-content'>
  <div class="display-content">
    <div class="cell">
      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title' style="font-size:14px;">
            <?php echo $chart->name;?>
            <?php common::printLink('chart', 'export', "chartID=0_{$chart->id}", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link btn-export pull-right hidden' data-toggle='modal' data-width='600px'")?>
          </div>
        </div>
        <div class='panel-body' style="padding: 16px;">
          <div id='filterContent'>
            <div id="filterItems"></div>
            <div id='queryButton3' class='hidden queryButton'>
              <button type="button" onclick='queryFilter()' class="btn btn-secondary save-step"><?php echo $lang->chart->query;?></button>
            </div>
          </div>
          <div id="draw" data-group='0' data-id='<?php echo $chart->id;?>' class='echart-content'></div>
          <div id='draw-no-data' class="panel-position-child hidden">
            <p><span class="text-muted"><?php echo $lang->chart->noData;?></span></p>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="config-content" id="sidebar">
    <div class="cell cell-filter">
      <div class='panel panel-settings'>
        <div class='panel-heading border-bottom filter-heading'>
          <div class='panel-title ptitle'>
            <?php echo $lang->chart->filter;?>
          </div>
          <div class="create-action pull-right">
            <?php echo '<button type="button" class="btn btn-info add-filter"><i class="icon icon-plus"></i> ' . $lang->chart->resultFilter . '</button>';?>
          </div>
        </div>

        <div class='panel-heading auto-scroll'>
          <div class='panel-body'>
            <form method='post' id='filterForm' class="form-ajax"></form>
            <p id='filter-tip' class='panel-body-child'>
              <span class='text-muted'><?php echo $lang->chart->noFilterTip;?></span>
              <?php echo '<button type="button" class="btn btn-info add-filter"><i class="icon icon-plus"></i> ' . $lang->chart->resultFilter . '</button>';?>
            </p>
          </div>
        </div>
      </div>
      <div class='panel pull-bottom'>
        <div class='panel-heading'>
          <div class='panel-footer'>
            <?php echo '<button type="button" class="btn btn-primary btn-next-step" onclick="nextStep()">' . $lang->chart->nextStep . '</button>';?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<template id="filterTpl">
  <div class='filter filter-{index}'>
    <div class="filter-header">
      <div class='filter-heading'>
        <div class='filter-title'>
          <span><?php echo $lang->chart->resultFilter . $lang->hyphen;?>{fieldName}</span>
          <div class="close-action pull-right">
            <button type="button" class="remove-filter close" onclick="removeFilter({index})"><i class='icon icon-minus'></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="filter-body">
      <form class="not-watch">
        <table class="table table-form">
          <tbody>
            <tr>
              <th class='w-60px'><?php echo $lang->chart->type;?></th>
              <td>{type}</td>
            </tr>
            <tr>
              <th><?php echo $lang->chart->field;?></th>
              <td>{fieldSelect}</td>
            </tr>
            <tr class='saveAs {saveAsClass}'>
              <th class='filter-width filter-thpadding'><?php echo $lang->chart->showAs;?></th>
              <td class='filter-tdpadding'>{saveAs}</td>
            </tr>
            <tr class='default-line'>
              <th><?php echo $lang->chart->default;?></th>
              <td>{defaultControl}</td>
            </tr>
            <tr>
              <th><?php echo $lang->chart->filterName;?></th>
              <td class='required'>
                <?php echo html::input('name', '{filterName}', "class='form-control' onchange='changeName(this, this.value)'");?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</template>

<template id='filterItemTpl'>
  <div class='filter-item filter-item-{index} input-group'>
    <span class='field-name input-group-addon'>{name}</span>
    {search}
  </div>
</template>

<script>
function queryFilter()
{
    setSearchFilters();
    refreshChart();

    /* Chart searchFilters use only once. */
    var chart = DataStorage.clone('chart');
    delete chart.searchFilters;
    DataStorage.chart = chart;
}

function setSearchFilters()
{
    var chart = DataStorage.clone('chart');
    chart.searchFilters = JSON.parse(JSON.stringify(chart.filters));

    $('#step' + DataStorage.step + 'Content #filterItems').children('.filter-item').each(function(index)
    {
        var $child = $(this);
        var filter = chart.searchFilters[index];
        var type   = filter.type;

        if(type == 'input' || type == 'select')
        {
            var value = $child.find('#default').val();
            chart.searchFilters[index].default = value;
        }
        else if(type == 'date' || type == 'datetime')
        {
            var begin = $child.find('input').first().val();
            var end   = $child.find('input').last().val();

            var value = {"begin":begin, "end":end};
            chart.searchFilters[index].default = value;
        }
        else if(type == 'condition')
        {
            var operator = $child.find('#operator').data('zui.picker').getValue();
            var value    = $child.find('#value').val();

            chart.searchFilters[index].operator = operator;
            chart.searchFilters[index].value    = value;
        }
    });
    DataStorage.chart = chart;
}
</script>
<script><?php include '../../js/filter.js';?></script>

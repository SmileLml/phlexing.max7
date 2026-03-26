<div id='mainContent' class='main-content'>
  <div class="display-content">
    <div class="cell">
      <div class='panel panel-position'>
        <div class='panel-heading'>
          <div class='panel-title ptitle'>
            <?php echo $chart->name;?>
            <?php common::printLink('chart', 'export', "chartID=0_{$chart->id}", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link btn-export pull-right hidden' data-toggle='modal' data-width='600px'")?>
          </div>
        </div>
        <div class='panel-body' style="padding: 16px;">
          <div id='filterContent'>
            <div id="filterItems"></div>
            <div id='queryButton2' class='hidden queryButton'>
              <button type="button" onclick='queryFilter()' class="btn btn-secondary save-step"><?php echo $lang->chart->query;?></button>
            </div>
          </div>
          <div id="draw" data-group='0' data-id='<?php echo $chart->id;?>' class='echart-content'></div>
          <div id='draw-tip' class="panel-position-child">
            <p><span class="text-muted"><?php echo $lang->chart->noChartTip;?></span></p>
          </div>
          <div id='draw-no-data' class="panel-position-child hidden">
            <p><span class="text-muted"><?php echo $lang->chart->noData;?></span></p>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="config-content" id="sidebar">
    <div class="cell">
      <div class='panel panel-settings'>
        <div class='panel-heading border-bottom'>
          <div class='panel-title ptitle'>
            <?php echo $lang->chart->baseSetting;?>
          </div>
        </div>

        <div class="panel-content auto-scroll">
          <div class='panel-heading border-bottom'>
            <div class='panel-title'>
              <?php echo $lang->chart->common . $lang->chart->type;?>
            </div>
            <div class='panel-body'>
              <?php echo html::select('type', $lang->chart->typeList, $chart->type, "class='form-control' onchange='loadConfigForm(this.value)' required");?>
            </div>
          </div>

          <div class='panel-heading'>
            <div class='panel-title'>
              <?php echo $lang->chart->config;?>
            </div>
            <div class='panel-body' id="chartConfig">
              <form method="post" class='chart-config form-ajax' id="chartForm"></form>
            </div>
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

<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>

<template id='filterItemTpl'>
  <div class='filter-item filter-item-{index} input-group'>
    <span class='field-name input-group-addon'>{name}</span>
    {search}
  </div>
</template>

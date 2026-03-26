<div id='mainContent' class='main-content'>
  <div class="display-content">
    <div class="cell">
      <div class='panel panel-position panel-padding'>
        <div class='panel-heading'>
          <div class='panel-title ptitle'>
            <?php echo $pivot->name;?>
            <?php if(common::hasPriv('pivot', 'export')):?>
              <a href="#" class="btn btn-link design-export pull-right hidden" data-toggle="modal" data-target="#export"><?php echo '<i class="icon-export muted"> </i>' . $lang->export;?></a>
            <?php endif;?>
          </div>
        </div>
        <div class="panel-body">
          <div id="datagridSpanExample3" class="datagrid">
            <div class='panel-body clear-padding clear-overflow'>
              <div id='filterContent' class='filterContent'>
                <div id="filterItems"></div>
                <div id='queryButton3' class='queryButton'>
                  <button type="button" onclick='queryFilter()' class="btn btn-secondary save-step"><?php echo $lang->pivot->query;?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="config-content" id="sidebar">
    <div class="cell">
      <div class='panel panel-settings'>
        <div class='border-bottom filter-padding'>
          <div class='panel-title'>
            <?php echo $lang->pivot->filter;?>
          </div>
          <div class="create-action pull-right filter-add">
            <?php echo '<button type="button" class="btn btn-info add-filter" onclick="addFilter()"><i class="icon icon-plus"></i> ' . $lang->pivot->resultFilter . '</button>';?>
          </div>
        </div>
        <div class='panel-heading auto-scroll'>
          <div class='panel-body clear-padding'>
            <form method='post' id='filterForm' class="form-ajax"></form>
            <p id='query-tip' class='panel-body-child'>
              <span class='text-muted'><?php echo $lang->pivot->noQueryTip;?></span>
              <?php echo '<button type="button" class="btn btn-info add-filter" onclick="addFilter()"><i class="icon icon-plus"></i> ' . $lang->pivot->resultFilter . '</button>';?>
            </p>
          </div>
        </div>
      </div>
      <div class='panel pull-bottom'>
        <div class='panel-heading'>
          <div class='panel-footer'>
            <?php echo '<button type="button" class="btn btn-secondary btn-save-setting" onclick="queryFilter()">' . $lang->pivot->saveSetting . '</button>';?>
            <?php echo '<button type="button" class="btn btn-primary btn-next-step" onclick="nextStep()">' . $lang->pivot->nextStep . '</button>';?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<template id="queryFilterBlockTpl">
  <div class='filter filter-{index}' data-index='{index}'>
    <div class="filter-header">
      <div class='filter-heading'>
        <div class='filter-title'>
          <span><?php echo $lang->pivot->queryFilter . $lang->hyphen;?><span class='filter-name-span'>{filterName}</span></span>
        </div>
      </div>
    </div>
    <div class="filter-body">
      <form class="not-watch">
        <table class="table table-form table-margin">
          <tbody>
            <tr>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->varCode;?></th>
              <td class='filter-tdpadding'><?php echo html::input('field', '{field}', "class='form-control' onchange='methods.step3.query.changeVar(this, this.value)'");?></td>
            </tr>
            <tr>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->type;?></th>
              <td class='filter-tdpadding'><?php echo html::select('type', $lang->dataview->varFilter->requestTypeList, '{type}', "class='form-control picker-select' onchange='methods.step3.query.changeType(this, this.value)'");?></td>
            </tr>
            <tr>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->varLabel;?></th>
              <td class='required filter-tdpadding'>
                <?php echo html::input('name', '{filterName}', "class='form-control' onchange='changeName(this, this.value)'");?>
              </td>
            </tr>
            <tr class='default-line'>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->default;?></th>
              <td class='filter-tdpadding'>
                <span class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-input' onchange='methods.step3.query.changeDefault(this, this.value)'")?></span>
                <span class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-date' onchange='methods.step3.query.changeDefault(this, this.value)'")?></span>
                <span class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-datetime' onchange='methods.step3.query.changeDefault(this, this.value)'")?></span>
                <span class="default-block hidden"><?php echo html::select('default', '', '', "class='form-control form-select' onchange='methods.step3.query.changeDefault(this, this.value)'");?></span>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</template>

<template id="resultFilterBlockTpl">
  <div class='filter filter-{index}'>
    <div class="filter-header">
      <div class='filter-heading'>
        <div class='filter-title'>
          <span><?php echo $lang->pivot->resultFilter . $lang->hyphen;?><span class="filter-name-span">{filterName}</span></span>
          <div class="close-action pull-right">
            <button type="button" class="remove-filter close" onclick="removeFilter({index})"><i class='icon icon-minus'></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="filter-body">
      <form class="not-watch">
        <table class="table table-form table-margin">
          <tbody>
            <tr>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->type;?></th>
              <td class='filter-tdpadding'>{type}</td>
            </tr>
            <tr>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->field;?></th>
              <td class='filter-tdpadding'>{fieldSelect}</td>
            </tr>
            <tr class='saveAs {saveAsClass}'>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->showAs;?></th>
              <td class='filter-tdpadding'>{saveAs}</td>
            </tr>
            <tr class='default-line'>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->default;?></th>
              <td class='filter-tdpadding'>{defaultControl}</td>
            </tr>
            <tr>
              <th class='filter-width filter-thpadding'><?php echo $lang->pivot->filterName;?></th>
              <td class='required filter-tdpadding'>
                <?php echo html::input('name', '{filterName}', "class='form-control' onchange='changeName(this, this.value)'");?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</template>

<template id='queryFilterItemTpl'>
  <div class='filter-item filter-item-{index} input-group' data-index='{index}'>
    <span class='field-name input-group-addon'>{name}</span>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-input '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-date '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-datetime '")?></div>
    <div class="default-block hidden"><?php echo html::select('default', '', '', "class='form-control form-select multiple'");?></div>
  </div>
</template>

<template id='resultFilterItemTpl'>
  <div class='filter-item filter-item-{index} input-group' data-index='{index}'>
    <span class='field-name input-group-addon'>{name}</span>
    {search}
  </div>
</template>
<script>
function queryFilter()
{
    setSearchFilters();
    apply();
}

function setSearchFilters()
{
    var pivot = DataStorage.clone('pivot');
    pivot.searchFilters = JSON.parse(JSON.stringify(pivot.filters));

    if(DataStorage.isQueryFilter())
    {
        pivot.filters.forEach(function(filter, index)
        {
            var find = $('#step' + DataStorage.step + 'Content #filterItems').find('.filter-item-' + index + ' .form-' + filter.type);
            if(find.length != 0) pivot.searchFilters[index].default = find.val();
        });
    }
    else
    {
       pivot.filters.forEach(function(filter, index)
        {
            var filterItem = $('#step' + DataStorage.step + 'Content #filterItems').find('.filter-item-' + index);
            var type = filter.type;
            if(type == 'input')
            {
                var find = filterItem.find(' .form-' + type);
                if(find.length != 0) pivot.searchFilters[index].default = find.val();
            }
            else if(type == 'select')
            {
                var find = filterItem.find(' .form-' + type);
                if(find.length != 0) pivot.searchFilters[index].default = find.data('zui.picker').getValue();
            }
            else if(type == 'date' || type == 'datetime')
            {
                var begin = filterItem.find('.form-' + type + '.default-begin');
                var end   = filterItem.find('.form-' + type + '.default-end');

                pivot.searchFilters[index].default =
                {
                    "begin": begin.length != 0 ? begin.val() : filter.default.begin,
                    "end": end.length != 0 ? end.val() : filter.default.end
                };
            }

        });
    }

    DataStorage.pivot = pivot;
}
</script>
<script><?php include('../../js/filter.js');?></script>

<?php js::set('filterID', count($filters) + 1)?>
<div id='mainContent' class='main-row'>
  <div class="main-col">
    <div class="cell">
      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title'>
            <?php echo $chart->name;?>
          </div>
        </div>
        <div class='panel-body'>
          <div id="filterItems">
            <?php
            foreach($filters as $index => $filter)
            {
                echo "<div class='filter-item filter-item-$index'><div class='field-name'>{$filter['name']}</div><div class='field-search'>";
                if($filter['type'] == 'input') echo html::input('default', $filter['default'], "class='form-control'");
                if($filter['type'] == 'date' or $filter['type'] == 'datetime')
                {
                    $class = $filter['type'] == 'date' ? 'form-date' : 'form-datetime';
                    echo '<div class="input-group">';
                    echo "<input type='text' name='default[begin]' id='default[begin]' value='{$filter['default']['begin']}' class='form-control $class default-begin' autocomplete='off' placeholder='{$lang->chart->unlimited}' onchange='checkDate(this, this.value)'>";
                    echo '<span class="input-group-addon fix-border borderBox" style="border-radius: 0px;">' . $lang->chart->colon . '</span>';
                    echo "<input type='text' name='default[end]' id='default[end]' value='{$filter['default']['end']}' class='form-control $class default-end' autocomplete='off' placeholder='{$lang->chart->unlimited}' onchange='checkDate(this, this.value)'>";
                    echo '</div>';
                }
                if($filter['type'] == 'select') echo html::select('default', array('' => '') + $options[$filter['typeOption']], isset($filter['default']) ? $filter['default'] : '', "class='form-control chosen' multiple");
                echo "</div></div>";
            }
            ?>
          </div>
          <div id="draw"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="side-col col-lg" id="sidebar">
    <div class="cell cell-filter">

      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title'>
            <?php echo $lang->chart->filter;?>
          </div>
            <div class="create-action pull-right">
              <?php echo '<button type="button" class="btn btn-info add-filter"><i class="icon icon-plus"></i> ' . $lang->chart->add . '</button>';?>
            </div>
        </div>
        <div class='panel-body'>
          <form method='post' id='filterForm' class="form-ajax"></form>
          <?php foreach(array_reverse($filters, true) as $index => $filter):?>
          <form class="not-watch">
            <div class='filter filter-<?php echo $index?>'>
              <div class="filter-header">
                <div class='filter-heading'>
                  <div class='filter-title'>
                    <?php echo $lang->chart->filter . $lang->hyphen . $filter['name'];?>
                    <div class="close-action pull-right">
                      <button type="button" class="remove-filter close"><i class='icon icon-minus'></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="filter-body">
                <table class="table table-form">
                  <tbody>
                    <tr>
                      <th class='w-70px'><?php echo $lang->chart->field;?></th>
                      <td><?php echo html::select('field', $fieldList, $filter['field'], "class='form-control chosen' onchange='initFilterForm(this, this.value)'");?></td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->chart->group;?></th>
                      <td>
                        <div class='input-group'>
                          <?php echo html::select('type', $lang->chart->fieldTypeList, $filter['type'], "class='form-control chosen' onchange='changeType(this, this.value)'");?>
                          <div class="typeOptionItem<?php if($filter['type'] != 'select') echo  ' hidden';?>">
                            <?php echo html::select('typeOption', $lang->chart->typeOptions, isset($filter['typeOption']) ? $filter['typeOption'] : '', "class='chosen form-control' onchange='setDefaultOptions(this, this.value)'");?>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th><?php echo $lang->chart->filterName;?></th>
                      <td>
                        <?php echo html::input('name', $filter['name'], "class='form-control' onchange='changeName(this, this.value)'");?>
                      </td>
                    </tr>
                    <tr class='default-line'>
                      <th><?php echo $lang->chart->default;?></th>
                      <td>
                        <?php
                        if($filter['type'] == 'input') echo html::input('default',  $filter['default'], "class='form-control' onchange='changeDefault(this,this.value)'");
                        if($filter['type'] == 'date' or $filter['type'] == 'datetime')
                        {
                            $class = $filter['type'] == 'date' ? 'form-date' : 'form-datetime';
                            echo '<div class="input-group">' . html::input('default[begin]', $filter['default']['begin'], "class='form-control $class default-begin' placeholder='{$lang->chart->unlimited}' onchange='changeDefault(this,this.value)'");
                            echo '<span class="input-group-addon fix-border borderBox" style="border-radius: 0px;">' . $lang->chart->colon . '</span>';
                            echo html::input('default[end]', $filter['default']['end'], "class='form-control $class default-end' placeholder='{$lang->chart->unlimited}' onchange='changeDefault(this,this.value)'") . '</div>';
                        }
                        if($filter['type'] == 'select') echo html::select('default[]', $options[$filter['typeOption']], isset($filter['default']) ? $filter['default'] : '', "class='form-control chosen' onchange='changeDefault(this,this.value)' multiple");
                        ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </form>
          <?php endforeach;?>
        </div>
        <div class='panel-footer'>
          <?php echo '<button type="button" class="btn btn-secondary" id="saveFilter">' . $lang->save . '</button>';?>
          <?php echo '<button type="button" class="btn btn-primary" id="toSave">' . $lang->chart->nextButton[$step] . '</button>';?>
        </div>
      </div>

    </div>
  </div>
</div>

<template id="filterTpl">
  <div class='filter filter-new'>
    <div class="filter-header">
      <div class='filter-heading'>
        <div class='filter-title'>
          <?php echo $lang->chart->filter . $lang->hyphen . $fieldSettings->{$fields[0]}->name;?>
          <div class="close-action pull-right">
            <button type="button" class="remove-filter close"><i class='icon icon-minus'></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="filter-body">
      <form class="not-watch">
        <table class="table table-form">
          <tbody>
            <tr>
              <th class='w-70px'><?php echo $lang->chart->field;?></th>
              <td><?php echo html::select('field', $fieldList, '', "class='form-control chosen' onchange='initFilterForm(this, this.value)'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->chart->group;?></th>
              <td>
                <div class='input-group'>
                  <?php echo html::select('type', $lang->chart->fieldTypeList, '', "class='form-control chosen' onchange='changeType(this, this.value)'");?>
                  <div class="typeOptionItem hidden">
                    <?php echo html::select('typeOption', $lang->chart->typeOptions, '', "class='chosen form-control hidden' onchange='setDefaultOptions(this, this.value)'");?>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->chart->filterName;?></th>
              <td>
                <?php echo html::input('name', $fieldSettings->{$fields[0]}->name, "class='form-control' onchange='changeName(this, this.value)'");?>
              </td>
            </tr>
            <tr class='default-line'>
              <th><?php echo $lang->chart->default;?></th>
              <td>
                <?php echo html::input('default', '', "class='form-control' onchange='changeDefault(this,this.value)'");?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</template>

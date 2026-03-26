<?php js::set('filters', $filters);?>
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
                if($filter['type'] == 'input') echo html::input($filter['field'], $filter['default'], "class='form-control' onchange='filterChange(\"{$filter['field']}\")'");
                if($filter['type'] == 'date' or $filter['type'] == 'datetime')
                {
                    $class = $filter['type'] == 'date' ? 'form-date' : 'form-datetime';
                    echo '<div class="input-group">';
                    echo "<input type='text' name='{$filter['field']}[begin]' id='{$filter['field']}[begin]' value='{$filter['default']['begin']}' class='form-control $class default-begin' autocomplete='off' onchange='filterChange(\"{$filter['field']}.begin\", this, this.value)' placeholder='{$lang->chart->unlimited}'>";
                    echo '<span class="input-group-addon fix-border borderBox" style="border-radius: 0px;">' . $lang->chart->colon . '</span>';
                    echo "<input type='text' name='{$filter['field']}[end]' id='{$filter['field']}[end]' value='{$filter['default']['end']}' class='form-control $class default-end' autocomplete='off' onchange='filterChange(\"{$filter['field']}.end\", this, this.value)' placeholder='{$lang->chart->unlimited}'>";
                    echo '</div>';
                }
                if($filter['type'] == 'select') echo html::select($filter['field'], array('' => '') + $options[$filter['typeOption']], isset($filter['default']) ? $filter['default'] : '', "class='form-control chosen' onchange='filterChange(\"{$filter['field']}\")' multiple");
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
    <div class="cell">
      <div class='tabs'>

        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasicInfo' data-toggle='tab'><?php echo $lang->chart->legendBasicInfo;?></a></li>
          <!-- <li><a href='#legendConfig' data-toggle='tab'><?php echo $lang->chart->legendConfig;?></a></li> -->
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasicInfo'>
            <form method="post" class="form-ajax" id="releaseForm">
            <table class="table table-form">
              <tbody>
                <tr>
                  <th><?php echo $lang->chart->group;?></th>
                  <td>
                    <?php echo html::select('group', $groups, $chart->group, "class='chosen form-control' data-max_drop_width='278'");?>
                  </td>
                </tr>
                <tr>
                  <th class='thWidth'><?php echo $lang->chart->name;?></th>
                  <td class='w-400px'>
                    <?php echo html::input('name', $chart->name, "class='form-control'");?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->chart->desc;?></th>
                  <td><?php echo html::textarea('desc', $chart->desc, "rows='7' class='form-control'");?></td>
                </tr>
              </tbody>
            </table>
            </form>
          </div>
          <div class='tab-pane' id='legendConfig'>
            <table class="table table-data">
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

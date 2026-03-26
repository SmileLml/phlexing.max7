<?php
/**
 * The batch operate view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('moduleName', $flow->module);?>
<?php js::set('action', $action->action);?>
<?php js::set('batchMode', $action->batchMode);?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo str_replace('-', '', $title);?></strong>
  </div>
  <div class='panel-body'>
    <form id='ajaxForm' method='post' action='<?php echo $actionURL;?>'>
      <div class="table-responsive">
        <table class='table table-form table-borderless'>
          <thead>
            <tr class='text-center'>
              <?php
              $hasPrevField = false;
              foreach($fields as $field)
              {
                  if(!$field->show) continue;

                  if($field->field == $prevField) $hasPrevField = true;

                  $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
                  $required = strpos(",$field->rules,", ",$notEmptyRule->id,") !== false ? 'required' : '';
                  echo "<th class='$required' style='width: $width'>$field->name</th>";
              }
              ?>
              <th class='w-100px'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $row = 1;
            foreach($dataList as $dataID)
            {
                echo "<tr data-key='$row'>";

                $index = 1;
                foreach($fields as $field)
                {
                    if(!$field->show) continue;

                    $value = ($field->field == $prevField ? $dataID : $field->defaultValue);

                    if($field->control == 'select')
                    {
                        if($row == 1)
                        {
                            $field->tmpOptions = $field->options;
                            unset($field->options['ditto']);
                        }
                        if($row > 1)
                        {
                            $field->options = $field->tmpOptions;
                            if($prevField && $prevField != $field->field) $value = 'ditto';
                        }
                    }

                    echo '<td>';

                    $element = "dataList[$row][$field->field]";
                    $control = $this->flow->buildControl($field, $value, $element);
                    $control = str_replace("rows='3'", "rows='1'", $control);

                    echo $control;

                    if($index == 1)
                    {
                        if($prevField && !$hasPrevField) echo html::hidden("dataList[$row][$prevField]", $dataID);
                        echo "<div id='error{$row}'></div>";
                    }

                    echo '</td>';

                    $index++;
                }

                echo '<td>';
                echo "<a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>";
                echo "<a href='javascript:;' class='btn delItem'><i class='icon icon-close'></i></a>";
                echo '</td>';
                echo '</tr>';

                $row++;
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class='form-actions text-center'>
        <?php echo html::submitButton();?>
        <?php echo html::backButton();?>
      </div>
    </form>
  </div>
</div>
<?php if($formulaScript) echo $formulaScript;?>

<?php
$index   = 1;
$itemRow = "<tr data-key='KEY'>";
foreach($fields as $field)
{
    if(!$field->show) continue;

    $value    = ($field->field == $prevField ? $dataID : $field->defaultValue);
    $element  = "dataList[KEY][$field->field]";
    $control  = $this->flow->buildControl($field, $value, $element);
    $control  = str_replace("rows='3'", "rows='1'", $control);
    $itemRow .= '<td>' . $control;
    if($index == 1)
    {
        if(!$hasPrevField) $itemRow .= html::hidden("dataList[KEY][$prevField]", $dataID);
        $itemRow .= "<div id='error{$dataID}'></div>";
    }
    $itemRow .= '</td>';

    $index++;
}
$itemRow .= '<td>';
$itemRow .= "<a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>";
$itemRow .= "<a href='javascript:;' class='btn delItem'><i class='icon icon-close'></i></a>";
$itemRow .= '</td>';
$itemRow .= '</tr>';
js::set('itemRow', $itemRow);
js::set('row', $row);
?>
<script>
<?php helper::import('../js/search.js');?>
</script>
<?php include 'footer.html.php';?>

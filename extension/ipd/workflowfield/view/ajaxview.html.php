<?php
/**
 * The ajaxView view file of workflowfield module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     workflowfield
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
$showFields = array('type', 'default', 'rules');
if(in_array($field->control, $config->workflowfield->optionControls)) $showFields[] = 'datasource';
if($field->type == 'file') $showFields = array();
if($field->type == 'varchar' || $field->type == 'char') $showFields[] = 'length';
if($field->control == 'formula') $showFields[] = 'expression';
if($field->type == 'decimal')
{
    $showFields[] = 'integerDigits';
    $showFields[] = 'decimalDigits';
    list($integerDigits, $decimalDigits) = explode(',', $field->length);
}
?>
<style>
#fieldDetail {background-color: #F1F1F1; border: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 0;}
#fieldDetail table.table tr > th{font-weight: normal; color: #64758b;}
#fieldDetail table.table tr > th, #fieldDetail table.table tr > td{padding: 2px 5px;}
</style>
<div id='fieldDetail' class='panel'>
  <div class='panel-heading'><strong><?php printf($lang->workflowfield->detail, $field->name);?></strong></div>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflowfield->name;?></th>
      <td><?php echo $field->name?></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->field;?></th>
      <td><?php echo $field->field?></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->control;?></th>
      <td><?php echo zget($lang->workflowfield->controlTypeList, $field->control);?></td>
    </tr>
    <?php if(in_array('type', $showFields)):?>
    <tr>
      <th><?php echo $lang->workflowfield->type;?></th>
      <td>
        <?php
        $typeName = $field->type;
        foreach($config->workflowfield->typeList as $typeList)
        {
            foreach($typeList as $key => $value)
            {
                if($key == $field->type) $typeName = $value;
            }
        }
        echo $typeName;
        ?>
      </td>
    </tr>
    <?php endif;?>
    <?php if(in_array('length', $showFields)):?>
    <tr>
      <th><?php echo $lang->workflowfield->length;?></th>
      <td><?php echo $field->length;?></td>
    </tr>
    <?php endif;?>
    <?php if(in_array('integerDigits', $showFields)):?>
    <tr>
      <th><?php echo $lang->workflowfield->integerDigits;?></th>
      <td><?php echo $integerDigits;?></td>
    </tr>
    <?php endif;?>
    <?php if(in_array('decimalDigits', $showFields)):?>
    <tr>
      <th><?php echo $lang->workflowfield->decimalDigits;?></th>
      <td><?php echo $decimalDigits;?></td>
    </tr>
    <?php endif;?>
    <?php if(in_array('datasource', $showFields)):?>
    <tr>
      <th><?php echo $lang->workflowfield->datasource;?></th>
      <td><?php echo is_string($field->options) ? zget($datasources, $field->options) : 'custom';?></td>
    </tr>
    <?php if(is_array($field->options)):?>
    <tr>
      <th><?php echo $lang->workflowfield->options;?></th>
      <td style='word-break: break-all;'><?php echo json_encode($field->options, JSON_UNESCAPED_UNICODE);?></td>
    </tr>
    <?php endif;?>
    <?php if($field->options == 'sql'):?>
    <tr>
      <th><?php echo $lang->workflowfield->sql;?></th>
      <td><?php echo $field->sql;?></td>
    </tr>
    <?php endif;?>
    <?php endif;?>
    <?php if(in_array('expression', $showFields)):?>
    <tr>
      <th><?php echo $lang->workflowfield->expression;?></th>
      <td>
        <?php
        $numberFields = $this->workflowfield->getNumberFields($flow->module);
        $formulaLang  = $lang->workflowfield->formula;
        $modules      = array($flow->module => $flow->name);
        $moduleFields = array($flow->module => $numberFields);
        $expression   = json_decode($field->expression, true);
        if($expression)
        {
            $expressionHtml = "<span class='item-name'>{$field->name}</span><span> = </span>";
            foreach($expression as $key => $current)
            {
                $text = $current['text'];
                if($current['type'] == 'target')
                {
                    $text = zget($modules, current['module']) . '_' . zget(zget($moduleFields, current['module'], array()), current['field']);
                    if(!empty($current['function'])) $text = sprintf($formulaLang->functions[$current['function']], zget($modules, $current['module']), zget(zget($moduleFields, current['module'], array()), current['field']));
                }
                $expressionHtml .= "<span class='item-expression item-{$current['type']}'>{$text}</span>";
            }
            echo $expressionHtml;
        }
        ?>
      </td>
    </tr>
    <?php endif;?>
    <?php if(in_array('default', $showFields)):?>
    <tr>
      <th><?php echo $lang->workflowfield->defaultValue;?></th>
      <td><?php echo $field->default;?></td>
    </tr>
    <?php endif;?>
    <?php if(in_array('rules', $showFields)):?>
    <tr>
      <th><?php echo $lang->workflowfield->rules;?></th>
      <td><?php foreach(explode(',', $field->rules) as $ruleID) echo zget($rules, $ruleID, '') . ' ';?></td>
    </tr>
    <?php endif;?>
  </table>
</div>

<?php
/**
 * The edit view file of workflowhook module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowhook
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('formulaLang', $lang->workflowfield->formula);?>
<?php js::set('moduleName', $flow->module);?>
<form id='editHookForm' class='hookForm' method='post' action='<?php echo inlink('edit', "action=$action->id&key=$key");?>'>
  <?php $hook  = $action->hooks[$key];?>
  <?php $type  = zget($hook, 'conditionType', 'data');?>
  <?php $class = empty($hook->conditions) ? 'hide' : '';?>
  <?php include './expression.html.php';?>
  <div id='conditionDIV' class='detail <?php echo $class;?>'>
    <div class='detail-heading'>
      <strong><?php echo $lang->workflowhook->condition;?></strong>
    </div>
    <div class='detail-content'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'></th>
          <td class='w-160px'></td>
          <td class='w-80px'></td>
          <td class='w-160px'></td>
          <td></td>
          <td class='w-100px'></td>
        </tr>
        <tr>
          <th><?php echo $lang->workflowhook->type;?></th>
          <td colspan='4'><?php echo html::select('conditionType', $lang->workflowhook->typeList, $type, "class='form-control'");?></td>
          <td></td>
        </tr>

        <?php $sqlConditionDatasources = $datasources;?>
        <?php unset($sqlConditionDatasources['formula']);?>
        <?php $dataConditionDatasources = $datasources;?>
        <?php unset($dataConditionDatasources['form']);?>
        <?php unset($dataConditionDatasources['record']);?>
        <?php unset($dataConditionDatasources['formula']);?>

        <?php $varKey  = 1;?>
        <?php $dataKey = 1;?>
        <?php /* SQL TR */ ?>
        <?php if($type == 'sql'):?>
        <tr class='sqlTR'>
          <th><?php echo $lang->workflowhook->sql;?></th>
          <td colspan='4' class='required'><?php echo html::textarea('sql', $hook->conditions->sql, "rows='5' class='form-control' placeholder='{$lang->workflowhook->placeholder->sql}'");?></td>
          <td></td>
        </tr>
        <?php if(!empty($hook->conditions->sqlVars)):?>
        <?php foreach($hook->conditions->sqlVars as $sqlVar):?>
        <tr class='sqlTR' data-key='<?php echo $varKey;?>'>
          <th><?php echo $lang->workflowhook->varName;?></th>
          <td><?php echo html::input("varName[$varKey]", $sqlVar->varName, "class='form-control' autocomplete='off'");?></td>
          <td><?php echo html::input("operator[$varKey]", '=', "class='form-control' disabled");?></td>
          <td><?php echo html::select("paramType[$varKey]", $sqlConditionDatasources, $sqlVar->paramType, "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("param[$varKey]", $sqlVar->param, "id='param{$varKey}' class='form-control paramValue' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addVar'><i class='icon-plus icon-large'></i></a>
            <a href='javascript:;' class='btn delVar'><i class='icon-close icon-large'></i></a>
          </td>
        </tr>
        <?php $varKey++;?>
        <?php endforeach;?>
        <?php else:?>
        <tr class='sqlTR' data-key='<?php echo $varKey;?>'>
          <th><?php echo $lang->workflowhook->varName;?></th>
          <td><?php echo html::input("varName[$varKey]", '', "class='form-control' autocomplete='off'");?></td>
          <td><?php echo html::input("operator[$varKey]", '=', "class='form-control' disabled");?></td>
          <td><?php echo html::select("paramType[$varKey]", $sqlConditionDatasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("param[$varKey]", '', "id='param{$varKey}' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addVar'><i class='icon-plus icon-large'></i></a>
            <a href='javascript:;' class='btn delVar'><i class='icon-close icon-large'></i></a>
          </td>
        </tr>
        <?php $varKey++;?>
        <?php endif;?>
        <tr class='sqlTR'>
          <th><?php echo $lang->workflowhook->result;?></th>
          <td colspan='4'><?php echo html::select('sqlResult', $lang->workflowhook->resultList, $hook->conditions->sqlResult, "class='form-control'");?></td>
          <td></td>
        </tr>
        <?php /* Data TR */ ?>
        <tr class='dataTR' data-key='<?php echo $dataKey;?>'>
          <th>
            <?php echo $lang->workflowhook->field;?>
            <?php echo html::hidden("conditions[logicalOperator][$dataKey]", 'and');?>
          </th>
          <td><?php echo html::select("conditions[field][$dataKey]", $fields, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("conditions[operator][$dataKey]", $config->workflowhook->operatorList, 'equal', "class='form-control chosen'");?></td>
          <td><?php echo html::select("conditions[paramType][$dataKey]", $dataConditionDatasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("conditions[param][$dataKey]", '', "id='conditionsparam{$dataKEY}' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addCondition'><i class='icon-plus icon-large'></i></a>
          </td>
        </tr>
        <?php $dataKey++;?>
        <?php endif;?>

        <?php if($type == 'data'):?>
        <?php /* SQL TR */ ?>
        <tr class='sqlTR'>
          <th><?php echo $lang->workflowhook->sql;?></th>
          <td colspan='4'><?php echo html::textarea('sql', '', "rows='5' class='form-control' placeholder='{$lang->workflowhook->placeholder->sql}'");?></td>
          <td></td>
        </tr>
        <tr class='sqlTR' data-key='<?php echo $varKey;?>'>
          <th><?php echo $lang->workflowhook->varName;?></th>
          <td><?php echo html::input("varName[$varKey]", '', "class='form-control' autocomplete='off'");?></td>
          <th><?php echo html::input("operator[$varKey]", '=', "class='form-control' disabled");?></th>
          <td><?php echo html::select("paramType[$varKey]", $sqlConditionDatasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("param[$varKey]", '', "id='param{$varKey}' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addVar'><i class='icon-plus icon-large'></i></a>
            <a href='javascript:;' class='btn delVar'><i class='icon-close icon-large'></i></a>
          </td>
        </tr>
        <?php $varKey++;?>
        <tr class='sqlTR'>
          <th><?php echo $lang->workflowhook->result;?></th>
          <td colspan='4'><?php echo html::select('sqlResult', $lang->workflowhook->resultList, 'empty', "class='form-control'");?></td>
          <td></td>
        </tr>
        <?php /* Data TR */ ?>
        <?php if(!empty($hook->conditions)):?>
        <?php foreach($hook->conditions as $key => $condition):?>
        <tr class='dataTR' data-key='<?php echo $dataKey;?>'>
          <th>
            <?php if($key == 0):?>
            <?php echo $lang->workflowhook->field;?>
            <?php echo html::hidden("conditions[logicalOperator][$dataKey]", $condition->logicalOperator);?>
            <?php else:?>
            <?php echo html::select("conditions[logicalOperator][$dataKey]", $lang->workflowhook->logicalOperatorList, $condition->logicalOperator, "class='form-control'");?>
            <?php endif;?>
          </th>
          <td><?php echo html::select("conditions[field][$dataKey]", $fields, $condition->field, "class='form-control chosen'");?></td>
          <td><?php echo html::select("conditions[operator][$dataKey]", $config->workflowhook->operatorList, $condition->operator, "class='form-control chosen'");?></td>
          <td><?php echo html::select("conditions[paramType][$dataKey]", $dataConditionDatasources, $condition->paramType, "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("conditions[param][$dataKey]", $condition->param, "id='conditionsparam{$dataKey}' class='form-control paramValue' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addCondition'><i class='icon-plus icon-large'></i></a>
            <?php if($dataKey > 1):?>
            <a href='javascript:;' class='btn delCondition'><i class='icon-close icon-large'></i></a>
            <?php endif;?>
          </td>
        </tr>
        <?php $dataKey++;?>
        <?php endforeach;?>
        <?php else:?>
        <tr class='dataTR' data-key='<?php echo $dataKey;?>'>
          <th>
            <?php echo $lang->workflowhook->field;?>
            <?php echo html::hidden("conditions[logicalOperator][$dataKey]", 'and');?>
          </th>
          <td><?php echo html::select("conditions[field][$dataKey]", $fields, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("conditions[operator][$dataKey]", $config->workflowhook->operatorList, 'equal', "class='form-control chosen'");?></td>
          <td><?php echo html::select("conditions[paramType][$dataKey]", $dataConditionDatasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("conditions[param][$dataKey]", '', "id='conditionsparam{$dataKey}' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addCondition'><i class='icon-plus icon-large'></i></a>
          </td>
        </tr>
        <?php $dataKey++;?>
        <?php endif;?>
        <?php endif;?>
      </table>
    </div>
  </div>

  <div id='hookDIV' class='detail'>
    <div class='detail-heading'>
      <strong><?php echo $lang->workflowhook->hook;?></strong>
    </div>
    <div class='detail-content'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->workflowhook->action;?></th>
          <td class='w-160px'><?php echo html::select('action', $lang->workflowhook->actionList, $hook->action, "class='form-control'");?></td>
          <th class='w-80px'><?php echo $lang->workflowhook->table;?></th>
          <td class='w-160px'><?php echo html::select('table', $tables, $hook->table, "class='form-control chosen'");?></td>
          <td></td>
          <td class='w-100px'></td>
        </tr>

        <?php /* Field TR */ ?>
        <?php $fieldKey = 1;?>
        <?php if(!empty($hook->fields)):?>
        <?php foreach($hook->fields as $field):?>
        <tr class='fieldTR' data-key='<?php echo $fieldKey;?>'>
          <th><?php if($fieldKey == 1) echo $lang->workflowhook->field;?></th>
          <td><?php echo html::select("fields[field][$fieldKey]", $hookFields, $field->field, "class='form-control chosen field'");?></td>
          <td><?php echo html::input("fields[operator][$fieldKey]", '=', "class='form-control' disabled");?></td>
          <td><?php echo html::select("fields[paramType][$fieldKey]", $datasources, $field->paramType, "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("fields[param][$fieldKey]", $field->param, "id='fieldsparam{$fieldKey}' class='form-control paramValue' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addField'><i class='icon-plus icon-large'></i></a>
            <?php if($fieldKey > 1):?>
            <a href='javascript:;' class='btn delField'><i class='delField icon-close icon-large'></i></a>
            <?php endif;?>
          </td>
        </tr>
        <?php $fieldKey++;?>
        <?php endforeach;?>
        <?php else:?>
        <tr class='fieldTR' data-key='<?php echo $fieldKey;?>'>
          <th><?php echo $lang->workflowhook->field;?></th>
          <td><?php echo html::select("fields[field][$fieldKey]", $hookFields, '', "class='form-control chosen field'");?></td>
          <td><?php echo html::input("fields[operator][$fieldKey]", '=', "class='form-control' disabled");?></td>
          <td><?php echo html::select("fields[paramType][$fieldKey]", $datasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("fields[param][$fieldKey]", '', "id='fieldsparam{$fieldKey}' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addField'><i class='icon-plus icon-large'></i></a>
          </td>
        </tr>
        <?php $fieldKey++;?>
        <?php endif;?>

        <?php /* Where TR */ ?>
        <?php $whereDatasources = $datasources;?>
        <?php unset($whereDatasources['formula']);?>
        <?php $whereKey = 1;?>
        <?php if(!empty($hook->wheres)):?>
        <?php foreach($hook->wheres as $where):?>
        <tr class='whereTR' data-key='<?php echo $whereKey;?>'>
          <th>
            <?php if($whereKey == 1):?>
            <?php echo $lang->workflowhook->where;?>
            <?php echo html::hidden("wheres[logicalOperator][$whereKey]", $where->logicalOperator);?>
            <?php else:?>
            <?php echo html::select("wheres[logicalOperator][$whereKey]", $lang->workflowhook->logicalOperatorList, $where->logicalOperator, "class='form-control'");?>
            <?php endif;?>
          </th>
          <td><?php echo html::select("wheres[field][$whereKey]", $hookFields, $where->field, "class='form-control chosen field'");?></td>
          <td><?php echo html::select("wheres[operator][$whereKey]", $config->workflowhook->operatorList, $where->operator, "class='form-control chosen'");?></td>
          <td><?php echo html::select("wheres[paramType][$whereKey]", $whereDatasources, $where->paramType, "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("wheres[param][$whereKey]", $where->param, "id='wheresparam{$whereKey}' class='form-control paramValue' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addWhere'><i class='icon-plus icon-large'></i></a>
            <?php if($whereKey > 1):?>                      
            <a href='javascript:;' class='btn delWhere'><i class='icon-close icon-large'></i></a>
            <?php endif;?>
          </td>
        </tr>
        <?php $whereKey++;?>
        <?php endforeach;?>
        <?php else:?>
        <tr class='whereTR' data-key='<?php echo $whereKey;?>'>
          <th>
            <?php echo $lang->workflowhook->where;?>
            <?php echo html::hidden("wheres[logicalOperator][$whereKey]", 'and');?>
          </th>
          <td><?php echo html::select("wheres[field][$whereKey]", $hookFields, '', "class='form-control chosen field'");?></td>
          <td><?php echo html::select("wheres[operator][$whereKey]", $config->workflowhook->operatorList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("wheres[paramType][$whereKey]", $whereDatasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input("wheres[param][$whereKey]", '', "id='wheresparam{$whereKey}' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addWhere'><i class='icon-plus icon-large'></i></a>
          </td>
        </tr>
        <?php $whereKey++;?>
        <?php endif;?>

        <tr>
          <th><?php echo $lang->workflowhook->message;?></th>
          <td colspan='4'><?php echo html::input('message', zget($hook, 'message', ''), "class='form-control' autocomplete='off'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='4'><?php echo html::textarea('comment', zget($hook, 'comment', ''), "class='form-control' rows='3'");?></td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td class='form-actions' colspan='5'>
            <?php echo html::hidden('condition', empty($hook->conditions) ? 0 : 1);?>
            <?php echo baseHTML::a('javascript:;', $lang->workflowhook->condition, "class='btn btn-primary toggleCondition'");?>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </div>
  </div>
</form>

<?php
$varName   = html::input('varName[KEY]', '', "class='form-control' autocomplete='off'");
$operator  = html::input('operator[KEY]', '=', "class='form-control' disabled");
$paramType = html::select('paramType[KEY]', $sqlConditionDatasources, 'custom', "class='form-control chosen'");
$param     = html::input('param[KEY]', '', "id='paramKEY' class='form-control' autocomplete='off'");
$varRow = <<<EOT
  <tr class='sqlTR' data-key='KEY'>
    <th></th>
    <td>{$varName}</td>
    <td>{$operator}</td>
    <td>{$paramType}</td>
    <td class='paramTD'>{$param}</td>
    <td>
      <a href='javascript:;' class='btn addVar'><i class='icon-plus icon-large'></i></a>
      <a href='javascript:;' class='btn delVar'><i class='icon-close icon-large'></i></a>
    </td>
  </tr>
EOT;

$logicOperater = html::select('conditions[logicalOperator][KEY]', $lang->workflowhook->logicalOperatorList, '', "class='form-control'");
$field         = html::select('conditions[field][KEY]', $fields, '', "class='form-control chosen'");
$operator      = html::select('conditions[operator][KEY]', $config->workflowhook->operatorList, 'equal', "class='form-control chosen'");
$paramType     = html::select('conditions[paramType][KEY]', $dataConditionDatasources, 'custom', "class='form-control chosen'");
$param         = html::input('conditions[param][KEY]', '', "id='conditionsparamKEY' class='form-control' autocomplete='off'");
$conditionRow = <<<EOT
  <tr class='dataTR' data-key='KEY'>
    <th>{$logicOperater}</th>
    <td>{$field}</td>
    <td>{$operator}</td>
    <td>{$paramType}</td>
    <td class='paramTD'>{$param}</td>
    <td>
      <a href='javascript:;' class='btn addCondition'><i class='icon-plus icon-large'></i></a>
      <a href='javascript:;' class='btn delCondition'><i class='icon-close icon-large'></i></a>
    </td>
  </tr>
EOT;

$field     = html::select('fields[field][KEY]', $fields, '', "class='form-control chosen field'");
$operator  = html::input('fields[operator][KEY]', '=', "class='form-control' disabled");
$paramType = html::select('fields[paramType][KEY]', $datasources, 'custom', "class='form-control chosen'");
$param     = html::input('fields[param][KEY]', '', "id='fieldsparamKEY' class='form-control' autocomplete='off'");
$fieldRow = <<<EOT
  <tr class='fieldTR' data-key='KEY'>
    <th></th>
    <td>{$field}</td>
    <td>{$operator}</td>
    <td>{$paramType}</td>
    <td class='paramTD'>{$param}</td>
    <td>
      <a href='javascript:;' class='btn addField'><i class='icon-plus icon-large'></i></a>
      <a href='javascript:;' class='btn delField'><i class='icon-close icon-large'></i></a>
    </td>
  </tr>
EOT;

$logicOperater = html::select('wheres[logicalOperator][KEY]', $lang->workflowhook->logicalOperatorList, '', "class='form-control'");
$field         = html::select('wheres[field][KEY]', $fields, '', "class='form-control chosen field'");
$operator      = html::select('wheres[operator][KEY]', $config->workflowhook->operatorList, 'equal', "class='form-control chosen'");
$paramType     = html::select('wheres[paramType][KEY]', $whereDatasources, 'custom', "class='form-control chosen'");
$param         = html::input('wheres[param][KEY]', '', "id='wheresparamKEY' class='form-control' autocomplete='off'");
$whereRow = <<<EOT
  <tr class='whereTR' data-key='KEY'>
    <th>{$logicOperater}</th>
    <td>{$field}</td>
    <td>{$operator}</td>
    <td>{$paramType}</td>
    <td class='paramTD'>{$param}</td>
    <td>
      <a href='javascript:;' class='btn addWhere'><i class='icon-plus icon-large'></i></a>
      <a href='javascript:;' class='btn delWhere'><i class='icon-close icon-large'></i></a>
    </td>
  </tr>
EOT;

$recordFields = html::select('NAME', $fields, '', "class='form-control chosen'");
$formFields   = html::select('NAME', array('' => '') + $layoutFields, '', "class='form-control chosen'");

js::set('recordFields', $recordFields);
js::set('formFields', $formFields);

js::set('hookVarKey', $varKey);
js::set('hookDataKey', $dataKey);
js::set('hookFieldKey', $fieldKey);
js::set('hookWhereKey', $whereKey);

js::set('varRow', $varRow);
js::set('conditionRow', $conditionRow);
js::set('fieldRow', $fieldRow);
js::set('whereRow', $whereRow);
?>
<?php include '../../common/view/footer.modal.html.php';?>

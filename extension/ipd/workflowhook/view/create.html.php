<?php
/**
 * The create view file of workflowhook module of ZDOO.
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
<form id='createHookForm' class='hookForm' method='post' action='<?php echo inlink('create', "action=$action->id");?>'>
  <?php include './expression.html.php';?>
  <div id='conditionDIV' class='detail hide'>
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
          <td class='w-120px'></td>
        </tr>
        <tr>
          <th><?php echo $lang->workflowhook->type;?></th>
          <td colspan='4'><?php echo html::select('conditionType', $lang->workflowhook->typeList, 'data', "class='form-control'");?></td>
          <td></td>
        </tr>

        <?php /* SQL TR */ ?>
        <?php $sqlConditionDatasources = $datasources;?>
        <?php unset($sqlConditionDatasources['formula']);?>
        <tr class='sqlTR'>
          <th><?php echo $lang->workflowhook->sql;?></th>
          <td colspan='4' class='required'><?php echo html::textarea('sql', '', "rows='5' class='form-control' placeholder='{$lang->workflowhook->placeholder->sql}'");?></td>
          <td></td>
        </tr>
        <tr class='sqlTR' data-key='1'>
          <th><?php echo $lang->workflowhook->varName;?></th>
          <td><?php echo html::input('varName[1]', '', "class='form-control' autocomplete='off'");?></td>
          <td><?php echo html::input('operator[1]', '=', "class='form-control' disabled");?></td>
          <td><?php echo html::select('paramType[1]', $sqlConditionDatasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input('param[1]', '', "id='param1' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addVar'><i class='icon-plus'></i></a>
            <a href='javascript:;' class='btn delVar'><i class='icon-close'></i></a>
          </td>
        </tr>
        <tr class='sqlTR'>
          <th><?php echo $lang->workflowhook->result;?></th>
          <td colspan='4'><?php echo html::select('sqlResult', $lang->workflowhook->resultList, 'empty', "class='form-control'");?></td>
          <td></td>
        </tr>

        <?php /* Data TR */ ?>
        <?php $dataConditionDatasources = $datasources;?>
        <?php unset($dataConditionDatasources['form']);?>
        <?php unset($dataConditionDatasources['record']);?>
        <?php unset($dataConditionDatasources['formula']);?>
        <tr class='dataTR' data-key='1'>
          <th>
            <?php echo $lang->workflowhook->field;?>
            <?php echo html::hidden("conditions[logicalOperator][1]", 'and');?>
          </th>
          <td><?php echo html::select('conditions[field][1]', $fields, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select('conditions[operator][1]', $config->workflowhook->operatorList, 'equal', "class='form-control chosen'");?></td>
          <td><?php echo html::select('conditions[paramType][1]', $dataConditionDatasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input('conditions[param][1]', '', "id='conditionsparam1' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addCondition'><i class='icon-plus'></i></a>
          </td>
        </tr>
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
          <td class='w-160px'><?php echo html::select('action', $lang->workflowhook->actionList, 'update', "class='form-control'");?></td>
          <th class='w-80px'><?php echo $lang->workflowhook->table;?></th>
          <td class='w-160px'><?php echo html::select('table', $tables, $flow->module, "class='form-control chosen'");?></td>
          <td></td>
          <td class='w-100px'></td>
        </tr>

        <?php /* Field TR */ ?>
        <tr class='fieldTR' data-key='1'>
          <th><?php echo $lang->workflowhook->field;?></th>
          <td><?php echo html::select('fields[field][1]', $fields, '', "class='form-control chosen field'");?></td>
          <td><?php echo html::input('operators[field][1]', '=', "class='form-control' disabled");?></td>
          <td><?php echo html::select('fields[paramType][1]', $datasources, 'custom', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input('fields[param][1]', '', "id='fieldsparam1' class='form-control' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addField'><i class='icon-plus'></i></a>
          </td>
        </tr>

        <?php /* Where TR */ ?>
        <?php $whereDatasources = $datasources;?>
        <?php unset($whereDatasources['formula']);?>
        <tr class='whereTR' data-key='1'>
          <th>
            <?php echo $lang->workflowhook->where;?>
            <?php echo html::hidden("wheres[logicalOperator][1]", 'and');?>
          </th>
          <td><?php echo html::select('wheres[field][1]', $fields, 'id', "class='form-control chosen field'");?></td>
          <td><?php echo html::select('wheres[operator][1]', $config->workflowhook->operatorList, 'equal', "class='form-control chosen'");?></td>
          <td><?php echo html::select('wheres[paramType][1]', $whereDatasources, 'record', "class='form-control chosen'");?></td>
          <td class='paramTD'><?php echo html::input('wheres[param][1]', 'id', "id='wheresparam1' class='form-control paramValue' autocomplete='off'");?></td>
          <td>
            <a href='javascript:;' class='btn addWhere'><i class='icon-plus'></i></a>
          </td>
        </tr>

        <tr>
          <th><?php echo $lang->workflowhook->message;?></th>
          <td colspan='4'><?php echo html::input('message', '', "class='form-control' autocomplete='off'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='4'><?php echo html::textarea('comment', '', "class='form-control' rows='3'");?></td>
          <td></td>
        </tr>
        <tr>
          <th></th>
          <td class='form-actions' colspan='5'>
            <?php echo html::hidden('condition', 0);?>
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
      <a href='javascript:;' class='btn addVar'><i class='icon-plus'></i></a>
      <a href='javascript:;' class='btn delVar'><i class='icon-close'></i></a>
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
      <a href='javascript:;' class='btn addCondition'><i class='icon-plus'></i></a>
      <a href='javascript:;' class='btn delCondition'><i class='icon-close'></i></a>
    </td>
  </tr>
EOT;

$field     = html::select('fields[field][KEY]', $fields, '', "class='form-control chosen field'");
$operator  = html::input('fields[operator][KEY]', '=', "class='form-control' disabled");
$paramType = html::select('fields[paramType][KEY]', $datasources, 'custom', "class='form-control chosen'");
$param     = html::input('fields[param][KEY]', '', "id='fieldsparamKEY' class='form-control' autocomplete='off'");
$fieldRow  = <<<EOT
  <tr class='fieldTR' data-key='KEY'>
    <th></th>
    <td>{$field}</td>
    <td>{$operator}</td>
    <td>{$paramType}</td>
    <td class='paramTD'>{$param}</td>
    <td>
      <a href='javascript:;' class='btn addField'><i class='icon-plus'></i></a>
      <a href='javascript:;' class='btn delField'><i class='icon-close'></i></a>
    </td>
  </tr>
EOT;

$logicOperater = html::select('wheres[logicalOperator][KEY]', $lang->workflowhook->logicalOperatorList, '', "class='form-control'");
$field         = html::select('wheres[field][KEY]', $fields, '', "class='form-control field chosen'");
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
      <a href='javascript:;' class='btn addWhere'><i class='icon-plus'></i></a>
      <a href='javascript:;' class='btn delWhere'><i class='icon-close'></i></a>
    </td>
  </tr>
EOT;

$recordFields = html::select('NAME', $fields, '', "class='form-control chosen'");

js::set('recordFields', $recordFields);

js::set('hookVarKey', 2);
js::set('hookDataKey', 2);
js::set('hookFieldKey', 2);
js::set('hookWhereKey', 2);

js::set('varRow', $varRow);
js::set('conditionRow', $conditionRow);
js::set('fieldRow', $fieldRow);
js::set('whereRow', $whereRow);
?>
<?php include '../../common/view/footer.modal.html.php';?>

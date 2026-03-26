<?php
/**
 * The set verification view file of workflowaction module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowaction
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('moduleName', $flow->module);?>
<form id='ajaxForm' method='post' action='<?php echo inlink('setVerification', "id=$action->id");?>'>
  <table class='table table-form' id='verificationTable'>
    <tr>
      <th class='w-80px'></th>
      <td class='w-160px'></td>
      <td class='w-80px'></td>
      <td class='w-160px'></td>
      <td></td>
      <td class='w-120px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowverification->type;?></th>
      <td colspan='4'><?php echo html::select('type', $lang->workflowverification->typeList, !empty($action->verifications->type) ? $action->verifications->type : 'no', "class='form-control'");?></td>
      <td></td>
    </tr>

    <?php /* SQL TR */ ?>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowverification->sql;?></th>
      <td colspan='4' class='required'>
        <?php echo html::textarea('sql', !empty($action->verifications->sql) ? $action->verifications->sql : '', "rows='5' class='form-control' placeholder='{$lang->workflowverification->placeholder->sql}'");?>
      </td>
      <td></td>
    </tr>
    <?php $varKey = 1;?>
    <?php if(!empty($action->verifications->sqlVars)):?>
    <?php foreach($action->verifications->sqlVars as $sqlVar):?>
    <tr class='sqlTR' data-key='<?php echo $varKey;?>'>
      <th><?php echo $lang->workflowverification->varName;?></th>
      <td><?php echo html::input("varName[$varKey]", $sqlVar->varName, "class='form-control'");?></td>
      <th><?php echo $lang->workflowverification->varValue;?></th>
      <td ><?php echo html::select("paramType[$varKey]", $datasources, $sqlVar->paramType, "class='form-control chosen'");?></td>
      <td class='paramTD'><?php echo html::input("param[$varKey]", $sqlVar->param, "id='param{$varKey}' class='form-control paramValue'");?></td>
      <td>
        <a href='javascript:;' class='btn addVar'><i class='icon-plus icon-large'></i></a>
        <a href='javascript:;' class='btn delVar'><i class='icon-close icon-large'></i></a>
      </td>
    </tr>
    <?php $varKey++;?>
    <?php endforeach;?>
    <?php else:?>
    <tr class='sqlTR' data-key='<?php echo $varKey;?>'>
      <th><?php echo $lang->workflowverification->varName;?></th>
      <td><?php echo html::input("varName[$varKey]", '', "class='form-control'");?></td>
      <th><?php echo $lang->workflowverification->varValue;?></th>
      <td><?php echo html::select("paramType[$varKey]", $datasources, $action->action == 'create' ? 'form' : 'record', "class='form-control chosen'");?></td>
      <td class='paramTD'><?php echo html::input("param[$varKey]", '', "id='param{$varKey}' class='form-control'");?></td>
      <td>
        <a href='javascript:;' class='btn addVar'><i class='icon-plus icon-large'></i></a>
        <a href='javascript:;' class='btn delVar'><i class='icon-close icon-large'></i></a>
      </td>
    </tr>
    <?php $varKey++;?>
    <?php endif;?>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowverification->result;?></th>
      <td colspan='4'><?php echo html::select('sqlResult', $lang->workflowverification->resultList, isset($action->verifications->sqlResult) ? $action->verifications->sqlResult : 'empty', "class='form-control'");?></td>
    </tr>

    <?php /* Data TR */ ?>
    <?php $dataKey = 1;?>
    <?php $conditionDatasources = $datasources;?>
    <?php unset($conditionDatasources['form']);?>
    <?php unset($conditionDatasources['record']);?>
    <?php if(!empty($action->verifications->fields)):?>
    <?php foreach($action->verifications->fields as $key => $verification):?>
    <tr class='dataTR' data-key='<?php echo $dataKey;?>'>
      <th>
        <?php if($key == 0):?>
        <?php echo $lang->workflowverification->field;?>
        <?php echo html::hidden("verifications[logicalOperator][$dataKey]", $verification->logicalOperator);?>
        <?php else:?>
        <?php echo html::select("verifications[logicalOperator][$dataKey]", $lang->workflowverification->logicalOperatorList, $verification->logicalOperator, "class='form-control'");?>
        <?php endif;?>
      </th>
      <td><?php echo html::select("verifications[field][$dataKey]", $fields, $verification->field, "class='form-control chosen'");?></td>
      <td><?php echo html::select("verifications[operator][$dataKey]", $config->workflowaction->operatorList, $verification->operator, "class='form-control chosen'");?></td>
      <td><?php echo html::select("verifications[paramType][$dataKey]", $conditionDatasources, $verification->paramType, "class='form-control chosen'");?></td>
      <td class='paramTD'>
        <?php $param = is_array($verification->param) ? implode(',', $verification->param) : $verification->param;?>
        <?php echo html::input("verifications[param][$dataKey]", $verification->param, "id='verificationsparam{$dataKey}' class='form-control paramValue'");?>
      </td>
      <td>
        <a href='javascript:;' class='btn addVerification'><i class='icon-plus icon-large'></i></a>
        <?php if($key > 0):?>
        <a href='javascript:;' class='btn delVerification'><i class='icon-close icon-large'></i></a>
        <?php endif;?>
      </td>
    </tr>
    <?php $dataKey++;?>
    <?php endforeach;?>
    <?php else:?>
    <tr class='dataTR' data-key='<?php echo $dataKey;?>'>
      <th>
        <?php if(empty($action->verifications->fields)):?>
        <?php echo $lang->workflowverification->field;?>
        <?php echo html::hidden("verifications[logicalOperator][$dataKey]", 'and');?>
        <?php else:?>
        <?php echo html::select("verifications[logicalOperator][$dataKey]", $lang->workflowverification->logicalOperatorList, 'and', "class='form-control'");?>
        <?php endif;?>
      </th>
      <td><?php echo html::select("verifications[field][$dataKey]", $fields, '', "class='form-control chosen'");?></td>
      <td><?php echo html::select("verifications[operator][$dataKey]", $config->workflowaction->operatorList, 'equal', "class='form-control chosen'");?></td>
      <td><?php echo html::select("verifications[paramType][$dataKey]", $conditionDatasources, 'custom', "class='form-control chosen'");?></td>
      <td class='paramTD'><?php echo html::input("verifications[param][$dataKey]", '', "id='verificationsparam{$dataKey}' class='form-control'");?></td>
      <td>
        <a href='javascript:;' class='btn addVerification'><i class='icon-plus icon-large'></i></a>
        <?php if(!empty($action->verifications->fields)):?>
        <a href='javascript:;' class='btn delVerification'><i class='icon-close icon-large'></i></a>
        <?php endif;?>
      </td>
    </tr>
    <?php $dataKey++;?>
    <?php endif;?>

    <tr class='messageTR'>
      <th><?php echo $lang->workflowverification->message;?></th>
      <td colspan='4' class='required'>
        <?php echo html::input('message', isset($action->verifications->message) ? $action->verifications->message : '', "class='form-control' placeholder='{$lang->workflowverification->placeholder->message}'");?>
      </td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions' colspan='5'>
        <?php echo html::submitButton();?>
      </td>
    </tr>
  </table>
</form>

<?php
$varName   = html::input('varName[KEY]', '', "class='form-control'");
$paramType = html::select('paramType[KEY]', $datasources, 'custom', "class='form-control chosen'");
$param     = html::input('param[KEY]', '', "id='paramKEY' class='form-control'");
$varRow = <<<EOT
  <tr class='sqlTR' data-key='KEY'>
    <th>{$lang->workflowverification->varName}</th>
    <td>{$varName}</td>
    <th>{$lang->workflowverification->varValue}</th>
    <td>{$paramType}</td>
    <td class='paramTD'>{$param}</td>
    <td>
      <a href='javascript:;' class='btn addVar'><i class='icon-plus icon-large'></i></a>
      <a href='javascript:;' class='btn delVar'><i class='icon-close icon-large'></i></a>
    </td>
  </tr>
EOT;

$logicOperater = html::select('verifications[logicalOperator][KEY]', $lang->workflowverification->logicalOperatorList, '', "class='form-control'");
$field         = html::select('verifications[field][KEY]', $fields, '', "class='form-control chosen'");
$operator      = html::select('verifications[operator][KEY]', $config->workflowaction->operatorList, 'equal', "class='form-control chosen'");
$paramType     = html::select('verifications[paramType][KEY]', $conditionDatasources, 'custom', "class='form-control chosen'");
$param         = html::input( 'verifications[param][KEY]', '', "id='verificationparamKEY' class='form-control'");
$verificationRow = <<<EOT
  <tr class="dataTR" data-key='KEY'>
    <th>{$logicOperater}</th>
    <td>{$field}</td>
    <td>{$operator}</td>
    <td>{$paramType}</td>
    <td class='paramTD'>{$param}</td>
    <td>
      <a href='javascript:;' class='btn addVerification'><i class='icon-plus icon-large'></i></a>
      <a href='javascript:;' class='btn delVerification'><i class='icon-close icon-large'></i></a>
    </td>
  </tr>
EOT;

$recordFields = html::select('NAME', $fields, '', "class='form-control chosen'");

js::set('recordFields', $recordFields);
js::set('verificationVarKey', $varKey);
js::set('verificationDataKey', $dataKey);
js::set('formFields', '');
js::set('varRow', $varRow);
js::set('verificationRow', $verificationRow);
?>
<?php include '../../common/view/footer.modal.html.php';?>

<?php
/**
 * The create view file of workflowdatasource module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowdatasource
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form id='ajaxForm' method='post' action='<?php echo inlink('create');?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflowdatasource->name;?></th>
      <td><?php echo html::input('name', '', "class='form-control'");?></td>
      <td class='w-40px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowdatasource->code;?></th>
      <td><?php echo html::input('code', '', "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowdatasource->type;?></th>
      <td><?php echo html::select('type', $lang->workflowdatasource->typeList, 'option', "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr class='optionTR'>
      <th><?php echo $lang->workflowdatasource->typeList['option'];?></th>
      <td id='datasource'>
        <div class="input-group">
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->key;?></span>
          <?php echo html::input('options[value][]', '', "class='form-control' placeholder='{$lang->workflowdatasource->placeholder->optionCode}'");?>
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->value;?></span>
          <?php echo html::input('options[text][]', '', "class='form-control'");?>
          <span class='input-group-btn'>
            <a href='javascript:;' class='btn addItem'><i class='icon-plus'></i></a>
          </span>
          <span class='input-group-btn'>
            <a href='javascript:;' class='btn delItem'><i class='icon-minus'></i></a>
          </span>
        </div> 
        <div id='options'></div>
      </td>
      <td></td>
    </tr>
    <tr class='systemTR'>
      <th><?php echo $lang->workflowdatasource->typeList['system'];?></th>
      <td>
        <div class="input-group">
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->app;?></span>
          <?php echo html::select('app', $apps, '', "class='form-control''");?>
          <span class='input-group-addon fix-border'><?php echo $lang->workflowdatasource->module;?></span>
          <?php echo html::select('module', '', '', "class='form-control'");?>
        </div> 
        <div class="input-group">
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->method;?></span>
          <?php echo html::select('method', '', '', "class='form-control'");?>
          <span class='input-group-addon fix-border methodDesc' style='display:none'><?php echo $lang->workflowdatasource->desc;?></span>
          <?php echo html::input('methodDesc', '', "class='form-control methodDesc' readonly='readonly' style='display:none'");?>
        </div> 
        <div class='input-group' id='paramsDIV'>
        </div>
        <div id='commentDIV' class='hide'></div>
      </td>
      <td></td>
    </tr>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowdatasource->sql;?></th>
      <td id='datasource'>
        <div class='required required-wrapper'></div>
        <?php echo html::textarea('sql', '', "rows='5' class='form-control' placeholder='{$lang->workflowdatasource->placeholder->sql}'");?>
      </td>
      <td></td>
    </tr>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowdatasource->key;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::select('keyField', array(''), '', "class='form-control chosen'");?>
      </td>
      <td></td>
    </tr>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowdatasource->value;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::select('valueField', array(''), '', "class='form-control chosen'");?>
      </td>
      <td></td>
    </tr>
    <tr class='langTR'>
      <th><?php echo $lang->workflowdatasource->typeList['lang'];?></th>
      <td id='datasource'><?php echo html::select('lang', $lang->workflowdatasource->langList, '', "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions'><?php echo html::submitButton();?></td>
      <td></td>
    </tr>
  </table>
</form>
<div id='optionGroup' class='hide'>
  <div class='input-group'>
    <span class='input-group-addon'><?php echo $lang->workflowdatasource->key;?></span>
    <?php echo html::input('options[value][]', '', "class='form-control' placeholder='{$lang->workflowdatasource->placeholder->optionCode}'");?>
    <span class='input-group-addon'><?php echo $lang->workflowdatasource->value;?></span>
    <?php echo html::input('options[text][]', '', "class='form-control'");?>
    <div class='input-group-btn'>
      <a href='javascript:;' class='btn addItem'><i class='icon-plus'></i></a>
    </div>
    <div class='input-group-btn'>
      <a href='javascript:;' class='btn delItem'><i class='icon-minus'></i></a>
    </div>
  </div> 
</div>
<?php include '../../common/view/footer.modal.html.php';?>

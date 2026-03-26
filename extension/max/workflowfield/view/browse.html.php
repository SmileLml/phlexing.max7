<?php
/**
 * The browse view file of workflowfield module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowfield
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../workflow/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php js::set('defaultField', $config->workflowfield->default);?>
<?php js::set('maxField', $config->workflowfield->max);?>
<?php js::set('minField', $config->workflowfield->min);?>
<?php js::set('tips', $lang->workflowfield->tips);?>
<?php js::set('placeholder', $lang->workflowfield->placeholder);?>
<?php js::set('formulaLang', $lang->workflowfield->formula);?>
<?php js::set('determine', $lang->determine);?>
<?php js::set('expression', array());?>
<?php js::set('defaultConfirmDelete', $lang->workflowfield->alert->confirmDelete);?>
<?php js::set('confirmDeleteHasQuote', $lang->workflowfield->alert->confirmDeleteHasQuote);?>
<?php js::set('confirmDeleteInQuote', $lang->workflowfield->alert->confirmDeleteInQuote);?>
<?php js::set('quotedFields', array_values($quotedFields));?>
<div class='space space-sm'></div>
<div class='row'>
  <div class='col-md-6'>
    <div class='panel' id='previewArea'>
      <div class='panel-heading'>
        <strong><?php echo $flow->name;?></strong>
      </div>
      <div class='panel-body'>
        <table class='table table-form'>
          <?php foreach($fields as $field):?>
          <tr>
            <th class='w-120px'><?php echo $field->name;?> </th>
            <td>
            <?php
            if($field->field == 'id' or $field->field == 'parent')
            {
                echo html::input($field->field, $lang->workflowfield->placeholder->auto, "class='form-control' disabled='disabled'");
            }
            else
            {
                echo $this->loadModel('flow')->buildControl($field, '', "preview_{$field->field}", '', true, true);
            }
            ?>
            </td>
            <td class='w-150px'></td>
          </tr>
          <?php endforeach;?>
        </table>
      </div>
    </div>
  </div>
  <div class='col-md-6'>
    <div class='panel'>
      <div class='panel-heading'>
        <?php if($flow->type == 'flow'):?>
        <strong><?php echo $lang->workflowfield->settings;?></strong>
        <?php else:?>
        <div class='btn-toolbar'>
          <?php echo baseHTML::a($this->createLink('workflow', 'browsedb', "parent={$flow->parent}&table={$flow->module}"), $lang->goback, "class='btn btn-back'");?>
          <div class='divider'></div>
          <div class='page-title'><span class='text'><?php echo $flow->name;?></span></div>
        </div>
        <?php endif;?>
        <?php if($flow->type != 'table' || $flow->role != 'quote'):?>
        <div class='panel-actions pull-right'>
          <?php if($flow->type == 'flow') extCommonModel::printLink('workflowfield', 'quote', "module={$flow->module}&groupID={$groupID}", $lang->workflowfield->quote, "class='btn btn-secondary iframe' data-width='600'");?>
          <?php $canImport         = commonModel::hasPriv('workflowfield', 'import');?>
          <?php $canExportTemplate = commonModel::hasPriv('workflowfield', 'exportTemplate');?>
          <?php if($canImport or $canExportTemplate):?>
          <div class='btn-group'>
            <button type='button' data-toggle='dropdown' class='btn btn-secondary dropdown-toggle'><?php echo $lang->importIcon . $lang->import;?> <span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php if($canImport) echo '<li>' . baseHTML::a(inlink('import', "module={$flow->module}&type={$flow->type}"), $lang->workflowfield->import, "data-toggle='modal'") . '</li>';?>
              <?php if($canExportTemplate) echo '<li>' . baseHTML::a(inlink('exportTemplate', "module={$flow->module}&type={$flow->type}"), $lang->workflowfield->exportTemplate, "class='iframe'") . '</li>';?>
            </ul>
          </div>
          <?php endif;?>
          <?php extCommonModel::printLink('workflowfield', 'create', "module=$flow->module", '<i class="icon-plus"> </i> ' . $lang->workflowfield->create, "class='btn btn-primary' data-toggle='modal'");?>
        </div>
        <?php endif;?>
      </div>
      <div class='panel-body main-table no-padding'>
        <table class='table'>
          <thead>
            <tr>
              <th class='w-50px text-center'> <?php echo $lang->sort;?></th>
              <th><?php echo $lang->workflowfield->name;?></th>
              <th class='w-100px'><?php echo $lang->workflowfield->field;?></th>
              <th class='w-100px'><?php echo $lang->workflowfield->control;?></th>
              <?php if($flow->type == 'flow'):?>
              <th class='w-100px'><?php echo $lang->workflowfield->group;?></th>
              <?php endif;?>
              <?php if($flow->buildin):?>
              <th class='w-60px text-center'><?php echo $lang->workflowfield->buildin;?></th>
              <?php endif;?>
              <th class='w-70px text-center'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody class='sortable' id='fieldList'>
            <?php foreach($fields as $field):?>
            <tr data-id='<?php echo $field->id;?>'>
              <td class='sort-handler text-center'><i class='icon icon-move text-muted'></i></td>
              <td title='<?php echo $field->name;?>'><?php echo $field->name;?></td>
              <td><?php echo $field->field;?></td>
              <td><?php echo zget($lang->workflowfield->controlTypeList, $field->control, '');?></td>
              <?php if($flow->type == 'flow'):?>
              <td><?php echo $field->groupName;?></td>
              <?php endif;?>
              <?php if($flow->buildin):?>
              <td class='text-center buildin<?php echo $field->buildin;?>'><?php echo $field->buildin ? "<i class='icon icon-check'></i>" : "<i class='icon icon-times'></i>";?></td>
              <?php endif;?>
              <td class='actions'>
                <?php
                $disabled = '';
                if($flow->role == 'quote') $disabled = 'disabled';
                if($field->buildin)        $disabled = 'disabled';
                extCommonModel::printLink('workflowfield', 'edit', "module=$field->module&id=$field->id", "<i class='icon icon-edit'></i>", "class='edit btn {$disabled}' data-toggle='modal' title='{$lang->workflowfield->edit}'");

                if(empty($disabled) && in_array($field->role, array('quote', 'custom')) && $field->buildin == '0')
                {
                    $deleteTitle = $field->role == 'quote' ? $lang->workflowfield->remove : $lang->workflowfield->delete;
                    $deleteIcon  = $field->role == 'quote' ? 'icon-unlink' : 'icon-trash';
                    extCommonModel::printLink('workflowfield', 'delete', "id=$field->id", "<i class='icon {$deleteIcon}'></i>", "class='deleteField btn' data-field='{$field->field}' data-role='{$field->role}' title='{$deleteTitle}'");
                }
                else
                {
                    echo baseHTML::a('javascript:;', "<i class='icon icon-trash'></i>", "class='disabled btn' title='{$lang->workflowfield->delete}'");
                }
                ?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

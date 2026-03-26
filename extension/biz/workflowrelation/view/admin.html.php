<?php
/**
 * The admin view file of workflowrelation module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowrelation
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../workflow/view/header.html.php';?>
<?php if($flow->buildin):?>
<?php unset($lang->workflowrelation->relationActionList['many2one']);?>
<?php unset($lang->workflowrelation->relationActionList['many2many']);?>
<?php endif;?>
<div class='space space-sm'></div>
<div class='main-row'>
  <div class='side-col'>
    <?php include '../../workflow/view/side.html.php';?>
  </div>
  <div class='main-col'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->workflowrelation->admin;?></strong>
      </div>
      <div class='panel-body'>
        <form id='ajaxForm' method='post' action='<?php echo inlink('admin', "prev=$prev&next=$next");?>'>
          <table class='table table-form' id='relationTable' style="width:<?php echo $lang->workflowrelation->tableWidth;?>px">
            <thead>
              <tr class='text-center'>
                <th class='w-140px required'><?php echo $lang->workflowrelation->next;?></th>
                <th class='w-200px required'><?php echo $lang->workflowrelation->foreignKey;?></th>
                <th><?php echo $lang->workflowrelation->action;?></th>
                <th class='w-100px text-left'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody>
              <?php $index = 1;?>
              <?php if($relations):?>
              <?php foreach($relations as $relation):?>
              <?php if($relation->buildin):?>
              <tr data-key='<?php echo $index;?>'>
                <td class='text-center'><?php echo zget($flows, $relation->next) . html::hidden("next[$index]", $relation->next);?></td>
                <td class='text-center'>
                  <div class='input-group'>
                    <?php echo html::hidden("field[$index]", 'id');?>
                    <?php echo html::hidden("newField[$index]", '');?>
                  </div>
                </td>
                <td class='text-center'>
                  <?php echo zget($lang->workflowrelation->relationActionList, $relation->actions) . "<div class='hidden'>" . html::checkbox("action[$index]", $lang->workflowrelation->relationActionList, $relation->actions, '', 'block') . "</div>";?>
                </td>
                <td>
                  <a href='javascript:;' class='btn addRelation'><i class='icon icon-plus'></i></a>
                  <a href='javascript:;' class='btn delRelation hidden'><i class='icon icon-close'></i></a>
                  <?php echo html::hidden("buildin[$index]", $relation->buildin);?>
                </td>
              </tr>
              <?php else:?>
              <tr data-key='<?php echo $index;?>'>
                <td><?php echo html::select("next[$index]", $flows, $relation->next, "class='form-control chosen'");?></td>
                <td>
                  <div class='input-group'>
                    <?php echo html::select("field[$index]", array($relation->field => $relation->field), $relation->field, "class='form-control chosen'");?>
                    <?php echo html::input("newField[$index]", '', "id='newField{$index}' class='form-control' placeholder='{$lang->workflowfield->placeholder->code}' style='display: none'");?>
                    <span class='input-group-addon'>
                      <div class='checkbox-primary'>
                        <input type='checkbox' value='1' name='createField[<?php echo $index;?>]' id='createField<?php echo $index;?>'>
                        <label for='createField<?php echo $index;?>'><?php echo $lang->workflowrelation->createForeignKey;?></label>
                      </div>
                    </span>
                  </div>
                </td>
                <td><?php echo html::checkbox("action[$index]", $lang->workflowrelation->relationActionList, $relation->actions, '', 'block');?></td>
                <td>
                  <a href='javascript:;' class='btn addRelation'><i class='icon icon-plus'></i></a>
                  <a href='javascript:;' class='btn delRelation'><i class='icon icon-close'></i></a>
                  <?php echo html::hidden("buildin[$index]", $relation->buildin);?>
                </td>
              </tr>
              <?php endif;?>
              <?php $index++;?>
              <?php endforeach;?>
              <?php else:?>
              <tr data-key='<?php echo $index;?>'>
                <td><?php echo html::select("next[$index]", $flows, $next, "class='form-control chosen'");?></td>
                <td>
                  <div class='input-group'>
                    <?php echo html::select("field[$index]", array('' => ''), '', "class='form-control chosen'");?>
                    <?php echo html::input("newField[$index]", '', "id='newField{$index}' class='form-control' placeholder='{$lang->workflowfield->placeholder->code}' style='display: none'");?>
                    <span class='input-group-addon'>
                      <div class='checkbox-primary'>
                        <input type='checkbox' value='1' name='createField[<?php echo $index;?>]' id='createField<?php echo $index;?>'>
                        <label for='createField<?php echo $index;?>'><?php echo $lang->workflowrelation->createForeignKey;?></label>
                      </div>
                    </span>
                  </div>
                </td>
                <td><?php echo html::checkbox("action[$index]", $lang->workflowrelation->relationActionList, '', '', 'block');?></td>
                <td>
                  <a href='javascript:;' class='btn addRelation'><i class='icon icon-plus'></i></a>
                  <a href='javascript:;' class='btn delRelation'><i class='icon icon-close'></i></a>
                  <?php echo html::hidden("buildin[$index]", '0');?>
                </td>
              </tr>
              <?php $index++;?>
              <?php endif;?>
              <tr>
                <td class='text-important' colspan='4'><?php echo $lang->workflowrelation->tips->foreignKey;?></td>
              </tr>
              <tr>
                <td class='form-actions' colspan='4'><?php echo html::submitButton();?></td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
echo html::select('relationTemplateNext', $flows, '', "class='form-control hidden'");

$next     = html::select('next[KEY]', array('' => ''), '', "class='form-control'");
$field    = html::select('field[KEY]', array('' => ''), '', "class='form-control'");
$newField = html::input('newField[KEY]', '', "id='newFieldKEY' class='form-control' placeholder='{$lang->workflowfield->placeholder->code}' style='display: none;'");
$action   = html::checkbox('action[KEY]', $lang->workflowrelation->relationActionList, '', '', 'block');
$buildin  = html::hidden('buildin[KEY]', '0');
$itemRow  = <<<EOT
<tr data-key='KEY'>
  <td>{$next}</td>
  <td>
    <div class='input-group'>
      {$field}
      {$newField}
      <span class='input-group-addon'>
        <div class='checkbox-primary'>
          <input type='checkbox' value='1' name='createField[KEY]' id='createFieldKEY'>
          <label for='createFieldKEY'>{$lang->workflowrelation->createForeignKey}</label>
        </div>
      </span>
    </div>
  </td>
  <td>{$action}</td>
  <td>
    <a href='javascript:;' class='btn addRelation'><i class='icon icon-plus'></i></a>
    <a href='javascript:;' class='btn delRelation'><i class='icon icon-close'></i></a>
    {$buildin}
  </td>
</tr>
EOT;
?>
<?php js::set('key', $index);?>
<?php js::set('itemRow', $itemRow);?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The tr view file of browse workflowaction module of ZDOO.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      LiuRuoGu <liuruogu@easycorp.ltd>
 * @package     workflowaction
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<tr data-id='<?php echo $action->id;?>' data-name='<?php echo $action->name;?>' data-action='<?php echo $action->action;?>' data-buildin='<?php echo $action->buildin;?>' data-extensiontype='<?php echo $action->extensionType;?>' data-open='<?php echo $action->open;?>' data-virtual='<?php echo $action->virtual;?>'>
  <?php if($sortable):?>
  <td class='<?php echo $canSort ? 'sort-handler' : '';?> text-center'>
    <?php if($canSort):?>
    <i class='icon icon-move text-muted'></i>
    <?php endif;?>
  </td>
  <?php endif;?>
  <td class='select-action'>
    <?php if($action->status != 'enable'):?>
    <span class='label label-default'><?php echo zget($lang->workflowaction->statusList, $action->status);?></span>
    <?php endif;?>
    <?php echo $action->name;?>
  </td>
  <td class='select-action'><?php echo $action->action;?></td>
  <?php if($flow->buildin):?>
  <td class='select-action text-center buildin<?php echo $action->buildin;?>'><?php echo $action->buildin ? "<i class='icon icon-check'></i>" : "<i class='icon icon-times'></i>";?></td>
  <td class='text-center'><?php if($action->buildin) echo zget($lang->workflowaction->extensionTypeList, $action->extensionType, '');?></td>
  <?php endif;?>
  <td class='actions'>
    <?php
    $isDefault = in_array($action->action, $config->workflowaction->defaultActions);

    $canSetCondition    = $this->workflowaction->isClickable($action, 'browseCondition');
    $canSetLayout       = $this->workflowaction->isClickable($action, 'admin');
    $canSetVerification = $this->workflowaction->isClickable($action, 'setVerification');
    $canSetHook         = $this->workflowaction->isClickable($action, 'browseHook');
    $canSetNotice       = $this->workflowaction->isClickable($action, 'setNotice');
    $canSetJS           = $this->workflowaction->isClickable($action, 'setJS');
    $canSetCSS          = $this->workflowaction->isClickable($action, 'setCSS');
    $canDelete          = $this->workflowaction->isClickable($action, 'delete');

    extCommonModel::printLink('workflowaction', 'edit', "id=$action->id", $lang->edit, "class='edit' data-toggle='modal' data-width='600'");

    if($canSetLayout)
    {
        echo baseHTML::a($this->createLink('workflowlayout', 'admin', "module=$action->module&action=$action->action"), $lang->workflowaction->layout, "class='layout' data-toggle='modal'");
    }
    else
    {
        echo baseHTML::a('javascript:;', $lang->workflowaction->layout, "class='layout disabled'");
    }

    if($canSetCondition)
    {
        echo baseHTML::a($this->createLink('workflowcondition', 'browse', "action=$action->id"), $lang->workflowaction->condition, "class='condition' data-toggle='modal'");
    }
    else
    {
        echo baseHTML::a('javascript:;', $lang->workflowaction->condition, "class='condition disabled'");
    }

    $moreItems = array();

    if($canSetVerification) $moreItems[] = baseHTML::a(inlink('setVerification', "id=$action->id"), $lang->workflowaction->setVerification, "class='verification' data-toggle='modal'");
    if($canSetHook)         $moreItems[] = baseHTML::a($this->createLink('workflowhook',    'browse', "action=$action->id"), $lang->workflowaction->hook,    "class='hook'    data-toggle='modal'");
    if($canSetNotice)       $moreItems[] = baseHTML::a(inlink('setNotice', "id=$action->id"), $lang->workflowaction->setNotice, "class='notice' data-toggle='modal'");
    if($canSetJS)           $moreItems[] = baseHTML::a(inlink('setJS',     "id=$action->id"), $lang->workflowaction->setJS,     "class='js'     data-toggle='modal'");
    if($canSetCSS)          $moreItems[] = baseHTML::a(inlink('setCSS',    "id=$action->id"), $lang->workflowaction->setCSS,    "class='css'    data-toggle='modal'");
    if($canDelete)          $moreItems[] = baseHTML::a(inlink('delete',    "id=$action->id"), $lang->delete, "class='deleter'");
    ?>
    <?php if($moreItems):?>
    <div class='dropdown'>
      <a href='javascript:;' data-toggle='dropdown' class='moreActions'><?php echo $lang->more;?><span class='caret'></span></a>
      <ul class='dropdown-menu pull-right'>
      <?php foreach($moreItems as $item) echo '<li>' . $item . '</li>';?>
      </ul>
    </div>
    <?php else:?>
    <a href='javascript:;' data-toggle='dropdown' class='moreActions disabled'><?php echo $lang->more;?><span class='caret'></span></a>
    <?php endif;?>
  </td>
</tr>

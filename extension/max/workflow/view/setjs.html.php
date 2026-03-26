<?php
/**
 * The set js view file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     workflow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php if($type == 'action'):?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/codeeditor.html.php';?>
<?php else:?>
<?php include '../../workflow/view/header.html.php';?>
<?php include '../../common/view/codeeditor.html.php';?>
<div class='space space-sm'></div>
<div class='main-row'>
  <div class='side-col'>
    <?php include '../../workflow/view/side.html.php';?>
  </div>
  <div class='main-col'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->workflow->setJS;?></strong>
      </div>
      <div class='panel-body'>
<?php endif;?>
        <form id='ajaxForm' method='post' action="<?php echo inlink('setJS', "id={$id}&type=$type");?>">
          <table class="table table-form">
            <tr><td><?php echo html::textarea('js', $js, "rows=5 class='form-control codeeditor' style='height:170px' data-mode='javascript'");?></td></tr>
            <tr>
              <td>
                <div class='form-actions'>
                  <?php echo html::submitButton();?>
                  <strong class='text-info'><?php echo $lang->workflow->tips->noJSTag . $lang->workflow->tips->{$type . 'JS'};?></strong>
                </div>
              </td>
            </tr>
          </table>
        </form>
<?php if($type != 'action'):?>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
<?php else:?>
<?php include '../../common/view/footer.modal.html.php';?>
<?php endif;?>

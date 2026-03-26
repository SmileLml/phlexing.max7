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
<?php include '../../workflow/view/header.html.php';?>
<div class='space space-sm'></div>
<div class='main-row'>
  <div class='side-col'>
    <?php include '../../workflow/view/side.html.php';?>
  </div>
  <div class='main-col'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->workflow->fullTextSearch->common;?></strong>
      </div>
      <div class='panel-body'>
        <form id='ajaxForm' method='post'>
          <table class="table table-form">
            <tr>
              <th><?php echo $lang->workflow->fullTextSearch->titleField;?></th>
              <td><?php echo html::select('titleField', $fields, $flow->titleField, "class='form-control chosen' data-placeholder='{$lang->workflow->placeholder->titleField}'");?></td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->workflow->fullTextSearch->contentField;?></th>
              <td><?php echo html::select('contentField[]', $fields, $flow->contentField, "class='form-control chosen' multiple data-placeholder='{$lang->workflow->placeholder->contentField}'");?></td>
              <td></td>
            </tr>
            <tr>
              <th></th>
              <td colspan='2'>
                <div class='alert alert-warning'><?php echo $lang->workflow->tips->fullTextSearch;?></div>
              </td>
            </tr>
            <tr id='resultTR' class='hide'>
              <th></th>
              <td colspan='2'>
                <ul id='resultBox'></ul>
              </td>
            </tr>
            <tr>
              <th></th>
              <td class='form-actions' colspan='2'>
                <?php echo html::submitButton();?>
                <?php if($flow->titleField) extCommonModel::printLink('workflow', 'buildIndex', "module={$flow->module}", $lang->workflow->buildIndex, "id='buildIndex' class='btn btn-secondary' data-loading='{$lang->loading}' data-confirm='{$lang->workflow->tips->buildIndex}'");?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

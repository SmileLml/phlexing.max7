<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include $app->getModuleRoot() . 'common/view/header.html.php';
include $app->getModuleRoot() . 'common/view/kindeditor.html.php';
?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $feedback->id;?></span>
        <?php echo isonlybody() ? ('<span title="' . $feedback->title . '">' . $feedback->title . '</span>') : html::a($this->createLink('feedback', 'view', 'feedback=' . $feedback->id), $feedback->title);?>

        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->feedback->assign;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->feedback->assignedTo;?></th>
          <td class='w-p25-f'><?php echo html::select('assignedTo', $users, $feedback->assignedTo, "class='form-control chosen'");?></td><td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->feedback->status;?></th>
          <td><?php echo html::hidden('status', $feedback->status);?></td>
        </tr>
        <?php $this->printExtendFields($feedback, 'table');?>
        <tr>
          <th><?php echo $lang->feedback->mailto;?></th>
          <td colspan='2'>
            <div class="input-group">
            <?php echo html::select('mailto[]', $users, str_replace(' ', '', $feedback->mailto), 'class="form-control chosen" multiple');?>
            <?php echo $this->fetch('my', 'buildContactLists');?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->server->http_referer, 'self', '', 'btn btn-wide');?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

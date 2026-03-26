<?php
/**
 * The activate file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     feedback
 * @version     $Id: start.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $feedback->id;?></span>
        <?php echo isonlybody() ? ("<span title='$feedback->title'>" . $feedback->title . '</span>') : html::a($this->createLink('feedback', 'view', 'feedbackID=' . $feedback->id), $feedback->title);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . $lang->feedback->activate;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->feedback->assignedTo;?></th>
          <td class='w-p35-f'>
            <div class="input-group">
              <?php echo html::select('assignedTo', $users, $assignedTo, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
            <th><?php echo $lang->comment?></th>
            <td colspan='3'><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
           <?php echo html::submitButton($lang->feedback->activate);?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>

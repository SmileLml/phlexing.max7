<?php
/**
 * The start view file of marketresearch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Deqing Chai <chaideqing@easycorp.ltd>
 * @package     marketresearch
 * @version     $Id: start.html.php 4769 2023-09-06 07:24:21Z
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $research->id;?></strong></span>
      <?php echo isonlybody() ? ("<span title='$research->name'>" . $research->name . '</span>') : html::a($this->createLink('research', 'view', 'researchID=' . $research->id), $research->name, '_blank');?>
      <?php if(!isonlybody()):?>
      <small><?php echo $lang->arrow . $lang->marketresearch->start;?></small>
      <?php endif;?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th class='w-100px'><?php echo $lang->project->realBegan;?></th>
          <td><?php echo html::input('realBegan', helper::today(), "class='form-control form-date' required");?></td>
          <td></td>
        </tr>
        <?php $this->printExtendFields($research, 'table', 'columns=3');?>
        <tr>
          <th class='w-40px'><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton($lang->marketresearch->start);?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
  <hr class='small' />
  <div class='main'>
    <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The retract view file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Deqing Chai <chaideqing@cnezsoft.com>
 * @package     story
 * @version     $Id: close.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo isonlybody() ? ("<span title='$story->title'>" . $story->title . '</span>') : html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?>
        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->demand->retract;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form class="load-indicator main-form" method='post' target='hiddenwin' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->demand->retractedReason;?></th>
          <td class='w-p25-f required'><?php echo html::select('retractedReason', $lang->demand->retractedReasonList, '', 'class="form-control"');?></td>
          <td>&nbsp;&nbsp;
            <i class="icon icon-exclamation text-warning"></i>
            &nbsp;<?php echo $lang->demand->retractedReasonTips;?>
          </td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->story->status;?></th>
          <td><?php echo html::hidden('status', 'closed');?></td>
        </tr>
        <?php $this->printExtendFields($story, 'table');?>
        <tr>
          <th><?php echo $lang->story->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->story->checkAffection;?></th>
          <td colspan='2'><?php include './affected.html.php';?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The close file of marketresearch module of ZenTaoPMS.
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
      <?php echo isonlybody() ? ("<span title='$research->name'>" . $research->name . '</span>') : html::a($this->createLink('marketresearch', 'view', 'researchID=' . $research->id), $research->name, '_blank');?>
      <?php if(!isonlybody()):?>
      <small> <?php echo $lang->arrow . $lang->marketresearch->close;?></small>
      <?php endif;?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tbody>
        <tr class='hide'>
          <th class='w-50px'><?php echo $lang->marketresearch->status;?></th>
          <td><?php echo html::hidden('status', 'closed');?></td>
        </tr>
        <?php $this->printExtendFields($research, 'table');?>
        <tr>
          <th><?php echo $lang->project->realEnd;?></th>
          <td>
            <div>
              <?php echo html::input('realEnd',(!helper::isZeroDate($research->realEnd) ? $research->realEnd : date('Y-m-d')), "class='form-control form-date'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->marketresearch->closedReason;?></th>
          <td><?php echo html::select('closedReason', $lang->marketresearch->reasonList, 'finished', 'class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='4'><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='5'><?php echo html::submitButton($lang->marketresearch->close) . html::linkButton($lang->goback, $this->session->marketresearchList, 'self', '', 'btn btn-wide'); ?></td>
        </tr>
      </tbody>
    </table>
  </form>
   <hr class='small' />
  <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The complete file of ticket module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     ticket
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $ticket->id;?></span>
        <?php echo isonlybody() ? ("<span title='$ticket->title'>" . $ticket->title . '</span>') : html::a($this->createLink('ticket', 'view', 'tickedID=' . $ticket->id), $ticket->title);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . $lang->ticket->finish;?></small>
        <?php endif;?>
      </div>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->ticket->hasConsumed;?></th>
          <td class='w-p25-f'><?php echo $ticket->consumed;?> <?php echo $lang->workingHour;?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->ticket->currentConsumed;?></th>
          <td>
            <div class='input-group'><?php echo html::input('currentConsumed', 0, "class='form-control'");?> <span class='input-group-addon'><?php echo $lang->ticket->hour;?></span></div>
          </td>
          <td>
            <div class='table-row'>
              <div class='table-col strong w-80px text-right' style='padding-right:10px'><?php echo $lang->ticket->consumed;?> </div>
              <div class='table-col'>
                <?php
                echo "<span id='totalConsumed'>" . (float)$ticket->consumed . "</span> " . $lang->workingHour . html::hidden('consumed', $ticket->consumed);
                js::set('consumed', $ticket->consumed);
                ?>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->ticket->resolvedBy;?></th>
          <td><?php echo html::select('resolvedBy', $users, $app->user->account, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->ticket->resolvedDate;?></th>
          <td><div class='datepicker-wrapper datepicker-date'><?php echo html::input('resolvedDate', helper::now(), "class='form-control form-date'");?></div></td>
        </tr>
        <?php $this->printExtendFields($ticket, 'table');?>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildoldform');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->ticket->resolution;?></th>
          <td colspan='2'><?php echo html::textarea('resolution', '', "rows='5' class='w-p100'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='5' class='w-p100'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton($lang->ticket->finish);?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

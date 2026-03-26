<?php
/**
 * The view mobile view file of leave module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     leave 
 * @version     $Id
 * @link        http://www.zdoo.com
 */

if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}

$moduleMenu = false;
$bodyClass  = 'with-nav-bottom';
$browseLink = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : inlink('personal');

include "../../common/view/m.header.html.php";
?>

<div id='page' class='list-with-actions'>
  <div class='section'>
    <div class='heading gray'>
      <div class='title'><i class='icon-calendar'> </i><?php echo $lang->leave->view;?></div>
      <nav class='nav'><a class='btn primary' href='<?php echo $browseLink;?>'><?php echo $lang->goback;?></a></nav>
    </div>
    <div class='box'>
      <table class='table bordered table-detail'>
        <tr>
          <td class='w-80px'><?php echo $lang->leave->createdBy;?></td>
          <td><?php echo zget($users, $leave->createdBy);?></td>
        </tr>
        <tr>
          <td><?php echo $lang->leave->type;?></td>
          <td><?php echo zget($lang->leave->typeList, $leave->type);?></td>
        </tr>
        <tr>
          <td><?php echo $lang->leave->status;?></td>
          <td><span class='label status-<?php echo $leave->status;?>-pale'><?php echo zget($lang->leave->statusList, $leave->status);?></span></td>
        </tr>
        <tr>
          <td><?php echo $lang->leave->begin;?></td>
          <td><?php echo $leave->begin . ' ' . $leave->start;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->leave->end;?></td>
          <td><?php echo $leave->end . ' ' . $leave->finish;?></td>
        </tr>
        <?php if(!empty($leave->backDate)):?>
        <tr>
          <td><?php echo $lang->leave->backDate;?></td>
          <td><?php echo formatTime($leave->backDate);?></td>
        </tr>
        <?php endif;?>
        <?php if(!empty($leave->hours)):?>
        <tr>
          <td><?php echo $lang->leave->hours;?></td>
          <td><?php echo $leave->hours;?></td>
        </tr>
        <?php endif;?>
        <?php if(!empty($leave->desc)):?>
        <tr>
          <td><?php echo $lang->leave->desc;?></td>
          <td><?php echo $leave->desc;?></td>
        </tr>
        <?php endif;?>
        <?php if(!empty($leave->reviewedBy)):?>
        <tr>
          <td><?php echo $lang->leave->reviewedBy;?></td>
          <td><?php echo zget($users, $leave->reviewedBy);?></td>
        </tr>
        <?php endif;?>
      </table>
    </div>
  </div>

  <div class='section' id='history'>
  </div>

  <nav class='nav affix dock-bottom nav-auto footer-actions'>
    <?php
    if($type == 'personal')
    {
        $canBack   = $this->leave->isClickable($leave, 'back');
        $canEdit   = $this->leave->isClickable($leave, 'edit');
        $canDelete = $this->leave->isClickable($leave, 'delete');
        $canSwitch = $this->leave->isClickable($leave, 'switchStatus');
    
        if($canBack) echo "<a data-remote='" . $this->createLink('leave', 'back', "id={$leave->id}") . "' data-display='modal' data-placement='bottom'>{$lang->leave->back}</a>";
        if($canSwitch)
        {
            $switchLabel = $leave->status == 'wait' ? $lang->leave->cancel : $lang->leave->submit;
            echo "<a data-remote='" . $this->createLink('leave', 'switchstatus', "id={$leave->id}") . "' data-display='ajaxAction' data-locate='self'>{$switchLabel}</a>";
        }
        if($canEdit)   echo "<a data-remote='" . $this->createLink('leave', 'edit',   "id={$leave->id}") . "' data-display='modal' data-placement='bottom'>{$lang->edit}</a>";
        if($canDelete) echo "<a data-remote='" . $this->createLink('leave', 'delete', "id={$leave->id}") . "' data-display='ajaxAction' data-ajax-delete='true' data-locate='{$this->createLink("leave", "personal")}'>{$lang->delete}</a>";
    }
    else
    {
        $canReview = $this->leave->isClickable($leave, 'review');
    
        if($canReview)
        {
            $params = $leave->status == 'back' ? '&mode=back' : '';
            echo "<a data-remote='" . $this->createLink('leave', 'review', "id={$leave->id}&status=pass$params")   . "' data-display='ajaxAction' data-confirm='{$lang->leave->confirmReview['pass']}' data-locate='self'>{$lang->leave->statusList['pass']}</a>";
            echo "<a data-remote='" . $this->createLink('leave', 'review', "id={$leave->id}&status=reject$params") . "' data-display='modal' data-placement='bottom'>{$lang->leave->statusList['reject']}</a>";
        }
    }
    ?>
  </nav>
</div>
<?php include "../../common/view/m.footer.html.php"; ?>

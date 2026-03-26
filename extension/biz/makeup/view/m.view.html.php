<?php
/**
 * The view mobile view file of makeup module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     makeup 
 * @version     $Id
 * @link        http://www.zdoo.com
 */

if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}

$moduleMenu = false;
$bodyClass  = 'with-nav-bottom';
$browseLink = $this->session->makeupList ? $this->session->makeupList : inlink('personal');

include "../../common/view/m.header.html.php";
?>

<div id='page' class='list-with-actions'>
  <div class='section'>
    <div class='heading gray'>
      <div class='title'><i class='icon-calendar'> </i><?php echo $lang->makeup->view;?></div>
      <nav class='nav'><a class='btn primary' href='<?php echo $browseLink;?>'><?php echo $lang->goback ?></a></nav>
    </div>
    <div class='box'>
      <table class='table bordered table-detail'>
        <tr>
          <td class='w-80px'><?php echo $lang->makeup->createdBy;?></td>
          <td><?php echo zget($users, $makeup->createdBy);?></td>
        </tr>
        <tr>
          <td><?php echo $lang->makeup->status;?></td>
          <td><span class='label status-<?php echo $makeup->status;?>-pale'><?php echo zget($lang->makeup->statusList, $makeup->status);?></span></td>
        </tr>
        <tr>
          <td><?php echo $lang->makeup->begin;?></td>
          <td><?php echo $makeup->begin . ' ' . $makeup->start;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->makeup->end;?></td>
          <td><?php echo $makeup->end . ' ' . $makeup->finish;?></td>
        </tr>
        <?php if($makeup->leave):?>
        <tr>
          <td><?php echo $lang->makeup->leave;?></th>
          <td>
            <?php foreach(explode(',', trim($makeup->leave, ',')) as $leave):?>
            <?php if($leave) echo zget($leaves, $leave) . '<br/>';?>
            <?php endforeach;?>
          </td>
        </tr>
        <?php endif;?>
        <?php if(!empty($makeup->hours)):?>
        <tr>
          <td><?php echo $lang->makeup->hours;?></td>
          <td><?php echo $makeup->hours;?></td>
        </tr>
        <?php endif;?>
        <?php if(!empty($makeup->desc)):?>
        <tr>
          <td><?php echo $lang->makeup->desc;?></td>
          <td><?php echo $makeup->desc;?></td>
        </tr>
        <?php endif;?>
        <?php if(!empty($makeup->reviewedBy)):?>
        <tr>
          <td><?php echo $lang->makeup->reviewedBy;?></td>
          <td><?php echo zget($users, $makeup->reviewedBy);?></td>
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
        $canEdit   = $this->makeup->isClickable($makeup, 'edit');
        $canDelete = $this->makeup->isClickable($makeup, 'delete');
        $canSwitch = $this->makeup->isClickable($makeup, 'switchStatus');
    
        if($canSwitch)
        {
            $switchLabel = $makeup->status == 'wait' ? $lang->makeup->cancel : $lang->makeup->submit;
            echo "<a data-remote='" . $this->createLink('makeup', 'switchstatus', "id={$makeup->id}") . "' data-display='ajaxAction' data-locate='self'>{$switchLabel}</a>";
        }
        if($canEdit)   echo "<a data-remote='" . $this->createLink('makeup', 'edit',   "id={$makeup->id}") . "' data-display='modal' data-placement='bottom'>{$lang->edit}</a>";
        if($canDelete) echo "<a data-remote='" . $this->createLink('makeup', 'delete', "id={$makeup->id}") . "' data-display='ajaxAction' data-ajax-delete='true' data-locate='self'>{$lang->delete}</a>";
    }
    else
    {
        $canReview = $this->makeup->isClickable($makeup, 'review');
    
        if($canReview) echo "<a data-remote='" . $this->createLink('makeup', 'review', "id={$makeup->id}&status=pass")   . "' data-display='ajaxAction' data-confirm='{$lang->makeup->confirmReview['pass']}' data-locate='self'>{$lang->makeup->statusList['pass']}</a>";
        if($canReview) echo "<a data-remote='" . $this->createLink('makeup', 'review', "id={$makeup->id}&status=reject") . "' data-display='modal' data-placement='bottom'>{$lang->makeup->statusList['reject']}</a>";
    }
    ?>
  </nav>
</div>
<?php include "../../common/view/m.footer.html.php"; ?>

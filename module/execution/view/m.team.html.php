<?php
/**
 * The team browse mobile view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     execution
 * @version     $Id
 * @link        http://www.zentao.net
 */

include "../../common/view/m.header.html.php";
?>

<div class='heading'>
  <div class='title'>
    <strong><?php echo $lang->execution->team;?></strong>
  </div>
</div>

<section id='page' class='section list-with-pager'>
  <table class='table bordered'>
    <thead>
      <tr>
        <th><?php echo $lang->team->account;?></th>
        <th class='text-center w-140px'><?php echo $lang->team->join;?></th>
        <th class='text-center'><?php echo $lang->team->role;?></th>
        <th class='text-center'><?php echo $lang->team->totalHours;?></th>
      </tr>
    </thead>
    <?php $totalHours = 0;?>
    <?php foreach($teamMembers as $member):?>
    <tr class= 'text-center'>
      <?php $memberHours = $member->days * $member->hours;?>
      <?php $totalHours  += $memberHours;?>
      <td class='text-left'><?php echo $member->realname;?></td>
      <td><?php echo substr($member->join, 2);?></td>
      <td><?php echo $member->role;?></td>
      <td><?php echo $memberHours . $lang->execution->workHour;?></td>
    </tr>
    <?php endforeach;?>
    <tr>
      <td colspan='4'><?php echo $lang->team->totalHours . '：' .  "<strong>$totalHours{$lang->execution->workHour}</strong>";?></td>
    </tr>
  </table>
</section>

<?php include "../../common/view/m.footer.html.php"; ?>

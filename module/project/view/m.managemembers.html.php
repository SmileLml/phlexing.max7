<?php
/**
 * The build browse mobile view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        http://www.zentao.net
 */

include "../../common/view/m.header.html.php";
?>

<div class='heading'>
  <div class='title'>
    <strong><?php echo $lang->project->manageMembers;?></strong>
  </div>
</div>

<section id='page' class='section list-with-pager'>
  <div class='box'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th><?php echo $lang->team->account;?></th>
          <th><?php echo $lang->team->role;?></th>
          <th class='w-80px'><?php echo $lang->team->days;?></th>
          <th class='w-100px'><?php echo $lang->team->hours;?></th>
          <th class='w-80px'><?php echo $lang->team->limited;?></th>
        </tr>
      </thead>
      <?php foreach($currentMembers as $member):?>
      <?php if(!isset($users[$member->account])) continue;?>
      <tr class= 'text-center'>
        <td class='text-left'><?php echo $member->realname;?></td>
        <td class='text-left'><?php echo $member->role;?></td>
        <td><?php echo $member->days;?></td>
        <td><?php echo $member->hours;?></td>
        <td><?php echo zget($lang->team->limitedList, $member->limited, '');?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>
</section>
<?php include "../../common/view/m.footer.html.php"; ?>

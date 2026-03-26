<?php
/**
 * The task browse mobile view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     execution
 * @version     $Id
 * @link        http://www.zentao.net
 */

$bodyClass = 'with-menu-top';
include "../../common/view/m.header.html.php";
?>

<nav id='menu' class='menu nav affix dock-top canvas'>
  <?php echo html::a($this->createLink('personnel', 'invest',     "programID=$programID"), $lang->personnel->label->invest,     '', "class='" . ($app->getMethodName() == 'invest'     ? 'active' : '') . "'");?>
  <?php echo html::a($this->createLink('personnel', 'accessible', "programID=$programID"), $lang->personnel->label->accessible, '', "class='" . ($app->getMethodName() == 'accessible' ? 'active' : '') . "'");?>
  <?php echo html::a($this->createLink('personnel', 'whitelist',  "programID=$programID"), $lang->personnel->label->whitelist,  '', "class='" . ($app->getMethodName() == 'whitelist'  ? 'active' : '') . "'");?>
  <a class='moreMenu hidden' data-display='dropdown' data-placement='beside-bottom'><?php echo $lang->more;?></a>
  <div id='moreMenu' class='list dropdown-menu'></div>
</nav>

<section id='page' class='section list-with-pager'>
  <?php $refreshUrl = $this->createLink('personnel', 'invest', "programID=$programID");?>
  <div class='box' data-refresh-url='<?php echo $refreshUrl;?>'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th class='text-center w-90px'><?php echo $lang->personnel->name;?> </th>
          <th class='text-center w-120px'><?php echo $lang->personnel->role;?> </th>
          <th class='text-center w-80px'><?php echo $lang->personnel->projects;?> </th>
          <th class='text-center w-80px'><?php echo $lang->personnel->executions;?> </th>
          <th class='text-center w-80px'><?php echo $lang->personnel->invest;?> </th>
          <th class='text-center w-80px'><?php echo $lang->personnel->left;?> </th>
        </tr>
      </thead>
      <?php foreach($investList as $personnel):?>
      <tr class='text-center'>
        <td><?php echo $personnel['realname'];?></td>
        <td><?php echo $personnel['role'];?></td>
        <td><?php echo $personnel['projects'];?></td>
        <td><?php echo $personnel['executions'];?></td>
        <td><?php echo round($personnel['consumedTask'], 1);?></td>
        <td><?php echo round($personnel['leftTask'], 1);?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>

</section>
<?php include "../../common/view/m.footer.html.php"; ?>

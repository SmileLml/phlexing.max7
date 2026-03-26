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
  <?php $refreshUrl = $this->createLink('personnel', 'accessible', "programID=$programID&deptID=0&browseType=$browseType&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");?>
  <div class='box' data-page='<?php echo $pager->pageID;?>' data-refresh-url='<?php echo $refreshUrl;?>'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th class='text-center'><?php echo $lang->personnel->department;?> </th>
          <th class='text-center w-80px'><?php echo $lang->personnel->realName;?> </th>
          <th class='text-center w-120px'><?php echo $lang->personnel->userName;?> </th>
          <th class='text-center w-80px'><?php echo $lang->personnel->job;?> </th>
          <th class='text-center w-50px'><?php echo $lang->personnel->genders;?> </th>
        </tr>
      </thead>
      <?php foreach($personnelList as $personnel):?>
      <tr class='text-center'>
        <td class='text-left'><?php echo zget($deptList, $personnel->dept);?></td>
        <td><?php echo $personnel->realname;?></td>
        <td class='text-left'><?php echo $personnel->account;?></td>
        <td><?php echo zget($lang->user->roleList, $personnel->role, '');?></td>
        <td><?php echo zget($lang->user->genderList, $personnel->gender, '');?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>

  <nav class='nav justify pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
</section>
<?php include "../../common/view/m.footer.html.php"; ?>

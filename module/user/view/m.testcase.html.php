<?php
/**
 * The testcase mobile view file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     user
 * @version     $Id: index.html.php 3830 2016-05-18 09:34:17Z liugang $
 * @link        http://www.zentao.net
 */
?>

<?php
$bodyClass = 'with-menu-top';
include "../../common/view/m.header.html.php";
include "./m.featurebar.html.php";
?>

<nav id='subMenu' class='menu nav gray'>
  <?php
  $that = zget($lang->user->thirdPerson, $user->gender);
  echo html::a($this->createLink('user', 'testtask', "userID={$user->id}"),                sprintf($lang->user->testTask2Him, $that));
  echo html::a($this->createLink('user', 'testcase', "userID={$user->id}&type=case2Him"),  sprintf($lang->user->case2Him, $that));
  echo html::a($this->createLink('user', 'testcase', "userID={$user->id}&type=caseByHim"), sprintf($lang->user->caseByHim, $that));
  ?>
  <a class='moreSubMenu hidden' data-display='dropdown' data-placement='beside-bottom'><?php echo $lang->more;?></a>
  <div id='moreSubMenu' class='list dropdown-menu'></div>
</nav>

<section id='page' class='section list-with-pager'>
  <div class='box' data-page='<?php echo $pager->pageID ?>' data-refresh-url='<?php echo $this->createLink('user', 'testcase', "userID={$user->id}&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"); ?>'>
    <table class='table bordered'>
      <thead>
        <tr>
          <th><?php echo $lang->testcase->title;?> </th>
          <th class='text-center w-80px'><?php echo $lang->testcase->type;?> </th>
          <th class='text-center w-80px'><?php echo $lang->testcase->status;?> </th>
        </tr>
      </thead>
      <?php foreach($cases as $case):?>
      <tr class='text-center' data-url='<?php echo $this->createLink('testcase', 'view', "caseID={$case->id}");?>' data-id='<?php echo $case->id;?>'>
        <td class='text-left'><?php echo $case->title;?></td>
        <td><?php echo zget($lang->testcase->typeList, $case->type);?></td>
        <td class='testcase-<?php echo $case->status?>'><?php echo zget($lang->testcase->statusList, $case->status);?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>

  <nav class='nav justify pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
</section>

<script>
$('#testtaskTab').addClass('active');
$('#subMenu > a').removeClass('active').filter('[href*="<?php echo $type?>"]').addClass('active');
</script>
<?php include "../../common/view/m.footer.html.php"; ?>

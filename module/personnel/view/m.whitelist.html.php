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

if($this->app->rawModule == 'personnel') $bodyClass = 'with-menu-top';
include "../../common/view/m.header.html.php";
?>

<?php if($this->app->rawModule == 'personnel'):?>
<nav id='menu' class='menu nav affix dock-top canvas'>
  <?php echo html::a($this->createLink('personnel', 'invest',     "objectID=$objectID"), $lang->personnel->label->invest,     '', "class='" . ($app->getMethodName() == 'invest'     ? 'active' : '') . "'");?>
  <?php echo html::a($this->createLink('personnel', 'accessible', "objectID=$objectID"), $lang->personnel->label->accessible, '', "class='" . ($app->getMethodName() == 'accessible' ? 'active' : '') . "'");?>
  <?php echo html::a($this->createLink('personnel', 'whitelist',  "objectID=$objectID"), $lang->personnel->label->whitelist,  '', "class='" . ($app->getMethodName() == 'whitelist'  ? 'active' : '') . "'");?>
  <a class='moreMenu hidden' data-display='dropdown' data-placement='beside-bottom'><?php echo $lang->more;?></a>
  <div id='moreMenu' class='list dropdown-menu'></div>
</nav>
<?php endif;?>

<section id='page' class='section list-with-pager'>
  <?php $refreshUrl = $this->createLink('personnel', 'whitelist', "objectID=$objectID&module=$module&objectType=program&orderBy=id_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");?>
  <div class='box' data-page='<?php echo $pager->pageID;?>' data-refresh-url='<?php echo $refreshUrl;?>'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th class='text-center w-80px'> <?php echo $lang->user->realname;?> </th>
          <th class='text-center'>        <?php echo $lang->user->dept;?> </th>
          <th class='text-center w-120px'><?php echo $lang->user->role;?> </th>
          <th class='text-center w-100px'> <?php echo $lang->user->qq;?> </th>
          <th class='text-center w-120px'><?php echo $lang->user->email;?> </th>
        </tr>
      </thead>
      <?php foreach($whitelist as $user):?>
      <tr class='text-center'>
        <td><?php echo $user->realname;?></td>
        <td class='text-left'><?php echo zget($depts, $user->dept);?></td>
        <td><?php echo zget($lang->user->roleList, $user->role, '');?></td>
        <td class='text-left'><?php echo $user->qq;?></td>
        <td class='text-left'><?php echo $user->email;?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>

  <nav class='nav justify pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
</section>
<?php include "../../common/view/m.footer.html.php"; ?>

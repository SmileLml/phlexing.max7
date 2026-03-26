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

include "../../common/view/m.header.html.php";
?>

<section id='page' class='section list-with-pager'>
  <?php $refreshUrl = $this->createLink('program', 'stakeholder', "programID=$programID&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");?>
  <div class='box' data-page='<?php echo $pager->pageID;?>' data-refresh-url='<?php echo $refreshUrl;?>'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th class='text-center w-80px'> <?php echo $lang->user->realname;?> </th>
          <th class='text-center w-80px'> <?php echo $lang->program->stakeholderType;?> </th>
          <th class='text-center w-120px'><?php echo $lang->user->role;?> </th>
          <th class='text-center w-80px'> <?php echo $lang->user->qq;?> </th>
          <th class='text-center w-120px'> <?php echo $lang->user->email;?> </th>
        </tr>
      </thead>
      <?php foreach($stakeholders as $stakeholder):?>
      <tr class='text-center'>
        <td><?php echo $stakeholder->realname;?></td>
        <td class='text-left'><?php echo zget($lang->program->stakeholderTypeList, $stakeholder->type, '');?></td>
        <td class='text-left'><?php echo zget($lang->user->roleList, $stakeholder->role, '');?></td>
        <td><?php echo $stakeholder->qq;?></td>
        <td><?php echo $stakeholder->email;?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>

  <nav class='nav justify pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
</section>
<?php include "../../common/view/m.footer.html.php"; ?>

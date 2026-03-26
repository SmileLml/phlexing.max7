<?php
/**
 * The batchcreate of cmcl module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     cmcl
 * @version     $Id: batchcreate.html.php 4903 2020-09-04 09:13:59Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->cmcl->batchCreate;?></h2>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr class='text-center'>
          <th class='w-50px'><?php echo $lang->cmcl->id;?></th>
          <th class='w-200px required'><?php echo $lang->cmcl->type;?></th>
          <th class='w-300px required'><?php echo $lang->cmcl->title;?></th>
          <th><?php echo $lang->cmcl->contents;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::select("type[$i]", $lang->cmcl->typeList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("title[$i]", $lang->cmcl->titleList, '',  "class='form-control chosen'");?></td>
          <td><?php echo html::input("contents[$i]", '', "class='form-control'");?></td>
        </tr>
        <?php endfor;?>
        <tr>
          <td colspan='4' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

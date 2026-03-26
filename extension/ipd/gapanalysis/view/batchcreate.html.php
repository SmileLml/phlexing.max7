<?php
/**
 * The batchcreate of gapanalysis module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     gapanalysis
 * @version     $Id: batchcreate.html.php 4903 2021-06-10 15:52:59Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('roles', $rolePairs);?>
<?php js::set('errorSameAccount', $lang->gapanalysis->errorSameAccount);?>
<style>
#dataform .table-form textarea {height: 32px;}
</style>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->gapanalysis->batchCreate;?></h2>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->gapanalysis->id;?></th>
          <th class='required'><?php echo $lang->gapanalysis->account;?></th>
          <th ><?php echo $lang->gapanalysis->role;?></th>
          <th ><?php echo $lang->gapanalysis->analysis;?></th>
          <th ><?php echo $lang->gapanalysis->needTrain;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::select("account[$i]", $members, '',  "class='form-control chosen'");?></td>
          <td><?php echo html::input("role[$i]", '', "class='form-control' readonly");?></td>
          <td><?php echo html::textarea("analysis[$i]", '', "row='1' class='form-control'");?></td>
          <td><?php echo html::radio("needTrain[$i]", $lang->gapanalysis->needTrainList, 'no');?></td>
        </tr>
        <?php endfor;?>
        <tr>
          <td colspan='5' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

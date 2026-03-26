<?php
/**
 * The batchcreate of trainplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     trainplan
 * @version     $Id: batchcreate.html.php 4903 2021-06-09 13:50:59Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->trainplan->batchCreate;?></h2>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr>
            <th class='w-50px'><?php echo $lang->trainplan->id;?></th>
            <th class='w-230px required'><?php echo $lang->trainplan->name;?></th>
            <th class='w-120px'><?php echo $lang->trainplan->begin;?></th>
            <th class='w-120px'><?php echo $lang->trainplan->end;?></th>
            <th class='w-200px'><?php echo $lang->trainplan->place;?></th>
            <th class='w-200px'><?php echo $lang->trainplan->trainee;?></th>
            <th class='w-200px'><?php echo $lang->trainplan->lecturer;?></th>
            <th class='w-200px'><?php echo $lang->trainplan->type;?></th>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 1; $i <= 10; $i ++):?>
          <tr>
            <td><?php echo $i;?></td>
            <td><?php echo html::input("name[$i]", '',  "class='form-control'");?></td>
            <td><?php echo html::input("begin[$i]", '', "class='form-control form-date'");?></td>
            <td><?php echo html::input("end[$i]", '', "class='form-control form-date'");?></td>
            <td><?php echo html::input("place[$i]", '',  "class='form-control'");?></td>
            <td><?php echo html::select("trainee[$i][]", $members,  '',  "class='form-control chosen' multiple");?></td>
            <td><?php echo html::input("lecturer[$i]", '',  "class='form-control'");?></td>
            <td><?php echo html::radio("type[$i]", $lang->trainplan->typeList, 'inside');?></td>
          </tr>
          <?php endfor;?>
          <tr>
            <td colspan='8' class='form-actions text-center'>
              <?php echo html::submitButton() . html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

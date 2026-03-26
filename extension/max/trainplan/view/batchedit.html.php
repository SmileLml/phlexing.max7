<?php
/**
 * The batchedit of trainplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     trainplan
 * @version     $Id: batchedit.html.php 4903 2021-06-09 16:07:59Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->trainplan->batchEdit;?></h2>
  </div>
  <form class="main-form" method='post' id='dataform' target="hiddenwin" action="<?php echo inLink('batchEdit', "projectID=$projectID");?>">
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
          <?php foreach($trainplans as $trainplanID => $trainplan):?>
          <tr>
            <td><?php echo $trainplanID . html::hidden("trainplanIDList[$trainplanID]", $trainplanID);?></td>
            <td><?php echo html::input("name[$trainplanID]", $trainplan->name,  "class='form-control'");?></td>
            <td><?php echo html::input("begin[$trainplanID]", helper::isZeroDate($trainplan->begin) ? '' : $trainplan->begin, "class='form-control form-date'");?></td>
            <td><?php echo html::input("end[$trainplanID]", helper::isZeroDate($trainplan->end) ? '' : $trainplan->end, "class='form-control form-date'");?></td>
            <td><?php echo html::input("place[$trainplanID]", $trainplan->place,  "class='form-control'");?></td>
            <td><?php echo html::select("trainee[$trainplanID][]", $members, $trainplan->trainee, "class='form-control chosen' multiple");?></td>
            <td><?php echo html::input("lecturer[$trainplanID]", $trainplan->lecturer,  "class='form-control'");?></td>
            <td><?php echo html::radio("type[$trainplanID]", $lang->trainplan->typeList, $trainplan->type);?></td>
          </tr>
          <?php endforeach;?>
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

<?php
/**
 * The batch create view of zoutput module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     zoutput
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade in">
  <div class="main-header">
    <h2><?php echo $lang->zoutput->batchCreate;?></h2>
  </div>
  <form id="batchCreateForm" target="hiddenwin" method="post">
    <table class="table table-form">
      <thead>
        <tr class="text-center">
          <th class="w-50px"><?php echo $lang->zoutput->id;?></th>
          <th class="w-220px required"><?php echo $lang->zoutput->activity;?></th>
          <th class="required"><?php echo $lang->zoutput->name;?></th>
          <th class="w-160px text-left"><?php echo $lang->zoutput->optional;?></th>
          <th class='w-400px'><?php echo $lang->zoutput->tailorNorm;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i++):?>
          <tr data-key="<?php echo $i;?>">
            <td><?php echo $i;?></td>
            <td><?php echo html::select("dataList[$i][activity]", $activity, $activityID, 'class="form-control chosen"')?></td>
            <td><?php echo html::input("dataList[$i][name]", '', 'class="form-control" autocomplete="off"')?></td>
            <td><?php echo html::radio("dataList[$i][optional]", $lang->zoutput->optionalList, 'yes');?></td>
            <td><?php echo html::input("dataList[$i][tailorNorm]", '', 'class="form-control"')?></td>
          </tr>
        <?php endfor;?>
      </tbody>
    </table>
    <div class="form-actions text-center">
      <?php echo html::submitButton() . html::backButton();?>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

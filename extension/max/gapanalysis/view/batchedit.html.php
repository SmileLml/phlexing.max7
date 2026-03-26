<?php
/**
 * The batchedit of gapanalysis module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     gapanalysis
 * @version     $Id: batchedit.html.php 4903 2021-06-11 13:14:59Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->gapanalysis->batchEdit;?></h2>
  </div>
  <form class="load-indicator main-form" method='post' target='hiddenwin' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->gapanalysis->id;?></th>
          <th class='required' style="width: 100%"><?php echo $lang->gapanalysis->account;?></th>
          <th class='c-role'><?php echo $lang->gapanalysis->role;?></th>
          <th class='c-needtrain'><?php echo $lang->gapanalysis->needTrain;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($gapanalysises as $gapanalysisID => $gapanalysis):?>
        <tr>
          <td><?php echo $gapanalysisID . html::hidden("gapanalysisIdList[$gapanalysisID]", $gapanalysisID);?></td>
          <td><?php echo html::input("account[$gapanalysisID]", zget($users, $gapanalysis->account),  "class='form-control chosen' disabled");?></td>
          <td><?php echo html::input("role[$gapanalysisID]", $gapanalysis->role, "class='form-control' readonly");?></td>
          <td><?php echo html::radio("needTrain[$gapanalysisID]", $lang->gapanalysis->needTrainList, $gapanalysis->needTrain);?></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='6' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

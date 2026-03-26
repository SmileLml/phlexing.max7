<?php
/**
 * The create of gapanalysis module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     gapanalysis
 * @version     $Id: edit.html.php 4903 2021-05-28 17:14:59Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->gapanalysis->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->gapanalysis->account;?></th>
            <td><?php echo html::input('account', zget($users, $gapanalysis->account), "class='form-control' disabled");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->gapanalysis->role;?></th>
            <td><?php echo html::input('role', $gapanalysis->role, "class='form-control' readonly")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gapanalysis->analysis;?></th>
            <td colspan="2"><?php echo html::textarea('analysis', $gapanalysis->analysis, "rows='6' class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gapanalysis->needTrain;?></th>
            <td><?php echo html::radio('needTrain', $lang->gapanalysis->needTrainList, $gapanalysis->needTrain);?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan='3' class='form-actions text-center'>
              <?php echo html::submitButton() . html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

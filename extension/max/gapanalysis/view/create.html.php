<?php
/**
 * The create of gapanalysis module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     gapanalysis
 * @version     $Id: create.html.php 4903 2021-05-28 14:26:59Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('roles', $rolePairs);?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->gapanalysis->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->gapanalysis->account;?></th>
            <td><?php echo html::select('account', $members, '', "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->gapanalysis->role;?></th>
            <td><?php echo html::input('role', '', "class='form-control' readonly")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gapanalysis->analysis;?></th>
            <td colspan="2"><?php echo html::textarea('analysis', '', "rows='6' class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gapanalysis->needTrain;?></th>
            <td><?php echo html::radio('needTrain', $lang->gapanalysis->needTrainList, 'no');?></td>
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

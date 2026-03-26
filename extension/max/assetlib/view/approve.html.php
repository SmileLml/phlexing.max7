<?php
/**
 * The view file of approve method of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: approve.html.php 4129 2021-06-28 08:18:14Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div class='main-content' id='mainContent'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $object->id;?></span>
        <?php echo $object->{$config->action->objectNameFields[$objectType]};?>
        <small><?php echo $lang->arrow . $lang->assetlib->approve;?></small>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->assetlib->approvedDate;?></th>
          <td class='w-p25-f'><?php echo html::input('approvedDate', helper::today(), "class='form-control form-date'");?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->assetlib->approveResult;?></th>
          <td class='required'><?php echo html::select('result', $lang->assetlib->resultList, '', 'class=form-control');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The create view file of chart of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $biPath = $this->app->getModuleExtPath('bi', 'view');?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='page-title'>
      <span title='<?php echo $title;?>' class='text'><?php echo $title;?></span>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <form method='post' target='hiddenwin' id='dataform' class="form-ajax">
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->chart->group;?></th>
          <td class='w-400px'>
            <?php echo html::select('group[]', $groups, '', "class='chosen form-control' data-max_drop_width='200' multiple");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->chart->name;?></th>
          <td><?php echo html::input('name', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->bi->driver;?></th>
          <td>
            <?php echo html::select('driver', $lang->bi->driverList, '', "class='chosen form-control' data-max_drop_width='200'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->chart->desc;?></th>
          <td><?php echo html::textarea('desc', '', "rows='7' class='form-control'");?></td>
        </tr>
        <?php $acl       = 'open';?>
        <?php $aclList   = $lang->chart->aclList;?>
        <?php $whitelist = '';?>
        <?php include $biPath['common'] . 'aclbox.html.php';?>
        <tr>
          <td colspan='2' class='form-actions text-center'>
            <?php echo html::submitButton($lang->chart->nextStep);?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>


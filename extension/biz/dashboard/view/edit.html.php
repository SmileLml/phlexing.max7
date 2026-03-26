<?php
/**
 * The edit view file of dashboard of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <?php echo $lang->dashboard->edit;?>
      </h2>
    </div>
    <form class='form-ajax' method='post'>
      <table class="table table-form">
        <tr>
          <th class='w-100px'><?php echo $lang->dashboard->name;?></th>
          <td class='required'><?php echo html::input('name', $dashboard->name, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->dashboard->module;?></th>
          <td>
            <?php echo html::select('module', $modulePairs, $dashboard->module, "class='form-control chosen'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->dashboard->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', $dashboard->desc, "class='form-control' rows=5");?></td>
        </tr>
        <tr>
          <th></th>
          <td class='form-actions text-left'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

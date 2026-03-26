<?php
/**
 * The view file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: view.html.php 4141 2021-07-02 10:47:13Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php common::printBack($browseLink, 'btn btn-secondary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $lib->id;?></span>
      <span class='text' title='<?php echo $lib->name;?>'><?php echo $lib->name;?></span>
      <?php if($lib->deleted):?>
      <span class='label label-danger'><?php echo $lang->assetlib->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id='mainContent'>
  <div class="main-col">
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->assetlib->desc;?></div>
        <div class='detail-content article-content'><?php echo $lib->desc;?></div>
      </div>
      <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
    </div>
    <div class='main-actions'>
      <nav class="container"></nav>
      <div class="btn-toolbar">
        <?php
        common::printBack($browseLink);
        if(!$lib->deleted)
        {
            echo "<div class='divider'></div>";
            common::printIcon('assetlib', 'edit' . ucfirst($lib->type) . 'Lib', "libID=$lib->id", '', 'button', 'edit', '', '', false, '', $lang->edit);
            common::printIcon('assetlib', 'delete' . ucfirst($lib->type) . 'Lib', "libID=$lib->id", '', 'button', 'trash', 'hiddenwin', '', false, '', $lang->delete);
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

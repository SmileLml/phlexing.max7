<?php
/**
 * The browse category view file of traincourse module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     traincourse
 * @version     $Id: browsecategory.html.php 4029 2022-02-10 10:50:41Z $
 * @link        https://www.zentao.net
 */
?>
<?php
include $app->getModuleRoot() . 'common/view/header.html.php';
include $app->getModuleRoot() . 'common/view/sortable.html.php';
js::set('type', $type);
js::set('originalType', $originalType);
js::set('categoryID', $categoryID);
?>
<div class='col-md-12'>
<?php if(strpos($categoryMenu, '<li>') !== false):?>
<div class='row'>
  <div class='col-md-4'>
    <div class='panel panel-block'>
      <div class='panel-heading'>
        <strong>
          <i class="icon-sitemap"></i>
          <?php echo zget($lang->traincourse->typeList, $originalType, $lang->traincourse->category) . $lang->fold . $lang->traincourse->browseCategory;?>
        </strong>
      </div>
      <div class='panel-body'><div id='treeMenuBox'><?php echo $categoryMenu;?></div></div>
    </div>
  </div>
  <div class='col-md-8' id='categoryBox'></div>
</div>
<?php else:?>
<div id='categoryBox'></div>
<?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The edit view of category module of XXB.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     category
 * @version     $Id: edit.html.php 4110 2016-10-08 09:37:28Z daitingting $
 * @link        https://wenrui.net
 */
?>
<?php js::set('categoryID', $category->id);?>
<?php
$webRoot   = $config->webRoot;
$jsRoot    = $webRoot . "js/";
$themeRoot = $webRoot . "theme/";
?>
<form method='post' class='form-horizontal' id='editForm' action="<?php echo inlink('editCategory', "type=editcategory&categoryID=$category->id");?>">
  <div class='panel panel-block'>
    <div class='panel-heading'>
      <strong><i class="icon-pencil"></i> <?php echo $lang->traincourse->editCategory;?></strong>
      <i class="icon-double-angle-right"></i>
      <?php echo html::a(inlink('browseCategory', "type=trainskill&category={$category->id}"), $category->name);?>
    </div>
    <div class='panel-body'>
      <div class='form-group'>
        <label class='col-md-2 control-label'><?php echo $lang->traincourse->category;?></label>
        <div class='col-md-4'><?php echo html::select('parent', $optionMenu, $category->parent, "class='chosen form-control'");?></div>
      </div>
      <div class='form-group'>
        <label class='col-md-2 control-label'><?php echo $lang->traincourse->categoryName;?></label>
        <div class='col-md-4'><?php echo html::input('name', $category->name, "class='form-control'");?></div>
      </div>
      <div class='form-group'>
        <label class='col-md-2'></label>
        <div class='col-md-4'><?php echo html::submitButton();?></div>
      </div>
    </div>
  </div>
</form>
<?php if(isset($pageJS)) js::execute($pageJS);?>

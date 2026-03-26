<?php
/**
 * The header.modal view of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php if(helper::isAjaxRequest()):?>
<?php
$webRoot   = $config->webRoot;
$jsRoot    = $webRoot . "js/";
$themeRoot = $webRoot . "theme/";
$modalSizeList = array('lg' => '900px', 'sm' => '300px');
if(!isset($modalWidth)) $modalWidth = 700;
if(is_numeric($modalWidth))
{
    $modalWidth .= 'px';
}
else if(isset($modalSizeList[$modalWidth]))
{
    $modalWidth = $modalSizeList[$modalWidth];
}
if(isset($pageCSS)) css::internal($pageCSS);

?>
<style>
#ajaxForm .table tr:first-child td:nth-child(3){width:5px;}
</style>
<div class="modal-dialog" style="width:<?php echo $modalWidth;?>;">
  <div class="modal-content">
    <div class="modal-header">
      <strong class="modal-title"><?php if(!empty($title)) echo $title; ?></strong>
      <?php if(!empty($subtitle)) echo "<label class='text-important'>" . $subtitle . '</label>'; ?>
      <div class='modal-actions'>
        <?php if(!empty($modalActions)) echo $modalActions;?>
        <button type='button' class='btn btn-link' style='font-size: 20px; margin-left: 10px' data-dismiss='modal' aria-hidden='true'>&times;</button>
      </div>
    </div>
    <div class="modal-body">
<?php else:?>
<?php include $this->app->getAppRoot() . 'module/common/view/header.html.php';?>
<?php endif;?>

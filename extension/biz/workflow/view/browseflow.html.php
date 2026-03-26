<?php
/**
 * The browse flow view file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php
include $app->getModuleRoot() . 'common/view/header.html.php';
include './deleteconfirm.html.php';
js::set('mode', $mode);
js::set('status', $status);
js::set('confirmToDeactivate', $lang->workflow->tips->deactivate);
?>
<li id='bysearchTab'><?php echo baseHTML::a('#', "<i class='icon-search icon'></i>" . $lang->search->common);?></li>
<div id='menuActions'>
  <div class='btn-group'>
    <?php echo baseHTML::a("javascript:;", "<i class='icon icon-cards-view'></i>", "data-mode='card' class='mode-toggle btn'");?>
    <?php echo baseHTML::a("javascript:;", "<i class='icon icon-bars'></i>", "data-mode='list' class='mode-toggle btn'");?>
  </div>
  <?php extCommonModel::printLink('workflow', 'create', "type=flow&parent=&app=$currentApp",  "<i class='icon-plus'> </i>" . $lang->workflow->create, "class='btn btn-primary' data-toggle='modal'");?>
</div>
<?php
$canEdit = $this->workflow->isClickable(null, 'edit');
$canView = $this->workflow->isClickable(null, 'view');
include 'card.html.php';
include 'list.html.php';
include $app->getModuleRoot() . 'common/view/footer.html.php';
?>

<?php
/**
 * The activities of process module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     process
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class="main-content" id="mainContent">
  <div class="main-header">
    <strong><?php echo $lang->process->activityList;?></strong>
  </div>
  <div class="modal-body" style="max-height: 528px; overflow: visible;">
    <div class="detail">
      <?php if($activities):?>
        <?php foreach($activities as $key => $val):?>
          <p>
            <?php echo html::a($this->createLink('activity', 'view', "activityID=$key"), $key.': '.$val);?>
            <?php echo html::a($this->createLink('activity', 'edit', "activityID=$key"), '<i class="icon-edit"></i>', '', "title={$lang->process->edit}");?>
            <?php echo html::a($this->createLink('activity', 'delete', "activityID=$key"), '<i class="icon-close"></i>', 'hiddenwin', "title={$lang->process->delete}");?>
          </p>
          <?php endforeach;?>
        <?php else:?>
          <div class="text-center"><?php echo $lang->process->emptyTip;?></div>
        <?php endif;?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

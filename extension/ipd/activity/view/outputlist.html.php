<?php
/**
 * The outputList of activity module of ZenTaoQC.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     activity
 * @version     $Id: outputlist.html.php 935 2020-09-09 07:49:24Z $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class="main-content" id="mainContent">
  <div class="main-header">
    <strong><?php echo $lang->activity->outputList;?></strong>
  </div>
  <div class="modal-body" style="max-height: 528px; overflow: visible;">
    <div class="detail">
      <?php if($outputList):?>
        <?php foreach($outputList as $key => $value):?>
          <p>
            <?php echo html::a($this->createLink('zoutput', 'view',   "id=$key"), $key.'. '.$value);?>
            <?php echo html::a($this->createLink('zoutput', 'edit',   "id=$key"), '<i class="icon-edit"></i>',  '', "title={$lang->activity->edit}");?>
            <?php echo html::a($this->createLink('zoutput', 'delete', "id=$key"), '<i class="icon-close"></i>', '', "title={$lang->activity->delete} class='deleter'");?>
          </p>
          <?php endforeach;?>
        <?php else:?>
          <div class="text-center"><?php echo $lang->noData;?></div>
        <?php endif;?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

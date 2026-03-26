<?php
/**
 * The view of meetingroom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Li Yuchun <liyuchun@easycorp.ltd>
 * @package     view
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->roomList ? $app->session->roomList : $this->createLink('meetingroom', 'browse');?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $room->id?></span>
      <span class="text" title='<?php echo $room->name;?>'><?php echo $room->name;?></span>
      <?php if($room->deleted):?>
      <span class='label label-danger'><?php echo $lang->meetingroom->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->meetingroom->position;?></div>
        <div class="detail-content article-content"><?php echo $room->position;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->meetingroom->seats;?></div>
        <div class="detail-content article-content"><?php echo $room->seats;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->meetingroom->equipment;?></div>
        <div class="detail-content article-content"><?php echo $room->equipmentName;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->meetingroom->openTime;?></div>
        <div class="detail-content article-content"><?php echo $room->openTimeName;?></div>
      </div>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$room->deleted):?>
        <?php
        echo "<div class='divider'></div>";
        common::printIcon('meetingroom', 'edit', "roomID=$room->id", $room);
        common::printIcon('meetingroom', 'delete', "roomID=$room->id", $room, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->meetingroom->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->meetingroom->createdBy;?></th>
                  <td><?php echo zget($users, $room->createdBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->meetingroom->createdDate;?></th>
                  <td><?php echo $room->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->meetingroom->editedBy;?></th>
                  <td><?php echo zget($users, $room->editedBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->meetingroom->editedDate;?></th>
                  <td><?php echo helper::isZeroDate($room->editedDate) ? '' : $room->editedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

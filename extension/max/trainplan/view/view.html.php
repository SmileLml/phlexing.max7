<?php
/**
 * The view of trainplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     view
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->trainplanList ? $app->session->trainplanList : $this->createLink('trainplan', 'browse', "projectID={$trainplan->project}");?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $trainplan->id?></span>
      <span class="text" title='<?php echo $trainplan->name;?>'><?php echo $trainplan->name;?></span>
      <?php if($trainplan->deleted):?>
      <span class='label label-danger'><?php echo $lang->trainplan->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->trainplan->summary;?></div>
        <div class="detail-content article-content"><?php echo $trainplan->summary;?></div>
      </div>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$trainplan->deleted):?>
        <?php
        common::printIcon('trainplan', 'finish', "trainplanID=$trainplan->id", $trainplan, "button", 'checked', '', 'iframe showinonlybody', true);
        common::printIcon('trainplan', 'summary', "trainplanID=$trainplan->id", $trainplan, 'button', '', '', 'iframe showinonlybody', true);
        echo "<div class='divider'></div>";
        common::printIcon('trainplan', 'edit', "trainplanID=$trainplan->id", $trainplan);
        common::printIcon('trainplan', 'delete', "trainplanID=$trainplan->id", $trainplan, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->trainplan->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th><?php echo $lang->trainplan->status;?></th>
                  <td class='status-<?php echo $trainplan->status;?>'><?php echo zget($lang->trainplan->statusList, $trainplan->status);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->trainplan->type;?></th>
                  <td><?php echo zget($lang->trainplan->typeList, $trainplan->type);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->trainplan->place;?></th>
                  <td><?php echo $trainplan->place;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->trainplan->trainee;?></th>
                  <td><?php echo $trainees;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->trainplan->lecturer;?></th>
                  <td><?php echo $trainplan->lecturer;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->trainplan->begin;?></th>
                  <td><?php echo $trainplan->begin;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->trainplan->end;?></th>
                  <td><?php echo $trainplan->end;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->trainplan->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->trainplan->createdBy;?></th>
                  <td><?php echo zget($users, $trainplan->createdBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->trainplan->createdDate;?></th>
                  <td><?php echo $trainplan->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->trainplan->editedBy;?></th>
                  <td><?php echo zget($users, $trainplan->editedBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->trainplan->editedDate;?></th>
                  <td><?php echo $trainplan->editedDate == '0000-00-00 00:00:00' ? '' : $trainplan->editedDate;?></td>
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

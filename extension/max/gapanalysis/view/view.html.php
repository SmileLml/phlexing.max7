<?php
/**
 * The view of gapanalysis module of ZenTaoPMS.
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
<?php $browseLink = $app->session->gapanalysisList ? $app->session->gapanalysisList : $this->createLink('gapanalysis', 'browse', "projectID={$gapanalysis->project}");?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $gapanalysis->id?></span>
      <span class="text"><?php echo zget($users, $gapanalysis->account);?></span>
      <?php if($gapanalysis->deleted):?>
      <span class='label label-danger'><?php echo $lang->gapanalysis->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->gapanalysis->analysis;?></div>
        <div class="detail-content article-content"><?php echo $gapanalysis->analysis;?></div>
      </div>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$gapanalysis->deleted):?>
        <?php
        echo "<div class='divider'></div>";
        common::printIcon('gapanalysis', 'edit', "gapanalysisID=$gapanalysis->id", $gapanalysis);
        common::printIcon('gapanalysis', 'delete', "gapanalysisID=$gapanalysis->id", $gapanalysis, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->gapanalysis->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->gapanalysis->account;?></th>
                  <td><?php echo zget($users, $gapanalysis->account);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->gapanalysis->role;?></th>
                  <td><?php echo zget($lang->user->roleList, $gapanalysis->role);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->gapanalysis->needTrain;?></th>
                  <td><?php echo zget($lang->gapanalysis->needTrainList, $gapanalysis->needTrain);?></td>
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
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->gapanalysis->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->gapanalysis->createdBy;?></th>
                  <td><?php echo zget($users, $gapanalysis->createdBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->gapanalysis->createdDate;?></th>
                  <td><?php echo $gapanalysis->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->gapanalysis->editedBy;?></th>
                  <td><?php echo zget($users, $gapanalysis->editedBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->gapanalysis->editedDate;?></th>
                  <td><?php echo helper::isZeroDate($gapanalysis->editedDate) ? '' : $gapanalysis->editedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

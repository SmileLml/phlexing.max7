<?php
/**
 * The storyview file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: storyview.html.php 4952 2021-06-29 10:00:58Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $story->id?></span>
      <span class="text" title='<?php echo $story->title;?>' style='color: <?php echo $story->color;?>'><?php echo $story->title?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->story->legendSpec;?></div>
        <div class="detail-content article-content"><?php echo $story->spec;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->story->legendVerify;?></div>
        <div class="detail-content article-content"><?php echo $story->verify;?></div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $story->files, 'fieldset' => 'true', 'object' => $story));?>
      <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=story&objectID=$story->id");?>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php
        common::printIcon('assetlib', 'editStory', "storyID=$story->id", $story, 'button', 'edit');
        if($story->status == 'draft') common::printIcon('assetlib', 'approveStory', "storyID=$story->id", $story, 'button', 'glasses', '', 'iframe showinonlybody', true);
        common::printIcon('assetlib', 'removeStory', "storyID=$story->id", $story, 'button', 'unlink', 'hiddenwin');
       ?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasicInfo' data-toggle='tab'><?php echo $lang->story->legendBasicInfo;?></a></li>
          <li><a href='#legendLifeTime' data-toggle='tab'><?php echo $lang->story->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasicInfo'>
            <table class="table table-data">
              <tbody>
                <?php $widthClass = common::checkNotCN() ? 'w-100px' : 'w-80px';?>
                <tr>
                  <th class='<?php echo $widthClass?>'><?php echo $lang->assetlib->sourceStory;?></th>
                  <td><?php echo html::a($this->createLink($story->type, 'view', "storyID={$story->fromStory}&version={$story->fromVersion}"), $story->sourceName)?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->status;?></th>
                  <td><span class='status-story status-<?php echo $story->status?>'><span class="label label-dot"></span> <?php echo zget($lang->assetlib->statusList, $story->status);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->category;?></th>
                  <td><?php echo zget($lang->story->categoryList, $story->category, $story->category)?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->pri;?></th>
                  <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri)?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->estimate;?></th>
                  <td title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->keywords;?></th>
                  <td><?php echo $story->keywords;?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th><?php echo $lang->assetlib->importedBy;?></th>
                  <td><?php echo zget($users, $story->openedBy) . $lang->at . $story->openedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->assetlib->approvedBy;?></th>
                  <td><?php if($story->status == 'active' and !empty($story->assignedTo)) echo zget($users, $story->assignedTo) . $lang->at . $story->approvedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->story->lastEditedBy;?></th>
                  <td><?php if($story->lastEditedBy) echo zget($users, $story->lastEditedBy) . $lang->at . $story->lastEditedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importToLib">
  <div class="modal-dialog mw-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->story->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('story', 'importToLib', "storyID=$story->id");?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->story->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveStory') and !common::hasPriv('assetlib', 'batchApproveStory')):?>
            <tr>
              <th><?php echo $lang->story->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $approvers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='3' class='text-center'>
                <?php echo html::submitButton($lang->story->importToLib, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

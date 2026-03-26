<?php
/**
 * The details view of approvalflow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     approvalflow
 * @version     $Id: view.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php
$browseLink = $app->session->approvalflowList ? $app->session->approvalflowList : $this->createLink('approvalflow', 'browse', "type={$flow->type}");
$createLink = $this->createLink('approvalflow', 'create', "type={$flow->type}");
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $flow->id?></span>
      <span class="text" title="<?php echo $flow->name?>"><?php echo $flow->name?></span>
      <?php if($flow->deleted):?>
      <span class='label label-danger'><?php echo $lang->approvalflow->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('approvalflow', 'create')) echo html::a($createLink, "<i class='icon icon-plus'></i> {$lang->approvalflow->create}", '', "class='btn btn-primary' data-app='{$app->tab}'");?>
  </div>
  <?php endif;?>
</div>
<div class="main-row" id="mainContent">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->approvalflow->desc;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($flow->desc) ? $flow->desc : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
    </div>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=approvalflow&objectID=$flow->id");?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$flow->deleted):?>
        <?php
          $params = "flowID=$flow->id";
          common::printIcon('approvalflow', 'design', $params, $flow);
          common::printIcon('approvalflow', 'edit',   $params, $flow);
          common::printIcon('approvalflow', 'delete', $params, $flow, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <details class="detail" open="">
      <summary class="detail-title"><?php echo $lang->approvalflow->basicInfo;?></summary>
      <div class="detail-content">
        <table class="table table-data">
          <tbody>
            <tr valign="middle">
              <th class="thWidth w-100px"><?php echo $lang->approvalflow->id;?></th>
              <td><?php echo $flow->id;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->approvalflow->type;?></th>
              <td><?php echo zget($lang->approvalflow->typeList, $flow->type);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->approvalflow->createdBy;?></th>
              <td><?php echo zget($users, $flow->createdBy);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->approvalflow->createdDate;?></th>
              <td><?php echo $flow->createdDate;?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importToLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->approvalflow->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('approvalflow', 'importToLib', "approvalflow=$flow->id");?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->approvalflow->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveStory') and !common::hasPriv('assetlib', 'batchApproveStory')):?>
            <tr>
              <th><?php echo $lang->approvalflow->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $approvers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

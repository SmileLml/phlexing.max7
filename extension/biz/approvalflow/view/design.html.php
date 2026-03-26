<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('nodes',             $flow->nodes);?>
<?php js::set('users',             $users);?>
<?php js::set('depts',             $depts);?>
<?php js::set('roles',             $roles);?>
<?php js::set('productRoles',      $lang->approvalflow->productRoleList);?>
<?php js::set('projectRoles',      $lang->approvalflow->projectRoleList);?>
<?php js::set('executionRoles',    $lang->approvalflow->executionRoleList);?>
<?php js::set('positions',         $lang->user->roleList);?>
<?php js::set('select',            $lang->approvalflow->select);?>
<?php js::set('userRange',         $lang->approvalflow->userRangeList);?>
<?php js::set('nodeTypeLang',      $lang->approvalflow->nodeTypeList);?>
<?php js::set('userTypeLang',      $lang->approvalflow->userTypeList);?>
<?php js::set('noticeTypeLang',    $lang->approvalflow->noticeTypeList);?>
<?php js::set('warningLang',       $lang->approvalflow->warningList);?>
<?php js::set('reviewerTypeLang',  $lang->approvalflow->reviewerTypeList);?>
<?php js::set('reviewTypeLang',    $lang->approvalflow->reviewTypeList);?>
<?php js::set('conditionTypeLang', $lang->approvalflow->conditionTypeList);?>
<?php js::set('conditionTextLang', $lang->approvalflow->conditionText);?>
<?php js::set('superiorList',      $lang->approvalflow->superiorList);?>
<?php js::set('needAllLang',       $lang->approvalflow->needAll);?>
<?php js::set('link',              inLink('design', "flowID=$flow->id"));?>

<div id='mainContent'>
  <div class='main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <?php if(!isonlybody()):?>
      <div class="main-header">
        <h2>
          <?php echo html::a(inLink('browse', "type=$flow->type"), "<i class='icon icon-back icon-sm'> $lang->goback</i>", '', 'class="btn"');?>
          <?php echo $flow->name;?>
          <small>&nbsp;<i class="icon-angle-right"></i>&nbsp;<?php echo $lang->approvalflow->design;?></small>
        </h2>
        <div class="btn-toolbar pull-right">
          <?php echo html::submitButton($lang->save, 'data-placement="bottom"', 'btn btn-primary');?>
        </div>
      </div>
      <?php endif;?>
      <div id="graph">
        <div id="editor" class="editor-node branch">
           <div id="root" class="nodes"></div>
        </div>
      </div>
      <?php echo html::hidden('nodes', '');?>
    </form>
    <div class="panel main-content">
      <div class="main-header"><h2><?php echo $lang->approvalflow->setNode?></h2></div>
      <div>
        <div class="detail">
           <div class="detail-title"><?php echo $lang->approvalflow->title;?></div>
              <div class="detail-content">
                <?php echo html::input('title', '', "class='form-control'");?>
              </div>
            </div>
        </div>
        <div class="detail">
           <div class="detail-title"><?php echo $lang->approvalflow->reviewer;?></div>
              <div class="detail-content">
                <?php echo html::select('reviewers', '', '', "class='form-control chosen'");?>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

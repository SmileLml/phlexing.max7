<?php
/**
 * The view file of marketreport module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Deqing Chai <chaideqing@easycorp.ltd>
 * @package   marketreport
 * @version   $Id: view.html.php 4808 2023-08-28 09:48:13Z zhujinyonging@gmail.com $
 * @link      http://www.zentao.net
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
      <span class="label label-id"><?php echo $report->id?></span>
      <span class="text" title='<?php echo $report->name;?>'><?php echo $report->name;?></span>
      <?php if($report->deleted):?>
      <span class='label label-danger'><?php echo $lang->marketreport->deleted;?></span>
      <?php else:?>
        <?php if($report->status == 'published'):?>
        <span class='label label-success'><?php echo $lang->marketreport->statusList['published'];?></span>
        <?php elseif($report->status == 'draft'):?>
        <span class='label label-info'><?php echo $lang->marketreport->statusList['draft'];?></span>
        <?php endif;?>
      <?php endif;?>
    </div>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php
    $link = $this->createLink('marketreport', 'create', "marketID={$this->session->marketID}");
    if(common::hasPriv('marketreport', 'create')) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->marketreport->create}", '', "class='btn btn-primary'");
    ?>
  </div>
  <?php endif;?>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
<div id="scrollContent">
<?php endif;?>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail0">
        <div class="detail-title"><?php echo $lang->marketreport->legendBasic;?></div>
        <div class="detail-content">
          <table class="table table-borderless table-data">
            <tbody>
              <tr>
                <th class="thwidth"><?php echo $lang->marketreport->market;?></th>
                <td><?php echo empty($report->market) ? '' : html::a(helper::createLink('market', 'view', "marketID=$report->market"), zget($marketList, $report->market, ''));?></td>
              </tr>
              <tr>
                <th><?php echo $lang->marketreport->source;?></th>
                <td><?php echo zget($lang->marketreport->sourceList, $report->source, '');?></td>
              </tr>
              <?php if($report->source == 'inside'):?>
              <tr>
                <th><?php echo $lang->marketreport->research;?></th>
                <td><?php echo empty($report->research) ? '' : html::a(helper::createLink('marketresearch', 'task', "researchID=$report->research"), zget($researchList, $report->research, ''));?></td>
              </tr>
              <tr>
                <th><?php echo $lang->marketreport->owner;?></th>
                <td><?php echo zget($users, $report->owner);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->marketreport->participants;?></th>
                <td><?php echo $report->participants;?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->marketreport->desc;?></th>
                <td>
                  <div class="report-desc">
                  <?php echo $report->desc;?>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <?php
      echo $this->fetch('marketreport', 'printcardfiles', array('files' => $report->files, 'fieldset' => 'true', 'object' => $report, 'method' => 'view', 'showDelete' => true));

      $canBeChanged = common::canBeChanged('marketreport', $report);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=marketreport&objectID=$report->id");
      ?>
    </div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php if(!isonlybody() and $this->app->getViewType() != 'xhtml'):?>
        <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn'");?>
        <?php echo "<div class='divider'></div>";?>
        <?php endif;?>
        <?php echo $this->marketreport->buildOperateMenu($report, 'view', $fromMarket);?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <?php if($this->app->getViewType() != 'xhtml'):?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <?php endif;?>
  </div>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
</div>
<?php endif;?>

<script>
</script>
<?php include $app->getModuleRoot() . 'common/view/syntaxhighlighter.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

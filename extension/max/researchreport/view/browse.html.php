<?php
/**
 * The browse view of researchreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     researchreport
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
#queryBox #searchform{border-bottom: 1px solid #ddd; margin-bottom: 20px;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->researchreport->featureBar['browse'] as $label => $labelName)
    {
        $active = $browseType == $label ? 'btn-active-text' : '';
        echo html::a($this->createLink('researchreport', 'browse', "projectID=$projectID&browseType=$label&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), '<span class="text">' . $labelName . '</span>', '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::canModify('project', $project) ? common::printLink('researchreport', 'create', "projectID=$projectID", "<i class='icon icon-plus'></i>" . $lang->researchreport->create, '', "class='btn btn-primary'") : null;?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=researchreport');?>" data-module='researchreport'></div>
<div id="mainContent" class="main-table">
  <div class="main-col">
    <?php if(empty($reportList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php if(common::hasPriv('researchreport', 'create')):?>
        <?php echo html::a($this->createLink('researchreport', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->researchreport->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <table id="researchreportList" class="table has-sort-head">
      <thead>
        <tr>
          <?php $vars = "projectID=$projectID&browseType=$browseType&param=0&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
          <th class="c-id w-60px"><?php common::printOrderLink('id', $orderBy, $vars, $lang->researchreport->id);?></th>
          <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->researchreport->title);?></th>
          <th class='w-80px text-left'><?php common::printOrderLink('author', $orderBy, $vars, $lang->researchreport->author);?></th>
          <th class='w-150px text-left'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->researchreport->createdDate);?></th>
          <th class='c-actions-1'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($reportList as $report):?>
        <tr>
          <?php $viewLink = $this->createLink('researchreport', 'view', "reportID=$report->id");?>
          <td><?php echo html::a($viewLink, sprintf('%03d', $report->id));?></td>
          <td class='c-name' title="<?php echo $report->title;?>"><?php echo html::a($viewLink, $report->title);?></td>
          <td class='c-name' title="<?php echo zget($users, $report->author);?>"><?php echo zget($users, $report->author);?></td>
          <td><?php echo $report->createdDate;?></td>
          <td class='c-actions'><?php common::printIcon('researchreport', 'edit', "reportID=$report->id", $report, 'list');?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The browse view of researchplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     researchplan
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
    foreach($lang->researchplan->featureBar['browse'] as $label => $labelName)
    {
        $active = $browseType == $label ? 'btn-active-text' : '';
        echo html::a($this->createLink('researchplan', 'browse', "projectID=$projectID&browseType=$label&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), '<span class="text">' . $labelName . '</span>', '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::canModify('project', $project) ? common::printLink('researchplan', 'create', "projectID=$projectID", "<i class='icon icon-plus'></i>" . $lang->researchplan->create, '', "class='btn btn-primary'") : null;?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=researchplan');?>" data-module='researchplan'></div>
<div id="mainContent" class="main-table">
  <div class="main-col">
    <?php if(empty($planList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php if(common::hasPriv('researchplan', 'create')):?>
        <?php echo html::a($this->createLink('researchplan', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->researchplan->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <table id="researchplanList" class="table has-sort-head">
      <thead>
        <tr>
          <?php $vars = "projectID=$projectID&browseType=$browseType&param=0&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
          <th class="c-id w-60px"><?php common::printOrderLink('id', $orderBy, $vars, $lang->researchplan->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->researchplan->name);?></th>
          <th class='w-180px'><?php common::printOrderLink('customer', $orderBy, $vars, $lang->researchplan->customer);?></th>
          <th class='w-200px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->researchplan->begin);?></th>
          <th class='w-200px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->researchplan->end);?></th>
          <th class='w-180px'><?php common::printOrderLink('location', $orderBy, $vars, $lang->researchplan->location);?></th>
          <th class='w-100px'><?php common::printOrderLink('method', $orderBy, $vars, $lang->researchplan->method);?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($planList as $plan):?>
        <tr>
          <?php $viewLink = $this->createLink('researchplan', 'view', "planID=$plan->id");?>
          <td><?php echo html::a($viewLink, sprintf('%03d', $plan->id));?></td>
          <td class='c-name' title="<?php echo $plan->name;?>"><?php echo html::a($viewLink, $plan->name);?></td>
          <td class='c-name' title="<?php echo $plan->customer;?>"><?php echo $plan->customer;?></td>
          <td><?php echo helper::isZeroDate($plan->begin) ? '' : $plan->begin;?></td>
          <td><?php echo helper::isZeroDate($plan->end)   ? '' : $plan->end;?></td>
          <td class='c-name' title="<?php echo $plan->location;?>"><?php echo $plan->location;?></td>
          <td class='c-name' title="<?php echo zget($lang->researchplan->methodList, $plan->method);?>"><?php echo zget($lang->researchplan->methodList, $plan->method);?></td>
          <td class='c-actions'>
          <?php
            common::printIcon('researchplan', 'edit', "planID=$plan->id", $plan, 'list');
            if(isset($reportPairs[$plan->id]))
            {
                common::printIcon('researchreport', 'view', "reportID={$reportPairs[$plan->id]}", $plan, 'list', 'summary');
            }
            else
            {
                common::printIcon('researchreport', 'create', "projectID=$projectID&planID=$plan->id", $plan, 'list', 'summary');
            }
          ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

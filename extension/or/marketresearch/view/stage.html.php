<?php
/**
 * The view file of marketresearch module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Hucheng Tang <tanghucheng@easycorp.ltd>
 * @package   marketresearch
 * @version   $Id: create.html.php 4808 2023-08-28 09:48:13Z zhujinyonging@gmail.com $
 * @link      http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('status', $browseType)?>
<?php js::set('summary', $lang->marketresearch->summary)?>
<?php js::set('allSummary', $lang->marketresearch->allSummary)?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    common::sortFeatureMenu();
    foreach(customModel::getFeatureMenu('marketresearch', 'task') as $menuItem)
    {
        if(isset($menuItem->hidden)) continue;
        $menuType = $menuItem->name;
        if($menuType == 'QUERY')
        {
            $searchBrowseLink = $this->createLink('marketresearch', 'stage', "marketresearch=$researchID&type=bySearch&param=%s&orderBy=id_desc&recTotal=0&recPerPage=100&pageID=1");
            $isBySearch       = $this->session->researchBrowseType == 'bysearch';
            include '../../common/view/querymenu.html.php';
        }
        elseif($menuType != 'status' and $menuType != 'QUERY')
        {
            $label   = "<span class='text'>{$menuItem->text}</span>";
            $label  .= $menuType == $this->session->researchBrowseType ? " <span class='label label-light label-badge'>{$taskTotal}</span>" : '';
            $active  = $menuType == $this->session->researchBrowseType ? 'btn-active-text' : '';
            echo html::a(inlink('stage', "marketresearch=$researchID&type=$menuType&param=0&orderBy=id_desc&recTotal=0&recPerPage=100&pageID=1"), $label, '', "id='{$menuType}' class='btn btn-link $active' $title");
        }
        elseif($menuType == 'status')
        {
            echo "<div class='btn-group' id='more'>";
            $researchBrowseType = isset($browseType) ? $this->session->researchBrowseType : '';
            $current        = $menuItem->text;
            $active         = '';
            $statusSelects  = isset($lang->execution->moreSelects['task']['status']) ? $lang->execution->moreSelects['task']['status'] : array();
            if(isset($statusSelects[$researchBrowseType]))
            {
                $current = "<span class='text'>{$statusSelects[$researchBrowseType]}</span> <span class='label label-light label-badge'>{$taskTotal}</span>";
                $active  = 'btn-active-text';
            }
            echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $active'");
            echo "<ul class='dropdown-menu'>";
            foreach($statusSelects as $key => $value)
            {
                if($key == '') continue;
                echo '<li' . ($key == $researchBrowseType ? " class='active'" : '') . '>';
                echo html::a($this->createLink('marketresearch', 'stage', "marketresearch=$researchID&type=$key&param=0&orderBy=id_desc&recTotal=0&recPerPage=100&pageID=1"), $value);
            }
            echo '</ul></div>';
        }
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('marketresearch', 'view', "researchID=$researchID", "<i class='icon-list-alt muted'> </i> " . $lang->marketresearch->view, '', "class='btn btn-link'")?>
    <?php if(common::hasPriv('marketresearch', 'createTask'))  echo html::a($this->createLink('researchtask', 'create', "researchID=$researchID&stageID=0&taskID=0"), "<i class='icon-plus'></i> {$lang->marketresearch->createTask}", '', "class='btn btn-secondary' data-app='market'");?>
    <?php if(common::hasPriv('marketresearch', 'createStage')) echo html::a($this->createLink('marketresearch', 'createStage', "researchID=$researchID&stageID=0&executionType=stage"), "<i class='icon-plus'></i> {$lang->marketresearch->createStage}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='task' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=task');?>"></div>
<div id='mainContent' class="main-row fade">
  <?php if(empty($stageStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
      <?php if(common::hasPriv('marketresearch', 'createStage')):?>
      <?php echo html::a($this->createLink('marketresearch', 'createStage', "researchID=$researchID&stageID=0&executionType=stage"), "<i class='icon icon-plus'></i> " . $lang->marketresearch->createStage, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='stageForm' method='post' data-nested='true' data-expand-nest-child='false' data-enable-empty-nested-row='true' data-replace-id='stageTableList' data-preserve-nested='true'>
    <table class="table table-from table-fixed table-nested" id="stageList">
      <thead>
        <tr>
          <th class='table-nest-title'>
            <div class="flex-between">
              <?php echo $lang->nameAB;?>
                <a class='table-nest-toggle icon table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse;?>'></a>
            </div>
          </th>
          <th class='c-status text-center'><?php echo $lang->project->status;?></th>
          <th class='w-100px'><?php echo $lang->execution->owner;?></th>
          <th class='c-date'><?php echo $lang->programplan->begin;?></th>
          <th class='c-date'><?php echo $lang->programplan->end;?></th>
          <th class='w-50px text-right'><?php echo $lang->task->estimateAB;?></th>
          <th class='w-50px text-right'><?php echo $lang->task->consumedAB;?></th>
          <th class='w-50px text-right'><?php echo $lang->task->leftAB;?> </th>
          <th class='w-50px'><?php echo $lang->project->progress;?></th>
          <th class='text-center c-actions-6'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id="stageTableList">
        <?php foreach($stageStats as $stage):?>
        <?php $this->marketresearch->printNestedList($stage, false, $users, $research);?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="table-statistic"></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

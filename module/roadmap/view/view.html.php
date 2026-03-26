<?php
/**
 * The view of roadmap module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     roadmap
 * @version     $Id: view.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/tablesorter.html.php';?>
<?php js::set('confirmUnlinkStory', $lang->roadmap->confirmUnlinkStory)?>
<?php js::set('roadmapID', $roadmap->id);?>
<?php js::set('product', $roadmap->product);?>
<?php js::set('storyPageID', $storyPager->pageID);?>
<?php js::set('storyRecPerPage', $storyPager->recPerPage);?>
<?php js::set('storyRecTotal', $storyPager->recTotal);?>
<?php js::set('storySummary', $summary);?>
<?php js::set('storyCommon', $lang->SRCommon);?>
<?php js::set('checkedSummary', $lang->roadmap->checkedSummary);?>
<?php js::set('confirmBatchUnlinkStory', $lang->roadmap->confirmBatchUnlinkStory);?>
<?php js::set('storyListSession', $this->session->storyList);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->roadmapList ? $this->session->roadmapList : inlink('browse', "roadmapID=$roadmap->product");?>
    <?php common::printBack($browseLink, 'btn btn-primary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $roadmap->id;?></span>
      <span title='<?php echo $roadmap->name;?>' class='text'><?php echo $roadmap->name;?></span>
      <span class='label label-info label-badge'>
        <?php echo ($roadmap->begin == $config->roadmap->future || $roadmap->end == $config->roadmap->future) ? $lang->roadmap->future : $roadmap->begin . '~' . $roadmap->end;?>
      </span>
      <?php if($roadmap->deleted):?>
      <span class='label label-danger'><?php echo $lang->product->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <?php if($config->vision == 'or'):?>
  <div class='btn-toolbar pull-right' id='actionsBox'>
    <?php if(!$roadmap->deleted && !isonlybody()) echo $this->roadmap->buildOperateMenu($roadmap, 'view'); ?>
  </div>
  <?php endif;?>
</div>
<div id='mainContent' class='main-content'>
  <div class='tabs' id='tabsNav'>
    <?php if($this->app->getViewType() == 'xhtml'):?>
    <div class="roadmap-title"><?php echo $product->name . ' ' . $roadmap->name ?></div>
    <div class='tab-btn-container'>
    <?php endif;?>
    <ul class='nav nav-tabs'>
        <li class='<?php if($type == 'story') echo 'active'?>'>
          <a href='#stories' data-toggle='tab'>
            <?php echo  html::icon($lang->icons['story'], 'text-primary') . ' ' . $lang->roadmap->linkedURS;?>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <span>(<?php echo $storyPager->recTotal;?>)</span>
            <?php endif;?>
          </a>
        </li>
        <li class='<?php if($type == 'roadmapInfo') echo 'active'?>'>
          <a href='#roadmapInfo' data-toggle='tab'><?php echo  html::icon($lang->icons['plan'], 'text-info') . ' ' . $lang->roadmap->view;?></a>
        </li>
    </ul>
    <?php if($this->app->getViewType() == 'xhtml'):?>
    </div>
    <?php endif;?>
    <div class='tab-content'>
      <div id='stories' class='tab-pane <?php if($type == 'story') echo 'active'?>'>
        <?php $canOrder = common::hasPriv('execution', 'storySort');?>
        <div class='actions'>
          <?php if(common::hasPriv('roadmap', 'linkUR', $roadmap) and $config->vision == 'or') echo html::a("javascript:showLink($roadmap->id, \"story\")", '<i class="icon-link"></i> ' . $lang->roadmap->linkUR, '', "class='btn btn-primary'");?>
        </div>
        <?php if(common::hasPriv('roadmap', 'linkUR')):?>
        <div class='linkBox cell hidden'></div>
        <?php endif;?>
        <form class='main-table table-story<?php if($link === 'true' and $type == 'story') echo " hidden";?>' data-ride="" method='post' target='hiddenwin' id="roadmapURList" action="<?php echo inlink('batchUnlinkUR', "roadmapID=$roadmap->id&orderBy=$orderBy");?>">
          <table class='table has-sort-head' id='storyList'>
            <?php
            $canBatchClose         = common::hasPriv('requirement', 'batchClose');
            $canBatchEdit          = common::hasPriv('requirement', 'batchEdit');
            $canBatchChangeBranch  = common::hasPriv('requirement', 'batchChangeBranch');
            $canBatchChangeModule  = common::hasPriv('requirement', 'batchChangeModule');
            $canBatchChangeRoadmap = common::hasPriv('requirement', 'batchChangeRoadmap');
            $canBatchAssignTo      = common::hasPriv('requirement', 'batchAssignTo');

            $canBatchAction = ($canBeChanged and ($canBatchUnlink or $canBatchClose or $canBatchEdit or $canBatchChangeBranch or $canBatchChangeModule or $canBatchChangeRoadmap or $canBatchAssignTo));
            $vars = "roadmapID={$roadmap->id}&type=story&orderBy=%s&link=$link&param=$param";
            ?>
            <thead>
              <tr class='text-center'>
                <?php if($this->app->getViewType() == 'xhtml'):?>
                <th class='c-id text-left'>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
                <th class='w-70px' title='<?php echo $lang->pri;?>'> <?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='w-70px'> <?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
                <?php else:?>
                <th class='c-id text-left'>
                  <?php if($roadmapStories && $canBatchAction):?>
                  <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                    <label></label>
                  </div>
                  <?php endif;?>
                  <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
                </th>
                <?php if($canOrder):?>
                <th class='w-90px'><?php echo $lang->roadmap->updateOrder;?></th>
                <?php endif;?>
                <th class='w-70px' title='<?php echo $lang->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
                <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->requirement);?></th>
                <th class='w-90px text-left'><?php common::printOrderLink('module', $orderBy, $vars, $lang->story->module);?></th>
                <th class='c-user'><?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
                <th class='w-70px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->statusAB);?></th>
                <?php if($config->vision == 'or'):?>
                <th class='c-actions-1 w-90px'><?php echo $lang->actions?></th>
                <?php endif;?>
                <?php endif;?>
              </tr>
            </thead>
            <tbody class='sortable text-center'>
              <?php
              $totalEstimate = 0.0;
              ?>
              <?php foreach($roadmapStories as $story):?>
              <?php
              $viewLink = $this->createLink('story', 'view', "storyID=$story->id&version=0&param=0&storyType=requirement");
              $totalEstimate += $story->estimate;
              ?>
              <tr data-id='<?php echo $story->id;?>' data-estimate='<?php echo $story->estimate?>' <?php if(!empty($story->children)) echo "data-children=" . count($story->children);?> data-cases='<?php echo zget($storyCases, $story->id, 0);?>'>
                <?php if($this->app->getViewType() == 'xhtml'):?>
                <td class='c-id text-left'>
                <?php printf('%03d', $story->id);?>
                </td>
                <td class='text-left nobr' title='<?php echo $story->title?>'>
                  <?php
                  if($story->parent > 0) echo "<span class='label label-badge label-light' title={$gradeGroup[$story->type][$story->grade]}>{$gradeGroup[$story->type][$story->grade]}</span>";
                  echo $story->title;
                  ?>
                </td>
                <td><span class="<?php echo $story->pri ? 'label-pri label-pri-' . $story->pri : "";?>" title='<?php echo zget($lang->story->priList, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri);?></span></td>
                <td>
                  <span class='status-story status-<?php echo $story->status?>'>
                    <?php echo $this->processStatus('story', $story);?>
                  </span>
                </td>
                <?php else:?>
                <td class='c-id text-left'>
                  <?php if($canBatchAction):?>
                  <?php echo html::checkbox('storyIdList', array($story->id => sprintf('%03d', $story->id)));?>
                  <?php else:?>
                  <?php printf('%03d', $story->id);?>
                  <?php endif;?>
                </td>
                <?php if($canOrder):?><td class='sort-handler'><i class='icon-move'></i></td><?php endif;?>
                <td><span class="<?php echo $story->pri ? 'label-pri label-pri-' . $story->pri : "";?>" title='<?php echo zget($lang->story->priList, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri);?></span></td>
                <td class='text-left nobr' title='<?php echo $story->title?>'>
                  <?php
                  if($story->parent > 0) echo "<span class='label label-badge label-light' title={$gradeGroup[$story->type][$story->grade]}>{$gradeGroup[$story->type][$story->grade]}</span>";
                  echo html::a($viewLink , $story->title, '', "style='color: $story->color' data-app={$this->app->tab}");
                  ?>
                </td>
                <td class='text-left nobr' title='<?php echo zget($modulePairs, $story->module, '');?>'><?php echo zget($modulePairs, $story->module, '');?></td>
                <td><?php echo zget($users, $story->openedBy);?></td>
                <td>
                  <span class='status-story status-<?php echo $story->status?>'><?php echo $this->processStatus('story', $story);?></span>
                </td>
                <?php if($config->vision == 'or'):?>
                <td class='c-actions'>
                  <?php
                  if($canBeChanged and common::hasPriv('roadmap', 'unlinkUR'))
                  {
                      $unlinkURL      = $this->createLink('roadmap', 'unlinkUR', "story=$story->id&roadmap=$roadmap->id", '', true);
                      $unlinkDisabled = !in_array($story->status, array('developing', 'closed')) ? '' : 'disabled';
                      $unlinkTitle    = $lang->roadmap->unlinkUR;
                      echo html::a($unlinkURL, '<i class="icon-unlink"></i>', 'hiddenwin', "class='btn $unlinkDisabled iframe' data-width='90%' title='$unlinkTitle'");
                  }
                  ?>
                </td>
                <?php endif;?>
                <?php endif;?>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          <?php if($roadmapStories):?>
          <div class='table-footer'>
            <?php if($canBatchAction and $this->app->getViewType() != 'xhtml' and $config->vision == 'or'):?>
            <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
            <div class='table-actions btn-toolbar'>
              <div class='btn-group dropup'>
                <?php echo html::commonButton($lang->roadmap->unlinkURAB, "id='batchUnlinkBtn'", 'btn btn-primary');?>
                <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php
                  $class = "class='disabled'";

                  $actionLink = $this->createLink('story', 'batchClose', "productID={$roadmap->product}&executionID=0&storyType=requirement");
                  $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink', '', this)\"" : $class;
                  echo "<li>" . html::a('#', $lang->close, '', $misc) . "</li>";

                  $actionLink = $this->createLink('story', 'batchEdit', "productID=$roadmap->product&projectID=$projectID&branch=$branch&storyType=requirement");
                  $misc = $canBatchEdit ? "onclick=\"setFormAction('$actionLink', '', this)\"" : $class;
                  echo "<li>" . html::a('#', $lang->edit, '', $misc) . "</li>";

                  if($canBatchChangeRoadmap)
                  {
                      $roadmapClass      = in_array($roadmap->status, array('launching', 'launched', 'closed')) ? "disabled" : 'dropdown-submenu';
                      $changeRoadmapTips = sprintf($lang->roadmap->changeRoadmapTips, strtolower(zget($lang->roadmap->statusList, $roadmap->status)));
                      $roadmapTips       = in_array($roadmap->status, array('launching', 'launched', 'closed')) ? "title='{$changeRoadmapTips}'" : '';

                      echo "<li class='$roadmapClass' $roadmapTips>";
                      echo html::a('javascript:;', $this->lang->roadmap->common, '', "id='roadmapItem'");
                      echo "<div class='dropdown-menu with-search'>";
                      echo '<ul class="dropdown-list" id=roadmapRows>';
                      echo '</ul>';
                      echo "<div class='menu-search' id='searchMenu'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo "</li>";
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->roadmap->common, '', $class) . '</li>';
                  }

                  if($canBatchChangeBranch and $this->session->currentProductType != 'normal')
                  {
                      $withSearch = count($branchTagOption) > 8;
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->product->branchName[$this->session->currentProductType], '', "id='branchItem'");
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach($branchTagOption as $branchID => $branchName)
                      {
                          $actionLink = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID");
                          echo "<li class='option' data-key='$branchID'>" . html::a('#', $branchName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
                      }
                      echo '</ul>';
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo '</div></li>';
                  }

                  if($canBatchChangeModule)
                  {
                      $withSearch = count($modules) > 8;
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->story->moduleAB, '', "id='moduleItem'");
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach($modules as $moduleId => $module)
                      {
                          $actionLink = $this->createLink('story', 'batchChangeModule', "moduleID=$moduleId");
                          echo "<li class='option' data-key='$moduleId'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
                      }
                      echo '</ul>';
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo '</div></li>';
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->story->moduleAB, '', $class) . '</li>';
                  }

                  if($canBatchAssignTo)
                  {
                        $withSearch = count($users) > 10;
                        $actionLink = $this->createLink('story', 'batchAssignTo', "productID=$roadmap->product");
                        echo html::select('assignedTo', $users, '', 'class="hidden"');
                        echo "<li class='dropdown-submenu'>";
                        echo html::a('javascript::', $lang->story->assignedTo, '', 'id="assignItem"');
                        echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                        echo '<ul class="dropdown-list">';
                        foreach ($users as $key => $value)
                        {
                            if(empty($key) or $key == 'closed') continue;
                            echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", false, \"#storyList\")", $value, '', '') . '</li>';
                        }
                        echo "</ul>";
                        if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                        echo "</div></li>";
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->story->assignedTo, '', $class) . '</li>';
                  }
                  ?>
                </ul>
              </div>
            </div>
            <?php endif;?>
            <?php if($this->app->getViewType() != 'xhtml'):?>
            <div class='table-statistic'><?php echo $summary;?></div>
            <?php endif;?>
            <?php
            $this->app->rawParams['type'] = 'story';
            $storyPager->show('right', 'pagerjs');
            $this->app->rawParams['type'] = $type;
            ?>
          </div>
          <?php endif;?>
        </form>
      </div>
      <div id='roadmapInfo' class='tab-pane <?php if($type == 'roadmapInfo') echo 'active';?>'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->roadmap->basicInfo;?></div>
            <div class='detail-content'>
              <table class='table table-data table-condensed table-borderless'>
                <tr>
                  <th class='w-80px strong'><?php echo $lang->roadmap->name;?></th>
                  <td><?php echo $roadmap->name;?></td>
                </tr>
                <?php if($product->type != 'normal'):?>
                <tr>
                  <th><?php echo $lang->product->branch;?></th>
                  <?php
                  $branches = '';
                  foreach(explode(',', $branch) as $branchID) $branches .= "{$branchOption[$branchID]},";
                  ?>
                  <td><?php echo trim($branches, ',');?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->roadmap->begin;?></th>
                  <td><?php echo $roadmap->begin == $config->roadmap->future ? $lang->roadmap->future : $roadmap->begin;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->roadmap->end;?></th>
                  <td><?php echo $roadmap->end == $config->roadmap->future ? $lang->roadmap->future : $roadmap->end;?></td>
                </tr>
                <?php $this->printExtendFields($roadmap, 'table', 'inForm=0');?>
                <tr>
                  <th><?php echo $lang->roadmap->status;?></th>
                  <td><?php echo $lang->roadmap->statusList[$roadmap->status];?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->roadmap->desc;?></th>
                  <td><?php echo $roadmap->desc;?></td>
                </tr>
              </table>
            </div>
          </div>
          <?php if($this->app->getViewType() != 'xhtml') include $app->getModuleRoot() . 'common/view/action.html.php';?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('param', helper::safe64Decode($param))?>
<?php js::set('link', $link)?>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('type', $type)?>
<?php js::set('roadmapStories', $roadmapStories)?>
<?php js::set('canBatchChangeRoadmap', $canBatchChangeRoadmap)?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

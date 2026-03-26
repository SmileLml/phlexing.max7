<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('storyType', 'track');?>
<?php js::set('rawModule', $this->app->rawModule);?>
<style>
.main-table .table td{white-space:nowrap;text-overflow:ellipsis;overflow:hidden;position:unset !important;border-bottom-color:#ddd !important;}
.requirement{background: #fff}
.main-table tbody>tr:hover { background-color: #fff; }
.main-table tbody>tr:nth-child(odd):hover { background-color: #f5f5f5; }
.fix-table-copy-wrapper {overflow: unset !important;}
.table tr > th .dropdown > a.dropdown-toggle {display: flex; align-items: center;}
.table tr > th .dropdown > a.dropdown-toggle .product-name {overflow: hidden;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $moreMenu = array();
    unset($lang->demand->featureBar['browse']['more']);
    foreach($lang->demand->featureBar['browse'] as $label => $labelName)
    {
        $active = $browseType == $label ? 'btn-active-text' : '';
        echo html::a($this->createLink('demandpool', 'track', "poolID=$poolID&browseType=$label&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), '<span class="text">' . $labelName . '</span>' . ($browseType == $label ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active'");
    }

    /* More drop menu. */
    echo "<div class='btn-group' id='more'>";
    $current = $lang->demand->more;
    $active  = '';
    if(isset($lang->demand->moreSelects['browse']['more'][$browseType]))
    {
        $current = "<span class='text'>{$lang->demand->moreSelects['browse']['more'][$browseType]}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        $active  = 'btn-active-text';
    }
    echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $active'");
    echo "<ul class='dropdown-menu'>";
    foreach($lang->demand->moreSelects['browse']['more'] as $key => $value)
    {
        if($key == '') continue;
        echo '<li' . ($key == $browseType ? " class='active'" : '') . '>';
        echo html::a($this->createLink('demandpool', 'track', "poolID=$poolID&type=$key"), $value);
    }
    echo '</ul></div>';
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='demand' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=demand');?>"></div>
    <?php if(empty($tracks)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
      </p>
    </div>
    <?php else:?>
    <?php $tab    = $this->app->rawModule == 'projectstory' ? 'project' : 'product';?>
    <?php $module = $this->app->rawModule == 'projectstory' ? 'projectstory' : 'story';?>
    <div class='main-table' data-ride="table">
      <table class='table table-bordered' id="trackList">
        <thead>
          <tr class='text-left'>
            <th><?php echo $lang->demandpool->demand;?></th>
            <th><?php echo $lang->story->requirement;?></th>
            <th><?php echo $lang->storyCommon;?></th>
            <th><?php echo $lang->story->tasks;?></th>
            <th><?php echo $lang->story->design;?></th>
            <th><?php echo $lang->story->case;?></th>
            <th><?php echo $lang->story->repoCommit;?></th>
            <th><?php echo $lang->story->bug;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tracks as $key => $demand):?>
          <tr>
            <td <?php if($demand->trackCount != 0) echo "rowspan=" . $demand->trackCount;?> class='requirement' title='<?php echo $demand->title;?>'>
              <span class="label label-primary label-outline"><?php echo zget($lang->demand->statusList, $demand->status);?></span>
              <?php $title = common::hasPriv('demand', 'view') ? html::a($this->createLink('demand', 'view', "demandID=$demand->id"), $demand->title, '', "title=$demand->title") : $demand->title;?>
              <?php echo $title;?>
            </td>
            <?php if($demand->trackCount != 0):?>
            <?php $i = 0;?>
            <?php foreach($demand->track as $requirementID => $requirement):?>
            <?php if($i > 0) echo "<tr class='collapsed-border'>";?>
              <td <?php if($requirement->trackCount != 0) echo "rowspan=" . $requirement->trackCount;?> class='requirement' title='<?php echo $requirement->title;?>'>
                <span class="label label-primary label-outline"><?php echo zget($lang->story->statusList, $requirement->status);?></span>
                <?php $title = common::hasPriv($requirement->type, 'view') ? html::a($this->createLink('story', 'view', "storyID=$requirement->id&version=0&param=0&storyType=requirement"), $requirement->title, '', "title=$requirement->title data-app='$tab'") : $requirement->title;?>
                <?php echo $title;?>
              </td>
            <?php if($requirement->trackCount != 0):?>
            <?php $j = 0;?>
            <?php foreach($requirement->track as $storyID => $story):?>
            <?php if($j > 0) echo "<tr class='collapsed-border'>";?>
              <td>
                <?php if(isset($story->parent) && $story->parent > 0 && $story->parent != $requirementID):?><span class="label label-badge label-light" title="<?php echo $this->lang->story->children;?>"><?php echo $this->lang->story->childrenAB;?></span><?php endif;?>
                <?php //echo html::a($this->createLink($module, 'view', "storyID=$storyID"), $story->title, '', "title='$story->title' data-app='$tab'");?>
                <?php echo "<span title='{$story->title}'>" . $story->title . '</span>';?>
              </td>
              <td>
                <?php foreach($story->tasks as $taskID => $task):?>
                <?php //echo html::a($this->createLink('task', 'view', "taskID=$taskID"), $task->name, '', "title='$task->name'") . '<br/>';?>
                <?php echo "<span title='{$task->name}'>" . $task->name . '</span><br/>';?>
                <?php endforeach;?>
              </td>
              <td>
                <?php foreach($story->designs as $designID => $design):?>
                <?php //echo html::a($this->createLink('design', 'view', "designID=$designID"), $design->name, '', "title='$design->name'") . '<br/>';?>
                <?php echo "<span title='{$design->name}'>" . $design->name . '</span><br/>';?>
                <?php endforeach;?>
              </td>
              <td>
                <?php foreach($story->cases as $caseID => $case):?>
                <?php //echo html::a($this->createLink('testcase', 'view', "caseID=$caseID"), $case->title, '', "title='$case->title'") . '<br/>';?>
                <?php echo "<span title='{$case->title}'>" . $case->title . '</span><br/>';?>
                <?php endforeach;?>
              </td>
              <?php if(helper::hasFeature('devops')):?>
              <td>
                <?php foreach($story->revisions as $revision => $repoComment):?>
                <?php $comment = '#'. $revision . '-' . $repoComment;?>
                <?php //echo html::a($this->createLink('design', 'revision', "repoID=$revision"), $comment, '', "data-app='devops'") . '<br/>';?>
                <?php echo "<span title='{$comment}'>" . $comment . '</span><br/>';?>
                <?php endforeach;?>
              </td>
              <?php endif;?>
              <td>
                <?php foreach($story->bugs as $bugID => $bug):?>
                <?php //echo html::a($this->createLink('bug', 'view', "bugID=$bugID"), $bug->title, '', "title='$bug->title'") . '<br/>';?>
                <?php echo "<span title='{$bug->title}'>" . $bug->title . '</span><br/>';?>
                <?php endforeach;?>
              </td>
            <?php if($j > 0) echo '</tr>';?>
            <?php $j++;?>
            <?php endforeach;?>
            <?php else:?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php endif;?>
            <?php if($i > 0) echo '</tr>';?>
            <?php $i++;?>
            <?php endforeach;?>
            <?php else:?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

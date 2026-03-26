<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>#bugList td {overflow:hidden;text-overflow:ellipsis;white-space: nowrap;}</style>
<?php js::set('browseType', $browseType);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach(customModel::getFeatureMenu('repo', 'review') as $menuItem)
    {
        echo html::a($this->repo->createLink('review', "repoID=$repoID&browseType={$menuItem->name}"), "<span class='text'>{$menuItem->text}</span>", '', "id='{$menuItem->name}Tab' class='btn btn-link' data-app='{$app->tab}'");
    }
    ?>
  </div>
</div>

<div id='mainContent' class='main-table' data-ride='table'>
  <table class='table' id='bugList'>
    <thead>
      <tr class="colhead">
      <th class="c-id"><?php echo 'ID';?></th>
      <th><?php echo $lang->repo->title?></th>
      <th class='w-300px'><?php echo $lang->repo->file . '/' . $lang->repo->location?></th>
      <th class='w-100px'><?php echo $lang->repo->revisionA?></th>
      <th class='w-80px'><?php echo $lang->repo->type?></th>
      <th class='w-80px'><?php echo $lang->repo->status?></th>
      <th class='w-100px'><?php echo $lang->repo->openedBy?></th>
      <th class='w-100px'><?php echo $lang->repo->assignedTo?></th>
      <th class='w-120px'><?php echo $lang->repo->openedDate?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($bugs as $bug):?>
      <tr>
        <td><?php echo sprintf('%03d', $bug->id);?></td>
        <?php $lines = explode(',', trim($bug->lines, ','));?>
        <td><?php echo html::a($this->createLink('bug', 'view', "bugID={$bug->id}&from=repo"), $bug->title, '', "data-app='qa'")?></td>
        <td>
          <?php
          echo "<span class='label label-info'>$bug->lines</span> ";
          $entry = $repo->name . '/' . $this->repo->decodePath($bug->entry);
          if(empty($bug->v1))
          {
              $revision = $repo->SCM != 'Subversion' ? $this->repo->getGitRevisionName($bug->v2, (int)zget($historys, $bug->v2)) : $bug->v2;
              $link     = $this->repo->createLink('view', "repoID=$repoID&objectID=0&entry={$bug->entry}&revision={$bug->v2}") . "#L$lines[0]";
          }
          else
          {
              $revision  = $repo->SCM != 'Subversion' ? substr($bug->v1, 0, 10) : $bug->v1;
              $revision .= ' : ';
              $revision .= $repo->SCM != 'Subversion' ? substr($bug->v2, 0, 10) : $bug->v2;
              if($repo->SCM != 'Subversion') $revision .= ' (' . zget($historys, $bug->v1) . ' : ' . zget($historys, $bug->v2) . ')';
              $link = $this->repo->createLink('diff', "repoID=$repoID&objectID=0&entry={$bug->entry}&oldRevision={$bug->v1}&newRevision={$bug->v2}") . "#L$lines[0]";
          }
          echo html::a($link, $entry, '', "title='{$entry}' data-app='{$app->tab}'");
          ?>
        </td>
        <td><?php echo $repo->SCM != 'Subversion' ? substr(strtr($bug->v2, '*', '-'), 0, 10) : $bug->v2?></td>
        <td><?php echo isset($lang->repo->typeList[$bug->repoType]) ? $lang->repo->typeList[$bug->repoType] : ''?></td>
        <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status]?></td>
        <td><?php echo zget($users, $bug->openedBy)?></td>
        <td><?php echo zget($users, $bug->assignedTo)?></td>
        <td><?php echo substr($bug->openedDate, 5, 11)?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
  </table>
  <?php if($bugs):?>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

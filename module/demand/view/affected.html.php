<div class='tabs'>
  <ul class='nav nav-tabs'>
    <li class='active'><a data-toggle='tab' href='#affectedProjects'><?php echo $lang->demand->affectedProjects;?> <?php $count = count($story->executions); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
    <li><a data-toggle='tab' href='#affectedStories'><?php echo $lang->demand->affectedStories;?> <?php $count = count($story->stories); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
    <li><a data-toggle='tab' href='#affectedBugs'><?php echo $lang->demand->affectedBugs;?> <?php $count = count($story->bugs); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
    <li><a data-toggle='tab' href='#affectedCases'><?php echo $lang->demand->affectedCases;?> <?php $count = count($story->cases); if($count > 0) echo "<span class='label label-danger label-badge label-circle'>" . $count . "</span>" ?></a></li>
  </ul>
  <div class='tab-content'>
    <div class='tab-pane active' id='affectedProjects'>
      <?php foreach($story->executions as $executionID => $execution):?>
        <h6><?php echo $execution->name ?> &nbsp;
            <?php if(!empty($story->teams[$executionID])):?>
            <small><i class='icon-group'></i> <?php foreach($story->teams[$executionID] as $member) echo zget($users, $member->account) . ' ';?></small>
            <?php endif;?>
        </h6>
          <table class='table'>
            <thead>
              <tr class='text-center'>
                <th class='c-id'><?php echo $lang->task->id;?></th>
                <th class='text-left'><?php echo $lang->task->name;?></th>
                <th class='c-user'><?php echo $lang->task->assignedTo;?></th>
                <th class='c-status'><?php echo $lang->task->status;?></th>
                <th class='c-consumed'><?php echo $lang->task->consumed;?></th>
                <th class='c-left'><?php echo $lang->task->left;?></th>
              </tr>
            </thead>
            <?php if(isset($story->tasks[$executionID])):?>
            <tbody class='<?php if(count($story->tasks[$executionID]) > $config->story->affectedFixedNum)  echo "linkbox";?>'>
            <?php foreach($story->tasks[$executionID] as $task):?>
              <tr class='text-center'>
                <td><?php echo $task->id;?></td>
                <td class='text-left'><?php echo $task->name;?></td>
                <td><?php echo zget($users, $task->assignedTo);?></td>
                <td>
                  <span class='status-task status-<?php echo $task->status?>'><?php echo $this->processStatus('task', $task);?></span>
                </td>
                <td><?php echo $task->consumed;?></td>
                <td><?php echo $task->left;?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
            <?php endif;?>
          </table>
      <?php endforeach;?>
    </div>
    <div class='tab-pane' id='affectedStories'>
      <table class='table'>
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->story->id;?></th>
            <th class='text-left'><?php echo $lang->executionstory->common . $lang->nameAB;?></th>
            <th class='c-title'><?php echo $lang->product->name;?></th>
            <th class='c-status'><?php echo $lang->story->status;?></th>
            <th class='c-user'><?php echo $lang->story->openedBy;?></th>
          </tr>
        </thead>
        <tbody class= '<?php if(count($story->stories) > $config->story->affectedFixedNum) echo "linkbox";?>'>
          <?php foreach($story->stories as $storyItem):?>
          <tr class='text-center'>
            <td><?php echo $storyItem->id;?></td>
            <td class='text-left'><?php echo $storyItem->title;?></td>
            <td><?php echo $storyItem->productTitle;?></td>
            <td>
              <span class='status-bug status-<?php echo $storyItem->status?>'><?php echo $this->processStatus('story', $storyItem);?></span>
            </td>
            <td><?php echo zget($users, $storyItem->openedBy);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class='tab-pane' id='affectedBugs'>
      <table class='table'>
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='text-left'><?php echo $lang->bug->title;?></th>
            <th class='c-status'><?php echo $lang->statusAB;?></th>
            <th class='c-user'><?php echo $lang->bug->openedBy;?></th>
            <th class='c-user'><?php echo $lang->bug->resolvedBy;?></th>
            <th class='text-left'><?php echo $lang->bug->resolution;?></th>
            <th class='c-user'><?php echo $lang->bug->lastEditedBy;?></th>
          </tr>
        </thead>
        <tbody class= '<?php if(count($story->bugs) > $config->story->affectedFixedNum) echo "linkbox";?>'>
          <?php foreach($story->bugs as $bug):?>
          <tr class='text-center'>
            <td><?php echo $bug->id;?></td>
            <td class='text-left'><?php echo $bug->title;?></td>
            <td>
              <span class='status-bug status-<?php echo $bug->status?>'><?php echo $this->processStatus('bug', $bug);?></span>
            </td>
            <td><?php echo zget($users, $bug->openedBy);?></td>
            <td><?php echo zget($users, $bug->resolvedBy);?></td>
            <td class='text-left'><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
            <td><?php echo zget($users, $bug->lastEditedBy);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class='tab-pane' id='affectedCases'>
      <table class='table'>
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='text-left'><?php echo $lang->testcase->title;?></th>
            <th class='c-status'><?php echo $lang->statusAB;?></th>
            <th class='c-user'><?php echo $lang->testcase->openedBy;?></th>
            <th class='c-user'><?php echo $lang->testcase->lastEditedBy;?></th>
          </tr>
        </thead>
        <tbody class='<?php if(count($story->cases) > $config->story->affectedFixedNum)  echo "linkbox";?>'>
        <?php foreach($story->cases as $case):?>
          <tr class='text-center'>
            <td><?php echo $case->id;?></td>
            <td class='text-left'><?php echo $case->title;?></td>
            <td>
              <span class='status-case status-<?php echo $case->status?>'><?php echo $this->processStatus('testcase', $case);?></span>
            </td>
            <td><?php echo zget($users, $case->openedBy);?></td>
            <td><?php echo zget($users, $case->lastEditedBy);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>

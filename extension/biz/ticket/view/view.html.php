<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink = $this->session->ticketList ? $this->session->ticketList : $this->createlink('ticket', 'browse')?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()) echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $ticket->id?></span>
      <span class="text" title="<?php echo $ticket->title;?>" style='color: <?php echo isset($ticket->color) ? $ticket->color : ''; ?>'><?php echo $ticket->title;?></span>
      <span class='label label-status status-<?php echo $ticket->status;?>'><?php echo zget($lang->ticket->statusList, $ticket->status); ?></span>
      <span class='label label-info'><?php echo zget($products, $ticket->product); ?></span>
      <?php if($ticket->deleted) echo "<span class='label label-danger'>{$lang->ticket->deleted}</span>";?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->ticket->desc;?></div>
        <div class="detail-content article-content">
          <?php echo $ticket->desc;?>
        </div>
      </div>
      <?php if(!empty($feedback)):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->ticket->fromFeedback; ?></div>
        <div class='detail-content'>
          <table class='table table-bordered table-fixed'>
            <thead>
              <tr class='text-center'>
                <th class='w-100px'><?php echo $lang->feedback->id;?></th>
                <th><?php echo $lang->feedback->title;?></th>
                <th><?php echo $lang->feedback->status;?></th>
              </tr>
            </thead>
            <tbody>
              <tr class='text-center'>
                <td><?php echo $feedback->id; ?></td>
                <td title='<?php echo $feedback->title;?>'>
                  <?php echo common::hasPriv('feedback', $config->vision == 'lite' ? 'view' : 'adminView') ? html::a($this->createLink('feedback', $config->vision == 'lite' ? 'view' : 'adminView', "id={$feedback->id}"), $feedback->title, '', "class='text-primary'") : $feedback->title;?>
                </td>
                <td><?php echo zget($lang->feedback->statusList, $feedback->status); ?></td>
              </tr>
            </body>
          </table>
        </div>
      </div>
      <?php endif;?>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $ticket->createFiles, 'fieldset' => 'true', 'object' => $ticket, 'method' => 'view', 'showDelete' => false));?>
    </div>
    <?php if(!empty($ticket->resolution)):?>
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->ticket->resolution;?></div>
        <div class="detail-content article-content">
          <?php echo $ticket->resolution; ?>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $ticket->finishFiles, 'fieldset' => 'true', 'object' => $ticket, 'method' => 'view', 'showDelete' => false));?>
    </div>
    <?php endif;?>
    <?php $this->printExtendFields($ticket, 'div', "position=left&inCell=1&inForm=0");?>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=ticket&objectID=$ticket->id");?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <div class='divider'></div>
        <?php echo $this->ticket->buildOperateViewMenu($ticket->id);?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasicInfo' data-toggle='tab'><?php echo $lang->ticket->legendBasicInfo;?></a></li>
          <li><a href='#legendLife' data-toggle='tab'><?php echo $lang->ticket->legendLife;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasicInfo'>
            <table class="table table-data">
              <tbody>
                <tr valign='middle'>
                  <th class='w-100px'><?php echo $lang->ticket->product;?></th>
                  <td><?php if(!common::printLink('product', 'view', "productID=$ticket->product", zget($products, $ticket->product), '', "data-app='product'")) echo zget($products, $ticket->product);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->module;?></th>
                  <td><?php echo zget($modules, $ticket->module);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->openedBuild;?></th>
                  <td>
                  <?php
                  if(!empty($ticket->openedBuild))
                  {
                      $openedBuilds = '';
                      foreach(explode(',', str_replace(' ', '', $ticket->openedBuild)) as $openedBuild) $openedBuilds .= zget($builds, $openedBuild) . '<br />';
                      echo trim($openedBuilds, '<br />');
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->type;?></th>
                  <td><?php echo zget($lang->ticket->typeList, $ticket->type);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->source;?></th>
                  <td>
                    <?php
                    if(!empty($feedback))
                    {
                        if(!common::printLink('feedback', 'adminView', "feedbackID=$feedback->id", '#' . $feedback->id . ' ' . $feedback->title, '', "data-app='feedback'")) echo '#' . $feedback->id . ' ' . $feedback->title;
                    }
                    ?>
                  </td>
                </tr>
                <tr valign='middle'>
                  <th><?php echo $lang->ticket->status;?></th>
                  <td><span class="status-task status-<?php echo $ticket->status;?>"><span class='label label-dot'></span> <?php echo zget($lang->ticket->statusList, $ticket->status);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->pri;?></th>
                  <td><span class='label-pri label-pri-<?php echo $ticket->pri;?>' title='<?php echo zget($lang->ticket->priList, $ticket->pri);?>'><?php echo zget($lang->ticket->priList, $ticket->pri);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->createdBy;?></th>
                  <td><?php echo zget($users, $ticket->openedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->assignedTo;?></th>
                  <td><?php echo zget($users, $ticket->assignedTo);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->mailto;?></th>
                  <td>
                  <?php
                  if(!empty($ticket->mailto))
                  {
                      foreach(explode(',', str_replace(' ', '', $ticket->mailto)) as $account) echo ' ' . zget($users, $account);
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->estimate;?></th>
                  <td><?php echo $ticket->estimate .$lang->workingHour;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->consumed;?></th>
                  <td><?php echo $ticket->consumed . $lang->workingHour;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->deadline;?></th>
                  <td>
                  <?php if($ticket->deadline) echo helper::isZeroDate($ticket->deadline) ? '' : $ticket->deadline;?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendLife'>
            <table class='table table-data'>
              <tbody>
                <tr>
                  <th class='w-100px'><?php echo $lang->ticket->createdByAB;?></th>
                  <td><?php echo zget($users, $ticket->openedBy) . $lang->at . $ticket->openedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->assignedTo;?></th>
                  <td><?php if($ticket->assignedTo and $ticket->assignedTo != 'closed') echo zget($users, $ticket->assignedTo) . $lang->at . $ticket->assignedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->startedBy;?></th>
                  <td><?php if($ticket->startedBy) echo zget($users, $ticket->startedBy) . $lang->at . $ticket->startedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->finishedByAB;?></th>
                  <td><?php if($ticket->finishedBy) echo zget($users, $ticket->finishedBy) . $lang->at . $ticket->finishedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->closedByAB;?></th>
                  <td><?php if($ticket->closedBy) echo zget($users, $ticket->closedBy) . $lang->at . $ticket->closedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->closedReason;?></th>
                  <td><?php echo zget($lang->ticket->closedReasonList, $ticket->closedReason);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->activatedBy;?></th>
                  <td><?php if($ticket->activatedBy) echo zget($users, $ticket->activatedBy) . $lang->at . $ticket->activatedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->activatedCount;?></th>
                  <td><?php echo $ticket->activatedCount;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->editedByAB;?></th>
                  <td><?php if($ticket->editedBy) echo zget($users, $ticket->editedBy) . $lang->at . $ticket->editedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#contacts' data-toggle='tab'><?php echo $lang->ticket->contacts;?></a></li>
          <li><a href='#legendMisc' data-toggle='tab'><?php echo $lang->ticket->legendMisc;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='contacts'>
            <table class="table table-data">
              <tbody>
              <?php foreach($ticketSources as $ticketSource):?>
                <tr class="has-top-line">
                  <th class='w-110px'><?php echo $lang->ticket->customer;?></th>
                  <td><?php echo $ticketSource->customer;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->contact;?></th>
                  <td><?php echo $ticketSource->contact;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->notifyEmail;?></th>
                  <td><?php echo $ticketSource->notifyEmail;?></td>
                </tr>
              <?php endforeach;?>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendMisc'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th><?php echo $lang->ticket->story;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    foreach($stories as $story)
                    {
                        if($story->vision == $this->config->vision)
                        {
                            echo "<li title='$story->title'>" . html::a($this->createLink('story', 'view', "story=$story->id", '', true), "#$story->id $story->title", '', "class='iframe' data-width='80%'") . '</li>';
                        }
                        else
                        {
                            echo "#$story->id $story->title <br>";
                        }
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->bug;?></th>
                  <td class='pd-0'>
                    <ul class='list-unstyled'>
                    <?php
                    foreach($bugs as $bug)
                    {
                        if($this->config->vision != 'lite')
                        {
                            echo "<li title='$bug->title'>" . html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), "#$bug->id $bug->title", '', "class='iframe' data-width='80%'") . '</li>';
                        }
                        else
                        {
                            echo "#$bug->id $bug->title <br>";
                        }
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php $this->printExtendFields($ticket, 'div', "position=right&inCell=1&inForm=0");?>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

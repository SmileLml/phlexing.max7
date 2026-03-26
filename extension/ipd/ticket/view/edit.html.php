<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('notifyEmailRequired', $notifyEmailRequired); ?>
<?php
$disableStartedBy  = $disableStartedDate  = $ticket->status == 'wait' ? 'disabled' : '';
$disableResolvedBy = $disableResolvedDate = ($ticket->status == 'wait' or $ticket->status == 'doing') ? 'disabled' : '';
$disableClosedBy   = $disableClosedDate   = $ticket->status != 'closed' ? 'disabled' : '';
?>
<div class='main-content' id='mainContent'>
  <form method='post' class='form-ajax' enctype='multipart/form-data' id='dataform'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $ticket->id;?></span>
        <?php echo html::a($this->createLink('ticket', 'view', "ticketID=$ticket->id"), $ticket->title, '', "class='ticket-title' title='$ticket->title'");?>
        <small><?php echo $lang->arrow . ' ' . $lang->ticket->edit;?></small>
      </h2>
    </div>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='detail'>
          <div class='detail-title'><?php echo $lang->ticket->title;?></div>
            <div class='detail-content required'>
              <div class='form-group'>
                <div class="input-control">
                  <?php echo html::input('title', $ticket->title, "class='form-control ticket-title'");?>
                </div>
              </div>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->ticket->desc;?></div>
            <div class='detail-content'>
              <?php echo html::textarea('desc', htmlSpecialString($ticket->desc), "rows='12' class='form-control kindeditor' hidefocus='true'");?>
            </div>
          </div>
          <?php $this->printExtendFields($ticket, 'div', 'position=left');?>
          <div class="detail">
            <div class="detail-title"><?php echo $lang->ticket->descFiles;?></div>
            <div class='detail-content'>
              <?php echo $this->fetch('file', 'printFiles', array('files' => $ticket->createFiles, 'fieldset' => 'false', 'object' => $ticket, 'method' => 'edit'));?>
              <?php echo $this->fetch('file', 'buildoldform', 'fileCount=&percent=&filesName=createFiles&labelsName=createLabels');?>
            </div>
          </div>
          <?php $ticketResolution = trim($ticket->resolution);?>
          <?php if(!empty($ticketResolution)):?>
          <div class='detail resolution-detail required'>
            <div class='detail-title'><?php echo $lang->ticket->resolution;?></div>
            <div class='detail-content article-content'>
              <?php echo html::textarea('resolution', isset($ticket->resolution) ? htmlSpecialString($ticket->resolution) : '', "rows='5' class='form-control kindeditor' hidefocus='true'");?>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><?php echo $lang->ticket->resolutionFiles;?></div>
            <div class='detail-content'>
              <?php echo $this->fetch('file', 'printFiles', array('files' => $ticket->finishFiles, 'fieldset' => 'false', 'object' => $ticket, 'method' => 'edit'));?>
              <?php echo $this->fetch('file', 'buildoldform', 'fileCount=&percent=&filesName=finishFiles&labelsName=finishLabels');?>
            </div>
          </div>
          <?php endif;?>
          <div class='actions form-actions text-center'>
            <?php
            //echo html::hidden('lastEditedDate', $ticket->lastEditedDate);
            echo html::submitButton();
            echo html::backButton();
            ?>
          </div>
          <hr class='small' />
          <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->ticket->legendBasicInfo;?></div>
            <table class='table table-form'>
              <tbody>
                <tr>
                  <th class='w-100px'><?php echo $lang->ticket->product;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('product', $products, $ticket->product, " class='form-control chosen' onchange='loadAll(this.value)'");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->ticket->module;?></th>
                  <td>
                    <div class='input-group' id='moduleBox'>
                    <?php echo html::select('module', $modules, $ticket->module, " class='form-control chosen'"); ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->openedBuild;?></th>
                  <td>
                    <div class='input-group'>
                    <?php echo html::select('openedBuild[]', $builds, $ticket->openedBuild, " class='form-control chosen' multiple"); ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->type;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('type', $lang->ticket->typeList, $ticket->type, " class='form-control chosen'");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->source;?></th>
                  <td>
                    <div class='input-group'>
                    <?php
                    if(!empty($feedback))
                    {
                        if(!common::printLink('feedback', 'adminView', "feedbackID=$feedback->id", $feedback->id . ' ' . $feedback->title, '', "data-app='feedback'")) echo $feedback->id . ' ' . $feedback->title;
                    }
                    else
                    {
                        echo $lang->noData;
                    }
                    ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->status;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('status', $lang->ticket->statusList, $ticket->status, " class='form-control chosen' disabled");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->pri;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('pri', $lang->ticket->priList, $ticket->pri, " class='form-control chosen'");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->assignedTo;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('assignedTo', $users, $ticket->assignedTo, " class='form-control chosen'");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->keywords;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('keywords', $ticket->keywords, " class='form-control'");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->mailto;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('mailto[]', $users, $ticket->mailto, "class='form-control picker-select' multiple");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->estimate;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('estimate', $ticket->estimate, "class='form-control ticket-estimate'");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->deadline;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('deadline', helper::isZeroDate($ticket->deadline) ? '' : $ticket->deadline, "class='form-control form-date'");?>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->ticket->legendLife;?></div>
            <table class='table table-form'>
              <tbody>
                <tr>
                  <th class='w-100px'><?php echo $lang->ticket->createdBy;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo zget($users, $ticket->openedBy);?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->startedBy;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('startedBy', $users, $ticket->startedBy, " class='form-control chosen' $disableStartedBy");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->startedDate;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('startedDate', helper::isZeroDate($ticket->startedDate) ? '' : $ticket->startedDate, "class='form-control form-date' $disableStartedDate");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->resolvedBy;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('resolvedBy', $users, $ticket->resolvedBy, " class='form-control chosen' $disableResolvedBy");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->resolvedDate;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('resolvedDate', helper::isZeroDate($ticket->resolvedDate) ? '' : $ticket->resolvedDate, "class='form-control form-date' $disableResolvedDate");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->closedBy;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('closedBy', $users, $ticket->closedBy, " class='form-control chosen' disabled");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->closedDate;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('closedDate', helper::isZeroDate($ticket->closedDate) ? '' : $ticket->closedDate, "class='form-control form-date' disabled");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->closedReason;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('closedReason', $ticket->closedReason, " class='form-control' disabled");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-80px'><?php echo $lang->ticket->activatedBy;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('activatedBy', $users, $ticket->activatedBy, " class='form-control chosen' disabled");?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class='w-100px'><?php echo $lang->ticket->activatedCount;?></th>
                  <td><?php echo $ticket->activatedCount;?></td>
                </tr>
                <tr>
                  <th class='w-100px'><?php echo $lang->ticket->lastEditedBy;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::select('editedBy', $users, $ticket->editedBy, " class='form-control chosen'");?>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->ticket->contacts;?></div>
            <table class='table table-form'>
              <tbody>
              <?php foreach($ticketSources as $i => $ticketSource):?>
                <tr class='customerBox'>
                  <th class='thWidth'><?php echo $lang->ticket->customer;?></th>
                  <td><?php echo html::input("customer[$i]", $ticketSource->customer, "class='form-control ticket-customer'");?></td>
                  <td class='w-100px'></td>
                </tr>
                <tr class='contactBox'>
                  <th class='thWidth'><?php echo $lang->ticket->contact;?></th>
                  <td><?php echo html::input("contact[$i]", $ticketSource->contact, "class='form-control ticket-contact'");?></td>
                  <td class='w-100px'>
                    <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
                    <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
                  </td>
                </tr>
                <tr class='notifyEmailBox'>
                  <th class='thWidth'><?php echo $lang->ticket->notifyEmail;?></th>
                  <td><?php echo html::input("notifyEmail[$i]", $ticketSource->notifyEmail, "class='form-control ticket-email'");?></td>
                  <td class='w-100px'></td>
                </tr>
              <?php endforeach;?>
              <?php js::set('itemIndex', $i + 1);?>
              </tbody>
            </table>
          </div>
          <?php $this->printExtendFields($ticket, 'div', 'position=right');?>
        </div>
      </div>
    </div>
  </form>
</div>
<?php $i = '%i%';?>
<table class="hidden">
  <tbody id="addItem">
    <tr class='customerBox'>
      <th class='thWidth'><?php echo $lang->ticket->customer;?></th>
      <td><?php echo html::input("customer[$i]", '', "class='form-control ticket-customer'");?></td>
      <td class='w-100px'></td>
    </tr>
    <tr class='contactBox'>
      <th class='thWidth'><?php echo $lang->ticket->contact;?></th>
      <td><?php echo html::input("contact[$i]", '', "class='form-control ticket-contact'");?></td>
      <td class='w-100px'>
        <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
    <tr class="notifyEmailBox">
      <th class='thWidth'><?php echo $lang->ticket->notifyEmail;?></th>
      <td><?php echo html::input("notifyEmail[$i]", '', "class='form-control ticket-email'");?></td>
      <td class='w-100px'></td>
    </tr>
  </table>
</table>

<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

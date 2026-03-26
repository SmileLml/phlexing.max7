<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php'
?>
<div class='row'>
  <div class='col-md-12'>
    <div class='clearfix'>
      <div class='panel'>
        <div class='panel-heading' style='padding: 15px;'>
          <strong><i class='icon-comments'></i> <?php echo $logTitle; ?></strong>
          <div class="pull-right panel-actions form-container">
            <form method='post' class='form-search pull-right'>
              <?php echo html::select('objectType', $lang->log->typeArray, $objectType, "class='form-control' required") ?>
              <?php echo html::input('search', $search, "class='form-control search-query' placeholder='{$lang->user->inputAccount}'"); ?>
              <input id="date" name="date" type="text" class="form-control form-date" placeholder="<?php echo $lang->log->datePlaceholder; ?>" readonly="readonly" value="<?php echo $date; ?>">
              <span class="btn-group">
                <?php echo html::submitButton($lang->user->searchUser, "btn btn-primary"); ?>
                <?php echo html::a(inlink('browse'), $lang->log->reset, "class='btn'"); ?>
              </span>
            </form>
          </div>
        </div>
        <table class='table table-hover table-striped table-bordered tablesorter table-fixed'>
          <thead>
            <tr>
              <?php $vars = "orderBy=%s&objectType={$objectType}&search={$search}&date={$date}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
              <th>ID</th>
              <th><?php echo $lang->log->objectType; ?></th>
              <th><?php echo $lang->log->objectId; ?></th>
              <th><?php echo $lang->log->actor; ?></th>
              <th><?php echo $lang->log->action; ?></th>
              <th><?php echo $lang->log->result; ?></th>
              <th><?php echo $lang->log->ip; ?></th>
              <th><?php commonModel::printOrderLink('date', $orderBy, $vars, $lang->log->date); ?></th>
              <th><?php echo $lang->log->comment; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $index = 0; ?>
            <?php foreach($records as $record) : ?>
              <tr>
                <td><?php echo ++$index + ($pager->pageID - 1) * intval($pager->recPerPage); ?></td>
                <td><?php echo $lang->log->typeArray[$record->objectType]; ?></td>
                <td><?php echo $record->objectID; ?></td>
                <td><?php echo $userPairs[$record->actor]; ?></td>
                <td><?php echo isset($lang->log->actionName[$record->action]) ? $lang->log->actionName[$record->action] : $record->action; ?></td>
                <td><?php echo $lang->log->resultName[$record->result]; ?></td>
                <td><?php echo $record->ip; ?></td>
                <td><?php echo $record->date; ?></td>
                <td>
                  <?php
                    echo empty($record->comment)
                      ? '--'
                      : "<a class='show-comment' data-toggle='modal' data-target='#comment-modal' data-comment={$record->comment}>{$lang->log->view}</a>"
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class='table-footer'><?php $pager->show(); ?></div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="comment-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><?php echo $lang->log->comment; ?></h4>
      </div>
      <div class="modal-body" style="word-break: break-word;">
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>

<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $charter->id;?></span>
        <?php echo isonlybody() ? ('<span title="' . $charter->name . '">' . $charter->name . '</span>') : html::a($this->createLink('charter', 'view', 'charter=' . $charter->id), $charter->title);?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <?php $disabled = empty($charter->reviewedResult) ? '' : 'disabled';?>
          <?php if(!$disabled) $disabled = empty($launchedCharter) ? '' : 'disabled';?>
          <th class='w-100px'><?php echo $lang->charter->review;?></th>
          <td class='w-p100-f required'><?php echo html::select('reviewedResult', array('') + $lang->charter->reviewResultList, $charter->reviewedResult, "class='form-control chosen $disabled' $disabled");?></td>
          <td><span class='roadmapConflict'><?php if($launchedCharter and $launchedCharter->id != $charter->id and $charter->status == 'wait') echo sprintf($this->lang->charter->roadmapConflict, $launchedCharter->id, $launchedCharter->name);?><span></td>
        </tr>
        <tr>
          <th><?php echo $lang->charter->reviewer;?></th>
          <td class='required'><?php echo html::select('reviewedBy[]', $users, $charter->reviewedBy, "class='form-control picker-select' multiple");?></td>
          <td>
          <div style="padding-left:10px">
            <div class='checkbox-primary'>
            <input id='check' name='check' value='1' type='checkbox' <?php echo $charter->check ? 'checked' : '';?>/>
             <label for='createBuild' title="{lang->charter->meetingMinutes}"><?php echo $lang->charter->meetingMinutes;?></label>
            </div>
          </div>
          </td>
        </tr>
        <!--<tr><td colspan='2' id='ipmt'><?php echo $lang->charter->IPMT;?></td></tr>-->
        <tr id="meetingBox" class="hidden">
          <th><?php echo $lang->charter->meetingDate;?></th>
          <td>
             <?php echo html::input('meetingDate', $charter->meetingDate ? $charter->meetingDate : helper::today(), "class='form-control form-date'");?>
          </td>
          <td></td>
        </tr>
        <tr id="meetingBox" class="hidden">
          <th><?php echo $lang->charter->meetingLocation;?></th>
          <td>
            <?php echo html::input('meetingLocation', $charter->meetingLocation, "class='form-control'")?>
          </td>
          <td></td>
        </tr>
        <tr id="meetingBox" class="hidden">
          <th><?php echo $lang->charter->meetingMinutes;?></th>
          <td colspan="2">
            <?php echo html::textarea('meetingMinutes', $charter->meetingMinutes, "rows='6' class='form-control kindeditor' hidefocus='true'");?>
          </td>
        </tr>
        </div>
        <tr>
          <td class='form-actions text-center' colspan='3'>
          <?php echo html::submitButton($lang->save, "", 'btn btn-primary btn-wide needReview');?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

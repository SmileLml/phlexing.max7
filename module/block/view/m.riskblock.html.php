<table class='table bordered'>
  <thead>
    <tr>
      <th class='c-name'><?php echo $lang->risk->name?></th>
      <th class='c-pri w-50px'><?php echo $lang->priAB?></th>
      <th class='c-category w-80px'><?php echo $lang->risk->category;?></th>
      <th class='c-plannedClosedDate w-110px'><?php echo $lang->risk->plannedClosedDate;?></th>
      <th class='c-status w-80px'><?php echo $lang->risk->status;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($risks as $risk):?>
    <tr>
      <td class='c-name' title='<?php echo $risk->name?>'><?php echo html::a($this->createLink('risk', 'view', "riskID=$risk->id", '', '', $risk->project), $risk->name)?></td>
      <td class='c-pri'><?php echo "<span class='pri-{$risk->pri}'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
      <td class='c-category'><?php echo zget($lang->risk->categoryList, $risk->category)?></td>
      <td class='c-plannedClosedDate'><?php echo $risk->plannedClosedDate == '0000-00-00' ? '' : $risk->plannedClosedDate;?></td>
      <?php $status = $this->processStatus('risk', $risk);?>
      <td class='c-status' title='<?php echo $status;?>'>
        <span class="status-risk status-<?php echo $risk->status?>"><?php echo $status;?></span>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>

<table class='table bordered'>
  <thead>
    <tr>
      <th class='c-name'><?php echo $lang->issue->title?></th>
      <th class='c-pri w-50px'><?php echo $lang->priAB?></th>
      <th class='c-category w-80px'><?php echo $lang->issue->severity;?></th>
      <th class='c-identifiedDate w-110px'><?php echo $lang->issue->deadline;?></th>
      <th class='c-status w-80px'><?php echo $lang->issue->status;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($issues as $issue):?>
    <tr>
      <td class='c-name' title='<?php echo $issue->title?>'><?php echo html::a($this->createLink('issue', 'view', "issueID=$issue->id", '', '', $issue->project), $issue->title)?></td>
      <td class='c-pri'><?php echo zget($lang->issue->priList, $issue->pri)?></td>
      <td class='c-severity'><?php echo zget($lang->issue->severityList, $issue->severity)?></td>
      <td class='c-deadline'><?php echo $issue->deadline == '0000-00-00' ? '' : $issue->deadline;?></td>
      <?php $status = $this->processStatus('issue', $issue);?>
      <td class='c-status' title='<?php echo $status;?>'>
        <span class="status-issue status-<?php echo $issue->status?>"><?php echo $status;?></span>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>

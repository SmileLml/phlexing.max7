<?php js::set('errNoData', $lang->error->noData);?>
<form method='post' onsubmit='setDownloading()' action='<?php echo $this->createLink('weekly', 'exportweeklyreport', "module=$module&projectID=$projectID&productID=$productID");?>' target='hiddenwin' id='exportForm'>
  <table class="w-p100 table-form">
    <tr>
      <th class="w-110px"><?php echo $lang->setFileName;?></th>
      <td class="w-150px"><?php echo html::input('fileName', '', 'class="form-control"');?></td>
      <td><?php echo html::submitButton($lang->save, '', 'btn btn-primary upload-btn') . html::hidden('selectedWeekBegin');?></td>
    </tr>
  </table>
</form>
<script>
$(function()
{
    $('#exportForm #submit').click(function()
    {
        if(selectedWeekBegin === undefined || selectedWeekBegin === '')
        {
            alert(errNoData);
            return false;
        }

        $('#exportForm #selectedWeekBegin').val(selectedWeekBegin);

        return true;
    });
})

function setDownloading()
{
    /* Opera don't support, omit it. */
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;
    $.cookie('downloading', 0);
    $('.upload-btn').attr('disabled', 'disabled').addClass('disabled loading');
    time = setInterval("closeWindow()", 300);
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        $.cookie('downloading', null);
        clearInterval(time);
        location.reload('parent.parent');
    }
}
</script>

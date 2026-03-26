<?php js::import($this->app->getWebRoot() . 'js/sheetjs/xlsx.full.min.js');?>
<?php js::import($this->app->getWebRoot() . 'js/filesaver/filesaver.js');?>
<?php $this->app->loadLang('file');?>
<?php js::set('untitled', $lang->file->untitled);?>
<div class="modal fade" id='export'>
  <div class="modal-dialog" style='width: 500px'>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">× </span></button>
        <h2 class="modal-title" style='font-weight: bold;'><?php echo $lang->export;?></h2>
      </div>
      <div class="modal-body">
        <div style="margin: 20px 50px 20px 30px;">
        <table class="table table-form" style="padding:30px">
          <tbody>
          <tr>
            <th class='w-120px'><?php echo $lang->setFileName;?></th>
            <td><?php echo html::input('fileName', '', "class='form-control' autofocus placeholder='{$lang->file->untitled}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->pivot->exportType;?></th>
            <td><?php echo html::select('fileType',  $config->pivot->fileType, '', 'class="form-control" style="width: 140px"');?></td>
          </tr>
          <?php if(isset($exportMode) and $exportMode == 'preview'):?>
          <tr>
            <th><?php echo $lang->pivot->exportRange;?></th>
            <td><?php echo html::select('range',  $lang->pivot->rangeList, '', 'class="form-control" style="width: 140px"');?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th></th>
            <td style='padding-left: 30px;'><button class='btn btn-primary' onclick='exportData()'><?php echo $lang->save;?></button></td>
          </tr>
          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    /* Page is not initialized before clicking export will have bug. */
    $('.btn-export').removeClass('hidden');
})

/**
 * Export file.
 *
 * @param  object $domObj
 * @access public
 * @return void
 */
function exportFile($domObj)
{
    if(typeof $domObj == 'undefined') return;

    var fileName  = $('#fileName').val().trim() ? $('#fileName').val().trim() : untitled;
    var fileType  = $('#fileType').val();
    var tableName = fileName + '.' + fileType;

    if(fileType == 'xlsx' || fileType == 'xls')
    {
        const new_sheet = XLSX.utils.table_to_book($domObj, {raw: true});
        XLSX.writeFile(new_sheet, tableName);
    }
    else if(fileType == 'html' || fileType == 'mht')
    {
        const htmlContent = $domObj.outerHTML;

        const $temp = $('<div>').html(htmlContent);
        $temp.find('*').removeAttr('style');
        $temp.find('*').removeAttr('class');
        $temp.find('*').removeAttr('data-flex');
        $temp.find('*').removeAttr('data-width');
        $temp.find('*').removeAttr('data-type');
        $temp.find('*').removeAttr('data-fixed-left-width');
        const cleanTableHTML = $temp.html();

        var head  = '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        var style = '<style>table, th, td {font-size: 12px; border: 1px solid gray; border-collapse: collapse;}table th, table td {padding: 5px;}</style>';
        var title = '<title>' + fileName + '</title></head>';
        var body  = '<body>' + cleanTableHTML + '</body>';
        const finalHTML = head + style + title + body;

        if(fileType == 'html')
        {
            const blob = new Blob([finalHTML], { type: 'text/html;charset=utf-8' });
            saveAs(blob, tableName);
        }
        else if(fileType == 'mht')
        {
            const data = {html: finalHTML, fileName: fileName};
            $.post(createLink('file', 'ajaxExport2mht'), data, function(resp)
            {
                const blob = new Blob([resp], { type: "application/x-mimearchive" });
                saveAs(blob, tableName);
            });
        }
    }
    $('#export').modal('hide');
}
</script>

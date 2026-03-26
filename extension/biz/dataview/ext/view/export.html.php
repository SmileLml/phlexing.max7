<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php js::set('type', $type);?>
<script>
$(function()
{
    if(type != 'query') return;

    var dataviewSql   = JSON.parse(sessionStorage.getItem('dataviewSql'));
    var fieldSettings = sessionStorage.getItem('fieldSettings');
    var langs         = sessionStorage.getItem('langs');
    $.each(dataviewSql, function(index, value)
    {
        if(value.name == 'sql')
        {
            dataviewSql = value.value;
            return;
        }
    });

    $('#sql').val(dataviewSql);
    $('#fields').val(fieldSettings);
    $('#langs').val(langs);
});

function setDownloading()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;   // Opera don't support, omit it.

    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        parent.$.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
</script>
<main id="main">
  <div class="container">
    <div id="mainContent" class='main-content load-indicator'>
      <div class='main-header'>
        <h2><?php echo $lang->export;?></h2>
      </div>
      <form method='post' target='hiddenwin' onsubmit='setDownloading();' style='margin:20px 0px;'>
        <table class='table table-form' style='padding:30px'>
          <tr>
            <th class='w-150px'><?php echo $lang->setFileName;?></th>
            <td><?php echo html::input('fileName', $fileName, "class='form-control'");?></td>
            <td>
              <?php
              echo html::select('fileType',   $lang->exportFileTypeList, '', 'class="form-control"');
              ?>
            </td>
            <td class='hidden'><?php echo html::hidden('sql');?></td>
            <td class='hidden'><?php echo html::hidden('fields');?></td>
            <td class='hidden'><?php echo html::hidden('langs');?></td>
            <td><?php echo html::submitButton('', '', 'btn btn-primary');?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</main>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>

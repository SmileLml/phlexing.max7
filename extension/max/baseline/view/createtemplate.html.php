<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/markdown.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->baseline->createTemplate;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-110px'><?php echo $lang->baseline->templateType;?></th>
            <td><?php echo html::select('templateType', $lang->baseline->objectList, '', "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th class='w-110px'><?php echo $lang->baseline->templateTitle;?></th>
            <td><?php echo html::input('title', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th class='w-110px'><?php echo $lang->baseline->docType;?></th>
            <?php unset($lang->baseline->docTypeList['markdown']);?>
            <td><?php echo html::radio('type', $lang->baseline->docTypeList, 'html', "onchange=toggleEditor(this.value)");?></td>
          </tr>
          <tr id='contentBox'>
            <th class='w-110px'><?php echo $lang->baseline->docContent;?></th>
            <td colspan='2'>
              <div class='contenthtml'><?php echo html::textarea('content', '', "style='width:100%;height:200px'");?></div>
              <div class='contentmarkdown hidden'><?php echo html::textarea('contentMarkdown', '', "style='width:100%;height:200px'");?></div>
              <?php echo html::hidden('contentType', 'html');?>
            </td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton() . ' ' . html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>

<script>
function toggleEditor(type)
{
    if(type == 'html')
    {
        $('#contentBox').removeClass('hidden');
        $('.contenthtml').removeClass('hidden');
        $('.contentmarkdown').addClass('hidden');
    }
    else if(type == 'markdown')
    {
        $('#contentBox').removeClass('hidden');
        $('.contenthtml').addClass('hidden');
        $('.contentmarkdown').removeClass('hidden');
    }
    else if(type == 'book')
    {
        $('#contentBox').addClass('hidden');
    }
    $('#contentType').val(type);
    return false;
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

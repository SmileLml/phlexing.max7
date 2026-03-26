<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $biPath = $this->app->getModuleExtPath('bi', 'view');?>

<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $title;?></h2>
    </div>
  </div>
  <form id='ajaxForm' method='post' target='hiddenwin'  action='<?php echo inlink('publish', "screenID={$screen->id}");?>'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->screen->name;?></th>
        <td><?php echo html::input('name', $screen->name, "class='form-control'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->screen->desc;?></th>
        <td><?php echo html::textarea('desc', $screen->desc, "class='form-control' rows='5'");?></td>
      </tr>
      <?php $acl       = $screen->acl;?>
      <?php $aclList   = $lang->screen->aclList;?>
      <?php $whitelist = $screen->whitelist;?>
      <?php include $biPath['common'] . 'aclbox.html.php';?>
      <tr class="hidden">
        <td><?php echo html::input('status', 'published', "class='form-control'")?></td>
        <td><?php echo html::input('scheme', '', "class='form-control'")?></td>
        <td><?php echo html::input('uuid', 'screen_thumbnail' . $screen->id, "class='form-control'")?></td>
        <td><input name="thumbnail" type="file" id="thumbnail" /></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>

<script>
$(document).ready(function()
{
    $('.hidden input#scheme').val(JSON.stringify(parent.window.storageInfo));
    if(!parent.window.fileDataUrl)
    {
        $('#thumbnail').remove();
        return;
    }
    const blob = dataURLToBlob(parent.window.fileDataUrl);
    // 获取文件输入字段
    const fileInput = document.getElementById('thumbnail');

    // 创建一个新的DataTransfer对象
    const dataTransfer = new DataTransfer();

    // 从Blob创建一个新的File对象
    const file = new File([blob], 'screen_thumbnail_' + parent.window.screen.id + '.png', { type: 'png' });

    // 将File对象添加到DataTransfer对象中
    dataTransfer.items.add(file);

    // 使用DataTransfer对象设置输入元素的files属性
    fileInput.files = dataTransfer.files;
})

// 将数据 URL 转换为 Blob 对象的函数
function dataURLToBlob(dataURL) {
  const arr = dataURL.split(',');
  const mime = arr[0].match(/:(.*?);/)[1];
  const bstr = atob(arr[1]);
  let n = bstr.length;
  const u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new Blob([u8arr], { type: mime });
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

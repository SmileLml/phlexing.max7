<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>

<?php
$webRoot = $this->app->getWebRoot();
$jsRoot  = $webRoot . "js/";
js::import($jsRoot . 'uploader/min.js');
css::import($jsRoot . 'uploader/min.css');
js::set('uid', $uid);
?>
<style>.actions .file-status {display: none}</style>
<script>
$.zui.Uploader.LANG.de = $.zui.Uploader.LANG.fr = $.zui.Uploader.LANG.en;
</script>
<main>
  <div class="container">
    <div id="mainContent" class='main-content'>
      <div class='main-header'>
        <h2><?php echo $lang->traincourse->uploadCourse;?></h2>
      </div>
      <form enctype='multipart/form-data' method='post' class='form-ajax' style='padding: 20px 0 15px'>
        <div id='uploader' class="uploader" data-ride="uploader" data-url="<?php echo $this->createLink('traincourse', 'ajaxUploadLargeFile', "uid=$uid")?>">
          <div class="uploader-message text-center">
            <div class="content"></div>
            <button type="button" class="close">×</button>
          </div>
          <div class="uploader-files file-list file-list-lg" data-drag-placeholder="<?php echo $lang->traincourse->drag?>"></div>
          <div class="uploader-actions">
            <div class="uploader-status pull-right text-muted"></div>
            <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> <?php echo $lang->traincourse->addFile?></button>
          </div>
          <div class='form-actions text-center'>
            <?php echo html::submitButton();?>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>
<script>
var durationList = {};
$('#uploader #submit').attr('disabled', 'disabled');
$('#uploader .btn-rename-file').hide();
$('#uploader').uploader({
    autoUpload: true,
    deleteActionOnDone: false,
    rename: false,
    filters:
    {
        mime_types: [
        {title: 'uploadImages', extensions: 'zip'},
        ],
        prevent_duplicates: true
    },
    onUploadProgress: function(file)
    {
        $('#uploader .btn-rename-file').hide();
        $('#uploader .file .file-icon .icon').addClass('icon-file');
    },
    onUploadComplete: function(file)
    {
        $('#uploader .file .file-icon .icon').addClass('icon-file');
        $('#uploader #submit').removeAttr('disabled');
        $('#uploader .file .icon-repeat').removeClass('icon-repeat').addClass('icon-refresh');
    },
    onFilesAdded: function(files)
    {
        $('.actions button').last().removeAttr('data-original-title');
    },
    onBeforeUpload: function(file)
    {
        $('#uploader .btn-rename-file').hide();
        var duration = null;
        if(durationList[file.id]) duration = durationList[file.id];
        this.plupload.setOption(
        {
            'multipart_params':
            {
                label: file.ext ? file.name.substr(0, file.name.length - file.ext.length - 1) : file.name,
                uuid: file.id,
                size: file.size,
                duration: duration,
            }
        });
    }
});

parent.$('#triggerModal .modal-content .modal-header .close').click(function()
{
    if(!$('#uploader').data('zui.uploader').isStopped())
    {
        if(confirm('<?php echo $lang->traincourse->confirmClose;?>'))
        {
            $('#uploader').data('zui.uploader').stop();
        }
        else
        {
            return false;
        }
    }
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>

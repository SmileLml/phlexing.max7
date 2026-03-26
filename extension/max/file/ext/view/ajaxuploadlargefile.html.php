<?php
$webRoot = $this->app->getWebRoot();
$jsRoot  = $webRoot . "js/";
js::import($jsRoot . 'uploader/min.js');
css::import($jsRoot . 'uploader/min.css');
js::set('uid', $uid);
js::set('module', $module);
?>
<div id='uploader' class="uploader" data-ride="uploader" data-url="<?php echo $this->createLink('file', 'ajaxUploadLargeFile', "module=$module&uid=$uid")?>">
  <div class="uploader-message text-center">
    <div class="content"></div>
    <button type="button" class="close">×</button>
  </div>
  <div class="uploader-files file-list file-list-lg" data-drag-placeholder="<?php echo $lang->file->drag?>"></div>
  <div class="uploader-actions">
    <div class="uploader-status pull-right text-muted"></div>
    <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> <?php echo $lang->file->addFile?></button>
    <button type='button' class='btn btn-link uploader-btn-start'><i class='icon icon-arrow-up'></i><?php echo $lang->file->beginUpload;?></button>
  </div>
</div>
<script>
var durationList = {}; 
$('#uploader').uploader({
    limitFilesCount: 1,
    filters:
    {
        mime_types: [
        {title: 'uploadImages', extensions: 'jpeg,jpg,gif,png,.bmp,flv,swf,mkv,avi,rm,rmvb,mpeg,mpg,ogg,ogv,mov,wmv,mp4,webm,mp3,wav,mid,doc,docx,dot,wps,wri,pdf,ppt,pptx,xls,xlsx,ett,xlt,xlsm,csv'},
        ],
        prevent_duplicates: true
    },
    onUploadProgress: function(file)
    {
        $('#uploader .file .file-icon .icon').addClass('icon-file');
    },
    onUploadComplete: function(file)
    {
        $('#uploader .file .file-icon .icon').addClass('icon-file');
    },
    onFilesAdded: function(files)
    {
        var self = this;
        for(var i = 0; i < files.length; i++)
        {
            var file = files[i]
            if(file.ext == 'mp4' || file.ext == 'wmv' || file.ext == 'mp3')
            {
                var fileurl = URL.createObjectURL(file.getNative());
                var audioElement = new Audio(fileurl);

                audioElement.addEventListener('loadedmetadata', function (_event) {
                    durationList[file.id] = parseInt(audioElement.duration);
                })
            }
        
        }
    },
    onBeforeUpload: function(file)
    {
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
})
</script>

<?php if($files):?>
<?php $sessionString = session_name() . '=' . session_id();?>
<?php if($fieldset == 'true'):?>
<div class="detail">
  <div class="detail-title"><?php echo $lang->marketreport->report;?> <i class="icon icon-paper-clip icon-sm"></i></div>
  <div class="detail-content">
<?php endif;?>
<style>
.file {padding-top: 2px;}
ul.files-list {margin-bottom: unset}
.files-list>li>a {display: inline; word-wrap: break-word;}
.files-list>li>.right-icon {opacity: 1;}
.fileAction {color: #0c64eb !important;}
.renameFile {display: flex;}
.renameFile .icon {margin-top: 8px;}
.renameFile .input-group-addon {width: 60px;}
.backgroundColor {background: #eff5ff; }
.right-icon .btn {padding: 0 6px;}
</style>
<script>
$(document).ready(function()
{
    $('.item').mouseover(function()
    {
        $(this).find('div span.right-icon').removeClass("invisible");
        $(this).addClass('backgroundColor');
    });

    $('.item').mouseout(function()
    {
        $(this).find('div span.right-icon').addClass("invisible");
        $(this).removeClass('backgroundColor');
    });
});

 /**
 * Delete a file.
 *
 * @param  int    $fileID
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteFile(fileID, obj)
{
    if(!fileID) return;

    <?php if($showDelete and $method == 'edit'):?>
    $('<input />').attr('type', 'hidden').attr('name', 'deleteFiles[' + fileID + ']').attr('value', fileID).appendTo('ul.files-list');
    $(obj).closest('li.file').addClass('hidden');
    <?php else:?>
    hiddenwin.location.href = createLink('file', 'delete', 'fileID=' + fileID);
    <?php endif;?>
}

/**
 * Download a file, append the mouse to the link. Thus we call decide to open the file in browser no download it.
 *
 * @param  int    $fileID
 * @param  int    $extension
 * @param  int    $imageWidth
 * @param  string $fileTitle
 * @param  string $type download|preview
 * @access public
 * @return void
 */
function downloadFile(fileID, extension, imageWidth, fileTitle, type = 'download')
{
    if(!fileID) return;
    var fileTypes      = 'txt,jpg,jpeg,gif,png,bmp';
    var windowWidth    = $(window).width();
    var width          = (windowWidth > imageWidth) ? ((imageWidth < windowWidth * 0.5) ? windowWidth * 0.5 : imageWidth) : windowWidth;
    var checkExtension = fileTitle.lastIndexOf('.' + extension) == (fileTitle.length - extension.length - 1);

    var url = createLink('file', type, 'fileID=' + fileID + '&mouse=left');
    url    += url.indexOf('?') >= 0 ? '&' : '?';
    url    += '<?php echo $sessionString;?>';

    if(fileTypes.indexOf(extension) >= 0 && checkExtension && config.onlybody != 'yes')
    {
        $('<a>').modalTrigger({url: url, type: 'iframe', width: width}).trigger('click');
    }
    else
    {
        url = url.replace('?onlybody=yes&', '?');
        url = url.replace('?onlybody=yes', '?');
        url = url.replace('&onlybody=yes', '');

        window.open(url, '_blank');
    }
    return false;
}

/* Show edit box for editing file name. */
/**
 * Show edit box for editing file name.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function showRenameBox(fileID)
{
    $('#renameFile' + fileID).closest('li').addClass('hidden');
    $('#renameBox' + fileID).closest('li').removeClass('hidden');
}

/**
 * Show File.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function showFile(fileID)
{
    $('#renameBox' + fileID).closest('li').addClass('hidden');
    $('#renameFile' + fileID).closest('li').removeClass('hidden');
}

/**
 * Smooth refresh file name.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function setFileName(fileID)
{
    var fileName  = $('#fileName' + fileID).val();
    var extension = $('#extension' + fileID).val();
    var postData  = {'fileName' : fileName, 'extension' : extension};
    $.ajax(
    {
        url:createLink('file', 'edit', 'fileID=' + fileID),
        dataType: 'json',
        method: 'post',
        data: postData,
        success: function(data)
        {
            var fileName  = data['title'];
            var fileTitle = fileName.substring(0, fileName.lastIndexOf('.'));
            $('#fileTitle' + fileID).html(fileName);
            $('#cardContent' + fileID).html(fileTitle);
            $('#renameFile' + fileID).closest('li').removeClass('hidden');
            $('#renameBox' + fileID).closest('li').addClass('hidden');
        },
        error: function(data)
        {
            if(data.status == 200) $('#showError').html(data.responseText);
        }
    })
}
</script>
    <?php include './viewfiles.html.php';?>
<?php if($fieldset == 'true'):?>
  </div>
</div>
<?php endif;?>
<?php endif;?>

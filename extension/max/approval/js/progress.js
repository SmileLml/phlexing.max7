$(function()
{
    $('li.file').mouseover(function()
    {
        $(this).children('span.right-icon').removeClass("hidden");
        $(this).addClass('backgroundColor');
    });

    $('li.file').mouseout(function()
    {
        $(this).children('span.right-icon').addClass("hidden");
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
    hiddenwin.location.href = createLink('file', 'delete', 'fileID=' + fileID);
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
    var fileTypes      = 'txt,jpg,jpeg,gif,png,bmp,mp4';
    var windowWidth    = $(window).width();
    var width          = (windowWidth > imageWidth) ? ((imageWidth < windowWidth * 0.5) ? windowWidth * 0.5 : imageWidth) : windowWidth;
    var checkExtension = fileTitle.lastIndexOf('.' + extension) == (fileTitle.length - extension.length - 1);

    var url = createLink('file', type, 'fileID=' + fileID + '&mouse=left');
    url    += url.indexOf('?') >= 0 ? '&' : '?';

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
  console.log(postData, fileID, fileName);
    $.ajax(
    {
        url:createLink('file', 'edit', 'fileID=' + fileID),
        dataType: 'json',
        method: 'post',
        data: postData,
        success: function(data)
        {
            $('#fileTitle' + fileID).html("<i class='icon icon-file-text'></i> &nbsp;" + data['title']);
            $('#renameFile' + fileID).closest('li').removeClass('hidden');
            $('#renameBox' + fileID).closest('li').addClass('hidden');
        }
    })
}

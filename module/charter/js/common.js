$(function()
{
    var browseType = $.cookie('browseType');

    $('#mainHeader #navbar .nav li').removeClass('active');
    $('#mainHeader #navbar .nav li[data-id="' + browseType + '"]').addClass('active');
})

/**
 * loadRoadmap
 *
 * @param  productID $productID
 * @access public
 * @return void
 */
function loadRoadmap(productID)
{
    var link = createLink('charter', 'ajaxGetRoadmaps', 'productID=' + productID);
    $.post(link, function(html)
    {
        $('#roadmap').replaceWith(html);
        $('#roadmap_chosen').remove();
        $('#roadmap').chosen();
        $('#roadmap').change();
    });
}

function changeLink(roadmapID)
{
  var link = createLink('charter', 'loadRoadmapStories', 'roadmapID=' + roadmapID, '', true);
  $('.loadAllDemands').attr('href', link);
}

/**
 * Change hidden.
 *
 * @param  name $name
 * @access public
 * @return void
 */
function changeHidden(name){
    var fileForm = $('div[data-filedName="' + name + '"]');
    fileForm.find('.file-input-btn').removeClass('hidden');
    fileForm.find('.muted').removeClass('hidden');
}

/**
 * Check danger extension and file size.
 *
 * @param  object $file
 * @access public
 * @return void
 */
function checkDangerExtension(file)
{
    var fileName = $(file).val();
    var index    = fileName.lastIndexOf(".");
    var fileSize = $(file)[0].files[0].size;

    totalSize += fileSize;

    if(index >= 0)
    {
        extension = fileName.substr(index + 1);
        if(dangerExtensions.lastIndexOf(',' + extension + ',') >= 0)
        {
            alert(dangerFile);
            $(file).val('');
            return false;
        }

        if(fileSize == 0)
        {
            alert(fileContentEmpty);
            $(file).val('');
            return false;
        }

        if(totalSize >= maxUploadSize)
        {
            alert(errorFileSize);
            totalSize -= fileSize;
            $(file).val('');
            return false;
        }
    }

    var name = $(file).attr('data-filedName');
    var fileForm = $('div[data-filedName="' + name + '"]');
    fileForm.find('.file-input-btn').addClass('hidden');
    fileForm.find('.muted').addClass('hidden');
}

function changeRoadmap(roadmapID)
{
    var link = createLink('charter', 'loadRoadmapStories', 'productID=' + productID + '&roadmapIDList=' + roadmapIDList + '&roadmapID=' + roadmapID, '', true);
    window.location.href = link;
}

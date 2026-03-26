window.fetchChartApi = createLink('screen', 'ajaxGetChart');
window.fetchDataApi = createLink('screen', 'ajaxGetTreeData', 'screenID=' + screen.id);
window.fetchMetricDataApi = createLink('screen', 'ajaxGetMetricData');
window.fetchFilterOptionsApi = createLink('screen', 'ajaxGetFilterOptions');
if(!window.loadPage)
{
  window.loadPage = function(url)
  {
    parent.loadPage(url);
  }
}
function saveAsDraft(storageInfo, fileDataUrl)
{
    save(storageInfo, 'draft', fileDataUrl);
}

function saveAsPublish(storageInfo, fileDataUrl)
{
    window.storageInfo = storageInfo;
    window.fileDataUrl = fileDataUrl;
    exitFullScreen();
    $('#publish').trigger('click');
}

function save(storageInfo, status, fileDataUrl)
{
    var fileName = 'screen_thumbnail_' + screen.id;
    var blob = dataURLToBlob(fileDataUrl);
    var formData = new FormData();
    formData.append('thumbnail', blob, fileName + '.png');
    formData.append('scheme', JSON.stringify(storageInfo));
    formData.append('status', status);
    formData.append('uuid', fileName);

    $.ajax({
        url: createLink('screen', 'design', 'screenID=' + screen.id),
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(resp)
        {
            resp = JSON.parse(resp);

            zuiMessage(resp.result, resp.message);
            if(resp.result == 'success')
            {
                setTimeout(function()
                {
                    exitFullScreen();
                    loadPage(resp.locate);
                }, 2000);
            }
        }
    });
}

function backBrowse()
{
    exitFullScreen();
    loadPage(backLink);
}

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

/**
 * Zui messager alert.
 *
 * @param  string result  success|fail
 * @param  string mes
 * @access public
 * @return void
 */
function zuiMessage(result, mes)
{
    var icon = result == 'success' ? 'check-circle' : 'exclamation-sign';
    var type = result == 'success' ? 'success' : 'danger';

    var message = new $.zui.Messager(mes,
    {
        html: true,
        icon: icon,
        type: type,
        close: true,
    });

    message.show();
}

/**
 * Display the kanban in full screen.
 *
 * @access public
 * @return void
 */
function fullScreen()
{
    var element       = document.getElementById('screenContainer');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $.cookie('isFullScreen', 1);
        }

        var whenFailEnterFullscreen = function()
        {
            exitFullScreen();
        }

        try
        {
            var result = requestMethod.call(element);
            if(result && (typeof result.then === 'function' || result instanceof window.Promise))
            {
                result.then(afterEnterFullscreen).catch(whenFailEnterFullscreen);
            }
            else
            {
                afterEnterFullscreen();
            }
        }
        catch (error)
        {
            whenFailEnterFullscreen(error);
        }
    }
}

/**
 * Exit full screen.
 *
 * @access public
 * @return void
 */
function exitFullScreen()
{
    $.cookie('isFullScreen', 0);
    let exitFullScreen = document.exitFullScreen ||
    document.mozCancelFullScreen ||
    document.webkitExitFullscreen ||
    document.msExitFullscreen;
    if (exitFullScreen) {
      exitFullScreen.call(document);
    }
}

document.addEventListener('fullscreenchange', function (e)
{
    if(!document.fullscreenElement) exitFullScreen();
});

document.addEventListener('webkitfullscreenchange', function (e)
{
    if(!document.webkitFullscreenElement) exitFullScreen();
});

document.addEventListener('mozfullscreenchange', function (e)
{
    if(!document.mozFullScreenElement) exitFullScreen();
});

document.addEventListener('msfullscreenChange', function (e)
{
    if(!document.msfullscreenElement) exitFullScreen();
});

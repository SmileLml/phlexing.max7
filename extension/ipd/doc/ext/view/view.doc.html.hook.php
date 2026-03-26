<?php
js::set('hasPrivDiff', (bool)common::hasPriv('doc', 'diff'));
js::set('diffLang', $lang->doc->diff);
js::set('objectType', isset($type) ? $type : '');
?>

<script>
var arrVersionDiff = [];
var isVersionDiff  = false;
/**
* Add diffButton dom.
*
* @access public
* @return void
*/
function appendExchangeDom()
{
    $('#docVersionMenu .drop-title').append('<div id="changeBtn" class="text-primary" style="cursor: pointer; display: flex; align-items: center;"><i class="icon icon-exchange"></i>' + diffLang + '</div>');
}

$('body').on('click', '#diffBtnGroup', function()
{
    setTimeout(function()
    {
        if($('#changeBtn').length == 0 && ($('.drop-body li').length >1) && hasPrivDiff) appendExchangeDom();
        $('button[data-id=confirm]').attr('disabled', (arrVersionDiff.length !== 2))
    }, 10)
})

/**
* Refresh checkbox dom.
*
* @access public
* @return void
*/

function refreshCheckbox()
{
    var $inputs = $('#docVersionMenu .checkbox-primary input');
    if(arrVersionDiff.length == 2)
    {
        for(var i = 0; i < $inputs.length; i++)
        {
            var $input = $($inputs[i]);
            if(!$input.prop('checked'))
            {
                $input.addClass('disabled');
                $input.attr('disabled', true);
                $input.closest('.checkbox-primary').addClass('disabled');
            }
        }
    }
    else if(arrVersionDiff.length < 2)
    {
        for(var i = 0; i < $inputs.length; i++)
        {
            var $input = $($inputs[i]);
            $input.removeClass('disabled');
            $input.attr('disabled', false);
            $input.closest('.checkbox-primary').removeClass('disabled');
        }
    }
}

/**
* Update data arrVersionDiff.
*
* @access public
* @return void
*/
function updateArrVersion()
{
    arrVersionDiff = [];
    var $checkboxs = $('.drop-body .checkbox-primary input')
    for(var i = 0; i < $checkboxs.length ; i ++)
    {
        var $checkbox = $($checkboxs[i]);
        if($checkbox.prop('checked')) arrVersionDiff.push($checkbox.data());
    }
    $('button[data-id=confirm]').attr('disabled', (arrVersionDiff.length !== 2))
}

/**
* Refresh dom view.
*
* @access public
* @return void
*/
function reloadView(newV, oldV)
{
    var param = 'objectType=' + objectType + '&docID=' + docID + '&newVersion=' + newV + '&oldVersion=' + oldV;
    $.get(createLink('doc', 'diff', param), function(data)
    {
        $('#diffContain').empty();
        $('#diffContain').append(data);
    })
}

/**
* Render buttonGroup dom.
*
* @access public
* @return void
*/
function renderdiffBtnGroup()
{
    var $btnGroup = $('#diffBtnGroup');
    var oldV = arrVersionDiff[0].version;
    var newV = arrVersionDiff[1].version;
    if(newV < oldV)
    {
        var tmpArr = [newV, oldV];
        newV       = tmpArr[1];
        oldV       = tmpArr[0];
    }
    if(isVersionDiff)
    {
        var tmpArr = [newV, oldV];
        newV       = tmpArr[1];
        oldV       = tmpArr[0];
    }
    var leftDom  = $btnGroup.find('a.left-dom').html('V' + oldV + '<span class="caret"></span>');
    var rightDom = $btnGroup.find('a.right-dom').html('V' + newV + '<span class="caret"></span>');
    reloadView(newV, oldV);
}

$('body').on('click', '#docVersionMenu .drop-bottom', function(e)
{
    var data = $(e.target).data();
    e.stopPropagation();
    if(data.id == 'cancel')
    {
        $('#docVersionMenu').removeClass('diff');
    }
    else if(data.id == 'confirm')
    {
        if(arrVersionDiff.length == 2)
        {
            var $btnGroup = $('#diffBtnGroup');
            $btnGroup.addClass('show-diff');
            renderdiffBtnGroup();
        }
        $(this.closest('.open.btn-group')).removeClass('open');
    }
}).on('click', '#docVersionMenu .drop-body .li-item', function(e)
{
    var $input  = $(this).find('input');
    if(!$(e.target).is('input')) $input.trigger('click');

    updateArrVersion();
    refreshCheckbox();
}).on('click', '#exchangeDiffBtn', function()
{
    isVersionDiff = !isVersionDiff;
    var $btn      = $(this);
    renderdiffBtnGroup();
    if(isVersionDiff)
    {
        $btn.addClass('text-primary');
    }
    else
    {
        $btn.removeClass('text-primary');
    }
});
</script>

<style>
#diffContain > .main-content {height: calc(100vh - 200px); overflow-y: auto;}
.htmldiff {white-space:pre-wrap;}
.htmldiff del img{border:2px solid red;}
.htmldiff ins img{border:2px solid green;}
.htmldiff del {color: #D91B1B; text-decoration: line-through;}
.htmldiff ins {color: #0CA76F; background: rgba(208, 246, 232, 0.4); text-decoration: none; border-left:2px solid #dfd; border-right:2px solid #dfd;}
</style>

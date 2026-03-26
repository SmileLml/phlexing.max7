<?php if(isset($from) and $from == 'template'):?>
<?php
js::set('replaceContentTip', $this->lang->doc->replaceContentTip);
js::set('baselinePriv', commonModel::hasPriv('baseline', 'template'));
$templateHTML  = html::select('template', '', '', "class='form-control chosen'");
$templateIcon  = "<a href='#' id='templateIcon' class='btn btn-link'><i class='icon-template' style='color:#2E7FFF;'></i><span class='text'> {$this->lang->doc->template}</span></a>";
$confirmHTML   = html::commonButton($lang->confirm, "onclick=loadContent()", "btn btn-primary btn-wide confirm-btn");
$cancelHTML    = html::commonButton($lang->cancel, "data-dismiss='modal'", "btn btn-wide");
$modalTemplate = <<<EOD
<div class='modal fade' id='modalTemplate' data-scroll-inside='true'>
  <div class='modal-dialog' style='width:480px;'>
    <div class='modal-content'>
      <div class='modal-body'>
        <button type='button' class='close' data-dismiss='modal'>
          <i class='icon icon-close'></i>
        </button>
        <table class='table table-form'>
          <tr>
            <th class='temp-tip'>{$lang->doc->selectTemplate}</th>
            <td class='templateBox'>{$templateHTML}</td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              {$confirmHTML}
              {$cancelHTML}
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
EOD;
?>
<style>
#modalTemplate .modal-body {padding:50px 50px 30px 10px;}
#modalTemplate .modal-body .close {position:absolute; right:15px; top:15px}
#modalTemplate .confirm-btn {margin-left: 52px;}
#modalTemplate .temp-tip {width: 110px; white-space: nowrap;}
</style>

<script>
$(function()
{
    $('#modalBasicInfo').before(<?php echo json_encode($modalTemplate)?>);
    $('.btn-tools').prepend(<?php echo json_encode($templateIcon)?>);
    loadTemplates();
    initDocContent();

    $('#templateIcon').click(function(){ $('#modalTemplate').modal(); });
});

/**
 * Load templates.
 *
 * @access public
 * @return void
 */
function loadTemplates()
{
    if(baselinePriv)
    {
        var link = createLink('baseline', 'ajaxGetTemplates', 'type=all&from=doc&contentType=html,markdown');
        $.post(link, function(data)
        {
            $('#modalTemplate .templateBox').html(data);
            $('#template').removeAttr('onchange').picker();
        })
    }
    else
    {
        $('#template').picker();
    }
}

/**
 * Init doc content.
 *
 * @access public
 * @return void
 */
function initDocContent()
{
    $('#contentType').val('html');
    editor['content'].html('');
    $('#contentBox').removeClass('hidden');
    $('.contenthtml').removeClass('hidden');
    $('.contentmarkdown').addClass('hidden');
}

/**
 * Load content.
 *
 * @access public
 * @return void
 */
function loadContent()
{
    $('#modalTemplate').modal('hide');

    var templateID = $('#template').val();
    if(templateID == '') return;

    var link = createLink('baseline', 'ajaxGetContent', 'templateID=' + templateID);
    $.post(link, function(data)
    {
        data = JSON.parse(data);

        if(isContentEmpty(data))
        {
            replaceContent(data);
        }
        else
        {
            bootbox.confirm(replaceContentTip, function(res)
            {
                if(res)
                {
                    replaceContent(data);
                }
            });
        }
    })
}

/**
 * Check doc content is empty or not.
 *
 * @param  array  $data
 * @access public
 * @return boolean
 */
function isContentEmpty(data)
{
    if(data.type == 'html')
    {
        return editor['content'].html() == '';
    }
    else if(data.type == 'markdown')
    {
        return markdownEditor['contentMarkdown'].value() == '';
    }

    return true;
}

/**
 * Replace content.
 *
 * @param  array  $data
 * @access public
 * @return void
 */
function replaceContent(data)
{
    var type = data.type;
    if(type == 'html') type = 'text';
    $('input[id*=' + type + ']').attr('checked', 'checked');
    toggleEditorMode(data.type);

    if(data.type == 'html')
    {
        var cmd = editor['content'].edit.cmd;
        editor['content'].html('');
        cmd.inserthtml(data.content);
    }
    else if(data.type == 'markdown')
    {
        markdownEditor['contentMarkdown'].value(data.content);
    }
}

</script>
<?php endif;?>

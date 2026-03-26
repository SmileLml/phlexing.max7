$(function()
{
    if(window.config.currentMethod == 'edit' && ($('#source').val() == 'meeting' || $('#source').val() == 'researchreport')) changeSource();
    $('#source').change(changeSource);
})

/**
 * Change source of story.
 *
 * @access public
 * @return void
 */
function changeSource()
{
    var source = $('#source').val();
    if(source == 'meeting')
    {
        $('#sourceNoteBox').html(meetingNote);
    }
    else if(source == 'researchreport')
    {
        $('#sourceNoteBox').html(reportNote);
    }
    else
    {
        $('#sourceNoteBox').html(sourceNote);
    }

    var link = createLink('story', 'ajaxGetSourceNote', 'storyType=' + storyType + '&source=' + source + '&from=' + config.currentMethod);
    $.get(link, function(data)
    {
        if(data)
        {
            $('#sourceNote').replaceWith(data);
            $('#sourceNote_chosen').remove();
            if(source == 'meeting' || source == 'researchreport')
            {
                $('#sourceNote').val(objectID);
                $('#sourceNote').chosen();
                $('#sourceNote_chosen').css('min-width', '140px');
            }
        }
    });
}

/**
 * Batch update source note.
 *
 * @param  string $source
 * @param  int    $number
 * @param  int    $objectID
 * @access public
 * @return void
 */
function batchChangeSource(source, number, objectID)
{
    if(typeof(objectID) == 'undefined') objectID = '';

    var link = createLink('story', 'ajaxGetSourceNote', 'source=' + source + '&from=' + config.currentMethod + '&number=' + number);
    var sourceNoteID = '#sourceNote_' + number;
    $.get(link, function(data)
    {
        if(data)
        {
            $(sourceNoteID).replaceWith(data);
            $(sourceNoteID + '_chosen').remove();
            if(source == 'meeting' || source == 'researchreport')
            {
                $(sourceNoteID).val(objectID);
                $(sourceNoteID).chosen();
            }

            var nextNumber = parseInt(number) + 1;
            var nextSource = $('#source_' + nextNumber).val();
            if(nextSource == 'ditto') batchChangeSource(source, nextNumber, objectID);
        }
    });
}

/**
 * Get last source.
 *
 * @param  number $number
 * @access public
 * @return string
 */
function getLastSource(number)
{
    for(var index = number - 1; index >= 0; index--)
    {
        var source = $('#source_' + index).val();
        if(source != 'ditto') return source;
    }
    return '';
}

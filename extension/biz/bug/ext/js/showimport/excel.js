$('#showData').on('change', '.picker-select', function(e)
{
    var id    = $(this).attr('id');
    var field = $(this).attr('data-field');
    var executionID = $(this).val();
    var num = Number(id.replace(/[^\d]/g, " "));

    if(field === 'execution')
    {
        if(typeof(num) == 'undefined') num = '';

        var productID = $(document.getElementById('product[' + num + ']')).val()
        var branch    = $(document.getElementById('branch[' + num + ']')).val()

        if(typeof(branch) == 'undefined') branch = 0;
        if(typeof(oldStoryID) == 'undefined') oldStoryID = 0;

        var link = createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=' + productID + '&branch=' + branch + '&moduleID=0&storyID=' + oldStoryID + '&number=' + num + '&type=full&status=all&from=bug');
        $.get(link, function(stories)
        {
            $('#story' + num).next('.picker').remove();
            $('#story' + num).replaceWith(stories);
            $('#story' + num).picker({chosenMode: true});
            $('#story' + num).attr('isInit', true);
        })
    }
});

var $modalButton = $("button[data-target='#importNoticeModal']");
requiredFields   = requiredFields.split(',');
$modalButton.on('click', function()
{
    var importData = $("#showData").closest('.main-form').serializeArray();
    var notice     = '';
    var lineDatas  = {};
    /* Set form data to line object.*/
    $.each(importData, function()
    {
        var oneData = this.name.split('[');
        if(oneData.length > 1)
        {
            var dataLine = oneData[1].replace('[]', '').replace(']', '');
            var dataName = oneData[0];

            dataLine--;
            if(!lineDatas[dataLine]) lineDatas[dataLine] = {};
            lineDatas[dataLine][dataName] = this.value;
        }
    });

    /* Check required fields. */
    $.each(lineDatas, function(line, lineData)
    {
        $.each(requiredFields, function(i, field)
        {
            if(field != 'project' && (!lineData[field] || lineData[field] === '0')) notice += noticeLang.replace('%s', line).replace('%s', bugLang[field]) + '\n';
        })
    })

    if(notice)
    {
        alert(notice);
        return false;
    }
});

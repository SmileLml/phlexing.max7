$(".submitBtn").click(function()
{
    var id        = $(this).parents('tr').attr('data-id');
    var pointAttr = JSON.parse(reviewPoints);
    var category  = id.split("-")[1];

    if(pointAttr[category]['disabled'])
    {
        new $.zui.Messager(pointAttr[category]['message'], {
            type: 'danger',
            icon: 'exclamation-sign'
        }).show();
        return false;
    }
});

$(".editDeadline").click(function()
{
    var editBtn      = $(this);
    var id           = editBtn.parents('tr').attr('data-id');
    var parentID     = editBtn.parents('tr').attr('data-parent');
    var stageEndDate = $("tr[data-id='" + parentID + "']").find('.endDate').text();
    var reviewID     = editBtn.parents('tr').attr('data-id');
    var deadlineDate = editBtn.prev().text();

    editBtn.css('display', 'inline-block');
    editBtn.off('changeDate');

    editBtn.datetimepicker(
    {
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format: "yyyy-mm-dd",
        startDate: new Date(),
        endDate: new Date(stageEndDate),
        initialDate: new Date(deadlineDate),
    })
    .on('changeDate', function(ev){
        var year  = ev.date.getFullYear();
        var month = ev.date.getMonth() + 1;
        var day   = ev.date.getUTCDate();
        var formattedDate = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;

        $.post(createLink('review', 'ajaxChangeTRDeadline'), {'deadline' : formattedDate, 'id' : reviewID , 'projectID' : projectID}, function()
        {
            $('tr[data-id="' + id + '"]').find('.endDate').text(formattedDate);
        });
    })
    .on('hide', function(){
      editBtn.css('display', 'none');
    })
    editBtn.datetimepicker('show');
});

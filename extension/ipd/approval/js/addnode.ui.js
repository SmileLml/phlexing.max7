window.changeNodeType = function(e)
{
    if(e.target.value == 'current')
    {
        window.oldReviewer = zui.Picker.query("#reviewer").options.items;
        const reviewer = JSON.parse(JSON.stringify(window.oldReviewer));

        reviewer.filter(r => currentReviewers.includes(r.value)).forEach(r => r.disabled = true);
        zui.Picker.query("#reviewer").render({items: reviewer});

        let selected = $('#reviewer').zui('picker').$.state.value;
        if(selected)
        {
            selected = selected.split(',').filter(r => !currentReviewers.includes(r));
            $('#reviewer').zui('picker').$.setValue(selected.join(','));
        }

        $('.multipleType').addClass('hidden');
        $('[name=addNodeTitle]').closest('.form-group').addClass('hidden');
    }
    else
    {
        if(window.oldReviewer) zui.Picker.query("#reviewer").render({items: window.oldReviewer});
        $('.multipleType').removeClass('hidden');
        $('[name=addNodeTitle]').closest('.form-group').removeClass('hidden');
    }
}

window.changeMultiple = function(e)
{
    $('[name=needAll]').parent().addClass('hidden');

    $(e.target).parent().find('[name=needAll]').parent().removeClass('hidden');
    $('[name=percent]').parent().toggleClass('hidden', $(e.target).val() != 'percent');
}

window.checkPercent = function(e)
{
    const percent = parseInt(e.target.value);
    if(percent < 1 || percent > 100 || isNaN(percent) || percent != e.target.value)
    {
        $('#percent').val('50');
        zui.Modal.alert(warningLang['percent']);
    }
}

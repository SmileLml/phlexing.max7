function jumpBrowse()
{
    window.parent.location.href = browseLink;
}

function closeParentModal()
{
    parent.parent.$.cookie('selfClose', 1);
    parent.parent.$.closeModal(parent.parent.$('a.refresh').click(), '');
}

$("#dataform").submit(function(e)
{
    if(feedbackCount !== 0 || feedbackModuleCount > 1)
    {
        if(confirm(syncConfirm)) $("#needMerge").val('yes');
    }
});

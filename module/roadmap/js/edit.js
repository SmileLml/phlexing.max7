$(function()
{
    if(productType != 'normal')
    {
        $('#submit').click(function()
        {
            var newBranch = $('#branch').val();
            if(branch != newBranch)
            {
                event.preventDefault();
                var link = createLink('roadmap', 'ajaxGetNotice', 'roadmapID=' + roadmapID + '&branch=' + newBranch);
                $.get(link, function(data)
                {
                    if(data)
                    {
                        if(confirm(changeBranchTips))
                        {
                            $('#dataform').submit();
                        }
                        else
                        {
                            return false;
                        }
                    }
                    else
                    {
                        $('#dataform').submit();
                    }
                })
            }
        })
    }
})

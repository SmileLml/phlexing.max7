$('#owner').change(function()
{
    var currentOwners = $('#owner').val();
    if(!currentOwners) currentOwners = [];
    if(!oldOwners)     oldOwners     = [];

    var diff = oldOwners.reduce((result, value) => {
        if (currentOwners.indexOf(value) === -1) {
            result.push(value);
        }
        return result;
    }, []);

    if(diff.length)
    {
        var account = diff[0];
        if(oldReviewers && oldReviewers.includes(account))
        {
            oldOwners = $('#owner').val();
            return false;
        }
        var link = createLink('demandpool', 'ajaxCheckReviewer', "poolID=" + poolID + "&account=" + account);
        $.get(link, function(data)
        {
            if(data)
            {
                alert(hasReview);
                $('#owner').val(oldOwners);
                $('#owner').trigger('chosen:updated');
            }
            else
            {
                oldOwners = $('#owner').val();
            }
        })
    }
    else
    {
        oldOwners = $('#owner').val();
    }
});

$('#reviewer').change(function()
{
    var currentReviewers = $('#reviewer').val();
    if(!currentReviewers) currentReviewers = [];

    var diff = oldReviewers.reduce((result, value) => {
        if (currentReviewers.indexOf(value) === -1) {
            result.push(value);
        }
        return result;
    }, []);

    if(diff.length)
    {
        var account = diff[0];
        if(oldOwners && oldOwners.includes(account))
        {
            oldReviewers = $('#reviewer').val();
            return false;
        }

        var link = createLink('demandpool', 'ajaxCheckReviewer', "poolID=" + poolID + "&account=" + account);
        $.get(link, function(data)
        {
            if(data)
            {
                alert(hasReview);
                $('#reviewer').val(oldReviewers);
                $('#reviewer').trigger('chosen:updated');
            }
            else
            {
                oldReviewers = $('#reviewer').val();
            }
        })
    }
    else
    {
        oldReviewers = $('#reviewer').val();
    }
});

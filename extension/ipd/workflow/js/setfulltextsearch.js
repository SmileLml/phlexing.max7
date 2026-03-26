$(document).ready(function()
{
    $('#buildIndex').click(function()
    {
        var $button = $(this);

        if($button.data('confirmed'))
        {
            buildIndex($button);
            return false;
        }

        bootbox.confirm($button.data('confirm'), function(result)
        {
            if(result)
            {
                $button.data('confirmed', true);
                buildIndex($button);
            }
        });
        return false;
    });
})

function buildIndex($button)
{
    $('#resultTR').show();
    toggleButton($button, 'disable');
    
    $.getJSON($button.attr('href'), function(response)
    {
        if(response.result == 'finished')
        {
            $('#resultBox').append("<li class='text-success'>" + response.message + "</li>");
            $button.data('confirmed', false);
            toggleButton($button, 'enable');
            return false;
        }
        else
        {
            $button.attr('href', response.next);
            $('#resultBox').append("<li class='text-success'>" + response.message + "</li>");
            return $button.click();
        }
    }); 
}

function toggleButton($button, action)
{
    label    = $button.val();
    loading  = $button.data('loading');
    disabled = action == 'disable';

    $button.attr('disabled', disabled);
    $button.val(loading);
    $button.data('loading', label);
}

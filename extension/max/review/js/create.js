$(function()
{
    $('#object').change(function()
    {
        var text = reviewText + $(this).find("option:selected").text();
        $('#title').val(text);

        var type    = $(this).val();
        var content = $('input[name="content"]:checked').val();

        if(type == 'PP')
        {
            $('input[name="content"').closest('tr').addClass('hide');
            $('#template').closest('tr').addClass('hide');
            $('#doc').closest('tr').addClass('hide');
            $('#doclib').closest('tr').addClass('hide');
        }
        else
        {
            $('input[name="content"').closest('tr').removeClass('hide');
            $('#doc').closest('tr').removeClass('hide');

            if(content == 'template')
            {
                $('#template').closest('tr').removeClass('hide');
                $('#doclib').closest('tr').addClass('hide');
            }
            else
            {
                $('#template').closest('tr').addClass('hide');
                $('#doclib').closest('tr').removeClass('hide');
            }
        }

        let productID = $('#product').val();
        var link      = createLink('review', 'ajaxGetNodes', "project=" + projectID + '&object=' + type + '&product=' + productID);
        $('#reviewerBox').load(link, function(){$(this).find('select').chosen()});

        var link = createLink('baseline', 'ajaxGetTemplates', 'type=' + type);
        $.post(link, function(data)
        {
            $('#template').replaceWith(data);
            $('#template_chosen').remove();
            $('#template').chosen();

            $('#template').on('change', function()
            {
                var template = $(this).val();
                var link = createLink('baseline', 'ajaxGetDocs', 'template=' + template + '&from=review&project=' + projectID);
                $.post(link, function(data)
                {
                    $('#doc').replaceWith(data);
                    $('#doc_chosen').remove();
                    $('#doc').chosen();
                })
            })

            $('#template').change();
        })
    })

    $('#object').change();

    $('#doclib').change(function()
    {
        var libID = $(this).val();
        var link  = createLink('doc', 'ajaxGetDocs', "libID=" + libID);
        $.post(link, function(data)
        {
            $('#doc').replaceWith(data);
            $('#doc_chosen').remove();
            $('#doc').chosen();
        })
    })

    $('input[name="content"]').change(function()
    {
        var content = $(this).val();
        if(content == 'template')
        {
            $('#template').change();
            $('#template').closest('tr').removeClass('hide');
            $('#doclib').closest('tr').addClass('hide');
        }
        else
        {
            $('#doclib').change();
            $('#template').closest('tr').addClass('hide');
            $('#doclib').closest('tr').removeClass('hide');
        }
    })

    $('#product').change(function(){
        let productID   = $(this).val();
        let type        = $('#object').val();
        const link      = createLink('review', 'ajaxGetNodes', "project=" + projectID + '&object=' + type + '&product=' + productID);
        $('#reviewerBox').load(link, function(){$(this).find('select').chosen()});
    })
})

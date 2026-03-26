window.renderRowData = function($row, index, row)
{
    index = index + 1;
    $.each(['projectApproval', 'completeApproval', 'cancelApproval'], function(i, value)
    {
        itemHTML = '';
        if(row)
        {
            if(row[value])
            {
                $.each(row[value], function(key, itemObject)
                {
                    itemHTML += "<div class='flex pb-2'><input class='form-control' type='hidden' name='index[" + index + "][" + value + "][]' value='" + itemObject.index + "'/><input class='form-control' name='name[" + index + "][" + value + "][]' value='" + itemObject.name + "'/><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm " + (row[value].length <= 1 ? 'opacity-0 cursor-default' : '') + "' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";
                });
            }
            else
            {
                itemHTML += "<div class='flex pb-2'><input class='form-control' type='hidden' name='index[" + index + "][" + value + "][]'/><input class='form-control' name='name[" + index + "][" + value + "][]'/><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";
                itemHTML += "<div class='flex pb-2'><input class='form-control' type='hidden' name='index[" + index + "][" + value + "][]'/><input class='form-control' name='name[" + index + "][" + value + "][]'/><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";
            }
        }
        else
        {
            itemHTML += "<div class='flex pb-2'><input class='form-control' type='hidden' name='index[" + index + "][" + value + "][]'/><input class='form-control' name='name[" + index + "][" + value + "][]'/><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";
            itemHTML += "<div class='flex pb-2'><input class='form-control' type='hidden' name='index[" + index + "][" + value + "][]'/><input class='form-control' name='name[" + index + "][" + value + "][]'/><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";
        }
        if($row.find('[data-name=' + value + ']').find('.itemHTML').length <= 0)
        {
            $row.find('[data-name=' + value + ']').empty().append("<div class='itemHTML'>" + itemHTML + '</div>');
        }
        else
        {
            $row.find('[data-name=' + value + '] input[name^=index]').attr('name', "index[" + index + "][" + value + "][]");
            $row.find('[data-name=' + value + '] input[name^=name]').attr('name', "name[" + index + "][" + value + "][]");
        }
    });
}

window.addNode = function(event)
{
    $row = $(event.target).closest('td');
    $(event.target).closest('div').after($(event.target).closest('div').prop('outerHTML'));
    $(event.target).closest('div').next().find('input').val('');
    checkBtn($row);
}

window.removeNode = function(event)
{
    if($(event.target).closest('button').hasClass('opacity-0')) return false;

    $row = $(event.target).closest('td');
    $(event.target).closest('div').remove();
    checkBtn($row);
}

window.checkBtn = function(row)
{
    if($(row).find('div.flex').length >= 2)
    {
        $(row).find('.form-batch-btn[data-type=remove]').removeClass('opacity-0 cursor-default');
    }
    else
    {
        $(row).find('.form-batch-btn[data-type=remove]').addClass('opacity-0 cursor-default');
    }
}

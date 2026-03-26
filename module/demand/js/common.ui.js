window.toggleProductDropdown = function()
{
    if($('[name=undetermined]').is(':checked'))
    {
        $("[name^=product]").zui('picker').render({disabled: true});
        $("[name^=product]").closest('.picker-box').hide();
        $("[name='undeterminedProduct']").removeClass('hidden');
    }
    else
    {
        $("[name^=product]").zui('picker').render({disabled: false});
        $("[name^=product]").closest('.picker-box').show();
        $("[name='undeterminedProduct']").addClass('hidden');
    }
}

window.deselectProduct = function(value)
{
    const deselectedProduct = value[0];
    const statusList        = ['launched', 'developing', 'closed'];

    if(distributedProducts[deselectedProduct])
    {
        if(statusList.includes(distributedProducts[deselectedProduct]))
        {
            zui.Modal.confirm(confirmChangProduct).then(result =>
            {
                if(!result)
                {
                    const productValue = $("[name^=product]").val();
                    productValue.push(deselectedProduct)

                    $("[name^=product]").zui('picker').$.setValue(productValue);
                }
            });
        }
    }
}

window.updateProducts = function()
{
    if($('#undetermined').is(':checked'))
    {
        $('#undetermined').prop('checked', false);
        toggleProductDropdown();
    }

    const poolID = $("[name='pool']").val();
    const link   = $.createLink('demand', 'ajaxGetProducts', 'poolID=' + poolID);
    $.getJSON(link, function(data)
    {
        const selectedProduct = $("[name^='product']").val();
        const $productPicker  = $("[name^='product']").zui('picker');
        $productPicker.render({items: data});
        $productPicker.$.setValue(selectedProduct);
    });

    if(!$("[name^=product]").val()) $("[name^=product]").closest('.picker-box').siblings(".input-group-addon").show();
};

window.clickSubmit = function(e)
{
    const status = $(e.submitter).data('status');
    if(status === undefined) return;

    const method = config.currentMethod;
    let demandStatus = status;
    if(status == 'active')
    {
        const reviewers = $('[name^=reviewer]').val().filter(function(reviewer) {return reviewer && reviewer.length > 0;})
        demandStatus = !reviewers.length || $('#needNotReview').prop('checked') ? 'active' : 'reviewing';
    }
    if(status == 'draft' && (method == 'change' || (method == 'edit' && $('#status').val() == 'changing')))
    {
        demandStatus = 'changing';
    }
    $(e.submitter).closest('form').find('[name=status]').val(demandStatus);
};

/* Update other picker on change */
$.zui.Picker.DEFAULTS.onChange = function(event)
{
    var picker = event.picker;
    if(!picker.$formItem.is('[name^=product]')) return;
    resfreshPicker('product');
}

$(function()
{
    resfreshPicker('product', true);

    $('#dataform .table-form').find('tr:first').find('a:last-child').text('').css('pointer-events', 'none');
    $('#dataform .table-form').find('tr:not(:first):not(:nth-last-child(2))').find('th').text('');
})

/**
 * Load roadmap.
 *
 * @param  productID $productID
 * @param  branch $branch
 * @access public
 * @return void
 */
function loadRoadmap(productID, branch, index = 0)
{
    var link = createLink('demand', 'ajaxGetRoadmaps', 'productID=' + productID + '&branch=' + branch + '&param=distributable&index=' + index);
    $.post(link, function(html)
    {
        $('#roadmap' + index).replaceWith(html);
        $('#roadmap' + index).parents('.roadmapBox').find('.picker').remove();
        $('#roadmap' + index).picker();

        var optionCount = $(html).find('option').length;

        if(optionCount < 2 && productID != 0)
        {
            $('.newRoadmap' + index).removeClass('hidden');
        }
        else
        {
            $('.newRoadmap' + index).addClass('hidden');
        }
    });
}

/**
 * Load branches when change product.
 *
 * @param  int   $productID
 * @access public
 * @return void
 */
function loadProductBranches(productID, index = 0)
{
    var branchId       = '#branch' + index;
    var branchPickerId = '#pk_branch' + index + '-search';
    var $product       = $('#product' + index);

    $(branchId).remove();
    $(branchPickerId).closest('.picker').remove();

    $.get(createLink('demand', 'ajaxGetBranches', "productID=" + productID + "&oldBranch=0&index=" + index + "&multiple=multiple"), function(data)
    {
        var $inputGroup = $product.closest('.input-group');
        if(data && $product.val() != 0)
        {
            $inputGroup.append(data);
            $(branchId).picker();
        }
        $inputGroup.fixInputGroup();

        var branch    = $(branchId).val();
        if(branch === undefined) branch = '';
        if(branch !== '')
        {
            $product.closest('.input-group').addClass('productDiv');
            $product.closest('.input-group').find('.input-group-addon').hide();
            $product.closest('.input-group').find('.picker:first').css('width', '70%');
            $product.closest('.input-group').find('.picker:last').css('width', '30%');
            $product.closest('.input-group').find('.picker').css('float', 'left');
        }
        else
        {
            $product.closest('.input-group').removeClass('productDiv');
            $product.closest('.input-group').find('.input-group-addon').show();
            $product.closest('.input-group').find('.picker:first').css('width', '100%');
            $product.closest('.input-group').find('.picker:last').css('width', '100%');
            $product.closest('.input-group').find('.picker').css('float', 'none');
        }
        loadRoadmap(productID, branch, index);
    })
}

/**
 * Load branch.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function loadBranch(obj, index)
{
    var productID = $('#product' + index).val();
    var branch    = $('#branch' + index).val();
    loadRoadmap(productID, branch, index);
}

/**
 * Add new product.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addNewProduct(obj)
{
    if($(obj).attr('checked'))
    {
        /* Hide product and plan dropdown controls. */
        $('.productsBox .select').addClass('hidden');
        $('.productsBox .select').find('select').attr('disabled', true).trigger("chosen:updated");

        /* Displays the input box for creating a product. */
        $("[name^='newProduct']").prop('checked', true);
        $('#productName').removeAttr('disabled', true);
        $('.productsBox .addProduct').removeClass('hidden');
    }
    else
    {
        /* Show product and product dropdown controls. */
        $('.productsBox .select').removeClass('hidden');
        $('.productsBox .select').find('select').removeAttr('disabled').trigger("chosen:updated");

        /* Hide the input box for creating a product. */
        $("[name^='newProduct']").prop('checked', false);
        $('#productName').attr('disabled', true);
        $('.productsBox .addProduct').addClass('hidden');
    }
}

/**
 * Add new roadmap.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addNewRoadmap(obj, index)
{
      console.log(index);
    if($(obj).attr('checked'))
    {
        $(obj).closest('#roadmapBox').find('.select').addClass('hidden');
        $(obj).closest('#roadmapBox').find('.select').attr('disabled', true).trigger("chosen:updated");
        $(obj).closest('#roadmapBox').find("[name^='newRoadmap']").prop('checked', true);
        $('#roadmapName' + index).removeAttr('disabled', true);
        $(obj).closest('#roadmapBox').find('.addRoadmap').removeClass('hidden');
    }
    else
    {
        $(obj).closest('#roadmapBox').find('.select').removeClass('hidden');
        $(obj).closest('#roadmapBox').find('.select').removeAttr('disabled').trigger("chosen:updated");
        $(obj).closest('#roadmapBox').find("[name^='newRoadmap']").prop('checked', false);
        $('#roadmapName' + index).attr('disabled', true);
        $(obj).closest('#roadmapBox').find('.addRoadmap').addClass('hidden');
    }
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    $(obj).closest('tr').find('.picker .picker-selection-remove').click();
    $(obj).closest('tr').remove();
    resfreshPicker('product');
}

let addItemindex = $('#dataform tbody').find('.productsBox').length;

/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    addItemindex++;
    var index   = addItemindex;
    var itemRow = $('#itemRow').html().replace(/itemIndex/g, index);;

    $(obj).closest('tr').after('<tr class="addedItem">' + itemRow  + '</tr>');
    var newItem  = $('#product' + index).closest('tr');
    var $product = $("#product" + index).picker();
    $("#roadmap" + index).picker();

    resfreshPicker('product');
}

function resfreshPicker(name = '', resferBranch = false)
{
    var disabledItems = [];

    $('#dataform select[name^=' + name + ']').each(function()
    {
        var $select = $(this);
        $select.picker({dropWidth: '100%'});
        var picker  = $select.data('zui.picker');
        if(!picker) return;

        if(prckerList.length == 0) prckerList = picker.list;
        var value      = picker.getValue();
        var selectItem = picker.getListItem(value);

        if(selectItem) disabledItems.push($.extend({}, selectItem, {disabled: true}));
    });

    $.each(prckerList, function(index, value) {value.disabled = '';});

    $('#dataform select[name^=' + name + ']').each(function()
    {
        var $select = $(this);
        var picker  = $select.data('zui.picker');
        var value   = picker.getValue();
        var index   = $select.attr('data-index');

        $select.picker({list: prckerList});
        if(disabledItems.length) $(this).data('zui.picker').updateOptionList(disabledItems);
        if(resferBranch) loadProductBranches(value, index);
    });

    if(prckerList.length - 1 == disabledItems.length)
    {
        $('#dataform .addItem').addClass('hidden');
    }
    else
    {
        $('#dataform .addItem').removeClass('hidden');
    }
}

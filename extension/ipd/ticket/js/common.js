/**
 * Load all by product.
 *
 * @param  int    productID
 *
 * @access public
 * @return void
 */
function loadAll(productID)
{
    loadModules(productID);
    loadBuilds(productID);
}

/**
 * Load modules by product.
 *
 * @param  int    productID
 *
 * @access public
 * @return void
 */
function loadModules(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=ticket&branch=all&rootModuleID=0&returnType=json');
    $.getJSON(link, function(data)
    {
        $('#module').empty();
        $.each(data, function(key, value)
        {
            $('#module').append('<option value=' + key + ' title="' + value + '">' + value + '</option>');
        });
        $('#module').trigger('chosen:updated');
    })
}

/**
 * Load builds by product.
 *
 * @param  int productID
 *
 * @access public
 * @return void
 */
function loadBuilds(productID)
{
    if(typeof(oldOpenedBuild) == 'undefined') oldOpenedBuild = 0;
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
    $.getJSON(link, function(data)
    {
        if(!data) data = '<select id="openedBuild" name="openedBuild" class="form-control" multiple=multiple></select>';
        $('#openedBuild').empty();
        $.each(data, function(key, value)
        {
            $('#openedBuild').append('<option value=' + value['value'] + ' title="' + value['text'] + '">' + value['text'] + '</option>');
        });
        $('#openedBuild').trigger('chosen:updated');
    })
}

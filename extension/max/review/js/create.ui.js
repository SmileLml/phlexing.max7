window.productChange = function()
{
    const productID = $('input[name=product]').val();
    const type      = $('input[name=object]').val();
    const getNodesLink = $.createLink('review', 'ajaxGetNodes', `project=${projectID}&object=${type}&product=${productID}`);
    loadCurrentPage({url: getNodesLink, selector: '#reviewerBox', partial: true});
}

window.objectChange = function()
{
    const type = $('input[name=object]').val();
    if(type == undefined) return;

    let title = type ? reviewText + objectList[type] : '';
    $('[data-name=title] input[name=title]').val(title);

    const canUseTemplateTypes = ['PP', 'SRS', 'HLDS', 'DDS', 'ADS', 'DBDS', 'ITTC', 'STTC'];
    if(type != '' && canUseTemplateTypes.indexOf(type) === -1)
    {
        $('input[name=content]').val('doc');
        $('input[name=content][value=template]').prop('disabled', true);
    }
    else
    {
        $('input[name=content]').val('template');
        $('input[name=content][value=template]').prop('disabled', false);
    }

    const content = $('input[name=content]:checked').val();
    if(content == 'template')
    {
        $('div.form-group[data-name=doclib]').addClass('hidden');
        $('div.form-group[data-name=doc]').addClass('hidden');
    }
    else
    {
        $('div.form-group[data-name=doclib]').removeClass('hidden');
        $('div.form-group[data-name=doc]').removeClass('hidden');
    }

    const productID    = $('input[name=product]').val();
    const getNodesLink = $.createLink('review', 'ajaxGetNodes', `project=${projectID}&object=${type}&product=${productID}`);
    loadCurrentPage({url: getNodesLink, selector: '#reviewerBox', partial: true});
}

window.contentChange = function()
{
    const content = $('input[name=content]:checked').val();
    if(content == 'template')
    {
        $('div.form-group[data-name=doclib]').addClass('hidden');
        $('div.form-group[data-name=doc]').addClass('hidden');
    }
    else
    {
        doclibChange();
        $('div.form-group[data-name=doclib]').removeClass('hidden');
        $('div.form-group[data-name=doc]').removeClass('hidden');
    }
}

window.doclibChange = function()
{
    const libID       = $('input[name=doclib]').val();
    const getDocsLink = $.createLink('doc', 'ajaxGetDocs', `lib=${libID}&viewType=json`);
    $.getJSON(getDocsLink, function(docItems)
    {
        const docID     = $('input[name=doc]').val();
        const docPicker = $('input[name=doc]').zui('picker');

        docPicker.render({items: docItems});
        docPicker.$.setValue(docID);
    })
}

waitDom('[name=object]', function()
{
    setTimeout(function(){ objectChange();}, 100);
})

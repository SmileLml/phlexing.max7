window.getSolutions = function()
{
    const mode = $('input[name=resolution]').val();
    loadTarget($.createLink('issue', 'ajaxGetResolveForm', `projectID=${projectID}&issueID=${issueID}&mode=${mode}&from=${from}`), '#resolvePanel');
}

window.loadProduct = function()
{
    const productID = $('#resolveForm input[name=product]').val();
    const mode      = $('input[name=resolution]').val();
    loadTarget($.createLink('issue', 'ajaxGetResolveForm', `projectID=${projectID}&issueID=${issueID}&mode=${mode}&from=${from}&params=productID=${productID}`), '#resolvePanel');
}

window.loadBranch = function()
{
    const productID = $('#resolveForm input[name=product]').val();
    const branch    = $('#resolveForm input[name=branch]').length ? $('#resolveForm input[name=branch]').val() : 0;
    const mode      = $('input[name=resolution]').val();
    loadTarget($.createLink('issue', 'ajaxGetResolveForm', `projectID=${projectID}&issueID=${issueID}&mode=${mode}&from=${from}&params=productID=${productID},branch=${branch}`), '#resolvePanel');
}

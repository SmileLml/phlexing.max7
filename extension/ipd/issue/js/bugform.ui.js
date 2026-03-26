window.loadModuleRelated = function()
{
    const moduleID  = $('#resolveForm input[name=module]').val();
    const productID = $('#resolveForm input[name=product]').val();
    const link      = $.createLink('bug', 'ajaxGetModuleOwner', `moduleID=${moduleID}&productID=${productID}`);
    $.getJSON(link, function(owner)
    {
        $('#resolveForm input[name=assignedTo]').zui('picker').$.setValue(owner.account);
    });
}

window.loadProductExecutions = function()
{
    const productID = $('#resolveForm input[name=product]').val();
    const projectID = $('#resolveForm input[name=project]').val();
    const branch    = $('#resolveForm input[name=branch]').length ? $('#resolveForm input[name=branch]').val() : 0;
    const link      = $.createLink('issue', 'ajaxGetExecutions', `productID=${productID}&projectID=${projectID}&branch=${branch}`);
    $.getJSON(link, function(data)
    {
        const executionID     = $('#resolveForm input[name=execution]').val();
        const executionPicker = $('#resolveForm input[name=execution]').zui('picker');
        executionPicker.render({items: data.items});
        executionPicker.$.setValue(executionID);
        $('#resolveForm input[name=execution]').closest('.form-group').toggleClass('hidden', !data.multiple);
    });
    loadProjectBuilds(projectID, productID, branch);
}

function loadProjectBuilds(projectID, productID, branch)
{
    const oldOpenedBuild = $('[name^="openedBuild"]').val() ? $('[name^="openedBuild"]').val() : 0;
    const link           = $.createLink('build', 'ajaxGetProjectBuilds', `projectID=${projectID}&productID=${productID}&varName=openedBuild&build=${oldOpenedBuild}&branch=${branch}`);
    $.getJSON(link, function(data)
    {
        const buildID      = $('#resolveForm [name^="openedBuild"]').val();
        const $buildPicker = $('#resolveForm [name^="openedBuild"]').zui('picker');
        $buildPicker.render({items: data});
        $buildPicker.$.setValue(buildID);
    })
}

window.loadExecutionRelated = function()
{
    const productID   = $('#resolveForm input[name=product]').val();
    const projectID   = $('#resolveForm input[name=project]').val();
    const executionID = $('#resolveForm input[name=execution]').val();
    const branch      = $('#resolveForm input[name=branch]').length ? $('#resolveForm input[name=branch]').val() : 0;
    if(executionID)
    {
        loadExecutionBuilds(executionID, productID, branch);
        loadAssignedToByExecution(executionID);
    }
    else if(projectID)
    {
        loadProjectBuilds(projectID, productID, branch);
    }
    else
    {
        loadProductBuilds(productID, branch);
    }
}

function loadExecutionBuilds(executionID, productID, branch)
{
    const oldOpenedBuild = $('[name^="openedBuild"]').val() ? $('[name^="openedBuild"]').val() : 0;
    const link           = $.createLink('build', 'ajaxGetExecutionBuilds', `executionID=${executionID}&productID=${productID}&varName=openedBuild&build=${oldOpenedBuild}&branch=${branch}`);
    $.getJSON(link, function(builds)
    {
        const buildID      = $('#resolveForm [name^="openedBuild"]').val();
        const $buildPicker = $('#resolveForm [name^="openedBuild"]').zui('picker');
        $buildPicker.render({items: builds});
        $buildPicker.$.setValue(buildID);
    });
}

function loadAssignedToByExecution(executionID)
{
    const link = $.createLink('bug', 'ajaxLoadAssignedTo', `executionID=${executionID}`);
    $.getJSON(link, function(data)
    {
        let assignedTo        = $('[name="assignedTo"]').val();
        let $assignedToPicker = $('[name="assignedTo"]').zui('picker');
        $assignedToPicker.render({items: data});
        $assignedToPicker.$.setValue(assignedTo);
    });
}

function loadProductBuilds(productID, branch, type = 'normal')
{
    const oldOpenedBuild = $('[name^="openedBuild"]').val() ? $('[name^="openedBuild"]').val() : 0;
    const link           = $.createLink('build', 'ajaxGetProductBuilds', `productID=${productID}&varName=openedBuild&build=${oldOpenedBuild}&branch=${branch}`);
    $.getJSON(link, function(builds)
    {
        const buildID      = $('#resolveForm [name^="openedBuild"]').val();
        const $buildPicker = $('#resolveForm [name^="openedBuild"]').zui('picker');
        $buildPicker.render({items: builds});
        $buildPicker.$.setValue(buildID);
    });
}

window.loadAllBuilds = function()
{
    const productID   = $('#resolveForm input[name=product]').val();
    const branch      = $('#resolveForm input[name=branch]').length ? $('#resolveForm input[name=branch]').val() : 0;
    loadProductBuilds(productID, branch, 'all');
}

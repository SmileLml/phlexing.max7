$(document).off('change', '[name=story], [name=execution]').on('change', '[name=story], [name=execution]', function()
{
    setStoryDesign();
});

window.waitDom('[name=design]', function(){setStoryDesign();})

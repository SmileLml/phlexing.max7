$(function()
{
    if(firstModuleID > 0) $('#module' + firstModuleID).closest('li').addClass('active open in');
    if(secondModuleID > 0) $('#module' + secondModuleID).closest('li').addClass('active open in');
    if(practiceID > 0) $('#practice' + practiceID).closest('li').addClass('active');

    var simplemde = new SimpleMDE({element: $("#markdownContent")[0], toolbar:false, status: false,renderingConfig:{singleLineBreaks: false}});
    simplemde.value(String(markdownText));
    simplemde.togglePreview();

    $('#content .CodeMirror .editor-preview a').attr('target', '_blank');

    $('.editor-preview h1:first').after(additionalHTML);
    $('.editor-preview a[href!=""]').attr('target', '_blank');
})

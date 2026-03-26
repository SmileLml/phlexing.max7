<?php
namespace zin;

h::importJs($app->getWebRoot() . 'js/sheetjs/xlsx.full.min.js');
h::importJs($app->getWebRoot() . 'js/filesaver/filesaver.js');
$this->app->loadLang('file');
jsVar('untitled', $lang->file->untitled);
$fileName = isset($pivotName) ? $pivotName : '';

div
(
    setID('export'),
    setClass('modal'),
    div
    (
        setClass('modal-dialog shadow'),
        div
        (
            setClass('modal-content'),
            div(setClass('modal-header'), div(setClass('modal-title'), $lang->export)),
            div
            (
                setClass('modal-actions'),
                button
                (
                    set::type('button'),
                    setClass('btn square ghost'),
                    set('data-dismiss', 'modal'),
                    span(setClass('close'))
                )
            ),
            div
            (
                setClass('modal-body'),
                div
                (
                    setClass('py-2 ml-8 mr-10 flex items-center'),
                    div
                    (
                        setStyle('width', '20%'),
                        setClass('text-right pr-4'),
                        $lang->file->fileName
                    ),
                    div
                    (
                        setStyle('width', '80%'),
                        input
                        (
                            set::name('fileName'),
                            set::placeholder($lang->file->untitled),
                            set::value($fileName)
                        )
                    )
                ),
                div
                (
                    setClass('py-2 ml-8 mr-10 flex items-center'),
                    div
                    (
                        setStyle('width', '20%'),
                        setClass('text-right pr-4'),
                        $lang->pivot->exportType
                    ),
                    div
                    (
                        setStyle('width', '80%'),
                        picker
                        (
                            set::name('fileType'),
                            set::items($config->pivot->fileType),
                            set::value('xlsx'),
                            set::required(true)
                        )
                    )
                )
            ),
            div
            (
                setClass('modal-footer flex justify-center'),
                button
                (
                    set::type('button'),
                    setID('export-data-button'),
                    setClass('btn primary'),
                    $lang->save
                )
            )
        )
    )
)
?>

<script id="exportJS">
/**
 * Export file.
 *
 * @param  object $domObj
 * @access public
 * @return void
 */
exportFile = function($domObj)
{
    if(typeof $domObj == 'undefined') return;

    var fileName  = $('#fileName').val().trim() ? $('#fileName').val().trim() : $('#fileName')[0].placeholder;
    var fileType  = $('[name=fileType]').zui('picker').$.value;
    var tableName = fileName + '.' + fileType;

    if(fileType == 'xlsx' || fileType == 'xls')
    {
        const new_sheet = XLSX.utils.table_to_book($domObj, {raw: true});
        XLSX.writeFile(new_sheet, tableName);
    }
    else if(fileType == 'html' || fileType == 'mht')
    {
        const htmlContent = $domObj.outerHTML;

        const $temp = $('<div>').html(htmlContent);
        $temp.find('*').removeAttr('style');
        $temp.find('*').removeAttr('class');
        $temp.find('*').removeAttr('data-flex');
        $temp.find('*').removeAttr('data-width');
        $temp.find('*').removeAttr('data-type');
        $temp.find('*').removeAttr('data-fixed-left-width');
        const cleanTableHTML = $temp.html();

        var head  = '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        var style = '<style>table, th, td {font-size: 12px; border: 1px solid gray; border-collapse: collapse;}table th, table td {padding: 5px;}</style>';
        var title = '<title>' + fileName + '</title></head>';
        var body  = '<body>' + cleanTableHTML + '</body>';
        const finalHTML = head + style + title + body;

        if(fileType == 'html')
        {
            const blob = new Blob([finalHTML], { type: 'text/html;charset=utf-8' });
            saveAs(blob, tableName);
        }
        else if(fileType == 'mht')
        {
            const data = {html: finalHTML, fileName: fileName};
            $.post($.createLink('file', 'ajaxExport2mht'), data, function(resp)
            {
                const blob = new Blob([resp], { type: "application/x-mimearchive" });
                saveAs(blob, tableName);
            });
        }
    }
    $('#export').modal('hide');
}
</script>

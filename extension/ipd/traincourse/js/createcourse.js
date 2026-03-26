var options = {
    limitFilesCount: 1, 
    previewImageSize: 
    {
        width: 320, height:162
    },
    filters: 
    {
        mime_types: [
            {title: 'img', extensions: 'jpg,jpeg,gif,png'},
        ]
    },
    onBeforeUpload: function (file)
    {
        this.plupload.setOption(
        {
            'multipart_params':
            {
                label: file.ext ? file.name.substr(0, file.name.length - file.ext.length - 1) : file.name,
                uuid: file.id,
                size: file.size,
            }
        });
    }
};
$('#imageUploader').uploader(options);

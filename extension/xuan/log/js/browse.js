$(document).ready(function()
{
    $('.show-comment').click(function()
    {
        var comment = JSON.stringify($(this).data('comment'), null, 4);
        $('#comment-modal .modal-body').html('<code style="white-space: pre;">' + comment + '</code>');
    });
});

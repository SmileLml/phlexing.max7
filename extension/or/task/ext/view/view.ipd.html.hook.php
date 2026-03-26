<style>.main-actions {display: none;}</style>
<script>
$(function()
{
    $('a:not([data-toggle="tab"])').each(function() {
        var text = $(this).text();
        $(this).replaceWith(text);
    });
})
</script>

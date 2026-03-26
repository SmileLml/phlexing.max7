<style>.main-actions {display: none;}</style>
<script>
$(function()
{
    $('#mainContent a').each(function() {
        var text = $(this).text();
        $(this).replaceWith(text);
    });
})
</script>

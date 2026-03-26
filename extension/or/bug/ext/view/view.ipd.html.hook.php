<style>.main-actions {display: none;}</style>
<script>
$(function()
{
    $('a:not([data-toggle="tab"]):not([data-app="product"])').each(function() {
        var text = $(this).text();
        $(this).replaceWith(text);
    });
})
</script>

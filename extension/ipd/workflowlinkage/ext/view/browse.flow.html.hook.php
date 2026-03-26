<script>
$('a.deleter').each(function()
{
    href = $(this).attr('href');
    $(this).attr('href', '###').attr('data-href', href);
})
</script>

<?php if($flow->buildin):?>
<script>
$(function()
{
    $('#type option[value="batch"]').remove();
    $('#position option[value="browse"]').remove();
    $('#position option[value="browseandview"]').remove();
    $('#show option[value="dropdownlist"]').remove();
    $('#show').val('direct');
})
</script>
<?php endif;?>

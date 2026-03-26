<?php if($viewType == 'feedback'):?>
<style>
    .exchange {float: right; margin-top: 10px;}
</style>
<?php
$syncHtml = common::buildIconButton('feedback', 'syncProduct', '', '', 'button', 'exchange', '', 'exchange iframe', true);
?>
<script>
    $('#subHeader .container').prepend(<?php echo json_encode($syncHtml);?>);
</script>
<?php endif;?>

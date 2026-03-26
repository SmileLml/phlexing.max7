<?php if($this->config->vision == 'rnd'):?>
<style>
    .c-feedback {padding-right: 20px !important;}
</style>
<?php
$html  = "<th class='c-feedback'><i class='icon icon-person icon-sm'></i> {$lang->product->feedback}</th>";
$html .= '<td><strong>' . zget($users, $product->feedback) . '</strong></td>';
?>
<script>
    $('.pm-detail').find('.detail-content').find('tr:eq(1)').find('td').after(<?php echo json_encode($html)?>);
</script>
<?php endif;?>

<?php if($this->config->vision == 'rnd'):?>
<style>
    .c-ticket {padding-right: 20px !important;}
</style>
<?php
$html  = "<th class='c-ticket'><i class='icon icon-person icon-sm'></i> {$lang->product->ticket}</th>";
$html .= '<td><strong>' . zget($users, $product->ticket) . '</strong></td>';
?>
<script>
   $('.pm-detail').find('.detail-content').find('tr:eq(2)').find('td').after(<?php echo json_encode($html)?>);
</script>
<?php endif;?>

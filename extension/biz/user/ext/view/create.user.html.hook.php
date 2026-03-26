<?php if(!empty($userAddWarning)):?>
<script>
$("#mainContent .main-header h2").after('<div class="text-danger" style="float:left;line-height:34px;"><?php echo $userAddWarning;?></div>');
</script>
<?php endif;?>

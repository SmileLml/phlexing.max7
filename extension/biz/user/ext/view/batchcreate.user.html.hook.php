<?php if(!empty($userAddWarning)):?>
<script>
$("#mainContent .main-header .btn-toolbar").css('height', '34px');
$("#mainContent .main-header .btn-toolbar").after('<div class="text-danger" style="float:left;line-height:34px;"><?php echo $userAddWarning;?></div>');
</script>
<?php endif;?>

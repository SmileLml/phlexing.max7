<?php if(!empty($_SESSION['user']->feedback) or !empty($_COOKIE['feedbackView'])):?>
<script>
$('.side-col').remove();
</script>
<?php endif;?>

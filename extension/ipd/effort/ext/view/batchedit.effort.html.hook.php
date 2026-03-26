<?php
 $nonRDUser = 'false';
 if(!empty($_SESSION['user']->feedback) or !empty($_COOKIE['feedbackView'])) $nonRDUser = 'true';

 global $app;
 ?>

 <script language='Javascript'>
 $(function()
 {
     var nonRDuser = <?php echo $nonRDUser;?>;
     if(nonRDuser == false) return false;

     $('#objectTable tr').find('th:eq(4)').hide();
     $('#objectTable tr').find('th:eq(5)').hide();
     $('#objectTable tr').find('td:eq(4)').hide();
     $('#objectTable tr').find('td:eq(5)').hide();

     $("#objectTable tr td[colspan]").attr('colspan','4');

 });
</script>

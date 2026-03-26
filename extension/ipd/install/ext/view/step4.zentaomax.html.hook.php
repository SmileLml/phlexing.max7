<script>
$tr = $('#modeALM').closest('tr');
$nextTR = $tr.next();
$tr.remove();
$nextTR.remove();
$('#submit').after("<input type='hidden' name='mode' value='ALM' />");
</script>

<script>
<?php
    $requiredFields = explode(',', $this->config->holiday->require->edit);
    foreach($requiredFields as $field)
    {
        echo "$('#$field').parent().addClass('required');";
    }
?>
</script>

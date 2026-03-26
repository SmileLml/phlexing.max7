<script>
<?php
    $requiredFields = explode(',', $this->config->holiday->require->create);
    foreach($requiredFields as $field)
    {
        echo "$('#$field').parent().addClass('required');";
    }
?>
</script>

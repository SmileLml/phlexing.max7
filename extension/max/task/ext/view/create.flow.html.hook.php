<?php
$relation = '';
$params   = $this->app->getParams();
$extras   = zget($params, 'extras', '');

if($extras)
{
    $extras = str_replace(array(',', ' '), array('&', ''), $extras);
    parse_str($extras, $params);

    if(isset($params['prevModule']) and isset($params['prevDataID']))
    {
        $relation = $this->loadModel('workflowrelation')->getByPrevAndNext($params['prevModule'], 'task');
    }
}
?>
<?php if($relation):?>
<script>
$(function()
{
    $('form #submit').after(<?php echo json_encode(html::hidden("{$relation->field}", $params['prevDataID']));?>);
})
</script>
<?php endif;?>

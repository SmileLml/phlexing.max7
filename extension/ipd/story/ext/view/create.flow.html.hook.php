<?php
$relation = '';
$params   = $this->app->getParams();
$extra    = zget($params, 'extra', '');

if($extra)
{
    $extras = str_replace(array(',', ' '), array('&', ''), $extra);
    parse_str($extras, $params);

    if(isset($params['prevModule']) and isset($params['prevDataID']))
    {
        $relation = $this->loadModel('workflowrelation')->getByPrevAndNext($params['prevModule'], 'story');
    }
}
?>
<?php if($relation):?>
<script>
$(function()
{
    if($('form [name="<?php echo $relation->field;?>"]').length == 0)
    {
        $('form #submit').after(<?php echo json_encode(html::hidden($relation->field, $params['prevDataID']));?>);
    }
    else
    {
        $('form [name="<?php echo $relation->field;?>"]').val('<?php echo $params['prevDataID'];?>').trigger('chosen:updated');
    }
})
</script>
<?php endif;?>

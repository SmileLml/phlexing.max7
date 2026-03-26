<?php
namespace zin;

$context = \zin\context();
extract($context->data);

$viewFile = strtolower($method) . '.html.php';
if(!file_exists($viewFile))
{
    $viewFile = dirname(__FILE__, 2) . DS . 'view' . DS . $viewFile;
    if(file_exists($viewFile))
    {
        include_once $viewFile;
        query('#pivotContent')->empty()->html($context->getRawContent());
    }
}
?>
<script>
window.waitDom('#moduleMenu menu li[z-key="' + '<?php echo $method;?>' + '"]', function(){$('#moduleMenu menu').find('li[z-key="' + '<?php echo $method;?>' + '"] div').first().addClass('selected')});
</script>

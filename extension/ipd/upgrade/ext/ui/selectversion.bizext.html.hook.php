<?php
namespace zin;
global $app;
$bizext  = $app->control->dao->select('*')->from(TABLE_EXTENSION)->where('code')->like('bizext%')->andWhere('status')->eq('installed')->orderBy('version desc')->fetch();
$version = empty($bizext) ? data('version') : 'pro' . str_replace('.', '_', $bizext->version);
?>
<script type='text/javascript'>$(function(){$('.picker-box').on('inited', function(_, info){$('[name=fromVersion]').zui('picker').$.setValue('<?php echo $version?>');})});</script>

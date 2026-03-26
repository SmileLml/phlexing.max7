<?php
/**
 * The export view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen<congzhi@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'file/view/export.html.php';?>
<div id='pivotBox' class='hidden'>
  <?php echo html::hidden('pivot');?>
</div>
<script>
$(document).ready(function()
{
    $('#fileName').closest('td').after($('#pivotBox').html());
    $('#pivotBox').empty();
    $('#pivot').val(JSON.stringify(parent.pivot));
});
</script>

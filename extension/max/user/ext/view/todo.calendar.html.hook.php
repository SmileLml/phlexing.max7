<?php
/**
 * The view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(common::hasPriv('user', 'todocalendar')):?>
<script>
$('#mainContent #contentNav ul.nav').prepend(<?php echo json_encode("<li id='todocalendar'>" . html::a(inlink('todocalendar', "userID={$user->id}"), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback) . '</li>');?>);
</script>
<?php endif;?>

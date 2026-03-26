<?php
namespace zin;
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
<?php
global $lang;
$userID  = data('user.id');
$backBtn = "<li id='todocalendar' class='nav-item item'>" . html::a(inlink('todocalendar', "userID={$userID}"), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback) . '</li>';
?>
<script>
$('#mainContent .dtable-sub-nav').prepend(<?php echo json_encode($backBtn);?>);
</script>
<?php endif;?>

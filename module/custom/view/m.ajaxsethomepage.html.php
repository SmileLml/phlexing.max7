<?php
/**
 * The setIndexPage view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.lite.html.php';?>
<script>
var result = confirm('<?php echo $this->lang->custom->notice->indexPage[$module];?>');
$.get(createLink('custom', 'ajaxSetHomepage', 'module=<?php echo $module?>&page=' + (result ? 'index' : 'browse')), function(){location.reload(true)});
</script>
<?php include '../../common/view/m.footer.html.php';?>

<?php
/**
 * The browse mobile view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */

?>
<?php include '../../common/view/m.header.html.php';?>
<div class='heading'>
  <div class='title'>
    <a id='sortTrigger' class='text-right sort-trigger' data-display data-target='#sortPanel' data-backdrop='true'><i class='icon icon-sort'></i>&nbsp;<span class='sort-name'><?php echo $lang->sort ?></span></a>
  </div>
</div>

<section id='page' class='section list-with-pager'>
  <?php $refreshUrl = $this->createLink('product', 'bug', http_build_query($this->app->getParams()));?>
  <div class='box' data-page='<?php echo $pager->pageID ?>' data-refresh-url='<?php echo $refreshUrl ?>'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th><?php echo $lang->bug->title;?></th>
          <th class='w-80px text-center'><?php echo $lang->bug->status;?></th>
        </tr>
      </thead>
      <?php foreach($bugs as $bug):?>
      <tr class='text-center' data-url='<?php echo $this->createLink('bug', 'view', "bugID={$bug->id}")?>' data-id='<?php echo$bug->id;?>'>
        <td class='text-left'>
        <?php
        $class = 'confirm' . $bug->confirmed;
        echo "<span class='$class'>[{$lang->bug->confirmedList[$bug->confirmed]}]</span> ";
        ?>
        <?php echo "<span style='color:$bug->color'>" . $bug->title . '</span>';?>
        </td>
        <td class='bug-<?php echo $bug->status?>'><?php echo zget($lang->bug->statusList, $bug->status);?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>
  <nav class='nav justified pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
</section>

<div class='list sort-panel hidden affix enter-from-bottom layer' id='sortPanel'>
  <?php
  $vars = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
  $sortOrders = array('id', 'severity', 'pri', 'title', 'status', 'openedBy', 'openedDate', 'deadline', 'assignedTo', 'resolvedBy', 'resolution');
  foreach ($sortOrders as $order)
  {
      commonModel::printOrderLink($order, $orderBy, $vars, '<i class="icon icon-sort-indicator"></i>' . ($lang->bug->{$order}));
  }
  ?>
</div>
<script>
$(function()
{
    $('#<?php echo $browseType;?>Tab').addClass('active');
    <?php if($config->global->flow != 'full'):?>
    $('#appnav a').removeClass('active');
    $("#appnav a[href*='<?php echo $browseType?>']").addClass('active');
    <?php endif;?>
})
</script>
<?php include '../../common/view/m.footer.html.php';?>

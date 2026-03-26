<?php
/**
 * The dynamic browse mobile view file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     company
 * @version     $Id: index.html.php 3830 2016-05-18 09:34:17Z liugang $
 * @link        http://www.zentao.net
 */
?>

<?php
$bodyClass = 'with-menu-top';
include "../../common/view/m.header.html.php";
?>

<nav id='menu' class='menu nav affix dock-top canvas'>
  <?php
  echo html::a(inlink('dynamic', "type=today"),      $lang->action->dynamic->today);
  echo html::a(inlink('dynamic', "type=yesterday"),  $lang->action->dynamic->yesterday);
  echo html::a(inlink('dynamic', "type=twodaysago"), $lang->action->dynamic->twoDaysAgo);
  echo html::a(inlink('dynamic', "type=thisweek"),   $lang->action->dynamic->thisWeek);
  echo html::a(inlink('dynamic', "type=lastweek"),   $lang->action->dynamic->lastWeek);
  echo html::a(inlink('dynamic', "type=thismonth"),  $lang->action->dynamic->thisMonth);
  echo html::a(inlink('dynamic', "type=lastmonth"),  $lang->action->dynamic->lastMonth);
  echo html::a(inlink('dynamic', "type=all"),        $lang->action->dynamic->all);
  ?>
  <a class='moreMenu hidden' data-display='dropdown' data-placement='beside-bottom'><?php echo $lang->more;?></a>
  <div id='moreMenu' class='list dropdown-menu'></div>
</nav>

<section id='page' class='section list-with-actions list-with-pager'>
  <?php $refreshUrl = $this->createLink('company', 'dynamic', "browseType=$browseType&param=$param&recTotal={$pager->recTotal}&date=&direction={$direction}");?>
  <div class='box' data-page='<?php echo $pager->pageID ?>' data-refresh-url='<?php echo $refreshUrl ?>'>
    <table class='table bordered'>
      <?php $firstAction = '';?>
      <?php foreach($dateGroups as $actions): ?>
      <?php foreach($actions as $action): ?>
      <?php if(empty($firstAction)) $firstAction = $action;?>
      <tr data-id='<?php echo $action->id;?>'>
        <td class='w-80px'><?php echo zget($accountPairs, $action->actor); ?></td>
        <td>
          <?php echo $action->actionLabel;?>
          <?php if(strpos(',login,logout,', ",$action->action,") === false):?>
          <?php echo ' ' . $action->objectLabel;?>
          <a class='text-link' href='<?php echo $action->objectLink ?>'><?php echo $action->objectName ?></a>
          <?php endif;?>
        </td>
        <td class='w-120px'><?php echo $action->date ?></td>
      </tr>
      <?php endforeach;?>
      <?php endforeach;?>
    </table>
  </div>

  <nav class='nav justify pager'>
    <?php if(!empty($firstAction)):?>
    <?php
    $firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
    $lastDate  = substr($action->originalDate, 0, 10);
    $hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
    $hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
    $preLink   = $hasPre ? inlink('dynamic', "browseType=$browseType&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($firstDate) . '&direction=pre') : 'javascript:;';
    $nextLink  = $hasNext ? inlink('dynamic', "browseType=$browseType&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($lastDate) . '&direction=next') : 'javascript:;';
    ?>
    <?php if($hasPre or $hasNext):?>
    <ul class='pager pager-justify'>
      <li class='previous<?php if(!$hasPre) echo ' disabled';?>'>
        <a id="prevPage" href="<?php echo $preLink;?>"><i class="icon icon-chevron-left"></i></a>
      </li>
      <li class='next<?php if(!$hasNext) echo ' disabled';?>'>
        <a id="nextPage" href="<?php echo $nextLink;?>"><i class="icon icon-chevron-right"></i></a>
      </li>
    </ul>
    <?php endif;?>
    <?php endif;?>
  </nav>
</section>

<script>
$('#menu > a').removeClass('active').filter('[href*="<?php echo $browseType ?>"]').addClass('active');
</script>
<?php include "../../common/view/m.footer.html.php"; ?>

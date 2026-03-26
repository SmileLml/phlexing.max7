<?php
/**
 * The admin view file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
js::set('moduleID', $moduleID);
js::set('productID', $productID);
js::set('browseType', isset($browseType) ? $browseType : '');
js::set('sessionBrowseType', $this->session->browseType);
js::set('sessionFeedbackBrowseType', $this->session->feedbackBrowseType);
js::set('sessionObjectID', $this->session->objectID);
?>
<div id='mainMenu' class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php
      echo $moduleName;
      if($moduleID != 'all' and $browseType != 'bysearch')
      {
          $removeLink = inlink('admin', "browseType=byProduct&param=all&orderBy=$orderBy&recTotal=0");
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'>
    <?php
    foreach($lang->feedback->featureBar['admin'] as $type => $label)
    {
        if($browseType == 'byProduct' or $browseType == 'byModule') $browseType = $this->session->feedbackBrowseType;

        $dropdownName = $lang->more;
        $activeClass  = $type == $browseType ? 'btn-active-text' : '';

        if($type == 'more')
        {
            if(isset($lang->feedback->moreSelects['admin'][$type][$browseType]))
            {
                $dropdownName = $lang->feedback->moreSelects['admin'][$type][$browseType];
                $activeClass  = 'btn-active-text';
            }
            echo '<div class="btn-group" id="more">';
            echo html::a('javascript:;', "<span class='text'>" . $dropdownName . "</span>" . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $activeClass' data-app={$app->tab} id=" . ($browseType != $this->session->feedbackBrowseType ? 'more' : $browseType) . 'Tab');
            echo "<ul class='dropdown-menu'>";
            foreach($lang->feedback->moreSelects['admin'][$type] as $moreType => $moreLabel)
            {
                $activeClass = $moreType == $browseType ? 'btn-active-text' : '';
                echo '<li>' . html::a(inlink('admin', "browseType=$moreType"), "<span class='text'>" . $moreLabel . "</span>", '', "class='btn btn-link $activeClass'") . '</li>';
            }
            echo '</ul></div>';
        }
        else
        {
            echo html::a(inlink('admin', "browseType=$type"), "<span class='text'>$label</span>", '',"class='btn btn-link $activeClass' data-app={$app->tab} id=" . $type .'Tab');
        }
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->feedback->search;?></a>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    if(common::hasPriv('feedback', 'export')) echo html::a($this->createLink('feedback', 'export', "browseType=$browseType&orderBy=$orderBy"), "<i class='icon-export muted'></i> " . $this->lang->export, '', "class='btn btn-link export'");
    if(common::hasPriv('feedback', 'create')) echo html::a($this->createLink('feedback', 'create', "extras=moduleID=$moduleID,productID=$productID"), "<i class='icon-plus'></i> " . $this->lang->feedback->create, '', "class='btn btn-primary'");
    ?>
  </div>
</div>
<div id='queryBox' data-module='feedback' class='cell <?php if($browseType == 'bysearch') echo 'show';?>'></div>
<div id='mainContent' class='main-row'>
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->feedback->noModule;?></div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php if($productID != 'all'):?>
        <?php $productID = $this->session->feedbackProduct;?>
        <?php common::printLink('tree', 'browse', "productID=$productID&view=feedback", $lang->feedback->manageCate, '', "class='btn btn-info btn-wide' data-group='feedback'");?>
        <?php endif;?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
  <?php $viewMethod = 'adminView'?>
  <?php include './data.html.php';?>
  </div>
</div>
<script>
$(function()
{
    if(browseType != 'bysearch' && sessionObjectID && productID)
    {
        if(sessionBrowseType == 'byProduct')
        {
            $('#product' + sessionObjectID).closest('li').addClass('active');
        }
        else if(sessionBrowseType == 'byModule')
        {
            $('#module' + sessionObjectID).closest('li').addClass('active');
        }
    }
});
if(browseType == 'byProduct' || browseType == 'byModule') browseType = sessionFeedbackBrowseType;
$("#" + browseType + 'Tab').find('.text').after(" <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
<?php if(count($feedbacks) <= 2):?>
$('#feedbackForm .table-footer .table-actions #assignedTo').closest('.btn-group.dropup').removeClass('dropup').addClass('dropdown');
$('#feedbackForm .table-footer .table-actions #moduleSearchBox').closest('.btn-group.dropup').removeClass('dropup').addClass('dropdown');
<?php endif;?>
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

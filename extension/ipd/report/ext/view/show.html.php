<?php
/**
 * The show view file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2014 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     report
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.html.php';?>
<?php if(strpos($report->module, 'product') !== false || strpos($report->module, 'project') !== false || strpos($report->module, 'test') !== false):?>
<style>#mainContent > .side-col.col-lg{width: 235px;}</style>
<?php endif;?>
<?php if(strpos($report->module, 'staff') !== false):?>
<style>#mainContent > .side-col.col-lg{width: 210px;}</style>
<?php endif;?>
<style>.hide-sidebar #sidebar{width: 0 !important}</style>
<div id='mainContent' class='main-row'>
  <div class='side-col col-lg' id='sidebar'>
    <?php include './blockreportlist.html.php'?>
  </div>
  <div class='main-col'>
    <?php if($setVars):?>
    <div class="cell">
      <div class="with-padding">
        <form method='post'>
          <div class="table-row" id='conditions'>
            <?php include 'setvarvalues.html.php';?>
            <div class='col-md-3 col-sm-6'><?php echo html::submitButton($lang->crystal->query, '', 'btn btn-primary');?></div>
          </div>
        </form>
      </div>
    </div>
    <?php endif;?>
    <?php if(!empty($dataList) and isset($step)):?>
    <div class='cell'>
      <div class='panel'>
        <div class="panel-heading">
          <div class="panel-title">
            <?php echo $title;?>
            <?php
            $desc = json_decode($report->desc, true);
            $clientLang = $this->app->getClientLang();
            if(!empty($desc[$clientLang])):
            ?>
            <?php
            /* Replace project or product by workflow. */
            $desc[$clientLang] = $this->report->replace4Workflow($desc[$clientLang]);
            ?>
            <a data-toggle='tooltip' title='<?php echo $desc[$clientLang];?>'><i class='icon-help'></i></a>
            <?php endif;?>
          </div>
          <?php if(common::hasPriv('report', 'crystalExport')):?>
          <nav class="panel-actions btn-toolbar">
            <?php echo html::a($this->createLink('report', 'crystalExport', "step=$step&reportID=$reportID"), $lang->export, '', "data-width='600' class='export btn btn-primary btn-sm'")?>
          </nav>
          <?php endif;?>
        </div>
        <div class='panel-body' style='padding:0px;'><?php include 'reportdata.html.php';?> </div>
        <?php if($step == 1):?>
        <div class='panel-footer'><?php printf($lang->crystal->noticeResult, count($dataList), count($dataList))?></div>
        <?php endif;?>
      </div>
    </div>
    <?php else:?>
    <div class="cell">
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

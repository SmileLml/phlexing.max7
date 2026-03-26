<?php
/**
 * The view of researchreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     researchreport
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $backLink = $this->session->researchreportList ? $this->session->researchreportList : inlink('browse', "projectID=$report->project");?>
    <?php if(!isonlybody()):?>
    <?php echo html::a($backLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $report->id;?></span>
      <span class="text" title='<?php echo $report->title;?>'><?php echo $report->title;?></span>
      <?php if($report->deleted):?>
      <span class='label label-danger'><?php echo $lang->researchreport->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->researchreport->content;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($report->content) ? $report->content : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($backLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$report->deleted):?>
        <?php
          common::printIcon('researchreport', 'edit',   "reportID=$report->id", $report);
          common::printIcon('researchreport', 'delete', "repo  rtID=$report->id", $report, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class="tabs">
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#basicInfo' data-toggle='tab'><?php echo $lang->researchreport->basicInfo;?></a></li>
          <li><a href='#lifeTime' data-toggle='tab'><?php echo $lang->researchreport->lifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class='table table-data'>
              <tbody>
                <tr>
                  <th class=<?php echo $this->app->getClientLang() == 'zh-cn' ? 'w-90px' : 'w-130px';?>><?php echo $lang->researchreport->author;?></th>
                  <td><?php echo zget($users, $report->author);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->relatedPlan;?></th>
                  <td title="<?php echo zget($planPairs, $report->relatedPlan);?>"><?php echo html::a($this->createLink('researchplan', 'view', "planID=$report->relatedPlan", '', true), zget($planPairs, $report->relatedPlan), '', "class='iframe' data-width='80%'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->customer;?></th>
                  <td title="<?php echo $report->customer;?>"><?php echo $report->customer;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->researchObjects;?></th>
                  <td title="<?php echo $report->researchObjects;?>"><?php echo $report->researchObjects;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->researchTime;?></th>
                  <td><?php echo (!helper::isZeroDate($report->begin) and !helper::isZeroDate($report->end)) ? substr($report->begin, 0, 16) . ' ~ ' . substr($report->end, 0, 16) : '';?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->location;?></th>
                  <td title="<?php echo $report->location;?>"><?php echo $report->location;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->method;?></th>
                  <td><?php echo zget($lang->researchreport->methodList, $report->method);?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='lifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->researchreport->createdBy;?></th>
                  <td><?php echo zget($users, $report->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->createdDate;?></th>
                  <td><?php echo $report->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->editedBy;?></th>
                  <td><?php echo zget($users, $report->editedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchreport->editedDate;?></th>
                  <td><?php echo helper::isZeroDate($report->editedDate) ? '' : $report->editedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendRelated' data-toggle='tab'><?php echo $lang->researchreport->legendRelated;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendRelated'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->researchreport->legendFromUR;?></th>
                  <td class="pd-0">
                    <ul class='list-unstyled'>
                    <?php
                    $inIframe = isonlybody() ? '' : 'iframe';

                    foreach($relatedUR as $id => $title)
                    {
                        echo "<li title='#$id $title'>" . html::a($this->createLink('story', 'view', "storyID=$id&version=0&param={$report->project}", '', true), "#$id $title", '', "class='$inIframe' data-width='80%'") . '</li>';
                    }
                    ?>
                    </ul>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

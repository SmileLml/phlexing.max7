<?php
/**
 * The view of researchplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     researchplan
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $backLink = $this->session->researchplanList ? $this->session->researchplanList : inlink('browse', "projectID=$report->project");?>
    <?php if(!isonlybody()):?>
    <?php echo html::a($backLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif; ?>
    <div class="page-title">
      <span class="label label-id"><?php echo $plan->id;?></span>
      <span class="text" title='<?php echo $plan->name;?>'><?php echo $plan->name;?></span>
      <?php if($plan->deleted):?>
      <span class='label label-danger'><?php echo $lang->researchplan->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->researchplan->outline;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($plan->outline) ? $plan->outline : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->researchplan->schedule;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($plan->schedule) ? $plan->schedule : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($backLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$plan->deleted):?>
        <?php
          common::printIcon('researchplan', 'edit', "planID=$plan->id", $plan);
          common::printIcon('researchplan', 'delete', "planID=$plan->id", $plan, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->researchplan->basicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class='table table-data'>
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->researchplan->customer;?></th>
                  <td title="<?php echo $plan->customer;?>"><?php echo $plan->customer;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->stakeholder;?></th>
                  <?php $stakeholders = ''; foreach(explode(',', $plan->stakeholder) as $stakeholder) $stakeholders .= zget($users, $stakeholder) . ' ';?>
                  <td title="<?php echo $stakeholders;?>"><?php echo $stakeholders;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->objective;?></th>
                  <td title="<?php echo $plan->objective;?>"><?php echo $plan->objective;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->location;?></th>
                  <td title="<?php echo $plan->location;?>"><?php echo $plan->location;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->begin;?></th>
                  <td><?php echo helper::isZeroDate($plan->begin) ? '' : $plan->begin;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->end;?></th>
                  <td><?php echo helper::isZeroDate($plan->end) ? '' : $plan->end;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->team;?></th>
                  <?php $teamUser = ''; foreach(explode(',', $plan->team) as $user) $teamUser .= zget($users, $user) . ' ';?>
                  <td title="<?php echo $teamUser;?>"><?php echo $teamUser;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->method;?></th>
                  <td><?php echo zget($lang->researchplan->methodList, $plan->method);?></td>
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
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->researchplan->lifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='lifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->researchplan->createdBy;?></th>
                  <td><?php echo zget($users, $plan->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->createdDate;?></th>
                  <td><?php echo $plan->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->editedBy;?></th>
                  <td><?php echo zget($users, $plan->editedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->researchplan->editedDate;?></th>
                  <td><?php echo helper::isZeroDate($plan->editedDate) ? '' : $plan->editedDate;?></td>
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

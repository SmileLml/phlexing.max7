<?php
/**
 * The team view file of marketresearch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hu Fangzhou
 * @package     marketresearch
 * @version     $Id: team.html.php 4143 2023-09-14 11:01:06Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('confirmUnlinkMember', $lang->marketresearch->confirmUnlinkMember);?>
<?php js::set('noAccess', $lang->user->error->noAccess);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <span class='text'><?php echo $lang->project->teamMember;?></span>
      <span class="label label-light label-badge"><?php echo count($teamMembers);?></span>
    </span>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    common::printBack($this->session->marketresearchList, 'btn btn-link');	
    if(!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])) common::printLink('marketresearch', 'manageMembers', "researchID=$researchID&dept=&copyResearchID=0", "<i class='icon icon-persons'></i> " . $lang->project->manageMembers, '', "class='btn btn-primary manage-team-btn'");
    ?>
  </div>
</div>
<div id='mainContent'>
  <?php if(empty($teamMembers)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->marketresearch->noMembers;?></span>
      <?php if((!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])) && common::hasPriv('marketresearch', 'manageMembers')):?>
      <?php echo html::a($this->createLink('marketresearch', 'manageMembers', "researchID=$researchID&dept=&copyResearchID=0"), "<i class='icon icon-persons'></i> " . $lang->project->manageMembers, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table'>
    <table class='table' id='memberList'>
      <thead>
        <tr>
          <th><?php echo $lang->team->account;?></th>
          <th><?php echo $lang->team->role;?></th>
          <th><?php echo $lang->team->join;?></th>
          <th><?php echo $lang->team->days;?></th>
          <th><?php echo $lang->team->hours;?></th>
          <th><?php echo $lang->team->totalHours;?></th>
          <th class='c-limited text-center'><?php echo $lang->team->limited;?></th>
          <th class='c-actions-1 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $totalHours = 0;?>
        <?php foreach($teamMembers as $member):?>
        <tr>
          <td>
          <?php
          echo $member->realname;

          $memberHours = $member->days * $member->hours;
          $totalHours += $memberHours;
          ?>
          </td>
          <td title='<?php echo $member->role;?>'><?php echo $member->role;?></td>
          <td><?php echo $member->join;?></td>
          <td><?php echo $member->days . $lang->execution->day;?></td>
          <td><?php echo $member->hours . $lang->execution->workHour;?></td>
          <td><?php echo $memberHours . $lang->execution->workHour;?></td>
          <td class="text-center"><?php echo $lang->team->limitedList[$member->limited];?></td>
          <td class='c-actions text-center'>
            <?php
            if (common::hasPriv('marketresearch', 'unlinkMember'))
            {
                echo html::a("javascript:deleteMemeber($researchID, \"{$member->account}\", \"{$member->userID}\")", '<i class="icon-green-project-unlinkMember icon-unlink"></i>', '', "class='btn' title='{$lang->execution->unlinkMember}'");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class='table-statistic'><?php echo $lang->team->totalHours . '：' .  "<strong>$totalHours{$lang->execution->workHour}" . sprintf($lang->project->teamMembersCount, count($teamMembers)) . "</strong>";?></div>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

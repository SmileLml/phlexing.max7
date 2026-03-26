<?php
/**
 * The view of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Li Yuchun <liyuchun@easycorp.ltd>
 * @package     view
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $browseLink = $app->session->meetingList ? $app->session->meetingList : $this->createLink('meeting', 'browse', "projectID=$projectID&from=$from");?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary' data-app='{$app->tab}'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $meeting->id?></span>
      <span class="text" title='<?php echo $meeting->name;?>'><?php echo $meeting->name;?></span>
      <?php if($meeting->deleted):?>
      <span class='label label-danger'><?php echo $lang->meeting->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->meeting->date;?></div>
        <div class="detail-content article-content"><?php echo $meeting->date . ' ' . $meeting->begin . ' - ' . $meeting->end;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->meeting->participant;?></div>
        <div class="detail-content article-content"><?php echo $meeting->participantName;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->meeting->minutes;?></div>
        <div class="detail-content article-content"><?php echo $meeting->minutes;?></div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $meeting->files, 'fieldset' => 'true', 'object' => $meeting));?>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink . "#app={$app->tab}");?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$meeting->deleted):?>
        <?php
        echo "<div class='divider'></div>";
        common::printIcon('meeting', 'edit', "meetingID=$meeting->id" . "&from=$from", $meeting);
        common::printIcon('meeting', 'minutes', "meetingID=$meeting->id", $meeting, 'list', 'summary', '', 'iframe', true);
        common::printIcon('meeting', 'delete', "meetingID=$meeting->id&from=$from", $meeting, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->meeting->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <?php if(!empty($project)):?>
                <tr>
                  <th class='w-90px'><?php echo $lang->meeting->project;?></th>
                  <td><?php if(!common::printLink('project', 'view', "projectID=$meeting->project", $project->name, '', 'data-app="project"')) echo $execution->name;?></td>
                </tr>
                <?php endif;?>
                <?php if(!empty($execution) && $execution->multiple):?>
                <tr>
                  <th class='w-90px'><?php echo $lang->meeting->execution;?></th>
                  <td><?php if(!common::printLink('execution', 'view', "executionID=$meeting->execution", $execution->name, '', 'data-app="execution"')) echo $execution->name;?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class="<?php echo $this->app->getClientLang() == 'zh-cn' ? 'w-90px' : 'w-100px';?>"><?php echo $lang->meeting->room;?></th>
                  <td><?php echo zget($rooms, $meeting->room, '');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->meeting->dept;?></th>
                  <td><?php echo zget($depts, $meeting->dept);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->meeting->mode;?></th>
                  <td><?php echo zget($lang->meeting->modeList, $meeting->mode);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->meeting->type;?></th>
                  <td><?php echo zget($typeList, $meeting->type);?></td>
                </tr>
                <?php if($meeting->objectType and $meeting->objectID):?>
                <tr>
                  <th><?php echo $lang->meeting->linked . $config->meeting->objectTypeList[$meeting->objectType];?></th>
                  <td><?php echo html::a($this->createLink($meeting->objectType, 'view', $meeting->objectType . "ID=" . $meeting->objectID, '', true), $meeting->objectName, '', isonlybody() ? '' : "class='iframe'");?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th><?php echo $lang->meeting->host;?></th>
                  <td><?php echo zget($users, $meeting->host);?></td>
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
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->meeting->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class="w-90px"><?php echo $lang->meeting->minutedBy;?></th>
                  <td><?php echo zget($users, $meeting->minutedBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->meeting->minutedDate;?></th>
                  <td><?php echo helper::isZeroDate($meeting->minutedDate) ? '' : $meeting->minutedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->meeting->createdBy;?></th>
                  <td><?php echo zget($users, $meeting->createdBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->meeting->createdDate;?></th>
                  <td><?php echo $meeting->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->meeting->editedBy;?></th>
                  <td><?php echo zget($users, $meeting->editedBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->meeting->editedDate;?></th>
                  <td><?php echo helper::isZeroDate($meeting->editedDate) ? '' : $meeting->editedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext, $this->createLink('meeting', 'view', "meetingID=%s&from=$from"));?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

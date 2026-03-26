<?php
/**
 * The details view of zoutput module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     zoutput
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php
$browseLink = $this->createLink('zoutput', 'browse', "model={$this->session->model}");
$createLink = $this->createLink('zoutput', 'create');
$dateFiled  = array('createdDate', 'editedDate');
foreach($output as $field => $value)
{
    if(in_array($field, $dateFiled) && strpos($value, '0000') === 0) $output->$field = '';
}
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $output->id;?></span>
      <span class="text" title="<?php echo $output->name;?>"><?php echo $output->name;?></span>
      <?php if($output->deleted):?>
      <span class='label label-danger'><?php echo $lang->zoutput->deleted;?></span>
      <?php endif;?>
    </div>
  </div>
</div>
<div class="main-row" id="mainContent">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->zoutput->content;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($output->content) ? $output->content : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php if($output->files):?>
      <div class="detail"><?php echo $this->fetch('file', 'printFiles', array('files' => $output->files, 'fieldset' => 'true'));?></div>
      <?php endif;?>
    </div>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=zoutput&objectID=$output->id");?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$output->deleted):?>
        <?php
        $params = "outputID=$output->id";
        echo "<div class='divider'></div>";
        common::printIcon('zoutput', 'edit', $params, $output);
        common::printIcon('zoutput', 'delete', $params, $output, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><strong><?php echo $lang->zoutput->basicInfo;?></strong></div>
        <div class="detail-content">
          <table class="table table-data">
            <tbody>
              <tr>
                <th class="w-90px"><?php echo $lang->zoutput->activity;?></th>
                <td title="<?php echo zget($activity, $output->activity, '');?>"><?php echo zget($activity, $output->activity, '');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->zoutput->optional;?></th>
                <td><?php echo zget($lang->zoutput->optionalList, $output->optional, '');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->zoutput->tailorNorm;?></th>
                <td title="<?php echo $output->tailorNorm;?>"><?php echo $output->tailorNorm;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->zoutput->createdBy;?></th>
                <td><?php echo zget($users, $output->createdBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->zoutput->createdDate;?></th>
                <td><?php echo $output->createdDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->zoutput->editedBy;?></th>
                <td><?php echo zget($users, $output->editedBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->zoutput->editedDate;?></th>
                <td><?php echo $output->editedDate;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The scope view file of deploy module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     deploy
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <?php include './nav.html.php';?>
  <div class='btn-toolbar pull-right'>
    <?php
    $browseLink = $this->session->stepList ? $this->session->stepList :  inlink('steps', "deployID=$deploy->id");
    $params     = "deployID=$deploy->id";
    common::printLink('deploy', 'manageScope', $params, $lang->deploy->manageScope, '', "class='btn btn-primary'");
    echo html::linkButton("<i class='icon-back icon-level-up icon-large icon-rotate-270'></i> " . $lang->goback, $browseLink, 'self', '', 'btn btn-secondary');
    ?>
  </div>
</div>
<div class='main-content'>
  <?php if(empty($scope)):?>
  <div class='table-empty-tip'><?php echo $lang->noData;?></div>
  <?php else:?>
  <table class='table table-bordered'>
    <thead>
      <tr>
        <th><?php echo $lang->deploy->service?></th>
        <th><?php echo $lang->deploy->hadHost?></th>
        <th><?php echo $lang->deploy->removeHost?></th>
        <th><?php echo $lang->deploy->addHost?></th>
      <tr>
    </thead>
    <tbody>
      <?php foreach($scope as $item):?>
      <tr>
        <td title='<?php echo $optionMenu[$item->service]?>'><?php echo $optionMenu[$item->service]?></td>
        <td><?php foreach(explode(',', $item->hosts) as $hostID) echo zget($hosts, $hostID, '') . ' ';?></td>
        <td><?php foreach(explode(',', $item->remove) as $hostID) echo zget($hosts, $hostID, '') . ' ';?></td>
        <td><?php foreach(explode(',', $item->add) as $hostID) echo zget($hosts, $hostID, '') . ' ';?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

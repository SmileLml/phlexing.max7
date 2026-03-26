<?php
/**
 * The view file of dataset module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dataset
 * @version     $Id: view.html.php 4386 2013-02-19 07:37:45Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->datasetList ? $this->session->datasetList : $this->createLink('dataset', 'browse');?>
    <?php common::printBack($browseLink, 'btn btn-secondary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='text' title='<?php echo $table->name;?>'><?php echo $table->name;?></span>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('chart', 'create', "dataset=$table->key", '<i class="icon icon-plus"></i> ' . $lang->chart->create, '', 'class="btn btn-primary"');?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='tabs' id='tabsNav'>
    <ul class='nav nav-tabs'>
      <li <?php if($type == 'data') echo "class='active'"?>><a href='#data' data-toggle='tab'><?php echo '<i class="icon-list"></i> ' . $lang->dataset->data;?></a></li>
      <li <?php if($type == 'schema') echo "class='active'"?>><a href='#schema' data-toggle='tab'><?php echo '<i class="icon-layout"></i> ' . $lang->dataset->schema;?></a></li>
    </ul>
    <div class='tab-content main-table'>
      <div class='tab-pane <?php if($type == 'data') echo 'active'?>' id='data'>
      <table class='table' id='tableData' style="min-width: <?php echo count($table->schema->fields)*100;?>px">
          <thead>
            <tr class='text-left'>
              <?php foreach($table->schema->fields as $field):?>
              <th><?php echo $field['name'];?></th>
              <?php endforeach;?>
            </tr>
          </thead>
          <tbody class='text-left'>
            <?php foreach($rows as $row):?>
            <tr>
              <?php foreach($table->schema->fields as $field => $info):?>
              <td><?php $this->dataset->printCell($row, $field, $info);?></td>
              <?php endforeach;?>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class='tab-pane <?php if($type == 'schema') echo 'active'?>' id='schema'>
        <table class='table' id='fieldList'>
          <thead>
            <tr>
              <th class='fieldName text-left'><?php echo $lang->dataset->fieldName;?></th>
              <th class='text-left'><?php echo $lang->dataset->fieldType;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($table->schema->fields as $field):?>
              <tr>
                <td class='text-left'><?php echo $field['name'];?></td>
                <td class='text-left'><?php echo $lang->dataset->fieldTypeList[$field['type']];?></td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

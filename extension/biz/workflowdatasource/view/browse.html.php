<?php
/**
 * The admin workflow datasources view file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflow 
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php extCommonModel::printLink('workflowdatasource', 'create', '', "<i class='icon icon-plus'> </i>" . $lang->workflowdatasource->create, "class='btn btn-primary' data-toggle='modal'");?>
</div>
<div class='main-table' data-ride='table'>
  <table class='table has-sort-head'>
    <thead>
      <tr>
        <?php $vars="&orderBy=%s";?>
        <th class='w-80px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->workflowdatasource->id);?></th>
        <th class='w-140px'><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->workflowdatasource->name);?></th>
        <th class='w-140px'><?php commonModel::printOrderLink('code', $orderBy, $vars, $lang->workflowdatasource->code);?></th>
        <th><?php echo $lang->workflowdatasource->datasource;?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->workflowdatasource->type);?></th>
        <th class='w-80px text-center'><?php commonModel::printOrderLink('buildin', $orderBy, $vars, $lang->workflowdatasource->buildin);?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->workflowdatasource->createdBy);?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('createdDate', $orderBy, $vars, $lang->workflowdatasource->createdDate);?></th>
        <th class='w-130px text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <?php foreach($datasources as $datasource):?>
      <tr>
        <td><?php echo $datasource->id;?></td>
        <td><?php echo $datasource->name;?></td>
        <td><?php echo $datasource->code;?></td>
        <td>
        <?php 
            if($datasource->type == 'system')
            {
                $data = json_decode($datasource->datasource);
                echo $data->app . '->' . $data->module . 'Model->' . $data->method . '(';
                $i = 0;
                foreach($data->params as $param)
                {
                    if($i > 0) echo ', ';
                    echo $param->name . '=' . $param->value;
                    $i++;
                }
                echo ')';
            }
            elseif($datasource->type == 'option')
            {
                echo implode(',', json_decode($datasource->datasource, true));
            }
            elseif($datasource->type == 'sql')
            {
                echo $datasource->datasource;
            }
            elseif($datasource->type == 'lang')
            {
                echo zget($lang->workflowdatasource->langList, $datasource->datasource);
            }
            elseif($datasource->type == 'category')
            {
                echo "treeModel->getOptionMenu('datasource_{$datasource->id}')";
            }
            elseif($datasource->type == 'func')
            {
            }
        ?>
        </td>
        <td><?php echo $lang->workflowdatasource->typeList[$datasource->type];?></td>
        <td class='text-center buildin<?php echo $datasource->buildin;?>'><?php echo $datasource->buildin ? "<i class='icon icon-check'></i>" : "<i class='icon icon-times'></i>";?></td>
        <td><?php echo zget($users, $datasource->createdBy);?></td>
        <td><?php echo formatTime($datasource->createdDate, DT_DATE1);?></td>
        <td class='actions'>
          <?php
          $canManage = commonModel::hasPriv('tree', 'browse') && $datasource->type == 'category';
          $canEdit   = commonModel::hasPriv('workflowdatasource', 'edit');
          $canDelete = commonModel::hasPriv('workflowdatasource', 'delete');

          if($canManage) echo baseHTML::a($this->createLink('tree', 'browse', "rootID=0&type=datasource_{$datasource->id}&currentModuleID=0&branch=&from=workflow"), $lang->workflowdatasource->category);
          if($canEdit)
          {
              if($datasource->buildin)  echo baseHTML::a('javascript:;', $lang->edit, "class='disabled'");
              if(!$datasource->buildin) echo baseHTML::a(inlink('edit', "id=$datasource->id"), $lang->edit, "class='edit' data-toggle='modal'");
          }
          if($canDelete)
          {
              if($datasource->buildin)  echo baseHTML::a('javascript:;', $lang->delete, "class='disabled'");
              if(!$datasource->buildin) echo baseHTML::a(inlink('delete', "id=$datasource->id"), $lang->delete, "class='deleter'");
          }
          ?>
        </td>
      </tr>
    <?php endforeach;?>
  </table>
  <div class='table-footer'><?php echo $pager->show('right', 'pagerjs');?></div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

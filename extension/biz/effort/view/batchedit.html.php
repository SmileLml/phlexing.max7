<?php
/**
 * The control file of effort module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     effort
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->effort->batchEdit;?></h2>
  </div>
  <form method='post' target='hiddenwin' action='<?php echo $this->createLink('effort', 'batchEdit', 'from=batchEdit')?>'>
    <table class='table table-form table-fixed' id='objectTable'>
      <thead>
        <?php if(!empty($efforts)):?>
        <tr>
          <th class='c-id'><?php echo $lang->idAB;?></th>
          <?php if($config->vision != 'lite'):?>
          <th class='c-product'><?php echo $lang->effort->product;?></th>
          <?php endif;?>
          <th class='c-execution'><?php echo $lang->effort->execution;?></th>
          <th class='c-date'><?php echo $lang->effort->date;?></th>
          <th><?php echo $lang->effort->work;?></th>
          <th class='c-consumed'><?php echo $lang->effort->consumed . '(' . $lang->effort->hour . ')';?></th>
          <th class='c-objectType'><?php echo $lang->effort->objectType;?></th>
          <th class='c-left'><?php echo $lang->effort->left . '(' . $lang->effort->hour . ')';?></th>
        </tr>
        <?php endif;?>
      </thead>
      <?php foreach($efforts as $id => $effort):?>
      <tr id='row<?php echo $id?>'>
        <?php $disabled = in_array($effort->objectType, array('risk', 'issue', 'opportunity')) ? 'disabled=disabled' : '';?>
        <td class='text-center'>
          <?php echo $id . html::hidden("id[$id]", $id) . html::hidden("objectID[$id]", $effort->objectID);?>
          <?php if($config->vision == 'lite') echo html::hidden("product[$id][]", $effort->product);?>
        </td>
        <?php if($config->vision != 'lite'):?>
        <td style='overflow:visible'>
          <?php $product = str_replace(',', '', $effort->product);?>
          <?php if(isset($shadowProducts[$product])):?>
          <?php echo html::input("product[$id][]", $shadowProducts[$product], "class='form-control' disabled");?>
          <?php echo html::hidden("product[$id][]", $effort->product);?>
          <?php else:?>
          <?php echo html::select("product[$id][]", $products, $effort->product, "class='form-control chosen' multiple $disabled");?>
          <?php endif;?>
        </td>
        <?php endif;?>
        <td style='overflow:visible'><?php echo html::select("execution[$id]", $executions, $effort->execution, "class='form-control chosen' $disabled");?></td>
        <td class='text-center'><?php echo html::input("date[$id]", "$effort->date", "class='form-date form-control'");?></td>
        <td><?php echo html::input("work[$id]", "$effort->work", "class='form-control'");?></td>
        <td><?php echo html::input("consumed[$id]", $effort->consumed, 'autocomplete="off" class="form-control text-center"');?></td>
        <td style='overflow:visible'><?php echo html::select("objectType[$id]", $typeList, "{$effort->objectType}_{$effort->objectID}", 'tabindex="9999" onchange=setLeftInput(this) class="form-control chosen"');?></td>
        <td>
          <?php $disabled = $effort->objectType == 'task' ? 'readonly' : 'disabled'?>
          <?php echo html::input("left[$id]", $effort->left, "autocomplete='off' class='$disabled form-control text-center' " . $disabled);?>
        </td>
      </tr>
      <?php endforeach;?>
      <tr>
          <td colspan='<?php echo $this->config->vision != 'lite' ? 8 : 7?>' class='text-center form-actions'>
          <?php echo html::hidden('effortIDList', join(',', $_POST['effortIDList']));?>
          <?php echo html::submitButton() . html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

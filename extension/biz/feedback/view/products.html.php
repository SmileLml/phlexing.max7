<?php
/**
 * The admin view file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <span class='label label-info'><?php echo $lang->feedback->hasPrivUser;?></span>
  <div class='btn-toolbar pull-right'>
  <?php common::printLink('feedback', 'productSetting', "", '<i class="icon icon-cog-outline"></i> ' . $lang->feedback->productSetting, '', "data-type='iframe' data-toggle='modal' data-size='lg'");?>
  </div>
</div>
<div id='mainContent' class='main-table'>
  <table id="productList" class="table has-sort-head table-nested table-fixed" data-ride="table" data-nested='true' data-preserve-nested='true' data-expand-nest-child='true'>
    <thead>
      <tr>
        <th class='w-400px'>
          <?php echo $lang->productCommon;?>
        </th>
        <th><?php echo $lang->user->common;?></th>
        <th class='w-60px c-actions text-left'><?php echo $lang->actions?></th>
      </tr>
    </thead>
    <?php if($productStructure):?>
    <tbody id="productTableList">
      <?php $lineNames = array();?>
      <?php foreach($productStructure as $programID => $program):?>
      <?php
      $trAttrs  = "data-id='program.$programID' data-parent='0' data-nested='true'";
      $trClass  = 'is-top-level table-nest-child text-center';
      $trAttrs .= " class='$trClass'";
      ?>
        <?php
        if(isset($programLines[$programID]))
        {
            foreach($programLines[$programID] as $lineID => $lineName)
            {
                if(!isset($program[$lineID]))
                {
                    $program[$lineID] = array();
                    $program[$lineID]['product']  = '';
                    $program[$lineID]['lineName'] = $lineName;
                }
            }
        }
        ?>
        <?php if(isset($program['programName']) and $config->systemMode == 'ALM'):?>
        <tr class="row-program" <?php echo $trAttrs;?>>
          <td class='text-left table-nest-title' title="<?php echo $program['programName']?>">
            <i class="table-nest-icon icon table-nest-toggle icon-plus"></i>
            <i class="icon icon-cards-view"></i>
            <span><?php echo $program['programName']?></span>
          </td>
        </tr>
        <?php unset($program['programName']);?>
        <?php endif;?>
        <?php foreach($program as $lineID => $line):?>
        <?php if(isset($line['lineName']) and isset($line['products']) and is_array($line['products']) and $config->systemMode == 'ALM'):?>
        <?php $lineNames[] = $line['lineName'];?>
        <?php
        if($programID)
        {
            $trAttrs  = "data-id='line.$lineID' data-parent='program.$programID'";
            $trAttrs .= " data-nest-parent='program.$programID' data-nest-path='program.$programID,line.$lineID'" . "class='text-center'";
        }
        else
        {
            $trAttrs  = "data-id='line.$lineID' data-parent='0' data-nested='true'";
            $trClass  = 'is-top-level table-nest-child text-center';
            $trAttrs .= " class='$trClass'";
        }
        ?>
        <tr class="row-line" <?php echo $trAttrs;?>>
          <td class='text-left table-nest-title' title="<?php echo $line['lineName']?>">
            <span class="table-nest-icon icon table-nest-toggle"></span>
            <?php echo $line['lineName']?>
          </td>
        </tr>
        <?php unset($line['lineName']);?>
        <?php endif;?>

        <?php if(isset($line['products']) and is_array($line['products'])):?>
        <?php foreach($line['products'] as $productID => $product):?>
        <?php
        $trClass = '';
        if($product->line and $this->config->systemMode == 'ALM')
        {
            $path = "line.$product->line,$product->id";
            if($this->config->systemMode == 'ALM' and $product->program) $path = "program.$product->program,$path";
            $trAttrs  = "data-id='$product->id' data-parent='line.$product->line'";
            $trClass .= ' is-nest-child  table-nest';
            $trAttrs .= " data-nest-parent='line.$product->line' data-nest-path='$path'";
        }
        elseif($product->program and $this->config->systemMode == 'ALM')
        {
            $trAttrs  = "data-id='$product->id' data-parent='program.$product->program'";
            $trClass .= ' is-nest-child  table-nest';
            $trAttrs .= " data-nest-parent='program.$product->program' data-nest-path='program.$product->program,$product->id'";
        }
        else
        {
            $trAttrs  = "data-id='$product->id' data-parent='0'";
            $trClass .= ' no-nest';
        }
        $trAttrs .= " class='$trClass'";
        ?>
        <tr class="row-product" <?php echo $trAttrs;?>>
          <td class="c-name text-left sort-handler table-nest-title" title='<?php echo $product->name?>'>
            <?php echo $product->name;?>
          </td>
          <?php
          $authorizedUser = '';
          if(isset($feedbackView[$productID]))
          {
              foreach($feedbackView[$productID] as $account => $view)
              {
                  if(!isset($users[$account])) continue;
                  $user = $users[$account];
                  $authorizedUser .= empty($user->realname) ? ',' . $account : ',' . $user->realname;
              }
              $authorizedUser = substr($authorizedUser, 1);
          }
          ?>
          <td class='text-left text-ellipsis' title='<?php echo $authorizedUser;?>'>
          <?php echo $authorizedUser;?>
          </td>
          <td class='c-actions'>
            <?php common::printLink('feedback', 'manageProduct', "product=$productID", "<i class='icon-group-managepriv icon-lock'></i>", '', "class='iframe btn' title='{$lang->feedback->manageProduct}'", '', true);?>
          </td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      <?php endforeach;?>
    </tbody>
    <?php endif;?>
  </table>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

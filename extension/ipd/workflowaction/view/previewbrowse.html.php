<?php
/**
 * The preview browse view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowaction
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<style>
.preview-content {overflow: hidden}
.preview-content .table-responsive {width: 100%}
.preview-content .menu {margin-top: 5px;}
.preview-content .menu .nav > li.active > a {position: relative; font-weight: 700; color: #272e33;}
.preview-content .menu .nav > li > a {padding: 6px 12px; color: #505b63; border-radius: 4px;}
.preview-content .menu .nav > li.active > a:before {position: absolute; right: 12px; bottom: 3px; left: 12px; display: block; height: 2px; content: ' '; background: #1ca5ff;}
.preview-content .main-table table tbody td:last-child {overflow: visible;}
</style>
<div class='preview-content'>
  <div class='menu'>
    <div class='btn-toolbar pull-left'>
      <ul class='nav nav-default'>
        <?php
        $index = 1;
        foreach($labels as $label)
        {
            $attr = $index == 1 ? "class='active'" : '';
            echo "<li $attr>" . baseHTML::a('javascript:;', $label, "class='btn btn-link'") . '</li>';
            $index++;
        }
        ?>
      </ul>
    </div>
    <div class='btn-toolbar pull-right'>
      <?php
      echo "<div class='btn-group'>";
      echo baseHTML::a('javascript:;', $lang->importIcon . $lang->import . " <span class='caret'></span>", "class='btn btn-secondary dropdown-toggle' data-toggle='dropdown'");
      echo "<ul class='dropdown-menu'>";
      echo '<li>' . baseHTML::a('javascript:;', $lang->workflowaction->default->actions['import']) . '</li>';
      echo '<li>' . baseHTML::a('javascript:;', $lang->workflowaction->default->actions['exporttemplate']) . '</li>';
      echo '</ul></div>';
      echo "<div class='btn-group'>";
      echo baseHTML::a('javascript:;', $lang->exportIcon . $lang->export . " <span class='caret'></span>", "class='btn btn-secondary dropdown-toggle' data-toggle='dropdown'");
      echo "<ul class='dropdown-menu'>";
      echo '<li>' . baseHTML::a('javascript:;', $lang->exportAll) . '</li>';
      echo '<li>' . baseHTML::a('javascript:;', $lang->exportThisPage) . '</li>';
      echo '</ul></div>';
      echo $this->flow->buildOperateMenu($flow, $data = null, $type = 'menu');
      ?>
    </div>
  </div>
  <div class='main-table' data-ride='table'>
    <div class='table-responsive'>
      <table class='table has-sort-head' id="<?php echo $flow->module;?>Table">
        <thead>
          <tr class='text-center'>
            <?php $index = 1;?>
            <?php foreach($fields as $field):?>
            <?php if(!$field->show) continue;?>
            <?php $width = $field->width && $field->width != 'auto' ? $field->width . 'px' : $field->width;?>
            <th class="text-<?php echo $field->position;?>" style="width:<?php echo $width;?>">
              <?php if($index == 1):?>
              <div class='checkbox-primary check-all' title='<?php echo $this->lang->selectAll;?>'><label></label></div>
              <?php endif;?>
              <?php echo $field->name;?>
            </th>
            <?php $index++;?>
            <?php endforeach;?>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 0; $i < 3; $i++):?>
          <tr>
            <?php $index = 1;?>
            <?php foreach($fields as $field):?>
            <?php if(!$field->show || $field->field == 'actions') continue;?>
            <td>
              <?php if($index == 1):?>
              <div class='checkbox-primary'><input type='checkbox' name='dataIDList[]' value='' id='dataIDList'>
                <label for='dataIDList'></label>
              </div>
              <?php endif;?>
              <?php echo $lang->workflowaction->previewData;?>
            </td>
            <?php $index++;?>
            <?php endforeach;?>
            <td class="nowrap"><?php echo $this->flow->buildOperateMenu($flow, $data = null, $type = 'browse');?></td>
          </tr>
          <?php endfor;?>
        </tbody>
      </table>
    </div>
    <div class='table-footer'>
      <div class='checkbox-primary check-all'><label><?php echo $lang->selectAll;?></label></div>
      <div class='table-actions btn-toolbar'>
        <?php echo $batchActions;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </div>
</div>
<script>
$('[data-ride=table]').table();
$('.preview-content a').attr('href', 'javascript:;').removeAttr('onclick').removeClass('deleter');
$('.preview-content a[data-toggle=modal]').removeAttr('data-toggle');
$('.preview-content #goto').removeAttr('onclick');
</script>

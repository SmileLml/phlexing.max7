<?php
/**
 * The link view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php js::set('mode', 'bysearch');?>
<div class='actions'>
  <?php echo baseHTML::a('#linkTypeBox', $lang->workflowaction->default->actions['link'], "class='btn btn-primary' data-toggle='modal'");?>
</div>
<?php if($linkedFields):?>
<?php if(commonModel::hasPriv($linkedFlow->module, 'search') && !empty($_SESSION[$linkedFlow->module . 'Form'])):?>
<div id='querybox' data-module='<?php echo $linkedFlow->module;?>' data-url='<?php echo $this->createLink('search', 'buildOldForm', "module={$linkedFlow->module}");?>' class='hidden'/>
<?php endif;?>
<?php if($unlinkedDatas):?>
<form id='link<?php echo $linkedFlow->module;?>Form' method='post' class='main-table form-ajax' data-ride='table'>
  <table class='table table-form unlinkedTable'>
    <thead>
      <?php $index = 1;?>
      <?php foreach($linkedFields as $field):?>
      <?php if(!$field->show) continue;?>
      <?php if($field->field == 'actions') continue;?>
      <?php $width = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');?>
      <th class="text-<?php echo $field->position;?>" style="width:<?php echo $width;?>">
        <?php if($index == 1):?>
        <div class='checkbox-primary check-all' title='<?php echo $this->lang->selectAll;?>'>
          <label><?php echo $field->name;?></label>
        </div>
        <?php else:?>
        <?php echo $field->name;?>
        <?php endif;?>
      </th>
      <?php $index++;?>
      <?php endforeach;?>
    </thead>
    <tbody>
      <?php foreach($unlinkedDatas as $unlinkedData):?>
      <tr>
        <?php $index = 1;?>
        <?php foreach($linkedFields as $field):?>
        <?php if(!$field->show or $field->field == 'actions') continue;?>
        <?php
        $output = '';
        if(is_array($unlinkedData->{$field->field}))
        {
            foreach($unlinkedData->{$field->field} as $value) $output .= zget($field->options, $value) . ' ';
        }
        else
        {
            if($field->field == 'id')
            {
                if(commonModel::hasPriv($linkedFlow->module, 'view'))
                {
                    $output = baseHTML::a(helper::createLink($linkedFlow->module, 'view', "dataID={$unlinkedData->id}"), $unlinkedData->id);
                }
                else
                {
                    $output = $unlinkedData->id;
                }
            }
            else
            {
                $output = zget($field->options, $unlinkedData->{$field->field});
            }
        }
        ?>
        <td class="text-<?php echo $field->position;?>" title='<?php echo strip_tags(str_replace("</p>", "\n", str_replace(array("\n", "\r"), "", $output)));?>'>
          <?php if($index == 1):?>
          <div class='checkbox-primary'><input type='checkbox' name='dataIDList[]' value='<?php echo $unlinkedData->id;?>' id='dataIDList<?php echo $unlinkedData->id;?>'>
            <label for='dataIDList<?php echo $unlinkedData->id;?>'>
              <?php if($field->field != 'id') echo $output;?>
            </label>
          </div>
          <?php if($field->field == 'id') echo $output;?>
          <?php else:?>
          <?php echo $output;?>
          <?php endif;?>
        </td>
        <?php $index++;?>
        <?php endforeach;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php if($unlinkedDatas):?>
    <div class='checkbox-primary check-all'><label><?php echo $lang->selectAll?></label></div>
    <div class='table-actions btn-toolbar'>
      <?php $actionLink = $this->createLink($flow->module, 'link', "dataID=$data->id&linkType=$linkedFlow->module");?>
      <?php echo baseHTML::a('javascript:;', $lang->flow->link . $linkedFlow->name, "class='btn' onclick=\"setFormAction('$actionLink', '', this)\"");?>
    </div>
    <?php endif;?>
    <div class='btn-toolbar'>
    </div>
    <div class='table-statistic'></div>
  </div>
</form>
<?php endif;?>
<?php endif;?>
<div class='main-actions'>
  <div class='btn-toolbar'>
    <?php extCommonModel::printLink($flow->module, 'view', "dataID=$data->id", $lang->goback, "class='btn'");?>
  </div>
</div>

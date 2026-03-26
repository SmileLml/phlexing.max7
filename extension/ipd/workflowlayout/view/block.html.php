<?php
/**
 * The block view file of workflowlayout module of ZDOO.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     workflowlayout
 * @version     $Id$
 * @link        https://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php $maxBlockKey = 0;?>
<?php $maxTabKey   = 0;?>
<form id='blockForm' method='post' action="<?php echo $this->createLink('workflowlayout', 'block', "module={$action->module}");?>">
  <ul class='blockList'>
    <?php $blocks = $action->blocks ? json_decode($action->blocks) : array();?>
    <?php foreach($blocks as $blockKey => $block):?>
    <?php if($maxBlockKey < $blockKey) $maxBlockKey = $blockKey;?>
    <li class='block'>
      <div class='row'>
        <div class='col col-md-1 text-right'><i class='icon icon-move text-muted sort-block-handler'></i></div>
        <div class='col col-md-8'>
          <div class='input-group' style='display: inline-table;'>
            <?php echo html::input("blockName[{$blockKey}]", $block->name, "class='form-control' placeholder='{$lang->workflowlayout->blockName}'");?>
            <?php echo html::hidden("key[{$blockKey}]", '');?>
            <span class='input-group-addon'>
              <label class='checkbox-inline'>
                <?php $checked = $block->showName == '1' ? "checked='checked'" : '';?>
                <input type='checkbox' name="showName[<?php echo $blockKey;?>]" id='showName' value='1' <?php echo $checked;?>/> <?php echo $lang->workflowlayout->showName;?>
              </label>
            </span>
          </div>
        </div>  
        <div class='col col-md-3'>  
          <?php echo baseHTML::a('javascript:;', $lang->workflowlayout->addBlock, "class='addBlock'");?>
          <?php echo baseHTML::a('javascript:;', $lang->workflowlayout->addTab, "class='addTab'");?>
          <?php echo baseHTML::a('javascript:;', $lang->delete, "class='removeBlock'");?>
        </div>
      </div>

      <?php if(!empty($block->tabs)):?>
      <ul class='tabList'>
        <?php foreach($block->tabs as $tabKey => $tabName):?>
        <?php if($maxTabKey < $tabKey) $maxTabKey = $tabKey;?>
        <li class='tab'>
          <div class='row'>
            <div class='col col-md-1 text-right'><i class='icon icon-move text-muted sort-tab-handler'></i></div>
            <div class='col col-md-8'>
              <?php echo html::input("tabName[{$tabKey}]", $tabName, "class='form-control' placeholder='{$lang->workflowlayout->tabName}'");?>
              <?php echo html::hidden("parent[$tabKey]", '', "class='parent'")?>
            </div>
            <div class='col col-md-3'><?php echo baseHTML::a('javascript:;', $lang->delete, "class='removeTab'");?></div>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
      <?php endif;?>
    </li>
    <?php endforeach;?>
    <?php $maxBlockKey++;?>
    <?php $maxTabKey++;?>
    <li class='block'>
      <div class='row'>
        <div class='col col-md-1 text-right'><i class='icon icon-move text-muted sort-block-handler'></i></div>
        <div class='col col-md-8'>
          <div class='input-group'>
            <?php echo html::input("blockName[{$maxBlockKey}]", '', "class='form-control' placeholder='{$lang->workflowlayout->blockName}'");?>
            <?php echo html::hidden("key[{$maxBlockKey}]", '');?>
            <span class='input-group-addon'>
              <label class='checkbox-inline'>
                <input type='checkbox' name="showName[<?php echo $maxBlockKey;?>]" id='showName' value='1' checked='checked' /> <?php echo $lang->workflowlayout->showName;?>
              </label>
            </span>
          </div>
        </div>
        <div class='col col-md-3'>
          <?php echo baseHTML::a('javascript:;', $lang->workflowlayout->addBlock, "class='addBlock'");?>
          <?php echo baseHTML::a('javascript:;', $lang->workflowlayout->addTab, "class='addTab'");?>
          <?php echo baseHTML::a('javascript:;', $lang->delete, "class='removeBlock'");?>
        </div>
      </div>
    </li>
    <?php $maxBlockKey++;?>
  </ul>
  <div class='row form-actions'>
    <div class='col col-md-1'></div>
    <div class='col col-md-8'><?php echo baseHTML::commonButton($lang->save, 'btn btn-primary submit');?></div>
    <div class='col col-md-3'></div>
  </div>
</form>

<div class='hide blockData'>
  <li class='block'>
    <div class='row'>
      <div class='col col-md-1 text-right'>
        <i class='icon icon-move sort-block-handler text-muted'></i>
      </div>
      <div class='col col-md-8'>
        <div class='input-group'>
          <?php echo html::input('blockName[blockKey]', '', "class='form-control' placeholder='{$lang->workflowlayout->blockName}'");?>
          <?php echo html::hidden('key[blockKey]', '');?>
          <span class='input-group-addon'>
            <label class='checkbox-inline'>
              <input type='checkbox' name='showName[blockKey]' id='showName' value='1' checked='checked' /> <?php echo $lang->workflowlayout->showName;?>
            </label>
          </span>
        </div>
      </div>
      <div class='col col-md-3'>
        <?php echo baseHTML::a('javascript:;', $lang->workflowlayout->addBlock, "class='addBlock'");?>
        <?php echo baseHTML::a('javascript:;', $lang->workflowlayout->addTab, "class='addTab'");?>
        <?php echo baseHTML::a('javascript:;', $lang->delete, "class='removeBlock'");?>
      </div>
    </div>
  </li>
</div>

<div class='hide tabData'>
  <li class='tab'>
    <div class='row'>
      <div class='col col-md-1 text-right'><i class='icon icon-move sort-tab-handler text-muted'></i></div>
      <div class='col col-md-8'><?php echo html::input('tabName[tabKey]', '', "class='form-control' placeholder='{$lang->workflowlayout->tabName}'");?></div>
      <div class='col col-md-3'>
        <?php echo baseHTML::a('javascript:;', $lang->delete, "class='removeTab'");?>
        <?php echo html::hidden('parent[tabKey]', '', "class='parent'")?>
      </div>
    </div>
  </li>
</div>
<?php js::set('blockKey', $maxBlockKey);?>
<?php js::set('tabKey', $maxTabKey);?>
<?php include '../../common/view/footer.modal.html.php';?>

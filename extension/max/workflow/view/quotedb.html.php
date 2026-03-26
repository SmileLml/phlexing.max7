<?php
/**
 * The quotedb view file of workflow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Sun Guangming <sunguangming@chandao.com>
 * @package     workflowfield
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<style>
#main{min-width:unset;}
.tree .checkbox-primary{display:inline-block; height:22px; max-width: calc(100% - 50px);}
.tree > li{padding-left:20px;}
.tree .checkbox-primary.focus>label:after{border-color: #3883fa; -webkit-box-shadow: 0 0 0 3px rgba(34, 201, 141, .2); box-shadow: 0 0 0 3px rgba(34, 201, 141, .2);}
.tree .checkbox-primary.checked>label:after, .tree .checkbox-primary>input:checked+label:after{background-color: #3883fa; border-color: #3883fa; border-width: 4px;}
.tree .preview{display:inline-block; margin-left:10px; padding:0px;}
.tree .preview.active{color:#3883fa;}
#previewArea, .cell{display:inline-block; vertical-align: top;}
label.active{ color:#3883fa; }
</style>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->workflow->quoteDB;?></h2>
  </div>

  <?php if(empty($tableGroups)):?>
  <div class='alert alert-info'><?php echo $lang->workflow->tips->noQuoteTables;?></div>
  <?php else:?>
  <?php js::set('firstTableID', $tableGroups[0]->items[0]->id);?>
  <div class='cell col-5'>
    <form method='post' id='ajaxForm' class='fieldForm' action='<?php echo inlink('quotedb', "module={$module}&groupID={$groupID}");?>'>
      <ul id='subTableTree' class="tree" data-ride="tree">
        <?php foreach($tableGroups as $group):?>
        <li>
        <?php echo $group->text;?>
          <ul>
            <?php foreach($group->items as $table):?>
            <li>
              <?php echo html::checkbox('tables', array($table->module => $table->text));?>
              <?php echo html::a('###', "<i class='icon icon-eye'></i>", '', "class='preview hidden' data-id='{$table->id}' data-url='" . inlink('ajaxViewDB', "id={$table->id}") . "'");?>
            </li>
            <?php endforeach;?>
          </ul>
        </li>
        <?php endforeach;?>
      </ul>
      <?php echo html::submitButton($lang->workflowtable->use);?>
    </form>
  </div>
  <div id='previewArea' class='col-6'></div>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>

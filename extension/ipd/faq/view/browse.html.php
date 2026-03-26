<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('hidden', $lang->faq->hidden)?>
<?php js::set('show', $lang->faq->show)?>
<div id='mainMenu' class="clearfix">
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('faq', 'create')) echo html::a($this->createLink('faq', 'create', "productID=$currentProduct&moduleID=$currentModule", '', true), "<i class='icon-plus'></i> " . $this->lang->faq->create, '', "class='btn btn-primary iframe'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->faq->noModule;?></div>
      <hr class="space">
      <?php endif;?>
      <ul id='modules' class='tree' data-ride="tree" class="tree no-margin" data-name='tree-story'>
        <?php foreach($moduleTree as $productID => $modules):?>
        <?php if(!isset($products[$productID])) continue;?>
        <li <?php if($currentProduct == $productID) echo "class='active'"?>>
          <?php echo html::a($this->createLink('faq', 'browse', "productID=$productID"), $products[$productID]);?>
          <?php foreach($modules as $module):?>
          <ul>
            <?php if(is_array($module)):?>
            <?php foreach($module['children'] as $children):?>
            <li>
            <?php echo $children['name'];?>
            <?php $this->faq->printChildModule($children['children'], $currentModule);?>
            </li>
            <?php endforeach;?>
            <?php else:?>
            <li <?php if($currentModule == $module->id) echo "class='active'"?>>
            <?php echo html::a($this->createLink('faq', 'browse', "productID=$module->root&moduleID=$module->id"), $module->name);?>
            <?php if(isset($module->children)) $this->faq->printChildModule($module->children, $currentModule);?>
            </li>
            <?php endif;?>
          </ul>
          <?php endforeach;?>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
  </div>
  <div class="main-col">
    <?php if(empty($faqs)):?>
      <?php if(isset($_COOKIE['feedbackView']) and isset($_COOKIE['feedbackView']) or !empty($_SESSION['user']->feedback)): ?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->noData?></span>
        </p>
      </div>
      <?php else:?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->faq->noFaq;?></span>
          <?php if(common::hasPriv('faq', 'create')):?>
          <?php echo html::a($this->createLink('faq', 'create', "productID=$currentProduct&moduleID=$currentModule", '', true), "<i class='icon icon-plus'></i> " . $lang->faq->create, '', "class='btn btn-info iframe'");?>
          <?php endif;?>
        </p>
      </div>
      <?php endif;?>
    <?php else:?>
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal"> <h2> <?php echo $lang->faq->faqList?></h2> </div>
      </div>
      <div class="panel-body">
        <table class='table table-form'>
          <tr>
            <td>
              [ <a id="toggleToc" href='#'><span><?php echo $lang->faq->hidden?></span></a> ]
              <ol style='padding-left: 35px'>
                <?php foreach($faqs as $id => $faq):?>
                <li style='margin-top:5px'><a href='<?php echo "#faq$id";?>'><?php echo $faq->question;?></a></li>
                <?php endforeach;?>
              </ol>
            </td>
          </tr>
        </table>
        <table class='table table-form'>
          <?php foreach($faqs as $id => $faq):?>
          <tr>
            <td>
            <?php echo "<strong id ='faq$id'> $faq->question</strong>";?>
            <span class='actions'>
            <?php if(common::hasPriv('faq', 'edit'))   echo html::a($this->createLink('faq', 'edit', "faqID=$faq->id", '', true), $lang->edit, '', "class='iframe'")?>
            <?php if(common::hasPriv('faq', 'delete')) echo html::a($this->createLink('faq', 'delete', "faqID=$faq->id"), $lang->delete, 'hiddenwin')?>
            </span>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $faq->answer;?>
              <hr />
            </td>
          </tr>
          <?php endforeach;?>
        </table>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->charter->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->charter->name;?></th>
            <td>
              <div class="input-control has-icon-right">
                <?php echo html::input('name', '', "class='form-control'");?>
              </div>
            </td>
            <td>
              <div class="table-row">
                <div class="table-col levelBox">
                  <div class='input-group'>
                    <span class='input-group-addon'><?php echo $lang->charter->level;?></span>
                    <?php echo html::select('level', $lang->charter->levelList, 3, "class='form-control picker-select'");?>
                  </div>
                </div>
                <div class="table-col categoryBox">
                  <div class='input-group'>
                    <span class='input-group-addon fix-border'><?php echo $lang->charter->category;?></span>
                    <?php echo html::select('category', $lang->charter->categoryList, '', "class='form-control picker-select' data-drop-width=auto");?>
                  </div>
                </div>
                <div class="table-col marketBox">
                  <div class='input-group'>
                    <span class='input-group-addon fix-border'><?php echo $lang->charter->market;?></span>
                    <?php echo html::select('market', $lang->charter->marketList, '', "class='form-control picker-select'");?>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->charter->appliedBy;?></th>
            <td><?php echo html::select('appliedBy', $users, $this->app->user->account, "class='form-control picker-select'");?></td>
            <td>
              <div id='budgetBox' class='input-group'>
                <span class='input-group-addon'><?php echo $lang->charter->budget;?></span>
                <?php echo html::input('budget', '', "class='form-control' maxlength='10' ");?>
                <span class='input-group-addon'></span>
                <?php echo html::select('budgetUnit', $budgetUnitList, $config->project->defaultCurrency, "class='form-control'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->charter->product;?></th>
            <td><?php echo html::select('product', $products, '', "class='form-control picker-select' onchange='loadRoadmap(this.value)'");?></td>
            <td>
              <div id='roadmapBox' class='input-group'>
                <span class='input-group-addon'><?php echo $lang->charter->roadmap;?></span>
                <?php echo html::select('roadmap', $roadmaps, '', "class='form-control chosen' onchange='changeLink(this.value)'");?>
                <?php if(common::hasPriv('charter', 'loadRoadmapStories')):?>
                <span class='input-group-btn'><?php echo html::a($this->createLink('charter', 'loadRoadmapStories', '', '', true), $lang->charter->loadStories, '', "class='btn btn-default iframe loadAllDemands'");?></span>
                <?php endif;?>
              </div>
            </td>
          </tr>
          <?php if($lang->charter->charterList):?>
          <tr>
            <th><?php echo $lang->charter->charterFiles;?></th>
            <td colspan='2'>
              <?php foreach($lang->charter->charterList as $key => $name):?>
              <?php echo $this->fetch('charter', 'buildfileform', "filesName=$key&buttonName={$lang->charter->add}$name");?>
              <?php endForeach;?>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->charter->spec;?></th>
            <td colspan="2"><?php echo html::textarea('spec', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='3'>
            <?php echo html::submitButton($lang->save, "", 'btn btn-primary btn-wide needReview');?>
            <?php echo html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

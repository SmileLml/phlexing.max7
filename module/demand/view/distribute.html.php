<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('prckerList', array());?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2>
        <span class='label label-id'><?php echo $demand->id;?></span>
        <span title='<?php echo $demand->title;?>'><?php echo $demand->title;?></span>
      </h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <?php foreach($preProducts as $index => $productID):?>
          <tr>
            <th class='w-100px'><?php echo $lang->demand->distributeProduct;?></th>
            <td class='w-p45 productsBox'>
              <div class='input-group required select'>
                <?php echo html::select("product[$index]", $products, $productID, "onchange='loadProductBranches(this.value, $index);' data-index='$index' class='form-control'");?>
                <span class='input-group-addon fix-border fix-padding'></span>
                <?php if(common::hasPriv('product', 'create') and count($products) <= 1):?>
                <span class='input-group-addon newProduct'>
                <?php echo html::checkBox('newProduct', $lang->demand->addProduct, '', "onchange=addNewProduct(this);");?>
                </span>
                <?php endif;?>
              </div>
              <div class="input-group addProduct hidden">
                <?php echo html::input('productName', '', "class='form-control'");?>
                <span class='input-group-addon required'><?php echo html::checkBox('newProduct', $lang->demand->addProduct, '', "onchange=addNewProduct(this);");?></span>
              </div>
            </td>
            <td class='w-p45 roadmapBox' id='roadmapBox'>
              <div class='input-group select'>
                <span class='input-group-addon'><?php echo $lang->demand->roadmap;?></span>
                <?php echo html::select("roadmap[$index]", $roadmaps, '', 'class="form-control"');?>
                <span class='input-group-addon fix-border fix-padding'></span>
                <?php if(common::hasPriv('roadmap', 'create') and empty($roadmaps)):?>
                <span class='input-group-addon newRoadmap<?php echo $index;?> hidden'>
                <?php echo html::checkBox("newRoadmap$index", $lang->demand->addRoadmap, '', "onchange=addNewRoadmap(this,'$index');");?>
                </span>
                <?php endif;?>
              </div>
              <div class="input-group addRoadmap hidden required">
                <span class='input-group-addon'><?php echo $lang->demand->roadmap;?></span>
                <?php echo html::input("roadmapName[$index]", '', "class='form-control'");?>
                <span class='input-group-addon'><?php echo html::checkBox("newRoadmap$index", $lang->demand->addRoadmap, '', "onchange=addNewRoadmap(this,'$index')");?></span>
              </div>
            </td>
            <td class='c-actions text-center w-p10'>
              <a href='javascript:;' onclick='addItem(this)'    class='addItem btn btn-link'><i class='icon-plus'></i></a>
              <a href='javascript:;' onclick='deleteItem(this)' class='deleteItem btn btn-link'><i class='icon icon-close'></i></a>
            </td>
          </tr>
          <?php endforeach;?>
          <tr>
            <th><?php echo $lang->comment;?></th>
            <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='4'><?php echo html::submitButton($lang->demand->distribute);?></td>
          </tr>
        </tbody>
      </table>
    </form>

    <table class="table table-form hidden">
      <tr id='itemRow'>
        <th class='w-100px'></th>
        <td class='w-p45 productsBox'>
          <div class='input-group required'>
            <?php echo html::select('product[itemIndex]', $products, '', "onchange=loadProductBranches(this.value,'itemIndex') data-index='itemIndex' class='form-control'");?>
            <span class='input-group-addon fix-border fix-padding'></span>
          </div>
        </td>
        <td class='w-p45 roadmapBox' id='roadmapBox'>
          <div class='input-group select'>
            <span class='input-group-addon'><?php echo $lang->demand->roadmap;?></span>
            <?php echo html::select('roadmap[itemIndex]', $roadmaps, '', 'class="form-control"');?>
            <span class='input-group-addon fix-border fix-padding'></span>
            <?php if(common::hasPriv('roadmap', 'create') and empty($roadmaps)):?>
            <span class='input-group-addon newRoadmapitemIndex hidden'>
            <?php echo html::checkBox('newRoadmapitemIndex', $lang->demand->addRoadmap, '', "onchange=addNewRoadmap(this,'itemIndex');");?>
            </span>
            <?php endif;?>
          </div>
          <div class="input-group addRoadmap hidden required">
            <span class='input-group-addon'><?php echo $lang->demand->roadmap;?></span>
            <?php echo html::input('roadmapName[itemIndex]', '', "class='form-control'");?>
            <span class='input-group-addon'><?php echo html::checkBox('newRoadmapitemIndex', $lang->demand->addRoadmap, '', "onchange=addNewRoadmap(this,'itemIndex');");?></span>
          </div>
        </td>
        <td class='c-actions text-center w-p10'>
          <a href='javascript:;' onclick='addItem(this)'    class='addItem btn btn-link'><i class='icon-plus'></i></a>
          <a href='javascript:;' onclick='deleteItem(this)' class='deleteItem btn btn-link'><i class='icon icon-close'></i></a>
        </td>
      </tr>
    </table>
    <hr/>
    <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

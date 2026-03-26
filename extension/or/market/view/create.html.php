<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->market->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-140px'><?php echo $lang->market->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->market->industry;?></th>
            <td><?php echo html::input('industry', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->market->scale;?></th>
            <td>
              <div class="input-group">
                <?php echo html::input('scale', '', "class='form-control'");?>
                <span class="input-group-addon"><?php echo $lang->market->hundredMillion;?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->market->speed;?></th>
            <td><?php echo html::select('speed', $lang->market->speedList, '', "class='form-control picker-select'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->market->maturity;?></th>
            <td><?php echo html::select('maturity', $lang->market->maturityList, '', "class='form-control picker-select'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->market->competition;?></th>
            <td><?php echo html::select('competition', $lang->market->competitionList, '', "class='form-control picker-select'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->market->ppm;?></th>
            <td><?php echo html::select('ppm', $lang->market->ppmList, '', "class='form-control picker-select'");?></td>
            <td>
              <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-lg tipWidth' data-html='true' data-content="<?php echo htmlspecialchars($lang->market->tips);?>"></icon>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->market->strategy;?></th>
            <td><?php echo html::select('strategy', $lang->market->strategyList, '', "class='form-control picker-select'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->market->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='3'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

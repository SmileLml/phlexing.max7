<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='main-col main-content'>
  <form class="load-indicator main-form form-ajax" method='post'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->project->approval . $lang->settings;?></strong>
      </div>
    </div>
    <table class='table table-form active-disabled table-condensed mw-600px'>
      <tbody>
        <tr>
          <td><strong><?php echo $lang->project->approvalflow->object;?></strong></td>
          <td><strong><?php echo $lang->project->approvalflow->flow;?></strong></td>
        </tr>
        <?php foreach($objectList as $object => $objectName):?>
        <?php if(!$object) continue;?>
        <?php $simpleID = in_array($object, array_keys($lang->baseline->objectList)) ? $simpleFlowID : '';?>
        <tr>
          <td><?php echo html::select('object[]', $objectList, $object, "class='form-control chosen'");?></td>
          <td><?php echo html::select('flow[]', $flows, isset($objectFlow[$object]) ? $objectFlow[$object] : $simpleID, "class='form-control chosen'");?></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton(); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

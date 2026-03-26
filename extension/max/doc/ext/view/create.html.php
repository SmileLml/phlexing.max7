<?php $this->app->loadConfig('file');?>
<?php if($docType and strpos($config->doc->officeTypes, $docType) !== false and !empty($config->file->libreOfficeTurnon) and $config->file->convertType == 'collabora'):?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<div id="mainContent" class="main-content">
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->doc->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="dataform" method='post'>
      <table class='table table-form'>
        <tbody>
          <?php if($objectType == 'project'):?>
          <tr>
            <th><?php echo $lang->doc->project;?></th>
            <td class='required'><?php echo html::select('project', $objects, $objectID, "class='form-control picker-select' onchange=loadExecutions(this.value)");?></td>
            <?php if($this->app->tab == 'doc'):?>
            <th><?php echo $lang->doc->execution?></th>
            <td id='executionBox'><?php echo html::select('execution', $executions, '', "class='form-control picker-select' data-placeholder='{$lang->doc->placeholder->execution}' onchange='loadObjectModules(\"execution\", this.value)'")?></td>
            <?php endif;?>
          </tr>
          <?php elseif($objectType == 'execution'):?>
          <tr>
            <th><?php echo $lang->doc->execution;?></th>
            <td class='required'><?php echo html::select('execution', $objects, $objectID, "class='form-control picker-select' onchange='loadObjectModules(\"execution\", this.value)'");?></td>
          </tr>
          <?php elseif($objectType == 'product'):?>
          <tr>
            <th><?php echo $lang->doc->product;?></th>
            <td class='required'><?php echo html::select('product', $objects, $objectID, "class='form-control picker-select' onchange='loadObjectModules(\"product\", this.value)'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th class='w-100px'><?php echo $lang->doc->libAndModule?></th>
            <td colspan='3' class='required'><span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control picker-select'");?></span></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->title;?></th>
            <td colspan='3'><?php echo html::input('title', '', "class='form-control' required");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->keywords;?></th>
            <td colspan='3'><?php echo html::input('keywords', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doclib->control;?></th>
            <td colspan='3' <?php if($objectType != 'mine') echo "class='aclBox'";?>>
              <?php echo html::radio('acl', $lang->doc->aclList, $objectType == 'mine' ? 'private' : 'open', "onchange='toggleAcl(this.value, \"doc\")'");?>
            </td>
          </tr>
          <tr id='whiteListBox' class='hidden'>
            <th><?php echo $lang->doc->whiteList;?></th>
            <td colspan='3'>
              <div class='input-group'>
                <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
                <?php echo html::select('groups[]', $groups, '', "class='form-control picker-select' multiple data-drop-direction='top'")?>
              </div>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
                <?php echo html::select('users[]', $users, '', "class='form-control picker-select' multiple data-drop-direction='top'")?>
                <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=users");?>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan='4' class='text-center form-actions'>
              <?php
              echo html::submitButton();
              echo html::hidden('status', 'normal');
              echo html::hidden('type', $docType);
              echo html::hidden('contentType', 'html');
              ?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php js::set('textType', $config->doc->textTypes);?>
<?php js::set('objectType', $objectType);?>
<?php js::set('docType', $docType);?>
<?php js::set('holders', $lang->doc->placeholder);?>
<script>
function openEditURL(docID, fileID)
{
    var editUrl = createLink('file', 'download', "fileID=" + fileID + "&mouse=left&edit=1");
    window.open(editUrl);
    parent.location.href = createLink('doc', 'view', "docID=" + docID);
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
<?php else:?>
<?php
$oldDir = getcwd();
chdir($app->getModuleRoot() . 'doc/view');
include './create.html.php';
chdir($oldDir);
?>
<?php endif;?>

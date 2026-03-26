<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php js::set('moduleLang', $lang->ticket->module); ?>
<?php js::set('fromType', $fromType); ?>
<?php js::set('productTicket', $productTicket); ?>
<?php js::set('notifyEmailRequired', $notifyEmailRequired); ?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->ticket->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-110px'><?php echo $lang->ticket->product;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('product', $products, $productID, "class='form-control chosen' required onchange='loadAll(this.value)'");?>
              </div>
            </td>
            <td>
              <div class='input-group' id='moduleBox'>
                <span class='input-group-addon'><?php echo $lang->ticket->module?></span>
                <?php echo html::select('module', $modules, $moduleID, "class='form-control chosen'");?>
              </div>
            </td>
            <td class='w-60px'></td>
          </tr>
          <tr>
            <th><nobr><?php echo $lang->ticket->type;?></nobr></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('type', array('' => '') + $lang->ticket->typeList, $defaultType, "class='form-control chosen'");?>
              </div>
            </td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->ticket->openedBuild?></span>
                <?php echo html::select('openedBuild[]', $builds, '', "class='form-control chosen' multiple");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><nobr><?php echo $lang->ticket->assignedTo;?></nobr></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('assignedTo', $users, !empty($product->ticket) ? $product->ticket : '', "class='form-control chosen'");?>
              </div>
            </td>
            <td id='deadlineTd'>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->ticket->deadline; ?></span>
                <span><?php echo html::input('deadline', '', "class='form-control form-date'");?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->ticket->from;?></th>
            <td colspan="2">
              <div class='input-group' style='display: flex;'>
                <div class='table-col' id='customerBox'>
                  <div class='input-group'>
                    <span class='input-group-addon fix-border'><?php echo $lang->ticket->customer?></span>
                    <?php echo html::input('customer[0]', $customer, "class='form-control'");?>
                  </div>
                </div>
                <div class='table-col' id='contactBox'>
                  <div class='input-group'>
                    <span class='input-group-addon fix-border'><?php echo $lang->ticket->contact?></span>
                    <?php echo html::input('contact[0]', $contact, "class='form-control'");?>
                  </div>
                </div>
                <div class='table-col' id='mailBox'>
                  <div class='input-group'>
                    <span class='input-group-addon fix-border'><?php echo $lang->ticket->notifyEmail;?></span>
                    <?php echo html::input('notifyEmail[0]', $email, "class='form-control'");?>
                  </div>
                </div>
              </div>
            </td>
            <td class="table-col c-actions text-left w-60px">
              <div>
                <a href='javascript:;' onclick='addItem(this)' class='btn btn-link' title='<?php echo $lang->ticket->addSource;?>'><i class='icon-plus'></i></a>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->ticket->title;?></th>
            <td colspan='2'>
              <div class='keep-row-height'>
                <div class="input-group title-group">
                  <div class="input-control has-icon-right">
                    <?php echo html::input('title', $ticketTitle, "class='form-control' required");?>
                    <div class="colorpicker">
                      <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                      <ul class="dropdown-menu clearfix">
                        <li class="heading"><?php echo $lang->ticket->colorTag;?><i class="icon icon-close"></i></li>
                      </ul>
                      <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                    </div>
                  </div>
                <div class="table-col w-120px priBox">
                  <div class='input-group'>
                    <span class="input-group-addon fix-border br-0"><?php echo $lang->ticket->pri;?></span>
                    <div class="input-group-btn pri-selector" data-type="pri">
                      <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                        <span class="pri-text"><span class="label-pri label-pri-<?php echo $pri?>" title="<?php echo $pri?>"><?php echo $pri?></span></span> &nbsp;<span class="caret"></span>
                      </button>
                      <div class='dropdown-menu pull-right'>
                        <?php unset($lang->ticket->priList[0])?>
                        <?php echo html::select('pri', $lang->ticket->priList, $pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="table-col w-120px estimateBox">
                  <div class="input-group">
                    <span class="input-group-addon fix-border br-0"><?php echo $lang->ticket->estimate;?></span>
                    <input type="text" name="estimate" id="estimate" value="" class="form-control" autocomplete="off">
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <tr>
          <th><?php echo $lang->ticket->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', $desc, "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <th><nobr><?php echo $lang->ticket->mailto;?></nobr></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('mailto[]', $users, '', "class='form-control picker-select' multiple");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->ticket->keywords?></span>
              <?php echo html::input('keywords', '', "class='form-control'");?>
            </div>
          </td>
        </tr>
        <?php $this->printExtendFields('', 'table');?>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='2'>
            <?php
            if(isset($sourceFiles))
            {
                echo $this->fetch('file', 'printFiles', array('files' => $sourceFiles, 'fieldset' => 'false', 'object' => null, 'method' => 'edit', 'showDelete' => true, 'showEdit' => false));
            }
            echo $this->fetch('file', 'buildoldform');
            ?>
          </td>
        </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-center form-actions">
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
              <?php if($fromType == 'feedback') echo html::hidden('feedback', (int)$fromID);?>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<?php js::set('itemIndex', 1);?>
<?php $i = '%i%';?>
  <table class="hidden">
    <tr id="addItem" class="hidden">
      <th></th>
      <td colspan="2">
        <div class='input-group' style='display: flex;'>
          <div class='table-col' id='customerBox' style='flex: 1 0 200px'>
            <div class='input-group'>
              <span class='input-group-addon fix-border'><?php echo $lang->ticket->customer?></span>
              <?php echo html::input("customer[$i]", '', "class='form-control'");?>
            </div>
          </div>
          <div class='table-col' id='contactBox'>
            <div class='input-group'>
              <span class='input-group-addon fix-border'><?php echo $lang->ticket->contact?></span>
              <?php echo html::input("contact[$i]", '', "class='form-control'");?>
            </div>
          </div>
          <div class='table-col' id='mailBox'>
            <div class='input-group'>
              <span class='input-group-addon fix-border'><?php echo $lang->ticket->notifyEmail;?></span>
              <?php echo html::input("notifyEmail[$i]", '', "class='form-control'");?>
            </div>
          </div>
        </div>
      </td>
      <td class="table-col c-actions text-left w-60px">
        <div>
          <a href='javascript:;' onclick='addItem(this)' class='btn btn-link' title='<?php echo $lang->ticket->addSource;?>'><i class='icon-plus'></i></a>
          <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link' title='<?php echo $lang->ticket->deleteSource;?>'><i class='icon icon-close'></i></a>
        </div>
      </td>
    </tr>
  </table>
<script>
var gap = $('#deadline').parent().parent().width();
$('#mailBox').css('flex', '0 0 ' + gap + 'px')
$("input[name='customer[0]']").parent().parent().css('flex', '1 0 200px')
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

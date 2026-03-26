<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php
foreach(explode(',', $config->demand->create->requiredFields) as $field)
{
    if($field and strpos($showFields, $field) === false) $showFields .= ',' . $field;
}
?>
<?php js::set('showFields', $showFields);?>
<?php js::set('requiredFields', $config->demand->create->requiredFields);?>
<?php js::set('isCopy', !empty($demand));?>
<?php js::set('from', $from);?>
<?php js::set('isUndetermined', !empty($demand) && $demand->product == '');?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->demand->create;?></h2>
      <?php if(!$this->demand->checkForceReview()):?>
      <div class="needNotReviewBox">
        <div class='checkbox-primary'>
          <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin' <?php echo $needReview;?>/>
          <label for='needNotReview'><?php echo $lang->demand->needNotReview;?></label>
        </div>
      </div>
      <?php endif;?>
      <div class="pull-right btn-toolbar">
        <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=demand&section=custom&key=createFields')?>
        <?php include $app->getModuleRoot() . 'common/view/customfield.html.php';?>
      </div>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <?php if(!empty($demand) || !empty($from)):?>
          <tr>
            <th><?php echo $lang->demand->demandpool;?></th>
            <td><?php echo html::select('pool', $demandpools, !empty($demand->pool) ? $demand->pool : '', "class='form-control picker-select'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->demand->product;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('product[]', $products, !empty($demand->product) ? $demand->product : '', "class='form-control picker-select' multiple");?>
                <?php echo html::input('undeterminedProduct', '', "class='form-control hidden' disabled");?>
                <span class='input-group-addon'>
                  <div class="checkbox-primary">
                  <input type="checkbox" name="undetermined" value="0" id="undetermined" onchange="toggleProductDropdown()" />
                    <label for="undetermined0"><?php echo $lang->demand->undetermined;?></label>
                  </div>
                </span>
              </div>
            </td>
            <td id="assignedToBox">
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->demand->assignedTo;?></span>
                <?php echo html::select('assignedTo', isset($assignTo) ? $assignTo : '', !empty($demand->assignedTo) ? $demand->assignedTo: '', "class='form-control picker-select'");?>
              </div>
            </td>
          </tr>
          <?php $hiddenSource = strpos(",$showFields,", ',source,') !== false ? '' : 'hidden';?>
          <tr class='sourceBox <?php echo $hiddenSource;?>'>
            <th><?php echo $lang->demand->source;?></th>
            <td>
              <div class='table-row'>
                <?php echo html::select('source', $lang->demand->sourceList, !empty($demand->source) ? $demand->source : '', "class='form-control chosen'");?>
              </div>
            </td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->demand->sourceNote;?></span>
                <?php echo html::input('sourceNote', !empty($demand->sourceNote) ? $demand->sourceNote : '', "class='form-control'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->demand->duration;?></th>
            <td><?php echo html::select('duration', $lang->demand->durationList, !empty($demand->duration) ? $demand->duration : '', "class='form-control chosen'");?></td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->demand->BSA;?>
                <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->demand->bsaTip;?>"></icon>
                </span>
                <?php echo html::select('BSA', $lang->demand->bsaList, !empty($demand->BSA) ? $demand->BSA : '', "class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->demand->feedbackedBy;?></th>
            <td>
              <div class='table-row'>
                <div class='table-col'>
                  <div class='input-group'>
                    <?php echo html::input('feedbackedBy', !empty($demand->feedbackBy) ? $demand->feedbackBy : '', "class='form-control'");?>
                  </div>
                </div>
                <div class='table-col'>
                  <div class='input-group'>
                    <span class='input-group-addon fix-border'><?php echo $lang->demand->email;?></span>
                    <?php echo html::input('email', !empty($demand->mail) ? $demand->mail : '', "class='form-control'");?>
                  </div>
                </div>
              </div>
            </td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->demand->parent;?></span>
                <?php echo html::select('parent', isset($parents) ? $parents : '', '', "class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr>
            <?php $required = $this->demand->checkForceReview() ? 'required' : '';?>
            <?php echo $this->demand->checkForceReview() ? '' : html::hidden('needNotReview', 1);?>
            <th><?php echo $lang->demand->reviewer;?></th>
            <td id='reviewerBox'><?php echo html::select('reviewer[]', isset($reviewers) ? $reviewers : '', !empty($demand->reviewers) ? $demand->reviewers : '',"class='form-control picker-select' multiple $required");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->demand->name;?></th>
            <td colspan='2'>
              <div class='table-row'>
                <div class='table-col input-size'>
                  <div class="input-control has-icon-right">
                    <?php echo html::input('title', !empty($demand->title) ? $demand->title : '', "class='form-control' required");?>
                    <div class="colorpicker">
                      <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                      <ul class="dropdown-menu clearfix">
                        <li class="heading"><?php echo $lang->demand->colorTag;?><i class="icon icon-close"></i></li>
                      </ul>
                      <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                    </div>
                  </div>
                </div>
                <div class="table-col categoryBox">
                  <div class="input-group">
                    <span class="input-group-addon fix-border br-0"><?php echo $lang->demand->category;?></span>
                    <?php echo html::select('category', $lang->demand->categoryList, !empty($demand->category) ? $demand->category : '', "class='form-control chosen'");?>
                  </div>
                </div>
                <?php $hiddenPri = strpos(",$showFields,", ',pri,') !== false ? '' : 'hidden';?>
                <div class="table-col priBox <?php echo $hiddenPri?>">
                  <div class="input-group">
                    <span class="input-group-addon fix-border br-0"><?php echo $lang->demand->pri;?></span>
                    <?php
                    $hasCustomPri = false;
                    foreach($lang->demand->priList as $priKey => $priValue)
                    {
                        if(!empty($priKey) and (string)$priKey != (string)$priValue)
                        {
                            $hasCustomPri = true;
                            break;
                        }
                    }

                    $pri = 3;
                    unset($lang->demand->priList['']);
                    $priList = $lang->demand->priList;
                    if(end($priList)) unset($priList[0]);
                    if(!isset($priList[$pri]))
                    {
                        reset($priList);
                        $pri = key($priList);
                    }
                    if(!empty($demand->pri)) $pri = $demand->pri;
                    ?>
                    <?php if($hasCustomPri):?>
                    <?php echo html::select('pri', (array)$priList, $pri, "class='form-control'");?>
                    <?php else:?>
                    <div class="input-group-btn pri-selector" data-type="pri">
                      <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                        <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($pri) ? '0' : $pri?>" title="<?php echo $pri?>"><?php echo $pri?></span></span> &nbsp;<span class="caret"></span>
                      </button>
                      <div class='dropdown-menu pull-right'>
                        <?php echo html::select('pri', (array)$priList, $pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                      </div>
                    </div>
                    <?php endif;?>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->demand->spec;?></th>
            <td colspan='2'><?php echo html::textarea('spec', !empty($demand->spec) ? $demand->spec : '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <?php $hiddenVerify = strpos(",$showFields,", ',verify,') !== false ? '' : 'hidden';?>
          <tr class="verifyBox <?php echo $hiddenVerify;?>">
            <th><?php echo $lang->demand->verify;?></th>
            <td colspan="2"><?php echo html::textarea('verify', !empty($demand->verify) ? $demand->verify : '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->files;?></th>
            <td colspan='2'>
            <?php
              if(isset($demand->files)) echo $this->fetch('file', 'printFiles', array('files' => $demand->files, 'fieldset' => 'false', 'object' => null, 'method' => 'edit', 'showDelete' => true, 'showEdit' => false));
              echo $this->fetch('file', 'buildoldform', 'fileCount=1&percent=0.85');
            ?>
            </td>
          </tr>
          <?php $hiddenMailto = strpos(",$showFields,", ',mailto,') !== false ? '' : 'hidden';?>
          <tr class='mailtoBox <?php echo $hiddenMailto;?>'>
            <th><?php echo $lang->demand->mailto;?></th>
            <td colspan='2'>
              <div class="input-group">
                <?php echo html::select('mailto[]', $users, !empty($demand->mailto) ? $demand->mailto : '', "class='form-control chosen' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
                <?php echo $this->fetch('my', 'buildContactLists');?>
              </div>
            </td>
          </tr>
          <?php $hiddenKeyWords = strpos(",$showFields,", ',keywords,') !== false ? '' : 'hidden';?>
          <tr class="<?php echo $hiddenKeyWords?> keywordsBox">
            <th><?php echo $lang->demand->keywords;?></th>
            <td colspan="2">
              <?php echo html::input('keywords', !empty($demand->keywords) ? $demand->keywords : '', 'class="form-control"');?>
            </td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='3'>
            <?php if(!$this->demand->checkForceReview()):?>
            <?php echo html::commonButton($lang->save, "id='saveDemand' onclick=save(this)", 'btn btn-primary btn-wide needNotReview');?>
            <?php echo html::commonButton($lang->demand->saveDraft, 'id="saveDraftDemand" onclick=save(this,"draft")', 'btn btn-secondary btn-wide needNotReview');?>
            <?php endif;?>

            <?php echo html::commonButton($lang->demand->saveDraft, "id='saveDraft' onclick=save(this,'draft')", 'btn btn-primary btn-wide needReview');?>
            <?php echo html::commonButton($lang->demand->submitReview, "id='submitReview' onclick=save(this)", 'btn btn-secondary btn-wide needReview');?>
            <?php if(empty($demand)):?>
            <?php echo html::hidden('pool', $pool->id);?>
            <?php endif;?>
            <?php echo html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

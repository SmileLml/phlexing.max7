<div class="center-block">
  <div class="main-header">
    <h2><?php echo $lang->demand->edit;?></h2>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <tbody>
        <tr>
          <th class='w-140px'><?php echo $lang->demand->pool;?></th>
          <td><?php echo html::select('pool', $demandpools, $demand->pool, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->demand->product;?></th>
          <td>
            <div class='input-group'>
              <?php $undetermined = empty(trim($demand->product, ',')) ? 'checked' : '';?>
              <?php $disableProduct  = $undetermined ? "disabled='disabled'" : '';?>
              <?php $hideProductName = $undetermined ? '' : 'hidden';?>
              <?php echo html::select('product[]', $products, $demand->product, "class='form-control picker-select' $disableProduct multiple");?>
              <?php echo html::input('undeterminedProduct', '', "class='form-control $hideProductName' disabled");?>
              <span class='input-group-addon'>
                <div class="checkbox-primary">
                  <input type="checkbox" name="undetermined" value="0" id="undetermined" <?php echo $undetermined;?> onchange="toggleProductDropdown()"/>
                  <label for="undetermined0"><?php echo $lang->demand->undetermined;?></label>
                </div>
              </span>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->demand->assignedTo;?></span>
              <?php echo html::select('assignedTo', $assignToList, $demand->assignedTo, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr class='sourceBox'>
          <th><?php echo $lang->demand->source;?></th>
          <td>
            <div class='table-row'>
              <?php echo html::select('source', $lang->demand->sourceList, $demand->source, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->demand->sourceNote;?></span>
              <?php echo html::input('sourceNote', $demand->sourceNote, "class='form-control'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->demand->duration;?></th>
          <td><?php echo html::select('duration', $lang->demand->durationList, $demand->duration, "class='form-control chosen'");?></td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->demand->BSA;?>
              <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->demand->bsaTip;?>"></icon>
              </span>
              <?php echo html::select('BSA', $lang->demand->bsaList, $demand->BSA, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->demand->feedbackedBy;?></th>
          <td>
            <div class='table-row'>
              <div class='table-col'>
                <div class='input-group'>
                  <?php echo html::input('feedbackedBy', $demand->feedbackedBy, "class='form-control'");?>
                </div>
              </div>
              <div class='table-col'>
                <div class='input-group'>
                  <span class='input-group-addon fix-border'><?php echo $lang->demand->email;?></span>
                  <?php echo html::input('email', $demand->email, "class='form-control'");?>
                </div>
              </div>
            </div>
          </td>
          <?php if($demand->parent >= 0):?>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->demand->parent;?></span>
              <?php echo html::select('parent', $parents, $demand->parent, "class='form-control chosen'");?>
            </div>
          </td>
          <?php endif;?>
        </tr>
        <?php if(strpos('draft,changing,reviewing', $demand->status) !== false):?>
        <tr>
          <th><?php echo $lang->demand->reviewer;?></th>
          <td id='reviewerBox'>
            <div class="table-row">
              <?php if(!$this->demand->checkForceReview()):?>
              <div class="table-col">
                <?php echo html::select('reviewer[]', $reviewers, $demand->reviewer, 'class="form-control picker-select" multiple')?>
              </div>
              <div class="table-col needNotReviewBox">
                <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-width: 0px;">
                  <div class='checkbox-primary'>
                  <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin' <?php echo $needReview;?>/>
                    <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
                  </div>
                </span>
              </div>
              <?php else:?>
              <div class="table-col">
                <?php echo html::select('reviewer[]', $reviewers, $demand->reviewer, 'class="form-control picker-select" multiple required')?>
              </div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->demand->title;?></th>
          <td colspan='2'>
            <div class='table-row'>
              <div class='table-col input-size'>
                <div class="input-control has-icon-right">
                  <?php $disabled = strpos('draft,changing', $demand->status) !== false ? '' : 'disabled=disabled';?>
                  <?php echo html::input('title', $demand->title, "class='form-control' required $disabled");?>
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
                  <?php echo html::select('category', $lang->demand->categoryList, $demand->category, "class='form-control chosen'");?>
                </div>
              </div>
              <div class="table-col priBox">
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

                  $pri = $demand->pri;
                  unset($lang->demand->priList['']);
                  $priList = $lang->demand->priList;
                  if(end($priList)) unset($priList[0]);
                  if(!isset($priList[$pri]))
                  {
                      reset($priList);
                      $pri = key($priList);
                  }
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
          <td colspan='2'>
          <?php echo strpos('draft,changing', $demand->status) !== false ? html::textarea('spec', $demand->spec, "rows='6' class='form-control kindeditor' hidefocus='true'") : "<div class='content'>{$demand->spec}</div>";?>
          </td>
        </tr>
        <tr class="verifyBox">
          <th><?php echo $lang->demand->verify;?></th>
          <td colspan="2">
          <?php echo strpos('draft,changing', $demand->status) !== false ? html::textarea('verify', $demand->verify, "rows='6' class='form-control kindeditor' hidefocus='true'") : "<div class='content'>{$demand->verify}</div>";?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildoldform', 'fileCount=1&percent=0.85');?></td>
        </tr>
        <tr class='mailtoBox'>
          <th><?php echo $lang->demand->mailto;?></th>
          <td colspan='2'>
            <div class="input-group">
              <?php echo html::select('mailto[]', $users, $demand->mailto, "class='form-control chosen' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
              <?php echo $this->fetch('my', 'buildContactLists');?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->demand->keywords;?></th>
          <td><?php echo html::input('keywords', $demand->keywords, "class='form-control'");?></td>
        </tr>
        <tr>
          <td class='form-actions text-center' colspan='3'>
          <?php
          if(strpos('draft,changing', $demand->status) !== false)
          {
              echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');
              echo html::commonButton($demand->status == 'changing' ? $lang->story->doNotSubmit : $lang->story->saveDraft, "id='saveDraftButton'", 'btn btn-secondary btn-wide');
          }
          else
          {
              echo html::submitButton($lang->save);
          }
          if(!isonlybody()) echo html::backButton();
          ?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

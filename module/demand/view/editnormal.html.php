<?php js::set('confirmChangProduct', $lang->demand->confirmChangProduct);?>
<?php js::set('distributedProducts', $distributedProducts);?>
<?php js::set('isUndetermined', $demand->product == '');?>
<form method='post' enctype='multipart/form-data' class='form-ajax' id='dataform'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $demand->id;?></span>
      <?php echo html::a($this->createLink('demand', 'view', "demandID=$demand->id"), $demand->title, '', 'class="demand-title"');?>
      <small><?php echo $lang->arrow . ' ' . $lang->demand->edit;?></small>
    </h2>
  </div>
  <div class='main-row'>
    <div class='main-col col-8'>
      <div class='cell'>
        <div class='form-group titleBox'>
          <div class="input-control has-icon-right">
            <div class="colorpicker">
              <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" title="<?php echo $lang->task->colorTag ?>"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
              <ul class="dropdown-menu clearfix">
                <li class="heading"><?php echo $lang->demand->colorTag;?><i class="icon icon-close"></i></li>
              </ul>
              <input type="hidden" class="colorpicker" id="color" name="color" value="<?php echo $demand->color ?>" data-icon="color" data-wrapper="input-control-icon-right" data-update-color=".demand-title"  data-provide="colorpicker">
            </div>
            <?php echo html::input('title', $demand->title, 'class="form-control disabled demand-title" disabled="disabled"');?>
          </div>
        </div>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->demand->spec;?></div>
          <div class='detail-content article-content'>
            <?php echo $demand->spec;?>
          </div>
        </div>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->demand->verify;?></div>
          <div class='detail-content article-content'>
            <?php echo $demand->verify;?>
          </div>
        </div>
        <?php $showFile = (strpos('draft,changing', $demand->status) === false and empty($demand->files)) ? false : true;?>
        <?php if($showFile):?>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->attatch;?></div>
          <div class='form-group'>
            <?php $canChangeFile = strpos('draft,changing', $demand->status) !== false ? true : false;?>
            <?php echo $this->fetch('file', 'printFiles', array('files' => $demand->files, 'fieldset' => 'false', 'object' => $demand, 'method' => 'edit', 'showDelete' => $canChangeFile));?>
            <?php echo $canChangeFile ? $this->fetch('file', 'buildoldform') : '';?>
          </div>
        </div>
        <?php endif;?>
        <?php $this->printExtendFields($demand, 'div', 'position=left');?>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->demand->comment;?></div>
          <div class='form-group'>
            <?php echo html::textarea('comment', '', "rows='5' class='form-control'");?>
          </div>
        </div>
        <div class='actions form-actions text-center'>
          <?php
          echo html::hidden('lastEditedDate', $demand->lastEditedDate);
          if(strpos('draft,changing', $demand->status) !== false)
          {
              echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');
              echo html::commonButton($demand->status == 'changing' ? $lang->demand->doNotSubmit : $lang->demand->saveDraft, "id='saveDraftButton'", 'btn btn-secondary btn-wide');
          }
          else
          {
              echo html::submitButton($lang->save);
          }
          if(!isonlybody()) echo html::backButton();
          ?>
        </div>
        <hr class='small' />
        <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
      </div>
    </div>
    <div class='side-col col-4'>
      <div class='cell'>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->demand->basicInfo;?></div>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->demand->pool;?></th>
              <td><?php echo html::select('pool', $demandpools, $demand->pool, "class='form-control chosen'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->product;?></th>
              <td>
                <div class='input-group'>
                  <?php $undetermined = empty(trim($demand->product, ',')) ? 'checked' : '';?>
                  <?php $disableProduct  = $undetermined ? "disabled='disabled'" : '';?>
                  <?php $hideProductName = $undetermined ? '' : 'hidden';?>
                  <?php echo html::select('product[]', $products, $demand->product, "class='form-control picker-select' $disableProduct data-max_drop_width multiple");?>
                  <?php echo html::input('undeterminedProduct', '', "class='form-control $hideProductName' disabled");?>
                  <span class='input-group-addon'>
                    <div class="checkbox-primary">
                      <input type="checkbox" name="undetermined" value="0" id="undetermined" <?php echo $undetermined;?> onchange="toggleProductDropdown()"/>
                      <label for="undetermined0"><?php echo $lang->demand->undetermined;?></label>
                    </div>
                  </span>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->source;?></th>
              <td><?php echo html::select('source', $lang->demand->sourceList, $demand->source, "class='form-control chosen'");?></td>
            </tr>
            <tr>
              <th id='sourceNoteBox'><?php echo $lang->demand->sourceNote;?></th>
              <td><?php echo html::input('sourceNote', $demand->sourceNote, "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->duration;?></th>
              <td><?php echo html::select('duration', $lang->demand->durationList, $demand->duration, "class='form-control chosen'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->BSA;?></th>
              <td><?php echo html::select('BSA', $lang->demand->bsaList, $demand->BSA, "class='form-control chosen'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->feedbackedBy;?></th>
              <td><?php echo html::input('feedbackedBy', $demand->feedbackedBy, "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->email;?></th>
              <td><?php echo html::input('email', $demand->email, "class='form-control'");?></td>
            </tr>
            <?php if($demand->parent >= 0):?>
            <tr>
              <th><?php echo $lang->demand->parent;?></th>
              <td><?php echo html::select('parent', $parents, $demand->parent, "class='form-control chosen'");?></td>
            </tr>
            <?php endif;?>
            <tr>
              <th><?php echo $lang->demand->status;?></th>
              <td>
                <span class='demand-<?php echo $demand->status;?>'><?php echo $this->processStatus('demand', $demand);?></span>
                <?php echo html::hidden('status', $demand->status);?>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->category;?></th>
              <td><?php echo html::select('category', $lang->demand->categoryList, $demand->category, "class='form-control chosen'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->pri;?></th>
              <td><?php echo html::select('pri', $lang->demand->priList, $demand->pri, "class='form-control chosen'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->mailto;?></th>
              <td>
                <div class='input-group'>
                  <?php echo html::select('mailto[]', $users, $demand->mailto, "class='form-control picker-select' multiple");?>
                  <?php echo $this->fetch('my', 'buildContactLists');?>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->keywords;?></th>
              <td><?php echo html::input('keywords', $demand->keywords, "class='form-control'");?></td>
            </tr>
          </table>
        </div>
        <div class='detail'>
          <div class='detail-title'><?php echo $lang->story->legendLifeTime;?></div>
          <table class='table table-form'>
            <tr>
              <th class='thWidth'><?php echo $lang->demand->createdBy;?></th>
              <td><?php echo zget($users, $demand->createdBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->assignedTo;?></th>
              <td><?php echo html::select('assignedTo', $assignToList, $demand->assignedTo, 'class="form-control chosen"');?></td>
            </tr>
            <?php if($demand->status == 'reviewing'):?>
            <tr>
              <th><?php echo $lang->demand->reviewer;?></th>
              <td><?php echo html::select('reviewer[]', $reviewers, $demand->reviewers, 'class="form-control picker-select" multiple');?></td>
            </tr>
            <?php endif;?>
            <?php if($demand->status == 'closed'):?>
            <tr>
              <th><?php echo $lang->demand->closedBy;?></th>
              <td><?php echo html::select('closedBy', $users, $demand->closedBy, 'class="form-control chosen"');?></td>
            </tr>
            <tr>
              <th><?php echo $lang->demand->closedReason;?></th>
              <td><?php echo html::select('closedReason', $lang->demand->reasonList, $demand->closedReason, "class='form-control'  onchange='setStory(this.value)'");?></td>
            </tr>
            <?php endif;?>
          </table>
        </div>
      </div>
    </div>
  </div>
</form>

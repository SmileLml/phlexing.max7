<?php
/**
 * The edit view file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/chosen.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('browseType', $browseType);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $feedback->id;?></span>
      <?php echo html::a(inlink('view', "id=$feedback->id"), $feedback->title);?>
      <small><?php echo html::icon($lang->icons['edit']) . ' ' . $lang->edit;?></small>
    </h2>
  </div>
  <form class='main-form form-ajax' method='post' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->feedback->product?></th>
        <td><?php echo html::select('product', $products, $feedback->product, "class='form-control chosen'")?></td>
        <td></td>
      </tr>
      <tr>
        <th class='w-80px'><?php echo $lang->feedback->module?></th>
        <td><?php echo html::select('module', $modules, $feedback->module, "class='form-control chosen'")?></td>
        <td></td>
      </tr>
      <tr>
        <th class='w-80px'><?php echo $lang->feedback->type;?></th>
        <td><?php echo html::select('type', $lang->feedback->typeList, $feedback->type, "class='form-control chosen'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->title?></th>
        <td colspan='2'>
          <div class='input-group'>
            <?php echo html::input('title', $feedback->title, "class='form-control'")?>
            <span class='input-group-addon'>
              <div class='checkbox-primary'>
                <input type='checkbox' id='public' name='public' value='1' <?php if($feedback->public) echo 'checked'?> />
                <label for='notify'><?php echo $lang->feedback->public?></label>
              </div>
            </span>
            <?php // begin print pri selector?>
            <span class="input-group-addon fix-border br-0"><?php echo $lang->feedback->pri;?></span>
            <?php
            $hasCustomPri = false;
            foreach($lang->feedback->priList as $priKey => $priValue)
            {
                if(!empty($priKey) and (string)$priKey != (string)$priValue)
                {
                    $hasCustomPri = true;
                    break;
                }
            }
            $priList = $lang->feedback->priList;
            if(end($priList)) unset($priList[0]);
            if(!isset($priList[$feedback->pri]))
            {
                reset($priList);
                $feedback->pri = key($priList);
            }
            ?>
            <?php if($hasCustomPri):?>
            <?php echo html::select('pri', (array)$priList, $feedback->pri, "class='form-control'");?>
            <?php else: ?>
            <div class="input-group-btn pri-selector" data-type="pri">
              <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($feedback->pri) ? '0' : $feedback->pri?>" title="<?php echo $feedback->pri?>"><?php echo $feedback->pri?></span></span> &nbsp;<span class="caret"></span>
              </button>
              <div class='dropdown-menu pull-right'>
                <?php echo html::select('pri', (array)$priList, $feedback->pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
              </div>
            </div>
            <?php endif; ?>
            <?php // end print pri selector ?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->desc?></th>
        <td colspan='2'><?php echo html::textarea('desc', $feedback->desc, "class='form-control'")?></td>
      </tr>
      <tr class='hide'>
        <th><?php echo $lang->feedback->status;?></th>
        <td><?php echo html::hidden('status', $feedback->status);?></td>
      </tr>
      <?php $this->printExtendFields($feedback, 'table');?>
      <tr>
        <th><?php echo $lang->feedback->feedbackBy;?></th>
        <td><?php echo html::input('feedbackBy', $feedback->feedbackBy, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->source;?></th>
        <td><?php echo html::input('source', $feedback->source, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->notifyEmail;?></th>
        <td><?php echo html::input('notifyEmail', $feedback->notifyEmail, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->mailto;?></th>
        <td>
          <div class="input-group">
            <?php echo html::select('mailto[]', $users, str_replace(' ', '', $feedback->mailto), "class='form-control picker-select' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
            <?php echo $this->fetch('my', 'buildContactLists');?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->keywords;?></th>
        <td><?php echo html::input('keywords', $feedback->keywords, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->files;?></th>
        <td>
          <?php echo $this->fetch('file', 'printFiles', array('files' => $feedback->files, 'fieldset' => 'false', 'object' => $feedback, 'method' => 'edit'));?>
          <?php echo $this->fetch('file', 'buildform');?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->notify;?></th>
        <td>
          <div class='checkbox-primary'>
            <input type='checkbox' id='notify' name='notify' value='1' <?php if($feedback->notify) echo 'checked'?> />
            <label for='notify'><?php echo $lang->feedback->mailNotify?></label>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php echo html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php
/**
 * The submit review file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     demand
 * @version     $Id: submitreview.html.php 935 2022-07-20 09:49:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('lastReviewer', explode(',', $lastReviewer))?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $demand->id;?></span>
        <?php echo isonlybody() ? ("<span title='$demand->title'>" . $demand->title . '</span>') : html::a($this->createLink('demand', 'view', 'demand=' . $demand->id), $demand->title);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . $lang->demand->submitReview;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->demand->reviewedBy;?></th>
          <td colspan='2' id='reviewerBox' <?php if($this->demand->checkForceReview() or !empty($demand->reviewer)) echo "class='required'";?>>
            <div class="table-row">
              <?php if(!$this->demand->checkForceReview()):?>
              <div class="table-col">
                <?php echo html::select('reviewer[]', $reviewers, $demand->reviewer, "class='form-control picker-select' multiple");?>
              </div>
              <div class="table-col needNotReviewBox">
                <span class="input-group-addon">
                  <div class='checkbox-primary'>
                    <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin' <?php echo $needReview;?>/>
                    <label for='needNotReview'><?php echo $lang->demand->needNotReview;?></label>
                  </div>
                </span>
              </div>
              <?php else:?>
              <div class="table-col">
                <?php echo html::select('reviewer[]', $reviewers, $demand->reviewer, "class='form-control picker-select' multiple");?>
              </div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::hidden('id', $demand->id);?>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

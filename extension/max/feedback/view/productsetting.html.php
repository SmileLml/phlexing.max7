<?php
/**
 * The complete file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zongjun Lan <lanzongjun@cnezsoft.com>
 * @package     feedback
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include $app->getModuleRoot() . 'common/view/header.html.php';
include $app->getModuleRoot() . 'common/view/kindeditor.html.php';
?>
<?php js::set('productHeadMap', $productHeadMap); ?>
<div class='cell'>
  <form class="modal-content load-indicator" method='post' target='hiddenwin' id="productSetForm">
    <div class="modal-header" id="productSetHeader">
      <h2><?php echo $lang->feedback->productSetting?></h2>
    </div>
    <div class='modal-body'>
      <table class='table table-form' id='objectTable'>
      <?php if(empty($productPairs)):?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->feedback->productSettingNoProduct;?></span></p>
      </div>
      <?php else:?>
        <tbody>
          <?php foreach($productPairs as $productID => $title):?>
          <?php if(!array_key_exists($productID, $products)) continue;?>
          <tr class="productSetBox">
            <td colspan='6' id='productBox'>
              <div class='input-group'>
                <div class="input-group-addon"><?php echo $lang->productCommon;?></div>
                <?php echo html::select('products[]', $products, $productID, "class='form-control picker-select' onchange='changeProduct(this)'");?>
              </div>
            </td>
            <td colspan='4' id='feedbackBox'>
              <div class='input-group'>
                <div class="input-group-addon"><?php echo $lang->feedback->head;?></div>
                <?php echo html::select('feedbacks[]', $users, !empty($productHeadMap[$productID]['feedback']) ? $productHeadMap[$productID]['feedback'] : '', "class='form-control picker-select'");?>
              </div>
            </td>
            <td colspan='4' id='ticketBox'>
              <div class='input-group'>
                <div class="input-group-addon"><?php echo $lang->feedback->ticketHead;?></div>
                <?php echo html::select('tickets[]', $users, !empty($productHeadMap[$productID]['ticket']) ? $productHeadMap[$productID]['ticket'] : '', "class='form-control picker-select'");?>
              </div>
            </td>
            <td colspan='2'>
              <a href='javascript:;' onclick='addProduct(this)' tabindex="9999" class='btn btn-link btn-add'><i class="icon icon-plus"></i></a>
              <a href='javascript:;' onclick='deleteProduct(this)' tabindex="9999" class='btn btn-link btn-delete'><i class="icon icon-close"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='14' class='text-center'>
              <?php echo html::submitButton();?>
            </td>
          </tr>
        </tfoot>
        <?php endif;?>
      </table>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

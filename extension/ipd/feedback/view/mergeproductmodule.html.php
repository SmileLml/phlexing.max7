<?php
/**
 * The syncproduct file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng <zenggang@easycorp.ltd>
 * @package     feedback
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->feedback->mergeModule;?></h2>
    <span class='text-primary'><?php echo sprintf($lang->feedback->mergeTip, $mergeCount, ceil($mergeCount / $recPerPage));?></span>
  </div>
  <form class='form-indicator main-form' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->idAB;?></th>
          <th class=''><?php echo $lang->tree->root;?></th>
          <th class=''><?php echo $lang->feedback->feedbackModule;?></th>
          <th class='required'><?php echo $lang->feedback->productModule;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1;?>
        <?php foreach($mergeList as $ID => $merge):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::input('product', $product->name, "class='form-control' disabled title='$product->name'");?></td>
          <td><?php echo html::hidden('mergeFrom[]', $ID) . html::input('mergeFromName[]', $merge, "class='form-control' disabled title='$merge'");?></td>
          <td><?php echo html::select('mergeTo[]', $productModules, '', "class='form-control picker-select'");?></td>
        </tr>
        <?php $i ++;?>
        <?php if($i > $recPerPage) break;?>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton(count($mergeList) > $recPerPage ? $lang->upgrade->next : '');?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

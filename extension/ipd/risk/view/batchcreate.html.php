<?php
/**
 * The batchcreate of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: batchcreate.html.php 4903 2020-09-04 09:13:59Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->risk->batchCreate;?></h2>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->risk->id;?></th>
          <th class='w-200px'><?php echo $lang->risk->execution;?></th>
          <th class='required'><?php echo $lang->risk->name;?></th>
          <th class='w-200px'><?php echo $lang->risk->source;?></th>
          <th class='w-200px'><?php echo $lang->risk->category;?></th>
          <th class='w-200px'><?php echo $lang->risk->strategy;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::select("execution[$i]", $executions, isset($executionID) ? $executionID : '', "class='form-control chosen'");?></td>
          <td><?php echo html::input("name[$i]", '',  "class='form-control'");?></td>
          <td><?php echo html::select("source[$i]", $lang->risk->sourceList,  '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("category[$i]", $lang->risk->categoryList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("strategy[$i]", $lang->risk->strategyList, '', "class='form-control chosen'");?></td>
        </tr>
        <?php endfor;?>
        <tr>
          <td colspan='6' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton($lang->goback, "data-app='{$app->tab}'");?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

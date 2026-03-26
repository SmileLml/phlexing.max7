<?php
/**
 * The batch create view of process module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     process
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id = "mainContent" class="main-content fade in">
  <div class="main-header">
    <h2><?php echo $lang->process->batchCreate;?></h2>
  </div>
  <form id="batchCreateForm" class="form-ajax" method="post">
    <table class="table table-form">
      <thead>
        <tr class="text-center">
          <th class="w-50px"><?php echo $lang->process->id;?></th>
          <th class="required"><?php echo $lang->process->name;?></th>
          <th class="w-200px"><?php echo $lang->process->type;?></th>
          <th class="w-600px"><?php echo $lang->process->abbr;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1;$i <= 10;$i++):?>
        <tr data-key="<?php echo $i;?>">
          <td><?php echo $i;?></td>
          <td><?php echo html::input("dataList[$i][name]", '', 'id="dataList' . $i . 'name" class="form-control" autocomplete="off"')?></td>
          <?php echo html::hidden("dataList[$i][model]", $model)?>
          <td><?php echo html::select("dataList[$i][type]", $lang->process->$classify, '', 'class="form-control chosen" id="dataList' . $i . 'type"')?></td>
          <td><?php echo html::input("dataList[$i][abbr]", '', 'class="form-control" id="dataList' . $i . 'abbr"');?></td>
       </tr>
        <?php endfor;?>
      </tbody>
    </table>
    <div class="form-actions text-center">
      <?php echo html::submitButton() . html::backButton();?>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
